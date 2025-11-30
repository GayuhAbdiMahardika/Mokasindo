<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Deposit;
use App\Models\Payment;
use Carbon\Carbon;

class OverviewController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'owner') {
            return $this->unauthorizedAccess();
        }

        $stats = $this->getRealStats();
        $recentTransactions = $this->getRealTransactions();
        $topAuctions = $this->getRealTopAuctions();
        $inventoryStatus = $this->getRealInventoryStatus();
        $depositAnalytics = $this->getRealDepositAnalytics();
        $chartData = $this->getRealWeeklyChartData();

        return view('pages.owner.overview', compact(
            'stats', 
            'recentTransactions', 
            'topAuctions',
            'inventoryStatus',
            'depositAnalytics',
            'chartData'
        ));
    }

    public function getWeeklyChart(Request $request)
    {
        if (auth()->user()->role !== 'owner') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $chartData = $this->getRealWeeklyChartData();
        return response()->json($chartData);
    }

    public function getQuickStats()
    {
        if (auth()->user()->role !== 'owner') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $stats = $this->getRealStats();
        return response()->json($stats);
    }

    private function getRealWeeklyChartData()
    {
        $dates = [];
        $revenueData = [];
        $userActivityData = [];
        $auctionActivityData = [];
        $transactionData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $dates[] = $date->format('D');
            
            $dailyRevenue = Payment::where('status', 'success')
                ->whereDate('paid_at', $dateString)
                ->sum('amount');
            $revenueData[] = (float) $dailyRevenue;
            
            $dailyUsers = User::whereDate('created_at', $dateString)->count();
            $userActivityData[] = $dailyUsers;
            
            $dailyAuctions = Auction::whereDate('created_at', $dateString)->count();
            $auctionActivityData[] = $dailyAuctions;
            
            $dailyTransactions = Transaction::whereDate('created_at', $dateString)->count();
            $transactionData[] = $dailyTransactions;
        }
        
        return [
            'labels' => $dates,
            'dates_full' => $this->getFullWeekDates(),
            'revenue' => $revenueData,
            'users' => $userActivityData,
            'auctions' => $auctionActivityData,
            'transactions' => $transactionData,
            'total_week_revenue' => array_sum($revenueData),
            'total_week_users' => array_sum($userActivityData),
            'total_week_auctions' => array_sum($auctionActivityData),
            'total_week_transactions' => array_sum($transactionData),
        ];
    }

    private function getFullWeekDates()
    {
        $fullDates = [];
        for ($i = 6; $i >= 0; $i--) {
            $fullDates[] = Carbon::now()->subDays($i)->format('d M Y');
        }
        return $fullDates;
    }

    private function getRealStats()
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();

        return [
            'total_users' => User::count(),
            'total_vehicles' => Vehicle::count(),
            'total_auctions' => Auction::count(),
            'total_transactions' => Transaction::count(),
            'total_revenue' => Payment::where('status', 'success')->sum('amount'),
            'platform_fee_earnings' => Transaction::sum('platform_fee'),
            'deposit_forfeited_earnings' => Deposit::where('status', 'forfeited')->sum('forfeit_to_platform'),
            'total_earnings' => Transaction::sum('platform_fee') + Deposit::where('status', 'forfeited')->sum('forfeit_to_platform'),
            'weekly_users' => User::where('created_at', '>=', $weekStart)->count(),
            'weekly_vehicles' => Vehicle::where('created_at', '>=', $weekStart)->count(),
            'weekly_auctions' => Auction::where('created_at', '>=', $weekStart)->count(),
            'weekly_revenue' => Payment::where('status', 'success')->where('created_at', '>=', $weekStart)->sum('amount'),
            'today_users' => User::whereDate('created_at', $today)->count(),
            'today_auctions' => Auction::whereDate('created_at', $today)->count(),
            'today_revenue' => Payment::where('status', 'success')->whereDate('created_at', $today)->sum('amount'),
            'conversion_rate' => $this->calculateConversionRate(),
            'success_rate' => $this->calculateAuctionSuccessRate(),
            'sold_vehicles' => Vehicle::where('status', 'sold')->count(),
            'active_vehicles' => Vehicle::where('status', 'approved')->count(),
            'user_growth' => $this->calculateUserGrowth(),
            'user_retention_rate' => $this->calculateUserRetentionRate(),
            'avg_transaction_value' => $this->calculateAverageTransactionValue(),
        ];
    }

    private function getRealTransactions()
    {
        return Transaction::with(['buyer', 'seller', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    private function getRealTopAuctions()
    {
        return Auction::with(['vehicle', 'winner'])
            ->where('status', 'ended')
            ->orderBy('current_price', 'desc')
            ->limit(5)
            ->get();
    }

    private function getRealInventoryStatus()
    {
        return [
            'sold' => Vehicle::where('status', 'sold')->count(),
            'approved' => Vehicle::where('status', 'approved')->count(),
            'pending' => Vehicle::where('status', 'pending')->count(),
            'rejected' => Vehicle::where('status', 'rejected')->count(),
        ];
    }

    private function getRealDepositAnalytics()
    {
        return [
            'total_deposits' => Deposit::count(),
            'paid_deposits' => Deposit::where('status', 'paid')->count(),
            'forfeited_deposits' => Deposit::where('status', 'forfeited')->count(),
            'refunded_deposits' => Deposit::where('status', 'refunded')->count(),
            'total_deposit_amount' => Deposit::sum('amount'),
            'forfeited_amount' => Deposit::where('status', 'forfeited')->sum('amount')
        ];
    }

    private function calculateConversionRate()
    {
        $totalAuctions = Auction::count();
        $successfulAuctions = Auction::where('status', 'ended')->whereNotNull('winner_id')->count();
        
        return $totalAuctions > 0 ? round(($successfulAuctions / $totalAuctions) * 100, 2) : 0;
    }

    private function calculateAuctionSuccessRate()
    {
        $endedAuctions = Auction::where('status', 'ended')->count();
        $successfulAuctions = Auction::where('status', 'ended')->whereNotNull('winner_id')->count();
        
        return $endedAuctions > 0 ? round(($successfulAuctions / $endedAuctions) * 100, 2) : 0;
    }

    private function calculateUserGrowth()
    {
        $currentMonth = User::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
            
        $lastMonth = User::whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->count();

        return $lastMonth > 0 ? round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2) : 100;
    }

    private function calculateUserRetentionRate()
    {
        $totalUsers = User::count();
        $activeUsers = User::whereHas('bids')->orWhereHas('vehicles')->count();
        
        return $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0;
    }

    private function calculateAverageTransactionValue()
    {
        return Transaction::where('status', 'completed')->avg('total_amount') ?? 0;
    }

    private function unauthorizedAccess()
    {
        return response("
            <h1 style='color: red;'>Akses Ditolak!</h1>
            <p>Hanya user dengan role <strong>owner</strong> yang dapat mengakses halaman ini.</p>
            <p>Silakan login sebagai owner terlebih dahulu: 
                <a href='/force-login-owner' style='color: blue;'>Login sebagai Owner</a>
            </p>
            <p>Atau kembali ke <a href='/'>halaman utama</a></p>
        ", 403);
    }
}