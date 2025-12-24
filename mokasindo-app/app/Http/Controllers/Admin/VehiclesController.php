<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Auction;
use App\Models\Setting;
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

        // Default auction duration from settings for approve modal
        $defaultDuration = Setting::get('default_auction_duration_hours', 48);

        return view('admin.vehicles.index', compact('vehicles', 'defaultDuration'));
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

    public function approve(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'duration_hours' => 'nullable|integer|min:1|max:720', // max 30 hari
        ]);

        $vehicle->status = 'approved';
        $vehicle->approved_at = now();
        $vehicle->approved_by = Auth::id();
        $vehicle->rejection_reason = null;
        $vehicle->save();

        $message = 'Vehicle approved';

        // Get duration from request or use default from settings
        $durationHours = $request->input('duration_hours', Setting::get('default_auction_duration_hours', 48));
        $depositPercentage = Setting::get('deposit_percentage', 5);

        // Check if already has active/scheduled auction
        $exists = Auction::where('vehicle_id', $vehicle->id)
            ->whereIn('status', ['scheduled', 'active'])
            ->exists();

        if (!$exists) {
            // Langsung buat auction dengan status active
            Auction::create([
                'vehicle_id' => $vehicle->id,
                'starting_price' => $vehicle->starting_price,
                'current_price' => 0,
                'deposit_amount' => $vehicle->starting_price * ($depositPercentage / 100),
                'deposit_percentage' => $depositPercentage,
                'duration_hours' => $durationHours,
                'start_time' => now(),
                'end_time' => now()->addHours($durationHours),
                'status' => 'active', // Langsung aktif
            ]);

            $message = "Vehicle approved & auction started ({$durationHours} hours duration)";
        } else {
            $message = 'Vehicle approved (already in active auction)';
        }

        return back()->with('status', $message);
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
            'duration_hours' => 'nullable|integer|min:1|max:720'
        ]);

        $ids = $request->ids;

        if ($request->action === 'approve') {
            // Update status kendaraan
            Vehicle::whereIn('id', $ids)->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id()
            ]);

            // Buat auction untuk setiap kendaraan yang di-approve
            $durationHours = $request->input('duration_hours', Setting::get('default_auction_duration_hours', 48));
            $depositPercentage = Setting::get('deposit_percentage', 5);
            $created = 0;

            $vehicles = Vehicle::whereIn('id', $ids)->get();
            foreach ($vehicles as $vehicle) {
                // Skip jika sudah ada lelang aktif
                $exists = Auction::where('vehicle_id', $vehicle->id)
                    ->whereIn('status', ['scheduled', 'active'])
                    ->exists();

                if (!$exists) {
                    Auction::create([
                        'vehicle_id' => $vehicle->id,
                        'starting_price' => $vehicle->starting_price,
                        'current_price' => 0,
                        'deposit_amount' => $vehicle->starting_price * ($depositPercentage / 100),
                        'deposit_percentage' => $depositPercentage,
                        'duration_hours' => $durationHours,
                        'start_time' => now(),
                        'end_time' => now()->addHours($durationHours),
                        'status' => 'active',
                    ]);
                    $created++;
                }
            }

            return back()->with('status', "Berhasil menyetujui " . count($ids) . " kendaraan. {$created} lelang aktif dibuat.");
        } elseif ($request->action === 'set_featured') {
            Vehicle::whereIn('id', $ids)->update(['is_featured' => true]);
        } elseif ($request->action === 'unset_featured') {
            Vehicle::whereIn('id', $ids)->update(['is_featured' => false]);
        }

        return back()->with('status', 'Bulk action applied');
    }
}
