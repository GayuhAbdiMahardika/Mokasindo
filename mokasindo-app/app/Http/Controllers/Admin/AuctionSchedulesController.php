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
        return view('admin.auction_schedules.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        AuctionSchedule::create($data);
        return redirect()->route('admin.auction-schedules.index')->with('status', 'Schedule created');
    }

    public function edit(AuctionSchedule $auctionSchedule)
    {
        return view('admin.auction_schedules.edit', ['schedule' => $auctionSchedule]);
    }

    public function update(Request $request, AuctionSchedule $auctionSchedule)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $auctionSchedule->update($data);
        return redirect()->route('admin.auction-schedules.index')->with('status', 'Schedule updated');
    }

    public function destroy(AuctionSchedule $auctionSchedule)
    {
        $auctionSchedule->delete();
        return back()->with('status', 'Schedule removed');
    }
}
