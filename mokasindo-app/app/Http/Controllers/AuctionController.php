<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Deposit;
use App\Models\Setting;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config as MidtransConfig;
use Midtrans\Transaction as MidtransTransaction;

class AuctionController extends Controller
{
    /**
     * Display a listing of active auctions.
     */
    public function index(Request $request)
    {
        $query = Auction::with(['vehicle.primaryImage'])
            ->where('status', 'active')
            ->where('start_time', '<=', now())
            ->where('end_time', '>', now());

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('vehicle', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        // Filter by city
            if ($request->filled('city')) {
                $query->whereHas('vehicle', function($q) use ($request) {
                    $q->where('city', 'like', "%{$request->city}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'ending_soon');
        switch ($sort) {
            case 'ending_soon':
                $query->orderBy('end_time', 'asc');
                break;
            case 'newest':
                $query->orderBy('start_time', 'desc');
                break;
            case 'highest_bid':
                $query->orderBy('current_price', 'desc');
                break;
            case 'lowest_price':
                $query->orderBy('current_price', 'asc');
                break;
        }

        $auctions = $query->paginate(12);

        return view('pages.auctions.index', compact('auctions'));
    }

    /**
     * Show auction detail with real-time bidding.
     */
    public function show($id)
    {
        $auction = Auction::with([
            'vehicle.images',
            'bids' => function ($query) {
                $query->orderBy('bid_amount', 'desc')->limit(10);
            },
            'bids.user'
        ])->findOrFail($id);

        // Check if auction is active
        if ($auction->status !== 'active') {
            abort(404, 'Auction not found or has ended');
        }

            $hasDeposit = false; // Removed unused deposit check

        // Get min bid increment from settings
        $minIncrement = Setting::where('key', 'min_bid_increment')->value('value') ?? 100000;

        // Calculate next minimum bid
        $nextMinBid = $auction->current_price + $minIncrement;

        // Get user's highest bid
        $userHighestBid = null;
        $pendingDeposit = null;
        if (Auth::check()) {
            $userHighestBid = Bid::where('auction_id', $auction->id)
                ->where('user_id', Auth::id())
                ->orderBy('bid_amount', 'desc')
                ->first();

            $pendingDeposit = Deposit::where('auction_id', $auction->id)
                ->where('user_id', Auth::id())
                ->whereIn('status', ['pending'])
                ->orderByDesc('id')
                ->first();
        }

        // Check if user is winning
        $isWinning = false;
        if ($userHighestBid && $auction->current_price == $userHighestBid->bid_amount) {
            $isWinning = true;
        }

        return view('pages.auctions.show', compact(
            'auction',
            'nextMinBid',
            'userHighestBid',
            'isWinning',
            'pendingDeposit'
        ));
    }

    /**
     * Create auction from vehicle (admin/owner only).
     */
    public function create($vehicleId)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        // Check if user owns the vehicle or is admin
        if ($vehicle->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action');
        }

        // Check if vehicle already has active auction
        $existingAuction = Auction::where('vehicle_id', $vehicle->id)
            ->whereIn('status', ['active', 'pending'])
            ->exists();

        if ($existingAuction) {
            return back()->with('error', 'Vehicle already has an active auction');
        }

        // Get min and max auction duration from settings
        $minDuration = Setting::get('min_auction_duration', 1);
        $maxDuration = Setting::get('max_auction_duration', 7);
        $depositPercentage = Setting::get('deposit_percentage', 5);

        return view('pages.auctions.create', compact('vehicle', 'minDuration', 'maxDuration', 'depositPercentage'));
    }

    /**
     * Store new auction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_time' => 'required|date|after:now',
            'duration_days' => 'required|integer|min:1|max:30',
            'starting_price' => 'required|numeric|min:1000000',
            'reserve_price' => 'nullable|numeric|min:' . $request->starting_price,
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        // Check ownership
        if ($vehicle->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Calculate end time
        $startTime = Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addDays($request->duration_days);

        // Get deposit percentage from settings
        $depositPercentage = Setting::get('deposit_percentage', 5);
        $depositAmount = ($request->starting_price * $depositPercentage) / 100;

        $auction = Auction::create([
            'vehicle_id' => $request->vehicle_id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'starting_price' => $request->starting_price,
            'current_price' => $request->starting_price,
            'reserve_price' => $request->reserve_price,
            'deposit_amount' => $depositAmount,
            'status' => 'pending',
        ]);

        return redirect()->route('auctions.show', $auction->id)
            ->with('success', 'Auction created successfully! It will start at ' . $startTime->format('d M Y H:i'));
    }

    /**
     * Place a bid on an auction.
     */
    public function placeBid(Request $request, $id)
    {
        // Normalize amount to plain integer (strip thousand separators/commas)
        $rawAmount = is_string($request->amount) ? $request->amount : (string) $request->amount;
        $normalized = (int) preg_replace('/[^0-9]/', '', $rawAmount);
        $request->merge(['amount' => $normalized]);

        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $auction = Auction::with('vehicle')->findOrFail($id);

        // Check if auction is active
        if ($auction->status !== 'active') {
            return $request->wantsJson()
                ? response()->json(['error' => 'Auction is not active'], 400)
                : back()->with('error', 'Lelang tidak aktif');
        }

        // Check if auction has ended
        if ($auction->end_time <= now()) {
            return $request->wantsJson()
                ? response()->json(['error' => 'Auction has ended'], 400)
                : back()->with('error', 'Lelang sudah berakhir');
        }

        // Check if auction has started
        if ($auction->start_time > now()) {
            return $request->wantsJson()
                ? response()->json(['error' => 'Auction has not started yet'], 400)
                : back()->with('error', 'Lelang belum dimulai');
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return $request->wantsJson()
                ? response()->json(['error' => 'You must be logged in to bid'], 401)
                : redirect()->route('login')->with('error', 'Silakan login untuk memasang bid');
        }

        // Check if user is not the vehicle owner
        if ($auction->vehicle->user_id === Auth::id()) {
            return $request->wantsJson()
                ? response()->json(['error' => 'You cannot bid on your own vehicle'], 400)
                : back()->with('error', 'Anda tidak bisa bid pada kendaraan sendiri');
        }

        // Get min bid increment
        $minIncrement = Setting::where('key', 'min_bid_increment')->value('value') ?? 100000;
        $minBid = $auction->current_price + $minIncrement;

        // Validate bid amount
        if ($request->amount < $minBid) {
            $msg = 'Bid minimal Rp ' . number_format($minBid, 0, ',', '.');
            return $request->wantsJson()
                ? response()->json(['error' => $msg], 400)
                : back()->with('error', $msg)->withInput();
        }

        try {
            DB::beginTransaction();

            // Lock auction for update to prevent race condition
            $auction = Auction::where('id', $id)->lockForUpdate()->first();

            // Double check current price (race condition prevention)
            if ($request->amount <= $auction->current_price) {
                DB::rollBack();
                $msg = 'Bid terlalu rendah. Harga saat ini Rp ' . number_format($auction->current_price, 0, ',', '.');
                return $request->wantsJson()
                    ? response()->json(['error' => $msg], 400)
                    : back()->with('error', $msg)->withInput();
            }

            // Calculate deposit amount based on setting (default 5%)
            $depositPercentage = Setting::get('deposit_percentage', 5);
            $depositAmount = (int) ceil($request->amount * $depositPercentage / 100);

            // Configure Midtrans (ensure keys are present)
            if (empty(config('services.midtrans.server_key'))) {
                throw new \RuntimeException('Midtrans server key belum dikonfigurasi');
            }
            $this->configureMidtrans();

            $orderId = 'BIDDEP-' . strtoupper(Str::random(12));

            // Create deposit record (pending)
            $deposit = Deposit::create([
                'auction_id' => $auction->id,
                'user_id' => Auth::id(),
                'amount' => $depositAmount,
                'status' => 'pending',
                'type' => 'bid_deposit',
                'payment_method' => 'midtrans',
                'order_number' => $orderId,
                'transaction_code' => $orderId,
                'payment_url' => null,
            ]);

            // Prepare Midtrans Snap transaction
            $user = Auth::user();
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $depositAmount,
                ],
                'callbacks' => [
                    'finish' => route('midtrans.finish'),
                    'unfinish' => route('midtrans.unfinish'),
                    'error' => route('midtrans.error'),
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
                'item_details' => [
                    [
                        'id' => 'bid-deposit-' . $auction->id,
                        'price' => $depositAmount,
                        'quantity' => 1,
                        'name' => 'Bid Deposit ' . ($depositPercentage) . '%',
                    ],
                ],
            ];

            try {
                $snap = Snap::createTransaction($params);
            } catch (\Exception $e) {
                Log::error('Midtrans Snap createTransaction failed', [
                    'order_id' => $orderId,
                    'auction_id' => $auction->id,
                    'user_id' => Auth::id(),
                    'amount' => $depositAmount,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }

            $deposit->update([
                'snap_token' => $snap->token ?? null,
                'snap_redirect_url' => $snap->redirect_url ?? null,
                'payment_url' => $snap->redirect_url ?? null,
            ]);

            // Remember previous highest bid to refund after placing new one
            $previousHighest = Bid::where('auction_id', $auction->id)
                ->orderBy('bid_amount', 'desc')
                ->first();

            // Create bid (ties to deposit)
            $bid = Bid::create([
                'auction_id' => $auction->id,
                'user_id' => Auth::id(),
                'deposit_id' => $deposit->id,
                'bid_amount' => $request->amount,
                'bid_time' => now(),
            ]);

            // Update auction current price
            $auction->update([
                'current_price' => $request->amount,
                'bid_count' => $auction->bid_count + 1,
            ]);

            // Refund previous highest deposit (logical flag only; integrate gateway refund as needed)
            if ($previousHighest && $previousHighest->deposit) {
                $prevDeposit = $previousHighest->deposit;

                // Attempt Midtrans refund for paid/pending deposits
                if ($prevDeposit->payment_method === 'midtrans' && $prevDeposit->order_number) {
                    try {
                        MidtransTransaction::refund($prevDeposit->order_number, [
                            'refund_key' => 'outbid-' . Str::random(6),
                            'reason' => 'Outbid refund',
                        ]);
                    } catch (\Exception $e) {
                        // swallow and still mark refunded logically
                    }
                }
                $depositPercentage = Setting::get('deposit_percentage', 5);
                $prevDeposit->update([
                    'refund_status' => 'refunded',
                    'refunded_at' => now(),
                    'status' => $prevDeposit->status === 'paid' ? 'refunded' : $prevDeposit->status,
                ]);
            }

            // Check if auction is about to end (auto-extend logic)
            $extendMinutes = Setting::where('key', 'auction_extend_minutes')->value('value') ?? 5;
            $timeUntilEnd = $auction->end_time->diffInMinutes(now());

            if ($timeUntilEnd < $extendMinutes) {
                $newEndTime = now()->addMinutes($extendMinutes);
                $auction->update(['end_time' => $newEndTime]);
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bid placed successfully! Complete deposit to confirm.',
                    'payment_url' => $snap->redirect_url ?? null,
                    'data' => [
                        'current_price' => $auction->current_price,
                        'bid_count' => $auction->bid_count,
                        'end_time' => $auction->end_time->toIso8601String(),
                        'your_bid' => $request->amount,
                    ]
                ]);
            }

            // Redirect user to Midtrans payment page for deposit
            if (!empty($snap->redirect_url)) {
                return redirect($snap->redirect_url)->with('success', 'Bid berhasil dipasang, selesaikan deposit Anda.');
            }

            return back()->with('success', 'Bid berhasil dipasang.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bid placement failed', [
                'auction_id' => $id,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            $errorMessage = 'Gagal memasang bid, coba lagi.';
            if (!app()->environment('production')) {
                $errorMessage .= ' (' . $e->getMessage() . ')';
            }

            return $request->wantsJson()
                ? response()->json(['error' => $errorMessage], 500)
                : back()->with('error', $errorMessage);
        }
    }

    private function configureMidtrans(): void
    {
        MidtransConfig::$serverKey = config('services.midtrans.server_key');
        MidtransConfig::$isProduction = (bool) config('services.midtrans.is_production', false);
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = (bool) config('services.midtrans.enable_3ds', true);
    }

    /**
     * Get auction real-time data (for polling/AJAX).
     */
    public function getData($id)
    {
        $auction = Auction::with(['bids' => function ($query) {
            $query->with('user')->orderBy('bid_amount', 'desc')->limit(10);
        }])->findOrFail($id);

        return response()->json([
            'current_price' => $auction->current_price,
            'bid_count' => $auction->bid_count,
            'end_time' => $auction->end_time->toIso8601String(),
            'status' => $auction->status,
            'time_left' => $auction->end_time->diffInSeconds(now()),
            'is_ending_soon' => $auction->end_time->diffInMinutes(now()) < 5,
            'bids' => $auction->bids->map(function ($bid) {
                return [
                    'user_name' => substr($bid->user->name, 0, 3) . '***', // Hide full name
                    'amount' => $bid->bid_amount,
                    'bid_time' => $bid->bid_time->diffForHumans(),
                ];
            }),
        ]);
    }

    /**
     * End auction (called by scheduler or manual).
     */
    public function end($id)
    {
        $auction = Auction::with(['bids', 'vehicle'])->findOrFail($id);

        // Check if already ended
        if ($auction->status === 'ended') {
            return response()->json(['message' => 'Auction already ended'], 400);
        }

        try {
            DB::beginTransaction();

            // Get highest bid
            $winningBid = Bid::where('auction_id', $auction->id)
                ->orderBy('bid_amount', 'desc')
                ->first();

            if ($winningBid) {
                // Check if reserve price is met
                if ($auction->reserve_price && $winningBid->bid_amount < $auction->reserve_price) {
                    // Reserve not met - no winner
                    $auction->update([
                        'status' => 'ended',
                        'winner_id' => null,
                        'final_price' => $winningBid->bid_amount,
                    ]);

                    // Mark all deposits as refunded (manual settlement)
                    Deposit::where('auction_id', $auction->id)
                        ->where(function ($q) {
                            $q->whereNull('refund_status')->orWhere('refund_status', '!=', 'refunded');
                        })
                        ->update([
                            'refund_status' => 'refunded',
                            'refunded_at' => now(),
                            'status' => 'refunded',
                        ]);
                } else {
                    // Auction won
                    $auction->update([
                        'status' => 'ended',
                        'winner_id' => $winningBid->user_id,
                        'final_price' => $winningBid->bid_amount,
                    ]);

                    // Update vehicle status
                    $auction->vehicle->update(['status' => 'sold']);

                    // TODO: Send winner notification
                    // TODO: Create payment invoice for winner

                    // Mark deposits of non-winners as refunded (manual status only)
                    Deposit::where('auction_id', $auction->id)
                        ->where('user_id', '!=', $winningBid->user_id)
                        ->where(function ($q) {
                            $q->whereNull('refund_status')->orWhere('refund_status', '!=', 'refunded');
                        })
                        ->update([
                            'refund_status' => 'refunded',
                            'refunded_at' => now(),
                            'status' => 'refunded',
                        ]);
                }
            } else {
                // No bids - auction failed
                $auction->update([
                    'status' => 'ended',
                    'winner_id' => null,
                    'final_price' => null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Auction ended successfully',
                'winner_id' => $auction->winner_id,
                'final_price' => $auction->final_price,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to end auction'], 500);
        }
    }

    /**
     * Cancel auction (admin/owner only before start).
     */
    public function cancel($id)
    {
        $auction = Auction::with('vehicle')->findOrFail($id);

        // Check authorization
        if ($auction->vehicle->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Can only cancel if not started or no bids
        if ($auction->status === 'active' && $auction->bid_count > 0) {
            return back()->with('error', 'Cannot cancel auction with existing bids');
        }

        $auction->update(['status' => 'cancelled']);

        // TODO: Refund all deposits

        return back()->with('success', 'Auction cancelled successfully');
    }

    /**
     * Get bids data for real-time updates (API)
     */
    public function getBidsData($id)
    {
        $auction = Auction::with(['bids' => function($query) {
            $query->latest()->limit(10);
        }, 'bids.user'])->findOrFail($id);

        $userBid = null;
        if (Auth::check()) {
            $userBid = $auction->bids()
                ->where('user_id', Auth::id())
                ->orderBy('bid_amount', 'desc')
                ->first();
        }

        return response()->json([
            'current_price' => $auction->current_price,
            'bid_count' => $auction->bids()->count(),
            'highest_bidder' => $auction->bids()->latest()->first()?->user->name ?? 'Belum ada',
            'bids' => $auction->bids->map(function($bid) {
                return [
                    'id' => $bid->id,
                    'user_name' => $bid->user->name,
                    'amount' => $bid->bid_amount,
                    'time' => $bid->created_at->diffForHumans(),
                    'is_current_user' => Auth::check() && $bid->user_id === Auth::id()
                ];
            }),
            'user_bid' => $userBid ? [
                'amount' => $userBid->bid_amount,
                'is_highest' => $userBid->bid_amount >= $auction->current_price
            ] : null
        ]);
    }

    /**
     * Get auction status data for real-time updates (API)
     */
    public function getStatusData($id)
    {
        $auction = Auction::findOrFail($id);

        $timeRemaining = null;
        if ($auction->status === 'active') {
            $now = now();
            $endTime = $auction->end_time;
            $diff = $now->diffInSeconds($endTime, false);
            
            if ($diff > 0) {
                $hours = floor($diff / 3600);
                $minutes = floor(($diff % 3600) / 60);
                $seconds = $diff % 60;
                $timeRemaining = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            } else {
                $timeRemaining = '00:00:00';
            }
        }

        return response()->json([
            'status' => $auction->status,
            'current_price' => $auction->current_price,
            'time_remaining' => $timeRemaining,
            'end_time' => $auction->end_time->toIso8601String(),
            'bid_count' => $auction->bids()->count()
        ]);
    }
}
