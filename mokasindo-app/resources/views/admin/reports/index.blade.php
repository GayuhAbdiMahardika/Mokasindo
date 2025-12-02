@extends('admin.layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
        <p class="text-gray-600 mt-1">Comprehensive business insights and statistics</p>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg">
            <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                <i class="fas fa-search mr-2"></i>Apply
            </button>
            <button type="button" onclick="window.print()" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-print mr-2"></i>Print
            </button>
        </form>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-dollar-sign text-3xl opacity-80"></i>
                <span class="text-sm opacity-80">Revenue</span>
            </div>
            <p class="text-3xl font-bold mb-1">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-sm opacity-90">
                <i class="fas fa-arrow-up mr-1"></i>
                {{ number_format($stats['revenue_growth'] ?? 0, 1) }}% vs last period
            </p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-gavel text-3xl opacity-80"></i>
                <span class="text-sm opacity-80">Auctions</span>
            </div>
            <p class="text-3xl font-bold mb-1">{{ $stats['total_auctions'] ?? 0 }}</p>
            <p class="text-sm opacity-90">
                {{ $stats['completed_auctions'] ?? 0 }} completed
            </p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-users text-3xl opacity-80"></i>
                <span class="text-sm opacity-80">Users</span>
            </div>
            <p class="text-3xl font-bold mb-1">{{ $stats['total_users'] ?? 0 }}</p>
            <p class="text-sm opacity-90">
                +{{ $stats['new_users'] ?? 0 }} new this period
            </p>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-car text-3xl opacity-80"></i>
                <span class="text-sm opacity-80">Vehicles</span>
            </div>
            <p class="text-3xl font-bold mb-1">{{ $stats['total_vehicles'] ?? 0 }}</p>
            <p class="text-sm opacity-90">
                {{ $stats['pending_approval'] ?? 0 }} pending approval
            </p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Revenue Trend</h3>
            <canvas id="revenueChart" height="300"></canvas>
        </div>

        <!-- Auction Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Auction Status Distribution</h3>
            <canvas id="auctionStatusChart" height="300"></canvas>
        </div>
    </div>

    <!-- Detailed Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Bidders -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Top Bidders</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bids</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($topBidders ?? [] as $index => $bidder)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-semibold">{{ $bidder->user_name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $bidder->bid_count }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-green-600">Rp {{ number_format($bidder->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Most Popular Vehicles -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Most Popular Vehicles</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bids</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($popularVehicles ?? [] as $index => $vehicle)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-semibold">{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                            <td class="px-6 py-4 text-sm">{{ $vehicle->views ?? 0 }}</td>
                            <td class="px-6 py-4 text-sm text-blue-600 font-bold">{{ $vehicle->bids_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Transaction Summary -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Transaction Summary</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="border-l-4 border-green-500 pl-4">
                    <p class="text-gray-600 text-sm mb-1">Deposit Top-Ups</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['deposit_topups'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stats['topup_count'] ?? 0 }} transactions</p>
                </div>
                <div class="border-l-4 border-blue-500 pl-4">
                    <p class="text-gray-600 text-sm mb-1">Payments Received</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['payments_received'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stats['payment_count'] ?? 0 }} transactions</p>
                </div>
                <div class="border-l-4 border-purple-500 pl-4">
                    <p class="text-gray-600 text-sm mb-1">Withdrawals</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['withdrawals'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stats['withdrawal_count'] ?? 0 }} requests</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart');
if (revenueCtx) {
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($revenueChartLabels ?? []),
            datasets: [{
                label: 'Revenue',
                data: @json($revenueChartData ?? []),
                borderColor: 'rgb(79, 70, 229)',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Auction Status Chart
const auctionCtx = document.getElementById('auctionStatusChart');
if (auctionCtx) {
    new Chart(auctionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Upcoming', 'Ended', 'Cancelled'],
            datasets: [{
                data: @json($auctionStatusData ?? [0, 0, 0, 0]),
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(59, 130, 246)',
                    'rgb(156, 163, 175)',
                    'rgb(239, 68, 68)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}
</script>
@endsection
