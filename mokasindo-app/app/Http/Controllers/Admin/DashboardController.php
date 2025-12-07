<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Auction;
use App\Models\Payment;
use App\Models\Deposit;
use App\Models\JobApplication;
use App\Models\Inquiry;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total_users' => User::count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'total_vehicles' => Vehicle::count(),
            'pending_vehicles' => Vehicle::where('status', 'pending')->count(),
            'total_auctions' => Auction::count(),
            'active_auctions' => Auction::where('status', 'active')->count(),
            'total_revenue' => Payment::where('status', 'success')->sum('amount'),
            'pending_deposits' => Deposit::where('status', 'pending')->count(),
            'new_inquiries' => Inquiry::where('status', 'new')->count(),
            'new_applications' => JobApplication::where('status', 'pending')->count(),
        ];

        // Recent Activities
        $recent_users = User::latest()->take(5)->get();
        $recent_vehicles = Vehicle::with('user')->latest()->take(5)->get();
        $recent_payments = Payment::with('user')->latest()->take(5)->get();
        $pending_deposits = Deposit::with('user')->where('status', 'pending')->latest()->take(5)->get();

        // Charts Data - Monthly Revenue
        $monthly_revenue = Payment::where('status', 'success')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        // Charts Data - User Growth
        $user_growth = User::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        // Transactions today and revenue today
        $transactions_today = Payment::whereDate('created_at', now()->toDateString())->count();
        $revenue_today = Payment::whereDate('created_at', now()->toDateString())->where('status', 'success')->sum('amount');

        // Last 7 days: revenue and new users (labels and series)
        $labels7 = [];
        $revenueLast7 = [];
        $usersLast7 = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $labels7[] = now()->subDays($i)->format('d M');

            $dayRevenue = Payment::whereDate('created_at', $date)
                ->where('status', 'success')
                ->sum('amount');
            $revenueLast7[] = (float) $dayRevenue;

            $dayUsers = User::whereDate('created_at', $date)->count();
            $usersLast7[] = (int) $dayUsers;
        }

        // Auction Status Distribution
        $auction_status = Auction::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return view('admin.dashboard', compact(
            'stats',
            'recent_users',
            'recent_vehicles',
            'recent_payments',
            'pending_deposits',
            'monthly_revenue',
            'user_growth',
            'auction_status',
            'transactions_today',
            'revenue_today',
            'labels7',
            'revenueLast7',
            'usersLast7'
        ));
    }
}
