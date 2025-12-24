<?php

namespace App\Http\Controllers; // <--- Namespace Standar

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bid;
use App\Models\Auction;

class MyBidController extends Controller
{
    public function index()
    {
        $bids = Bid::where('user_id', Auth::id())
            ->with(['auction.vehicle.primaryImage'])
            ->orderBy('bid_amount', 'desc')
            ->get()
            ->unique('auction_id');

        return view('pages.profile.bids', compact('bids'));
    }

    /**
     * Menampilkan lelang yang dimenangkan user
     */
    public function wins()
    {
        $wonAuctions = Auction::where('winner_id', Auth::id())
            ->with(['vehicle.primaryImage', 'bids' => function ($q) {
                $q->where('user_id', Auth::id())->orderBy('bid_amount', 'desc');
            }])
            ->orderBy('won_at', 'desc')
            ->get();

        return view('pages.profile.wins', compact('wonAuctions'));
    }
}