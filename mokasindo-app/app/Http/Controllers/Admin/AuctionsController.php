<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\AuctionSchedule;
use App\Models\Bid;
use App\Models\Setting;
use App\Models\Vehicle;

class AuctionsController extends Controller
{
    public function index(Request $request)
    {
        $query = Auction::with(['vehicle.user', 'vehicle.images', 'schedule'])->latest();

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Schedule filter
        if ($request->filled('schedule_id')) {
            $query->where('auction_schedule_id', $request->schedule_id);
        }

        // Search by vehicle brand/model/year/plate or owner name
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->whereHas('vehicle', function ($vehicle) use ($term) {
                    $vehicle->where('brand', 'like', "%{$term}%")
                        ->orWhere('model', 'like', "%{$term}%")
                        ->orWhere('year', 'like', "%{$term}%")
                        ->orWhere('license_plate', 'like', "%{$term}%");
                })->orWhereHas('vehicle.user', function ($user) use ($term) {
                    $user->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            });
        }

        $auctions = $query->paginate(20)->appends($request->query());

        // Get schedules for filter dropdown
        $schedules = AuctionSchedule::orderBy('start_date', 'desc')->get();

        // Get approved vehicles not in active auction for adding
        $availableVehicles = Vehicle::with('user', 'images')
            ->where('status', 'approved')
            ->whereDoesntHave('auctions', function ($q) {
                $q->whereIn('status', ['scheduled', 'active']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get active schedules for adding vehicles
        $activeSchedules = AuctionSchedule::where('is_active', true)
            ->where('end_date', '>', now())
            ->orderBy('start_date')
            ->get();

        // Calculate stats
        $stats = [
            'total' => Auction::count(),
            'active' => Auction::where('status', 'active')->count(),
            'scheduled' => Auction::where('status', 'scheduled')->count(),
            'ended' => Auction::whereIn('status', ['ended', 'sold', 'cancelled'])->count(),
        ];

        return view('admin.auctions.index', compact('auctions', 'schedules', 'availableVehicles', 'activeSchedules', 'stats'));
    }

    /**
     * Add vehicle(s) to an auction schedule.
     */
    public function addVehicles(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:auction_schedules,id',
            'vehicle_ids' => 'required|array|min:1',
            'vehicle_ids.*' => 'exists:vehicles,id',
        ]);

        $schedule = AuctionSchedule::findOrFail($request->schedule_id);
        $depositPercentage = Setting::get('deposit_percentage', 5);
        
        $created = 0;
        $skipped = 0;

        foreach ($request->vehicle_ids as $vehicleId) {
            $vehicle = Vehicle::find($vehicleId);
            
            if (!$vehicle || $vehicle->status !== 'approved') {
                $skipped++;
                continue;
            }

            // Check if already has active/scheduled auction
            $exists = Auction::where('vehicle_id', $vehicle->id)
                ->whereIn('status', ['scheduled', 'active'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Auction::create([
                'vehicle_id' => $vehicle->id,
                'auction_schedule_id' => $schedule->id,
                'starting_price' => $vehicle->starting_price,
                'current_price' => 0,
                'deposit_amount' => $vehicle->starting_price * ($depositPercentage / 100),
                'deposit_percentage' => $depositPercentage,
                'start_time' => $schedule->start_date,
                'end_time' => $schedule->end_date,
                'status' => 'scheduled',
            ]);

            $created++;
        }

        $message = "Berhasil menambahkan {$created} kendaraan ke lelang \"{$schedule->title}\".";
        if ($skipped > 0) {
            $message .= " ({$skipped} dilewati)";
        }

        return back()->with('status', $message);
    }

    public function show(Auction $auction)
    {
        $auction->load('vehicle.user');
        $bids = Bid::where('auction_id', $auction->id)->latest()->take(50)->get();
        return view('admin.auctions.show', compact('auction', 'bids'));
    }

    public function bids(Auction $auction)
    {
        // snapshot of recent bids
        $bids = Bid::where('auction_id', $auction->id)->latest()->take(200)->get();
        return view('admin.auctions.bids', compact('auction', 'bids'));
    }

    public function forceEnd(Auction $auction)
    {
        $auction->status = 'ended';
        $auction->end_time = now();
        $auction->save();
        return back()->with('status', 'Auction force-ended');
    }

    public function reopen(Auction $auction)
    {
        $auction->status = 'reopened';
        $auction->save();
        return back()->with('status', 'Auction reopened');
    }

    public function adjustTimer(Request $request, Auction $auction)
    {
        $data = $request->validate(['end_time' => 'required|date']);
        $auction->end_time = $data['end_time'];
        $auction->save();
        return back()->with('status', 'Auction timer updated');
    }

    /**
     * Manually sync auction statuses (alternative to cron).
     */
    public function syncStatus()
    {
        $now = now();
        $results = ['activated' => 0, 'ended' => 0, 'cancelled' => 0];

        // 1. Activate scheduled auctions that have started
        $results['activated'] = Auction::where('status', 'scheduled')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->update(['status' => 'active']);

        // 2. End active auctions that have passed their end time
        $results['ended'] = Auction::where('status', 'active')
            ->where('end_time', '<=', $now)
            ->update(['status' => 'ended']);

        // 3. Cancel scheduled auctions that were never activated and passed end time
        $results['cancelled'] = Auction::where('status', 'scheduled')
            ->where('end_time', '<=', $now)
            ->update(['status' => 'cancelled']);

        $total = array_sum($results);
        if ($total > 0) {
            $message = "Status lelang diperbarui: {$results['activated']} diaktifkan, {$results['ended']} diakhiri, {$results['cancelled']} dibatalkan.";
        } else {
            $message = 'Tidak ada lelang yang perlu diperbarui.';
        }

        return back()->with('status', $message);
    }
}
