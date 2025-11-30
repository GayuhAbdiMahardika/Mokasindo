<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Payment;
use App\Models\Deposit;
use Carbon\Carbon;

class ReportController extends Controller
{
    private $chartController;
    private $analyticsController;

    public function __construct()
    {
        $this->chartController = new ReportChartController();
        $this->analyticsController = new ReportAnalyticsController();
    }

    public function index(Request $request)
    {
        if (auth()->user()->role !== 'owner') {
            return $this->unauthorizedAccess();
        }

        $aiAgentController = new AiAgentController();
        $period = $request->get('period', 'daily');

        $chartData = $period === 'monthly' ? $this->getRealMonthlyChartData() : $this->getRealDailyChartData();
        $trends = $this->getRealTrends();
        $performanceMetrics = $this->getRealPerformanceMetrics();
        $aiRecommendations = $aiAgentController->getRealRecommendations();
        $aiAlerts = $aiAgentController->getRealAlerts();
        $aiMetrics = $this->calculateRealAiMetrics();

        return view('pages.owner.reports', compact(
            'chartData', 
            'trends',
            'performanceMetrics',
            'aiRecommendations',
            'aiAlerts',
            'aiMetrics',
            'period'
        ));
    }

    public function getChartData(Request $request)
    {
        if (auth()->user()->role !== 'owner') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $period = $request->get('period', 'daily');
        
        switch ($period) {
            case 'monthly':
                $data = $this->getRealMonthlyChartData();
                break;
            default:
                $data = $this->getRealDailyChartData();
        }

        return response()->json($data);
    }

    public function exportExcel(Request $request)
{
    if (auth()->user()->role !== 'owner') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $period = $request->get('period', 'daily');
    
    if ($period === 'monthly') {
        $data = $this->getRealMonthlyChartData();
        $title = 'Laporan Tahunan';
        $filenamePeriod = 'yearly';
    } else {
        $data = $this->getRealDailyChartData();
        $title = 'Laporan Bulanan';
        $filenamePeriod = 'monthly';
    }

    $filename = 'laporan-transaksi-' . $filenamePeriod . '-' . Carbon::now()->format('Y-m-d') . '.xls';
    
    $html = $this->generateHTMLTable($data, $title);
    
    return response($html, 200, [
        'Content-Type' => 'application/vnd.ms-excel',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ]);
}

private function generateHTMLTable($data, $title)
{
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>' . $title . '</title>
        <style>
            table { border-collapse: collapse; width: 100%; }
            th { background-color: #2E86C1; color: white; font-weight: bold; padding: 8px; border: 1px solid #ddd; }
            td { padding: 8px; border: 1px solid #ddd; }
            .total { background-color: #F4D03F; font-weight: bold; }
            .title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; }
        </style>
    </head>
    <body>
        <div class="title">' . $title . ' - ' . Carbon::now()->format('d F Y') . '</div>
        
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pendapatan (Rp)</th>
                    <th>Lelang Baru</th>
                    <th>Pengguna Baru</th>
                    <th>Transaksi</th>
                    <th>Fee Platform (Rp)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>';
    
    foreach ($data['labels'] as $index => $label) {
        $revenue = $data['revenue'][$index] ?? 0;
        $status = $this->getStatusText($revenue);
        
        $html .= '<tr>
            <td>' . $label . '</td>
            <td>' . number_format($revenue, 0, ',', '.') . '</td>
            <td>' . ($data['auctions'][$index] ?? 0) . '</td>
            <td>' . ($data['users'][$index] ?? 0) . '</td>
            <td>' . ($data['transactions'][$index] ?? 0) . '</td>
            <td>' . number_format($data['platform_fee'][$index] ?? 0, 0, ',', '.') . '</td>
            <td>' . $status . '</td>
        </tr>';
    }
    
    $html .= '<tr class="total">
        <td>TOTAL</td>
        <td>' . number_format(array_sum($data['revenue']), 0, ',', '.') . '</td>
        <td>' . array_sum($data['auctions']) . '</td>
        <td>' . array_sum($data['users']) . '</td>
        <td>' . array_sum($data['transactions']) . '</td>
        <td>' . number_format(array_sum($data['platform_fee']), 0, ',', '.') . '</td>
        <td></td>
    </tr>';
    
    $html .= '</tbody>
        </table>
    </body>
    </html>';
    
    return $html;
}

private function getStatusText($revenue)
{
    if ($revenue > 100000000) return 'TINGGI';
    if ($revenue > 50000000) return 'NORMAL';
    if ($revenue > 0) return 'RENDAH';
    return 'TIDAK ADA';
}

    // Chart Data Methods
    private function getRealDailyChartData()
    {
        return $this->chartController->getRealDailyChartData();
    }

    private function getRealMonthlyChartData()
    {
        return $this->chartController->getRealMonthlyChartData();
    }

    // Analytics Methods
    private function calculateConversionRate()
    {
        return $this->analyticsController->calculateConversionRate();
    }

    private function calculateAuctionSuccessRate()
    {
        return $this->analyticsController->calculateAuctionSuccessRate();
    }

    private function calculateUserGrowth()
    {
        return $this->analyticsController->calculateUserGrowth();
    }

    private function calculateRevenueGrowth()
    {
        return $this->analyticsController->calculateRevenueGrowth();
    }

    private function calculateUserRetentionRate()
    {
        return $this->analyticsController->calculateUserRetentionRate();
    }

    private function calculateCompletionRate()
    {
        return $this->analyticsController->calculateCompletionRate();
    }

    private function calculateAvgTransactionValue()
    {
        return $this->analyticsController->calculateAvgTransactionValue();
    }

    private function calculateAvgBidsPerAuction()
    {
        return $this->analyticsController->calculateAvgBidsPerAuction();
    }

    private function calculateTotalTransactions()
    {
        return $this->analyticsController->calculateTotalTransactions();
    }

    private function calculateTotalRevenue()
    {
        return $this->analyticsController->calculateTotalRevenue();
    }

    // Local Methods
    private function getRealTrends()
    {
        $popularCategories = Vehicle::groupBy('category')
            ->selectRaw('category, COUNT(*) as count')
            ->orderBy('count', 'desc')
            ->get();

        $peakHours = Auction::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        $geographicDistribution = User::join('provinces', 'users.province_id', '=', 'provinces.id')
            ->selectRaw('provinces.name as province, COUNT(*) as count')
            ->groupBy('provinces.name')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return [
            'popular_categories' => $popularCategories,
            'peak_hours' => $peakHours,
            'geographic_distribution' => $geographicDistribution,
        ];
    }

    private function getRealPerformanceMetrics()
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

    private function calculateRealAiMetrics()
    {
        return [
            'conversion_rate' => $this->calculateConversionRate(),
            'success_rate' => $this->calculateAuctionSuccessRate(),
            'avg_transaction_value' => $this->calculateAvgTransactionValue(),
            'user_growth' => $this->calculateUserGrowth(),
            'revenue_growth' => $this->calculateRevenueGrowth(),
            'user_retention_rate' => $this->calculateUserRetentionRate(),
            'avg_bids_per_auction' => $this->calculateAvgBidsPerAuction(),
            'completion_rate' => $this->calculateCompletionRate(),
            'total_transactions' => $this->calculateTotalTransactions(),
            'total_revenue' => $this->calculateTotalRevenue(),
        ];
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