<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuctionSchedule;

class AuctionSchedulesController extends Controller
{
    public function index()
    {
        $schedules = AuctionSchedule::orderBy('start_date', 'desc')->paginate(20);
        return view('admin.auction_schedules.index', compact('schedules'));
    }

    public function create()
    {
        // provide cities list for location select
        $cities = \App\Models\City::orderBy('name')->get();
        return view('admin.auction_schedules.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location_id' => 'required|exists:cities,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        AuctionSchedule::create($data);
        return redirect()->route('admin.auction-schedules.index')->with('status', 'Schedule created');
    }

    public function edit(AuctionSchedule $auctionSchedule)
    {
        $cities = \App\Models\City::orderBy('name')->get();
        return view('admin.auction_schedules.edit', ['schedule' => $auctionSchedule, 'cities' => $cities]);
    }

    public function update(Request $request, AuctionSchedule $auctionSchedule)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location_id' => 'required|exists:cities,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        $auctionSchedule->update($data);
        return back()->with('status', 'Schedule updated');
    }

    public function destroy(AuctionSchedule $auctionSchedule)
    {
        $auctionSchedule->delete();
        return back()->with('status', 'Schedule removed');
    }
}
