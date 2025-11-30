<?php

namespace App\Http\Controllers; // <--- Namespace Standar

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bid;

class MyBidController extends Controller
{
    public function index()
    {
        $bids = Bid::where('user_id', Auth::id())
            ->with(['auction.vehicle.primaryImage'])
            ->orderBy('amount', 'desc')
            ->get()
            ->unique('auction_id');

        return view('pages.profile.bids', compact('bids'));
    }
}