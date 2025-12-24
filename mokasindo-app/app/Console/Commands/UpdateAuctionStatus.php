<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Deposit;
use App\Models\Setting;
use App\Models\Notification;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateAuctionStatus extends Command
{
    protected $signature = 'auctions:update-status';
    protected $description = 'Update auction status based on start/end time and process winners';

    public function handle()
    {
        $now = Carbon::now();

        // 1. Activate scheduled auctions that have started
        $activated = Auction::where('status', 'scheduled')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->update(['status' => 'active']);

        if ($activated > 0) {
            $this->info("Activated {$activated} auctions.");
        }

        // 2. End active auctions that have passed their end time AND process winners
        $auctionsToEnd = Auction::with(['bids', 'vehicle'])
            ->where('status', 'active')
            ->where('end_time', '<=', $now)
            ->get();

        foreach ($auctionsToEnd as $auction) {
            $this->processAuctionEnd($auction);
        }

        if ($auctionsToEnd->count() > 0) {
            $this->info("Ended and processed {$auctionsToEnd->count()} auctions.");
        }

        // 3. Cancel scheduled auctions that were never activated and passed end time
        $cancelled = Auction::where('status', 'scheduled')
            ->where('end_time', '<=', $now)
            ->update(['status' => 'cancelled']);

        if ($cancelled > 0) {
            $this->info("Cancelled {$cancelled} expired scheduled auctions.");
        }

        // 4. Check for payment deadline overdue
        $this->processOverduePayments();

        if ($activated == 0 && $auctionsToEnd->count() == 0 && $cancelled == 0) {
            $this->info('No auctions needed status update.');
        }

        return Command::SUCCESS;
    }

    /**
     * Process auction end: determine winner, send notifications, handle deposits
     */
    private function processAuctionEnd(Auction $auction)
    {
        try {
            DB::beginTransaction();

            // Get highest bid
            $winningBid = Bid::where('auction_id', $auction->id)
                ->orderBy('bid_amount', 'desc')
                ->first();

            $paymentDeadlineHours = (int) Setting::get('payment_deadline_hours', 24);

            if ($winningBid) {
                // Check if reserve price is met (jika ada)
                if ($auction->reserve_price && $winningBid->bid_amount < $auction->reserve_price) {
                    // Reserve not met - no winner
                    $this->handleNoWinner($auction, 'Reserve price tidak tercapai');
                } else {
                    // We have a winner!
                    $this->handleWinner($auction, $winningBid, $paymentDeadlineHours);
                }
            } else {
                // No bids - auction failed
                $this->handleNoWinner($auction, 'Tidak ada bid');
            }

            DB::commit();
            $this->info("Processed auction #{$auction->id}: " . ($auction->winner_id ? "Winner: User #{$auction->winner_id}" : "No winner"));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to process auction #{$auction->id}: " . $e->getMessage());
            $this->error("Failed to process auction #{$auction->id}: " . $e->getMessage());
        }
    }

    /**
     * Handle auction with a winner
     */
    private function handleWinner(Auction $auction, Bid $winningBid, int $paymentDeadlineHours)
    {
        // Update auction dengan winner
        $auction->update([
            'status' => 'ended',
            'winner_id' => $winningBid->user_id,
            'final_price' => $winningBid->bid_amount,
            'won_at' => now(),
            'payment_deadline' => now()->addHours($paymentDeadlineHours),
        ]);

        // Update winning bid
        $winningBid->update(['is_winning' => true, 'status' => 'won']);

        // Update vehicle status
        if ($auction->vehicle) {
            $auction->vehicle->update(['status' => 'sold']);
        }

        // Send notification to winner
        $this->notifyWinner($auction, $winningBid);

        // Refund deposits of non-winners
        $this->refundNonWinnerDeposits($auction, $winningBid->user_id);

        // Notify non-winners
        $this->notifyNonWinners($auction, $winningBid->user_id);
    }

    /**
     * Handle auction without winner
     */
    private function handleNoWinner(Auction $auction, string $reason)
    {
        $auction->update([
            'status' => 'ended',
            'winner_id' => null,
            'final_price' => null,
        ]);

        // Refund all deposits
        Deposit::where('auction_id', $auction->id)
            ->whereIn('status', ['paid', 'approved'])
            ->update([
                'refund_status' => 'pending',
                'status' => 'refunded',
            ]);

        // Notify owner
        if ($auction->vehicle && $auction->vehicle->user) {
            Notification::create([
                'user_id' => $auction->vehicle->user_id,
                'type' => 'auction_ended_no_winner',
                'title' => 'Lelang Berakhir Tanpa Pemenang',
                'message' => "Lelang {$auction->vehicle->brand} {$auction->vehicle->model} berakhir: {$reason}",
                'data' => ['auction_id' => $auction->id],
            ]);
        }
    }

    /**
     * Send notification to winner via Telegram and in-app
     */
    private function notifyWinner(Auction $auction, Bid $winningBid)
    {
        $winner = $winningBid->user;
        $vehicleName = $auction->vehicle ? "{$auction->vehicle->brand} {$auction->vehicle->model}" : 'Kendaraan';
        $finalPrice = $winningBid->bid_amount;

        // In-app notification
        Notification::create([
            'user_id' => $winner->id,
            'type' => 'auction_won',
            'title' => '🏆 Selamat! Anda Memenangkan Lelang',
            'message' => "Anda memenangkan lelang {$vehicleName} dengan harga Rp " . number_format($finalPrice, 0, ',', '.') . ". Segera lakukan pembayaran dalam 24 jam.",
            'data' => [
                'auction_id' => $auction->id,
                'vehicle_id' => $auction->vehicle_id,
                'final_price' => $finalPrice,
                'payment_url' => route('payments.show', $auction->id),
            ],
        ]);

        // Telegram notification
        try {
            $telegram = new TelegramService();
            $telegram->sendAuctionWinnerNotif($winner, $vehicleName, $finalPrice);
        } catch (\Exception $e) {
            Log::warning("Failed to send Telegram notification to winner: " . $e->getMessage());
        }
    }

    /**
     * Refund deposits of non-winners
     */
    private function refundNonWinnerDeposits(Auction $auction, int $winnerUserId)
    {
        Deposit::where('auction_id', $auction->id)
            ->where('user_id', '!=', $winnerUserId)
            ->whereIn('status', ['paid', 'approved'])
            ->update([
                'refund_status' => 'pending', // Admin akan proses manual refund
                'status' => 'refunded',
                'refunded_at' => now(),
            ]);
    }

    /**
     * Notify non-winners about auction end
     */
    private function notifyNonWinners(Auction $auction, int $winnerUserId)
    {
        $vehicleName = $auction->vehicle ? "{$auction->vehicle->brand} {$auction->vehicle->model}" : 'Kendaraan';

        // Get unique bidders except winner
        $nonWinnerUserIds = Bid::where('auction_id', $auction->id)
            ->where('user_id', '!=', $winnerUserId)
            ->distinct()
            ->pluck('user_id');

        foreach ($nonWinnerUserIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'type' => 'auction_lost',
                'title' => 'Lelang Berakhir',
                'message' => "Lelang {$vehicleName} telah berakhir. Sayangnya Anda tidak memenangkan lelang ini. Deposit Anda akan dikembalikan.",
                'data' => ['auction_id' => $auction->id],
            ]);
        }
    }

    /**
     * Process overdue payments - cancel winner and reopen auction or assign to next bidder
     */
    private function processOverduePayments()
    {
        $overdueAuctions = Auction::where('status', 'ended')
            ->whereNotNull('winner_id')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<', now())
            ->where('payment_completed', false)
            ->get();

        foreach ($overdueAuctions as $auction) {
            try {
                DB::beginTransaction();

                $oldWinnerId = $auction->winner_id;

                // Forfeit winner's deposit
                Deposit::where('auction_id', $auction->id)
                    ->where('user_id', $oldWinnerId)
                    ->update([
                        'refund_status' => 'forfeited',
                        'status' => 'forfeited',
                    ]);

                // Notify old winner
                Notification::create([
                    'user_id' => $oldWinnerId,
                    'type' => 'payment_overdue',
                    'title' => 'Pembayaran Gagal',
                    'message' => 'Batas waktu pembayaran telah lewat. Deposit Anda hangus.',
                    'data' => ['auction_id' => $auction->id],
                ]);

                // Get next highest bidder
                $nextBid = Bid::where('auction_id', $auction->id)
                    ->where('user_id', '!=', $oldWinnerId)
                    ->orderBy('bid_amount', 'desc')
                    ->first();

                if ($nextBid) {
                    // Assign to next bidder
                    $paymentDeadlineHours = (int) Setting::get('payment_deadline_hours', 24);
                    
                    $auction->update([
                        'winner_id' => $nextBid->user_id,
                        'final_price' => $nextBid->bid_amount,
                        'won_at' => now(),
                        'payment_deadline' => now()->addHours($paymentDeadlineHours),
                    ]);

                    $nextBid->update(['is_winning' => true, 'status' => 'won']);

                    // Notify new winner
                    $this->notifyWinner($auction, $nextBid);

                    $this->info("Auction #{$auction->id}: Assigned to next bidder User #{$nextBid->user_id}");
                } else {
                    // No other bidders - reopen or close
                    $auction->update([
                        'status' => 'cancelled',
                        'winner_id' => null,
                    ]);

                    // Return vehicle to available
                    if ($auction->vehicle) {
                        $auction->vehicle->update(['status' => 'available']);
                    }

                    $this->info("Auction #{$auction->id}: No other bidders, cancelled");
                }

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to process overdue auction #{$auction->id}: " . $e->getMessage());
            }
        }
    }
}
