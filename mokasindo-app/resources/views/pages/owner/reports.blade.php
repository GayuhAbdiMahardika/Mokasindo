@extends('layouts.owner')

@section('title', 'Laporan & Analitik - Mokasindo')
@section('page-title', 'Laporan & Analitik')
@section('owner-subtitle', 'Analisis performa sistem')

@section('owner-content')

<!-- AI Insights Mini -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
        <i class="bi bi-robot text-indigo-600 text-3xl mr-3"></i>
        AI Insights & Rekomendasi
    </h2>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Recommendations -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center mb-4">
                <i class="bi bi-lightbulb text-yellow-500 text-xl mr-2"></i>
                Rekomendasi Prioritas
            </h3>
            <!-- Di bagian rekomendasi, update tampilan -->
<div class="space-y-3">
    @if(isset($aiRecommendations) && count($aiRecommendations) > 0)
        @foreach($aiRecommendations as $rec)
        <div class="p-4 rounded-lg border-l-4 {{ $rec['priority'] === 'high' ? 'bg-red-50 border-red-500' : ($rec['priority'] === 'medium' ? 'bg-yellow-50 border-yellow-500' : 'bg-blue-50 border-blue-500') }}">
            <div class="flex justify-between items-start mb-2">
                <h4 class="font-semibold text-gray-900">{{ $rec['title'] }}</h4>
                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                    {{ $rec['priority'] === 'high' ? 'bg-red-200 text-red-800' : ($rec['priority'] === 'medium' ? 'bg-yellow-200 text-yellow-800' : 'bg-blue-200 text-blue-800') }}">
                    {{ $rec['priority'] }}
                </span>
            </div>
            <p class="text-sm text-gray-600 mb-2">{{ $rec['description'] }}</p>
            <div class="flex justify-between text-xs text-gray-500">
                <span>Current: {{ $rec['current_value'] }}{{ isset($rec['metric']) && str_contains($rec['metric'], 'rate') ? '%' : '' }}</span>
                <span>Target: {{ $rec['target_value'] }}{{ isset($rec['metric']) && str_contains($rec['metric'], 'rate') ? '%' : '' }}</span>
            </div>
        </div>
        @endforeach
    @else
        <div class="p-4 rounded-lg border-l-4 bg-gray-50 border-gray-500">
            <p class="text-sm text-gray-600">Semua metrik dalam kondisi optimal. Tidak ada rekomendasi khusus saat ini.</p>
        </div>
    @endif
</div>
        </div>

        <!-- Alerts -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center mb-4">
                <i class="bi bi-bell text-orange-500 text-xl mr-2"></i>
                Notifikasi Sistem
            </h3>
            <div class="space-y-3">
                @if(isset($aiAlerts) && count($aiAlerts) > 0)
                    @foreach($aiAlerts as $alert)
                    <div class="flex items-start p-3 rounded-lg {{ $alert['type'] === 'warning' ? 'bg-yellow-50' : ($alert['type'] === 'success' ? 'bg-green-50' : 'bg-blue-50') }}">
                        <i class="bi {{ $alert['type'] === 'warning' ? 'bi-exclamation-triangle-fill text-yellow-600' : ($alert['type'] === 'success' ? 'bi-check-circle-fill text-green-600' : 'bi-info-circle-fill text-blue-600') }} text-xl mr-3 mt-1"></i>
                        <p class="text-sm text-gray-700">{{ $alert['message'] }}</p>
                    </div>
                    @endforeach
                @else
                    <div class="flex items-start p-3 rounded-lg bg-green-50">
                        <i class="bi bi-check-circle-fill text-green-600 text-xl mr-3 mt-1"></i>
                        <p class="text-sm text-gray-700">Tidak ada notifikasi sistem saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 text-center">
            <i class="bi bi-graph-up-arrow text-blue-600 text-2xl mb-2"></i>
            <div class="text-2xl font-bold text-gray-900">{{ $aiMetrics['conversion_rate'] }}%</div>
            <div class="text-sm text-gray-600 mt-1">Tingkat Konversi</div>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4 text-center">
            <i class="bi bi-trophy text-green-600 text-2xl mb-2"></i>
            <div class="text-2xl font-bold text-gray-900">{{ $aiMetrics['success_rate'] }}%</div>
            <div class="text-sm text-gray-600 mt-1">Tingkat Keberhasilan</div>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4 text-center">
            <i class="bi bi-currency-dollar text-purple-600 text-2xl mb-2"></i>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($aiMetrics['avg_transaction_value'] / 1000000, 0) }}Jt</div>
            <div class="text-sm text-gray-600 mt-1">Rata-rata Transaksi</div>
        </div>
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-4 text-center">
            <i class="bi bi-people text-amber-600 text-2xl mb-2"></i>
            <div class="text-2xl font-bold text-gray-900">+{{ $aiMetrics['user_growth'] }}%</div>
            <div class="text-sm text-gray-600 mt-1">Pertumbuhan Pengguna</div>
        </div>
    </div>
</div>

