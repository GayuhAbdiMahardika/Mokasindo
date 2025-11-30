<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Auction;
use App\Models\AuctionSchedule;
use Illuminate\Support\Facades\Auth;

class VehiclesController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::with('user')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('brand', 'like', "%{$s}%")
                  ->orWhere('model', 'like', "%{$s}%")
                  ->orWhere('license_plate', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vehicles = $query->paginate(15)->appends($request->query());
        $schedules = AuctionSchedule::where('is_active', true)->get();

        return view('admin.vehicles.index', compact('vehicles', 'schedules'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|digits:4|integer',
            'starting_price' => 'required|numeric|min:0',
            'status' => 'required|in:draft,pending,approved,rejected,sold',
            'rejection_reason' => 'nullable|string',
        ]);

        $vehicle->update($data);

        return redirect()->route('admin.vehicles.index')->with('status', 'Vehicle updated');
    }

    public function approve(Vehicle $vehicle)
    {
        $vehicle->status = 'approved';
        $vehicle->approved_at = now();
        $vehicle->approved_by = Auth::id();
        $vehicle->rejection_reason = null;
        $vehicle->save();

        return back()->with('status', 'Vehicle approved');
    }

    public function reject(Request $request, Vehicle $vehicle)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $vehicle->status = 'rejected';
        $vehicle->rejection_reason = $request->rejection_reason;
        $vehicle->approved_at = null;
        $vehicle->approved_by = null;
        $vehicle->save();

        return back()->with('status', 'Vehicle rejected');
    }

    public function toggleFeature(Vehicle $vehicle)
    {
        $vehicle->is_featured = !$vehicle->is_featured;
        $vehicle->save();

        return back()->with('status', $vehicle->is_featured ? 'Marked as featured' : 'Removed from featured');
    }

    public function changeStatus(Request $request, Vehicle $vehicle)
    {
        $request->validate(['status' => 'required|in:draft,pending,approved,rejected,sold']);

        $vehicle->status = $request->status;
        $vehicle->save();

        return back()->with('status', 'Status updated');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'ids' => 'required|array',
            'schedule_id' => 'nullable|exists:auction_schedules,id'
        ]);

        $ids = $request->ids;

        if ($request->action === 'approve') {
            Vehicle::whereIn('id', $ids)->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id()
            ]);
        } elseif ($request->action === 'set_featured') {
            Vehicle::whereIn('id', $ids)->update(['is_featured' => true]);
        } elseif ($request->action === 'unset_featured') {
            Vehicle::whereIn('id', $ids)->update(['is_featured' => false]);
        }

        return back()->with('status', 'Bulk action applied');
    }

    /**
     * Assign selected vehicles to an auction schedule by creating scheduled auctions.
     */
    public function assignSchedule(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:auction_schedules,id',
            'ids' => 'required|array'
        ]);

        $schedule = AuctionSchedule::find($request->schedule_id);
        $created = 0;

        $vehicles = Vehicle::whereIn('id', $request->ids)->get();
        foreach ($vehicles as $vehicle) {
            // skip if there's already a scheduled/active auction for this vehicle
            $exists = Auction::where('vehicle_id', $vehicle->id)
                ->whereIn('status', ['scheduled', 'active'])->exists();
            if ($exists) continue;

            $auction = new Auction();
            $auction->vehicle_id = $vehicle->id;
            $auction->starting_price = $vehicle->starting_price;
            $auction->current_price = 0;
            $auction->deposit_amount = $vehicle->starting_price * 0.05;
            $auction->deposit_percentage = 5.00;
            $auction->start_time = $schedule->start_date;
            $auction->end_time = $schedule->end_date;
            $auction->status = 'scheduled';
            $auction->save();

            $created++;
        }

        return back()->with('status', "Assigned schedule to {$created} vehicles");
    }
}
