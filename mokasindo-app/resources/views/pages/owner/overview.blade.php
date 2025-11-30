@extends('layouts.owner')

@section('title', 'Dashboard Owner - Mokasindo')
@section('page-title', 'Owner Dashboard')
@section('owner-subtitle', 'Manajemen sistem Mokasindo')

@section('owner-content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Revenue -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-white rounded-lg shadow-md">
                <i class="bi bi-cash-coin text-green-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                <p class="text-green-700 text-sm mt-1 font-medium">
                    <i class="bi bi-arrow-up-right mr-1"></i>
                    Rp {{ number_format($stats['weekly_revenue'], 0, ',', '.') }} minggu ini
                </p>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-white rounded-lg shadow-md">
                <i class="bi bi-people-fill text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pengguna</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_users'], 0, ',', '.') }}</h3>
                <p class="text-blue-700 text-sm mt-1 font-medium">
                    <i class="bi bi-person-plus-fill mr-1"></i>
                    {{ $stats['weekly_users'] }} baru minggu ini
                </p>
            </div>
        </div>
    </div>

    <!-- Total Auctions -->
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-white rounded-lg shadow-md">
                <i class="bi bi-hammer text-purple-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Lelang</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_auctions'], 0, ',', '.') }}</h3>
                <p class="text-purple-700 text-sm mt-1 font-medium">
                    <i class="bi bi-lightning-charge-fill mr-1"></i>
                    {{ $stats['weekly_auctions'] }}+ minggu ini
                </p>
            </div>
        </div>
    </div>

    <!-- Success Rate -->
    <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-white rounded-lg shadow-md">
                <i class="bi bi-trophy-fill text-amber-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Tingkat Keberhasilan</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $stats['success_rate'] }}%</h3>
                <p class="text-amber-700 text-sm mt-1 font-medium">
                    <i class="bi bi-bullseye mr-1"></i>
                    {{ $stats['conversion_rate'] }}% konversi
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Inventory & Deposit Overview -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Inventory Status -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6">
        <div class="flex items-center mb-4">
            <i class="bi bi-car-front-fill text-blue-600 text-xl mr-2"></i>
            <h4 class="text-lg font-semibold text-gray-800">Status Inventori Kendaraan</h4>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="text-center p-4 border-l-4 border-green-500 bg-green-50 rounded-lg shadow-sm">
                <i class="bi bi-check-circle-fill text-green-600 text-lg mb-2"></i>
                <div class="text-2xl font-bold text-gray-900">{{ $inventoryStatus['sold'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Terjual</div>
            </div>
            <div class="text-center p-4 border-l-4 border-blue-500 bg-blue-50 rounded-lg shadow-sm">
                <i class="bi bi-check-lg text-blue-600 text-lg mb-2"></i>
                <div class="text-2xl font-bold text-gray-900">{{ $inventoryStatus['approved'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Disetujui</div>
            </div>
            <div class="text-center p-4 border-l-4 border-yellow-500 bg-yellow-50 rounded-lg shadow-sm">
                <i class="bi bi-clock-history text-yellow-600 text-lg mb-2"></i>
                <div class="text-2xl font-bold text-gray-900">{{ $inventoryStatus['pending'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Menunggu</div>
            </div>
            <div class="text-center p-4 border-l-4 border-red-500 bg-red-50 rounded-lg shadow-sm">
                <i class="bi bi-x-circle-fill text-red-600 text-lg mb-2"></i>
                <div class="text-2xl font-bold text-gray-900">{{ $inventoryStatus['rejected'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Ditolak</div>
            </div>
        </div>
    </div>

    <!-- Deposit Analytics -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6">
        <div class="flex items-center mb-4">
            <i class="bi bi-wallet-fill text-green-600 text-xl mr-2"></i>
            <h4 class="text-lg font-semibold text-gray-800">Analisis Deposit</h4>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="text-center p-4 bg-gray-50 rounded-lg shadow-sm border border-gray-200">
                <i class="bi bi-wallet2 text-gray-600 text-lg mb-2"></i>
                <div class="text-xl font-bold text-gray-900">{{ number_format($depositAnalytics['total_deposits']) }}</div>
                <div class="text-sm text-gray-600 mt-1">Total Deposit</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg shadow-sm border border-green-200">
                <i class="bi bi-check-circle-fill text-green-600 text-lg mb-2"></i>
                <div class="text-xl font-bold text-green-700">{{ number_format($depositAnalytics['paid_deposits']) }}</div>
                <div class="text-sm text-gray-600 mt-1">Dibayar</div>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-lg shadow-sm border border-red-200">
                <i class="bi bi-exclamation-triangle-fill text-red-600 text-lg mb-2"></i>
                <div class="text-xl font-bold text-red-700">{{ number_format($depositAnalytics['forfeited_deposits']) }}</div>
                <div class="text-sm text-gray-600 mt-1">Hangus</div>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg shadow-sm border border-blue-200">
                <i class="bi bi-arrow-return-left text-blue-600 text-lg mb-2"></i>
                <div class="text-xl font-bold text-blue-700">{{ number_format($depositAnalytics['refunded_deposits']) }}</div>
                <div class="text-sm text-gray-600 mt-1">Dikembalikan</div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6">
        <div class="flex items-center mb-4">
            <i class="bi bi-graph-up-arrow text-green-600 text-xl mr-2"></i>
            <h4 class="text-lg font-semibold text-gray-800">Grafik Pendapatan 7 Hari Terakhir</h4>
        </div>
        <div class="h-64">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6">
        <div class="flex items-center mb-4">
            <i class="bi bi-activity text-blue-600 text-xl mr-2"></i>
            <h4 class="text-lg font-semibold text-gray-800">Aktivitas Pengguna & Lelang</h4>
        </div>
        <div class="h-64">
            <canvas id="userChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Transactions & Top Auctions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Transactions -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center">
                <i class="bi bi-receipt text-gray-600 text-xl mr-2"></i>
                <h4 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h4>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider bg-gray-100">
                            <i class="bi bi-hash mr-1"></i>Kode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider bg-gray-100">
                            <i class="bi bi-person mr-1"></i>Pembeli
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider bg-gray-100">
                            <i class="bi bi-currency-dollar mr-1"></i>Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider bg-gray-100">
                            <i class="bi bi-info-circle mr-1"></i>Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentTransactions as $transaction)
                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <i class="bi bi-receipt text-gray-400 mr-2"></i>
                            {{ $transaction->transaction_code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <i class="bi bi-person-circle text-gray-400 mr-2"></i>
                            {{ $transaction->buyer->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            <i class="bi bi-cash-coin text-gray-400 mr-2"></i>
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800 border border-green-200' : 
                                   ($transaction->status === 'paid' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200') }}">
                                <i class="bi {{ $transaction->status === 'completed' ? 'bi-check-circle' : ($transaction->status === 'paid' ? 'bi-credit-card' : 'bi-clock') }} mr-1"></i>
                                {{ $transaction->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Performing Auctions -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center">
                <i class="bi bi-trophy text-amber-600 text-xl mr-2"></i>
                <h4 class="text-lg font-semibold text-gray-800">Lelang dengan Harga Tertinggi</h4>
            </div>
        </div>
        <div class="p-4 space-y-4">
            @foreach($topAuctions as $auction)
            <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors duration-150">
                <div class="flex items-start">
                    <i class="bi bi-car-front text-blue-500 text-lg mr-3 mt-1"></i>
                    <div>
                        <h5 class="font-semibold text-gray-900">
                            {{ $auction->vehicle->brand }} {{ $auction->vehicle->model }}
                        </h5>
                        <p class="text-sm text-gray-600 mt-1">
                            <i class="bi bi-calendar3 mr-1"></i>Tahun: {{ $auction->vehicle->year }} 
                            • <i class="bi bi-hammer ml-2 mr-1"></i>{{ $auction->total_bids }} bid
                        </p>
                        @if($auction->winner)
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="bi bi-trophy-fill text-amber-500 mr-1"></i>Pemenang: {{ $auction->winner->name }}
                        </p>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-green-600">
                        <i class="bi bi-currency-dollar mr-1"></i>
                        Rp {{ number_format($auction->current_price, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('owner-scripts')
<script>
    window.overviewChartData = {
        labels: <?php echo json_encode($chartData['labels']); ?>,
        revenue: <?php echo json_encode($chartData['revenue']); ?>,
        users: <?php echo json_encode($chartData['users']); ?>,
        auctions: <?php echo json_encode($chartData['auctions']); ?>
    };
</script>

@vite(['resources/js/owner-overview.js'])
@endsection