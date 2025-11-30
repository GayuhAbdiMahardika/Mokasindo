<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Vehicle;

class AuctionsController extends Controller
{
    public function index(Request $request)
    {
        $query = Auction::with('vehicle.user')->latest();
        if ($request->filled('status')) $query->where('status', $request->status);
        $auctions = $query->paginate(20)->appends($request->query());
        return view('admin.auctions.index', compact('auctions'));
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
}
