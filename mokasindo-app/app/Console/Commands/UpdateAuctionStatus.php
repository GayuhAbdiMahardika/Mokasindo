<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use Carbon\Carbon;

class UpdateAuctionStatus extends Command
{
    protected $signature = 'auctions:update-status';
    protected $description = 'Update auction status based on start/end time';

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

        // 2. End active auctions that have passed their end time
        $ended = Auction::where('status', 'active')
            ->where('end_time', '<=', $now)
            ->update(['status' => 'ended']);

        if ($ended > 0) {
            $this->info("Ended {$ended} auctions.");
        }

        // 3. Cancel scheduled auctions that were never activated and passed end time
        $cancelled = Auction::where('status', 'scheduled')
            ->where('end_time', '<=', $now)
            ->update(['status' => 'cancelled']);

        if ($cancelled > 0) {
            $this->info("Cancelled {$cancelled} expired scheduled auctions.");
        }

        if ($activated == 0 && $ended == 0 && $cancelled == 0) {
            $this->info('No auctions needed status update.');
        }

        return Command::SUCCESS;
    }
}
