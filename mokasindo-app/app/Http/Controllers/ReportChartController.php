<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;

class ReportChartController extends Controller
{
    public function getRealDailyChartData()
    {
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        
        $paymentDates = Payment::where('status', 'success')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('DATE(paid_at) as date')
            ->groupBy('date')
            ->pluck('date');
        
        $auctionDates = Auction::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->pluck('date');
        
        $userDates = User::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->pluck('date');
        
        $transactionDates = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->pluck('date');
        
        $allDates = $paymentDates->merge($auctionDates)
                                ->merge($userDates)
                                ->merge($transactionDates)
                                ->unique()
                                ->sort()
                                ->values();
        
        $data = [
            'labels' => [],
            'revenue' => [],
            'auctions' => [],
            'users' => [],
            'transactions' => [],
            'platform_fee' => []
        ];
        
        if ($allDates->count() === 0) {
            return $data;
        }
        
        foreach ($allDates as $date) {
            $carbonDate = Carbon::parse($date);
            
            $data['labels'][] = $carbonDate->format('d M Y');
            $data['revenue'][] = (float) Payment::where('status', 'success')
                ->whereDate('paid_at', $date)
                ->sum('amount');
            $data['auctions'][] = Auction::whereDate('created_at', $date)->count();
            $data['users'][] = User::whereDate('created_at', $date)->count();
            $data['transactions'][] = Transaction::whereDate('created_at', $date)->count();
            
            $revenue = $data['revenue'][count($data['revenue'])-1];
            $data['platform_fee'][] = $revenue * 0.05;
        }
        
        return $data;
    }

    public function getRealMonthlyChartData()
    {
        $startDate = Carbon::now()->subMonths(12);
        $endDate = Carbon::now();
        
        $paymentMonths = Payment::where('status', 'success')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('YEAR(paid_at) as year, MONTH(paid_at) as month')
            ->groupBy('year', 'month')
            ->get();
        
        $auctionMonths = Auction::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupBy('year', 'month')
            ->get();
        
        $userMonths = User::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupBy('year', 'month')
            ->get();
        
        $transactionMonths = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupBy('year', 'month')
            ->get();
        
        $allMonths = collect();
        
        foreach ([$paymentMonths, $auctionMonths, $userMonths, $transactionMonths] as $monthData) {
            $allMonths = $allMonths->merge($monthData);
        }
        
        $allMonths = $allMonths->unique(function ($item) {
            return $item->year . '-' . $item->month;
        })->sortBy(function ($item) {
            return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
        })->values();
        
        $data = [
            'labels' => [],
            'revenue' => [],
            'auctions' => [],
            'users' => [],
            'transactions' => [],
            'platform_fee' => []
        ];
        
        if ($allMonths->count() === 0) {
            return $data;
        }
        
        foreach ($allMonths as $month) {
            $date = Carbon::create($month->year, $month->month, 1);
            
            $data['labels'][] = $date->format('M Y');
            $data['revenue'][] = (float) Payment::where('status', 'success')
                ->whereYear('paid_at', $month->year)
                ->whereMonth('paid_at', $month->month)
                ->sum('amount');
            $data['auctions'][] = Auction::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $data['users'][] = User::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $data['transactions'][] = Transaction::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
                
            $revenue = $data['revenue'][count($data['revenue'])-1];
            $data['platform_fee'][] = $revenue * 0.05;
        }
        
        return $data;
    }
}