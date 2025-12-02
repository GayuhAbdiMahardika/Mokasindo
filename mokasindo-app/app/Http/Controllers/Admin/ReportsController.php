<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Auction, Bid, User, Vehicle, Deposit, Payment};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Calculate stats
        $stats = $this->calculateStats($startDate, $endDate);
        
        // Get chart data
        $revenueChartLabels = $this->getRevenueChartLabels($startDate, $endDate);
        $revenueChartData = $this->getRevenueChartData($startDate, $endDate);
        
        // Get auction status distribution
        $auctionStatusData = $this->getAuctionStatusData();
        
        // Get top bidders
        $topBidders = $this->getTopBidders($startDate, $endDate);
        
        // Get popular vehicles
        $popularVehicles = $this->getPopularVehicles($startDate, $endDate);

        return view('admin.reports.index', compact(
            'stats',
            'revenueChartLabels',
            'revenueChartData',
            'auctionStatusData',
            'topBidders',
            'popularVehicles'
        ));
    }

    private function calculateStats($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate)->endOfDay();

        // Calculate previous period for comparison
        $periodDays = $start->diffInDays($end);
        $prevStart = $start->copy()->subDays($periodDays + 1);
        $prevEnd = $start->copy()->subDay()->endOfDay();

        // Revenue
        $totalRevenue = Payment::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->sum('total_amount');

        $prevRevenue = Payment::whereBetween('created_at', [$prevStart, $prevEnd])
            ->where('status', 'completed')
            ->sum('total_amount');

        $revenueGrowth = $prevRevenue > 0 ? (($totalRevenue - $prevRevenue) / $prevRevenue) * 100 : 0;

        // Auctions
        $totalAuctions = Auction::whereBetween('created_at', [$start, $end])->count();
        $completedAuctions = Auction::whereBetween('end_time', [$start, $end])
            ->where('status', 'ended')
            ->count();

        // Users
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $newUsers = User::whereBetween('created_at', [$start, $end])
            ->where('role', '!=', 'admin')
            ->count();

        // Vehicles
        $totalVehicles = Vehicle::count();
        $pendingApproval = Vehicle::where('status', 'pending')->count();

        // Transactions
        $depositTopups = Deposit::whereBetween('created_at', [$start, $end])
            ->where('type', 'topup')
            ->where('status', 'approved')
            ->sum('amount');

        $topupCount = Deposit::whereBetween('created_at', [$start, $end])
            ->where('type', 'topup')
            ->where('status', 'approved')
            ->count();

        $paymentsReceived = Payment::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->sum('total_amount');

        $paymentCount = Payment::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->count();

        $withdrawals = Deposit::whereBetween('created_at', [$start, $end])
            ->where('type', 'withdrawal')
            ->where('status', 'approved')
            ->sum('amount');

        $withdrawalCount = Deposit::whereBetween('created_at', [$start, $end])
            ->where('type', 'withdrawal')
            ->count();

        return [
            'total_revenue' => $totalRevenue,
            'revenue_growth' => $revenueGrowth,
            'total_auctions' => $totalAuctions,
            'completed_auctions' => $completedAuctions,
            'total_users' => $totalUsers,
            'new_users' => $newUsers,
            'total_vehicles' => $totalVehicles,
            'pending_approval' => $pendingApproval,
            'deposit_topups' => $depositTopups,
            'topup_count' => $topupCount,
            'payments_received' => $paymentsReceived,
            'payment_count' => $paymentCount,
            'withdrawals' => $withdrawals,
            'withdrawal_count' => $withdrawalCount,
        ];
    }

    private function getRevenueChartLabels($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $labels = [];
        $current = $start->copy();

        while ($current <= $end) {
            $labels[] = $current->format('M d');
            $current->addDay();
        }

        return $labels;
    }

    private function getRevenueChartData($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $data = [];
        $current = $start->copy();

        while ($current <= $end) {
            $revenue = Payment::whereDate('created_at', $current)
                ->where('status', 'completed')
                ->sum('total_amount');
            
            $data[] = $revenue;
            $current->addDay();
        }

        return $data;
    }

    private function getAuctionStatusData()
    {
        return [
            Auction::where('status', 'active')->count(),
            Auction::where('status', 'upcoming')->count(),
            Auction::where('status', 'ended')->count(),
            Auction::where('status', 'cancelled')->count(),
        ];
    }

    private function getTopBidders($startDate, $endDate)
    {
        return Bid::select('user_id', DB::raw('count(*) as bid_count'), DB::raw('sum(amount) as total_amount'))
            ->with('user')
            ->whereBetween('created_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])
            ->groupBy('user_id')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get()
            ->map(function($bid) {
                return (object)[
                    'user_name' => $bid->user->name,
                    'bid_count' => $bid->bid_count,
                    'total_amount' => $bid->total_amount
                ];
            });
    }

    private function getPopularVehicles($startDate, $endDate)
    {
        return Vehicle::select('vehicles.*', DB::raw('count(bids.id) as bids_count'))
            ->leftJoin('auctions', 'vehicles.id', '=', 'auctions.vehicle_id')
            ->leftJoin('bids', 'auctions.id', '=', 'bids.auction_id')
            ->whereBetween('bids.created_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])
            ->groupBy('vehicles.id')
            ->orderByDesc('bids_count')
            ->limit(10)
            ->get();
    }
}
