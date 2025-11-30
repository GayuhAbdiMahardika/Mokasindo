<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;

class ReportAnalyticsController extends Controller
{
    public function calculateConversionRate()
    {
        $totalAuctions = Auction::count();
        $successfulAuctions = Auction::where('status', 'ended')->whereNotNull('winner_id')->count();
        
        return $totalAuctions > 0 ? round(($successfulAuctions / $totalAuctions) * 100, 2) : 0;
    }

    public function calculateAuctionSuccessRate()
    {
        $endedAuctions = Auction::where('status', 'ended')->count();
        $successfulAuctions = Auction::where('status', 'ended')->whereNotNull('winner_id')->count();
        
        return $endedAuctions > 0 ? round(($successfulAuctions / $endedAuctions) * 100, 2) : 0;
    }

    public function calculateUserGrowth()
    {
        $currentMonth = User::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
            
        $lastMonth = User::whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->count();

        return $lastMonth > 0 ? round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2) : 100;
    }

    public function calculateRevenueGrowth()
    {
        $currentMonthRevenue = Payment::where('status', 'success')
            ->whereYear('paid_at', now()->year)
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');
            
        $lastMonthRevenue = Payment::where('status', 'success')
            ->whereYear('paid_at', now()->subMonth()->year)
            ->whereMonth('paid_at', now()->subMonth()->month)
            ->sum('amount');

        return $lastMonthRevenue > 0 ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2) : 100;
    }

    public function calculateUserRetentionRate()
    {
        $totalUsers = User::count();
        $activeUsers = User::whereHas('bids')->orWhereHas('vehicles')->count();
        
        return $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0;
    }

    public function calculateCompletionRate()
    {
        $totalTransactions = Transaction::count();
        $completedTransactions = Transaction::where('status', 'completed')->count();
        
        return $totalTransactions > 0 ? round(($completedTransactions / $totalTransactions) * 100, 2) : 0;
    }

    public function calculateAvgTransactionValue()
    {
        $avg = Transaction::where('status', 'completed')->avg('total_amount');
        return $avg ? round($avg) : 0;
    }

    public function calculateAvgBidsPerAuction()
    {
        $avg = Auction::where('total_bids', '>', 0)->avg('total_bids');
        return $avg ? round($avg, 1) : 0;
    }

    public function calculateTotalTransactions()
    {
        return Transaction::count();
    }

    public function calculateTotalRevenue()
    {
        return Payment::where('status', 'success')->sum('amount');
    }
}