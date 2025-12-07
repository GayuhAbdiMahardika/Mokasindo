<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Deposit;
use App\Models\Payment;
use App\Models\Wishlist;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistics
        $stats = [
            'total_vehicles' => Vehicle::where('user_id', $user->id)->count(),
            'active_vehicles' => Vehicle::where('user_id', $user->id)->where('status', 'approved')->count(),
            'total_bids' => Bid::where('user_id', $user->id)->count(),
            'won_auctions' => Bid::where('user_id', $user->id)->where('is_winner', true)->count(),
            'deposit_balance' => Deposit::where('user_id', $user->id)->where('status', 'approved')->sum('amount'),
            'total_wishlist' => Wishlist::where('user_id', $user->id)->count(),
        ];

        // Recent Activities
        $recent_vehicles = Vehicle::where('user_id', $user->id)
            ->with(['primaryImage'])
            ->latest()
            ->take(3)
            ->get();

        $recent_bids = Bid::where('user_id', $user->id)
            ->with(['auction.vehicle.primaryImage'])
            ->latest()
            ->take(5)
            ->get();

        $recent_payments = Payment::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Active Auctions (vehicles yang sedang dilelang milik user)
        $my_active_auctions = Auction::whereHas('vehicle', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'active')
            ->with(['vehicle.primaryImage', 'bids'])
            ->get();

        // Auctions in User's Area (lelang aktif di kota user)
                $area_auctions = Auction::where('status', 'active')
                        ->whereHas('vehicle', function($query) use ($user) {
                                        $query->where('city', $user->city)
                                            ->where('user_id', '!=', $user->id); // Exclude user's own vehicles
                        })
                        ->with(['vehicle.primaryImage', 'bids'])
            ->orderBy('end_time', 'asc')
            ->take(6)
            ->get();

        return view('pages.dashboard.index', compact(
            'stats',
            'recent_vehicles',
            'recent_bids',
            'recent_payments',
            'my_active_auctions',
            'area_auctions'
        ));
    }
}