<!-- Grafik Interaktif -->
<div class="mb-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
            <i class="bi bi-graph-up text-indigo-600 text-3xl mr-3"></i>
            Grafik Interaktif
        </h2>

      <div class="flex space-x-2">
          <button onclick="changePeriod('daily')" id="btn-daily" 
              class="px-4 py-2 rounded-lg font-medium {{ $period === 'daily' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors">
              Bulanan
         </button>
          <button onclick="changePeriod('monthly')" id="btn-monthly" 
              class="px-4 py-2 rounded-lg font-medium {{ $period === 'monthly' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors">
              Tahunan
          </button>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="bi bi-cash-stack text-green-600 text-xl mr-2"></i>
                Grafik Pendapatan
            </h3>
            <div class="h-80"><canvas id="revenueChart"></canvas></div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="bi bi-activity text-blue-600 text-xl mr-2"></i>
                Aktivitas Sistem
            </h3>
            <div class="h-80"><canvas id="activityChart"></canvas></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="bi bi-pie-chart text-purple-600 text-xl mr-2"></i>
                Kategori Populer
            </h3>
            <div class="h-64"><canvas id="categoryChart"></canvas></div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="bi bi-clock-history text-orange-600 text-xl mr-2"></i>
                Jam Sibuk
            </h3>
            <div class="h-64"><canvas id="peakHoursChart"></canvas></div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="bi bi-geo-alt text-red-600 text-xl mr-2"></i>
                Distribusi Geografis
            </h3>
            <div class="h-64"><canvas id="geoChart"></canvas></div>
        </div>
    </div>
</div>

<!-- Laporan Detail -->
<div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
            <i class="bi bi-file-earmark-text text-indigo-600 text-3xl mr-3"></i>
            Laporan Detail Transaksi
        </h2>
        
        <div class="flex space-x-3">
            <button onclick="exportReport('excel')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center">
                <i class="bi bi-file-excel mr-2"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $aiMetrics['total_transactions'] }}</div>
            <div class="text-sm text-blue-700">Total Transaksi</div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-600">Rp {{ number_format($aiMetrics['total_revenue'] / 1000000, 0) }}Jt</div>
            <div class="text-sm text-green-700">Total Pendapatan</div>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $aiMetrics['success_rate'] }}%</div>
            <div class="text-sm text-purple-700">Tingkat Keberhasilan</div>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-amber-600">{{ $aiMetrics['avg_bids_per_auction'] }}</div>
            <div class="text-sm text-amber-700">Rata-rata Bid/Lelang</div>
        </div>
    </div>

    <<!-- Tabel laporan detail -->
<div class="overflow-x-auto">
    <div class="max-h-96 overflow-y-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0 z-10">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider bg-gray-50">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider bg-gray-50">Pendapatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider bg-gray-50">Lelang Baru</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider bg-gray-50">Pengguna Baru</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider bg-gray-50">Transaksi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider bg-gray-50">Fee Platform</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider bg-gray-50">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if(count($chartData['labels']) > 0)
                    @foreach($chartData['labels'] as $index => $label)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $label }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            @if($chartData['revenue'][$index] > 0)
                                Rp {{ number_format($chartData['revenue'][$index] / 1000000, 0) }}Jt
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">{{ $chartData['auctions'][$index] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">{{ $chartData['users'][$index] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">{{ $chartData['transactions'][$index] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($chartData['platform_fee'][$index] > 0)
                                Rp {{ number_format($chartData['platform_fee'][$index] / 1000000, 1) }}Jt
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($chartData['revenue'][$index] > 0)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $chartData['revenue'][$index] > 100000000 ? 'bg-green-100 text-green-800 border border-green-200' : 
                                       ($chartData['revenue'][$index] > 50000000 ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200') }}">
                                    {{ $chartData['revenue'][$index] > 100000000 ? 'Tinggi' : 
                                       ($chartData['revenue'][$index] > 50000000 ? 'Normal' : 'Rendah') }}
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                    Tidak Ada
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="bi bi-inbox text-4xl mb-3 text-gray-300"></i>
                                <p class="font-medium text-lg mb-1">Belum ada data transaksi</p>
                                <p class="text-sm">Data akan muncul ketika ada aktivitas di sistem</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Info jumlah data -->
@if(count($chartData['labels']) > 0)
<div class="mt-4 text-sm text-gray-500 flex justify-between items-center">
    <div>
        Menampilkan {{ count($chartData['labels']) }} data transaksi
    </div>
    <div class="flex items-center space-x-2">
        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Tinggi: >Rp 100Jt</span>
        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Normal: Rp 50-100Jt</span>
        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Rendah: ≤Rp 50Jt</span>
    </div>
</div>
@endif
</div>

@endsection

@section('owner-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    window.reportsChartData = {
        labels: <?php echo json_encode($chartData['labels']); ?>,
        revenue: <?php echo json_encode($chartData['revenue']); ?>,
        auctions: <?php echo json_encode($chartData['auctions']); ?>,
        users: <?php echo json_encode($chartData['users']); ?>,
        transactions: <?php echo json_encode($chartData['transactions']); ?>,
        platform_fee: <?php echo json_encode($chartData['platform_fee']); ?>
    };

    window.reportsTrendsData = {
        categories: <?php echo json_encode($trends['popular_categories']->pluck('count', 'category')->toArray()); ?>,
        peakHours: <?php echo json_encode($trends['peak_hours']->pluck('count', 'hour')->toArray()); ?>,
        geo: <?php echo json_encode($trends['geographic_distribution']->pluck('count', 'province')->toArray()); ?>
    };
</script>

@vite([
    'resources/js/owner-reports-chartstable.js',
    'resources/js/owner-reports-utils.js'
])
@endsection