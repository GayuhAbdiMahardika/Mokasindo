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
use Carbon\Carbon;

class AuctionController extends Controller
{
    /**
     * Display a listing of active auctions.
     */
    public function index(Request $request)
    {
        $query = Auction::with(['vehicle.primaryImage', 'vehicle.city', 'vehicle.province'])
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
        if ($request->filled('city_id')) {
            $query->whereHas('vehicle', function ($q) use ($request) {
                $q->where('city_id', $request->city_id);
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
            'vehicle.city',
            'vehicle.province',
            'vehicle.district',
            'vehicle.subDistrict',
            'bids' => function ($query) {
                $query->orderBy('amount', 'desc')->limit(10);
            },
            'bids.user'
        ])->findOrFail($id);

        // Check if auction is active
        if ($auction->status !== 'active') {
            abort(404, 'Auction not found or has ended');
        }

        // Check if user has paid deposit
        $hasDeposit = false;
        if (Auth::check()) {
            $hasDeposit = Deposit::where('user_id', Auth::id())
                ->where('auction_id', $auction->id)
                ->where('status', 'paid')
                ->exists();
        }

        // Get min bid increment from settings
        $minIncrement = Setting::where('key', 'min_bid_increment')->value('value') ?? 100000;

        // Calculate next minimum bid
        $nextMinBid = $auction->current_price + $minIncrement;

        // Get user's highest bid
        $userHighestBid = null;
        if (Auth::check()) {
            $userHighestBid = Bid::where('auction_id', $auction->id)
                ->where('user_id', Auth::id())
                ->orderBy('amount', 'desc')
                ->first();
        }

        // Check if user is winning
        $isWinning = false;
        if ($userHighestBid && $auction->current_price == $userHighestBid->amount) {
            $isWinning = true;
        }

        return view('pages.auctions.show', compact(
            'auction',
            'hasDeposit',
            'nextMinBid',
            'userHighestBid',
            'isWinning'
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
        $minDuration = Setting::where('key', 'min_auction_duration')->value('value') ?? 1;
        $maxDuration = Setting::where('key', 'max_auction_duration')->value('value') ?? 7;
        $depositPercentage = Setting::where('key', 'deposit_percentage')->value('value') ?? 5;

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
        $depositPercentage = Setting::where('key', 'deposit_percentage')->value('value') ?? 5;
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
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $auction = Auction::with('vehicle')->findOrFail($id);

        // Check if auction is active
        if ($auction->status !== 'active') {
            return response()->json(['error' => 'Auction is not active'], 400);
        }

        // Check if auction has ended
        if ($auction->end_time <= now()) {
            return response()->json(['error' => 'Auction has ended'], 400);
        }

        // Check if auction has started
        if ($auction->start_time > now()) {
            return response()->json(['error' => 'Auction has not started yet'], 400);
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in to bid'], 401);
        }

        // Check if user is not the vehicle owner
        if ($auction->vehicle->user_id === Auth::id()) {
            return response()->json(['error' => 'You cannot bid on your own vehicle'], 400);
        }

        // Check if user has paid deposit
        $hasDeposit = Deposit::where('user_id', Auth::id())
            ->where('auction_id', $auction->id)
            ->where('status', 'paid')
            ->exists();

        if (!$hasDeposit) {
            return response()->json(['error' => 'You must pay deposit first'], 400);
        }

        // Get min bid increment
        $minIncrement = Setting::where('key', 'min_bid_increment')->value('value') ?? 100000;
        $minBid = $auction->current_price + $minIncrement;

        // Validate bid amount
        if ($request->amount < $minBid) {
            return response()->json([
                'error' => 'Bid must be at least Rp ' . number_format($minBid, 0, ',', '.')
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Lock auction for update to prevent race condition
            $auction = Auction::where('id', $id)->lockForUpdate()->first();

            // Double check current price (race condition prevention)
            if ($request->amount <= $auction->current_price) {
                DB::rollBack();
                return response()->json([
                    'error' => 'Your bid is too low. Current price is Rp ' . number_format($auction->current_price, 0, ',', '.')
                ], 400);
            }

            // Create bid
            $bid = Bid::create([
                'auction_id' => $auction->id,
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'bid_time' => now(),
            ]);

            // Update auction current price
            $auction->update([
                'current_price' => $request->amount,
                'bid_count' => $auction->bid_count + 1,
            ]);

            // Check if auction is about to end (auto-extend logic)
            $extendMinutes = Setting::where('key', 'auction_extend_minutes')->value('value') ?? 5;
            $timeUntilEnd = $auction->end_time->diffInMinutes(now());

            if ($timeUntilEnd < $extendMinutes) {
                $newEndTime = now()->addMinutes($extendMinutes);
                $auction->update(['end_time' => $newEndTime]);
            }

            DB::commit();

            // TODO: Send notifications
            // - Notify previous highest bidder (outbid)
            // - Notify auction owner (new bid)
            // - Broadcast to WebSocket (real-time update)

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!',
                'data' => [
                    'current_price' => $auction->current_price,
                    'bid_count' => $auction->bid_count,
                    'end_time' => $auction->end_time->toIso8601String(),
                    'your_bid' => $request->amount,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to place bid. Please try again.'], 500);
        }
    }

    /**
     * Get auction real-time data (for polling/AJAX).
     */
    public function getData($id)
    {
        $auction = Auction::with(['bids' => function ($query) {
            $query->with('user')->orderBy('amount', 'desc')->limit(10);
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
                    'amount' => $bid->amount,
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
                ->orderBy('amount', 'desc')
                ->first();

            if ($winningBid) {
                // Check if reserve price is met
                if ($auction->reserve_price && $winningBid->amount < $auction->reserve_price) {
                    // Reserve not met - no winner
                    $auction->update([
                        'status' => 'ended',
                        'winner_id' => null,
                        'final_price' => $winningBid->amount,
                    ]);

                    // TODO: Refund all deposits
                } else {
                    // Auction won
                    $auction->update([
                        'status' => 'ended',
                        'winner_id' => $winningBid->user_id,
                        'final_price' => $winningBid->amount,
                    ]);

                    // Update vehicle status
                    $auction->vehicle->update(['status' => 'sold']);

                    // TODO: Send winner notification
                    // TODO: Create payment invoice for winner
                    // TODO: Refund deposits for non-winners
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
        if (auth()->check()) {
            $userBid = $auction->bids()
                ->where('user_id', auth()->id())
                ->orderBy('amount', 'desc')
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
                    'amount' => $bid->amount,
                    'time' => $bid->created_at->diffForHumans(),
                    'is_current_user' => auth()->check() && $bid->user_id === auth()->id()
                ];
            }),
            'user_bid' => $userBid ? [
                'amount' => $userBid->amount,
                'is_highest' => $userBid->amount >= $auction->current_price
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
