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

class AiAgentController extends Controller
{

    public function getRealRecommendations($limit = 5)
    {
        $recommendations = [];

        // 1. ANALISIS TINGKAT KEBERHASILAN LELANG
        $successRate = $this->calculateAuctionSuccessRate();
        $totalAuctions = Auction::count();
        $successfulAuctions = Auction::where('status', 'ended')->whereNotNull('winner_id')->count();
        
        if ($successRate < 60) {
            $recommendations[] = [
                'title' => 'Tingkatkan Success Rate Lelang',
                'description' => "Success rate lelang hanya {$successRate}% ({$successfulAuctions}/{$totalAuctions}). Analisis lelang yang gagal dan optimasi harga reserve.",
                'priority' => $successRate < 40 ? 'high' : 'medium',
                'metric' => 'success_rate',
                'current_value' => $successRate,
                'target_value' => 70
            ];
        }

        // 2. ANALISIS KONVERSI LELANG
        $conversionRate = $this->calculateConversionRate();
        if ($conversionRate < 50) {
            $failedAuctions = Auction::where('status', 'ended')->whereNull('winner_id')->count();
            $recommendations[] = [
                'title' => 'Optimasi Conversion Rate',
                'description' => "Hanya {$conversionRate}% lelang yang berhasil. {$failedAuctions} lelang gagal tanpa pemenang. Pertimbangkan penyesuaian durasi atau marketing.",
                'priority' => $conversionRate < 30 ? 'high' : 'medium',
                'metric' => 'conversion_rate',
                'current_value' => $conversionRate,
                'target_value' => 60
            ];
        }

        // 3. ANALISIS TRANSAKSI BELUM DIBAYAR
        $overdueTransactions = Transaction::where('status', 'pending')
            ->where('payment_deadline', '<', now())
            ->count();

        if ($overdueTransactions > 0) {
            $totalAmount = Transaction::where('status', 'pending')
                ->where('payment_deadline', '<', now())
                ->sum('total_amount');
                
            $recommendations[] = [
                'title' => 'Follow Up Transaksi Overdue',
                'description' => "Ada {$overdueTransactions} transaksi overdue dengan total Rp " . number_format($totalAmount) . ". Segera lakukan follow up ke pembeli.",
                'priority' => 'high',
                'metric' => 'overdue_transactions',
                'current_value' => $overdueTransactions,
                'target_value' => 0
            ];
        }

        usort($recommendations, function($a, $b) {
            $priorityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];
            return $priorityOrder[$b['priority']] - $priorityOrder[$a['priority']];
        });

        return array_slice($recommendations, 0, $limit);
    }

    public function getRealAlerts()
    {
        $alerts = [];

        // 1. Alert untuk lelang stuck tanpa bid
        $stuckAuctions = Auction::where('status', 'active')
            ->where('total_bids', 0)
            ->where('created_at', '<', Carbon::now()->subDays(2))
            ->count();

        if ($stuckAuctions > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "{$stuckAuctions} lelang aktif tanpa penawaran dalam 2 hari terakhir. Perlu review harga atau promosi.",
                'timestamp' => now(),
                'action' => 'review_auctions'
            ];
        }

        // 2. Alert untuk transaksi overdue
        $overdueTransactions = Transaction::where('status', 'pending')
            ->where('payment_deadline', '<', now())
            ->count();

        if ($overdueTransactions > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "{$overdueTransactions} transaksi melewati batas pembayaran. Segera follow up.",
                'timestamp' => now(),
                'action' => 'follow_up_payments'
            ];
        }

        // 3. Alert positif untuk pertumbuhan
        $userGrowth = $this->calculateUserGrowth();
        if ($userGrowth > 15) {
            $alerts[] = [
                'type' => 'success',
                'message' => "Pertumbuhan pengguna +{$userGrowth}% bulan ini! Performance excellent.",
                'timestamp' => now(),
                'action' => 'maintain_growth'
            ];
        }

        return $alerts;
    }

    public function getRealPerformanceMetrics()
    {
        return [
            'conversion_rate' => $this->calculateConversionRate(),
            'success_rate' => $this->calculateAuctionSuccessRate(),
            'avg_transaction_value' => Transaction::where('status', 'completed')->avg('total_amount') ?? 0,
            'user_growth' => $this->calculateUserGrowth(),
            'revenue_growth' => $this->calculateRevenueGrowth(),
            'user_retention_rate' => $this->calculateUserRetentionRate(),
            'avg_bids_per_auction' => Auction::where('total_bids', '>', 0)->avg('total_bids') ?? 0,
            'completion_rate' => $this->calculateCompletionRate(),
        ];
    }

    public function getRealTrends()
    {
        $popularCategories = Vehicle::groupBy('category')
            ->selectRaw('category, COUNT(*) as count')
            ->orderBy('count', 'desc')
            ->get()
            ->pluck('count', 'category')
            ->toArray();

        $peakHours = Auction::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get()
            ->pluck('count', 'hour')
            ->toArray();

        $geographicDistribution = User::join('provinces', 'users.province_id', '=', 'provinces.id')
            ->selectRaw('provinces.name as province, COUNT(*) as count')
            ->groupBy('provinces.name')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->pluck('count', 'province')
            ->toArray();

        return [
            'popular_categories' => $popularCategories,
            'peak_hours' => $peakHours,
            'geographic_distribution' => $geographicDistribution,
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

    private function calculateRevenueGrowth()
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

    private function calculateUserRetentionRate()
    {
        $totalUsers = User::count();
        $activeUsers = User::whereHas('bids')->orWhereHas('vehicles')->count();
        
        return $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0;
    }

    private function calculateCompletionRate()
    {
        $totalTransactions = Transaction::count();
        $completedTransactions = Transaction::where('status', 'completed')->count();
        
        return $totalTransactions > 0 ? round(($completedTransactions / $totalTransactions) * 100, 2) : 0;
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