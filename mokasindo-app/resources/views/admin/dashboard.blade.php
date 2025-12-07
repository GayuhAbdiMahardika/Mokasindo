@extends('admin.layout')

@section('title', __('admin.dashboard.title'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ __('admin.dashboard.title') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('admin.dashboard.welcome', ['name' => auth()->user()->name]) }}</p>
        </div>
        <div class="text-right">
            <p id="local-date" class="text-sm text-gray-500">&nbsp;</p>
            <p id="local-time" class="text-xs text-gray-400">&nbsp;</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('admin.dashboard.total_users') }}</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_users']) }}</h3>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-arrow-up"></i> +{{ $stats['new_users_this_month'] }} {{ __('admin.dashboard.new_users_this_month') }}
                    </p>
                </div>
                <div class="text-blue-500">
                    <i class="fas fa-users text-4xl opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Total Vehicles -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('admin.dashboard.total_vehicles') }}</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_vehicles']) }}</h3>
                    <p class="text-xs text-yellow-600 mt-1">
                        <i class="fas fa-clock"></i> {{ $stats['pending_vehicles'] }} {{ __('admin.dashboard.pending_approval') }}
                    </p>
                </div>
                <div class="text-green-500">
                    <i class="fas fa-car text-4xl opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Active Auctions -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('admin.dashboard.active_auctions') }}</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['active_auctions']) }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ number_format($stats['total_auctions']) }} {{ __('admin.dashboard.total_auctions') }}
                    </p>
                </div>
                <div class="text-purple-500">
                    <i class="fas fa-gavel text-4xl opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('admin.dashboard.total_revenue') }}</p>
                    <h3 class="text-3xl font-bold text-gray-800">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ __('admin.dashboard.all_time_revenue') }}
                    </p>
                </div>
                <div class="text-yellow-500">
                    <i class="fas fa-money-bill-wave text-4xl opacity-20"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>{{ __('admin.dashboard.quick_actions') }}
            </h3>
            <div class="space-y-2">
                <a href="{{ route('admin.vacancies.create') }}" class="block px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                    <i class="fas fa-briefcase text-blue-600 mr-2"></i>
                    <span class="text-sm font-medium">{{ __('admin.dashboard.post_new_job') }}</span>
                </a>
                <a href="{{ route('admin.pages.create') }}" class="block px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg transition">
                    <i class="fas fa-file-alt text-green-600 mr-2"></i>
                    <span class="text-sm font-medium">{{ __('admin.dashboard.create_page') }}</span>
                </a>
                <a href="{{ route('admin.teams.create') }}" class="block px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                    <i class="fas fa-user-plus text-purple-600 mr-2"></i>
                    <span class="text-sm font-medium">{{ __('admin.dashboard.add_team_member') }}</span>
                </a>
                <a href="{{ route('admin.faqs.create') }}" class="block px-4 py-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition">
                    <i class="fas fa-question-circle text-orange-600 mr-2"></i>
                    <span class="text-sm font-medium">{{ __('admin.dashboard.add_faq') }}</span>
                </a>
            </div>
        </div>

        <!-- Pending Items -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-clock text-orange-500 mr-2"></i>{{ __('admin.dashboard.pending_items') }}
            </h3>
            <div class="space-y-3">
                <a href="#" class="block">
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ __('admin.dashboard.pending_vehicles') }}</p>
                            <p class="text-xs text-gray-500">{{ __('admin.dashboard.awaiting_approval') }}</p>
                        </div>
                        <span class="bg-yellow-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                            {{ $stats['pending_vehicles'] }}
                        </span>
                    </div>
                </a>
                <a href="#" class="block">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ __('admin.dashboard.pending_deposits') }}</p>
                            <p class="text-xs text-gray-500">{{ __('admin.dashboard.awaiting_verification') }}</p>
                        </div>
                        <span class="bg-blue-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                            {{ $stats['pending_deposits'] }}
                        </span>
                    </div>
                </a>
                <a href="{{ route('admin.inquiries.index') }}" class="block">
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg hover:bg-red-100 transition">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ __('admin.dashboard.new_inquiries') }}</p>
                            <p class="text-xs text-gray-500">{{ __('admin.dashboard.customer_support') }}</p>
                        </div>
                        <span class="bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                            {{ $stats['new_inquiries'] }}
                        </span>
                    </div>
                </a>
                <a href="#" class="block">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ __('admin.dashboard.new_applications') }}</p>
                            <p class="text-xs text-gray-500">{{ __('admin.dashboard.job_applicants') }}</p>
                        </div>
                        <span class="bg-green-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                            {{ $stats['new_applications'] }}
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <!-- System Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>{{ __('admin.dashboard.system_info') }}
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">{{ __('admin.dashboard.laravel_version') }}</span>
                    <span class="font-medium">{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">{{ __('admin.dashboard.php_version') }}</span>
                    <span class="font-medium">{{ PHP_VERSION }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">{{ __('admin.dashboard.environment') }}</span>
                    <span class="font-medium">{{ config('app.env') }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">{{ __('admin.dashboard.timezone') }}</span>
                    <span class="font-medium">{{ config('app.timezone') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Today KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
            <p class="text-sm text-gray-600 mb-1">{{ __('admin.dashboard.transactions_today') }}</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ number_format($transactions_today) }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ __('admin.dashboard.revenue_today_label') }} <strong>Rp {{ number_format($revenue_today,0,',','.') }}</strong></p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-teal-500">
            <p class="text-sm text-gray-600 mb-1">{{ __('admin.dashboard.revenue_today') }}</p>
            <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($revenue_today,0,',','.') }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ __('admin.dashboard.transactions_count') }} {{ number_format($transactions_today) }}</p>
        </div>
    </div>

    <!-- Charts: 7-day trends -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('admin.dashboard.revenue_last7') }}</h3>
            <canvas id="revenueChart" height="160"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('admin.dashboard.users_last7') }}</h3>
            <canvas id="usersChart" height="160"></canvas>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-users text-blue-500 mr-2"></i>{{ __('admin.dashboard.recent_users') }}
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($recent_users as $user)
                    <div class="flex items-center justify-between py-2 border-b last:border-0">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs px-2 py-1 rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ __('roles.' . $user->role) }}
                            </span>
                            <p class="text-xs text-gray-400 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">{{ __('admin.dashboard.no_users') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Vehicles -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-car text-green-500 mr-2"></i>{{ __('admin.dashboard.recent_vehicles') }}
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($recent_vehicles as $vehicle)
                    <div class="flex items-center justify-between py-2 border-b last:border-0">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                            <p class="text-xs text-gray-500">by {{ $vehicle->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs px-2 py-1 rounded-full 
                                {{ $vehicle->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $vehicle->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $vehicle->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($vehicle->status) }}
                            </span>
                            <p class="text-xs text-gray-400 mt-1">{{ $vehicle->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">{{ __('admin.dashboard.no_vehicles') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-money-bill-wave text-yellow-500 mr-2"></i>{{ __('admin.dashboard.recent_payments') }}
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.dashboard.user') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.dashboard.amount') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.dashboard.type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.dashboard.status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.dashboard.date') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recent_payments as $payment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $payment->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ __('payments.type.' . $payment->type) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $payment->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ __('payments.status.' . $payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $payment->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">{{ __('admin.dashboard.no_payments') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Deposits -->
    @if($pending_deposits->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-hourglass-half text-orange-500 mr-2"></i>{{ __('admin.dashboard.pending_deposits_action') }}
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.dashboard.user') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.dashboard.amount') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.dashboard.bank') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.dashboard.date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.dashboard.action') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pending_deposits as $deposit)
                    <tr class="hover:bg-yellow-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $deposit->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            Rp {{ number_format($deposit->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $deposit->bank_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $deposit->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">
                                <i class="fas fa-check-circle mr-1"></i>{{ __('admin.dashboard.review') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare data from server
    const labels7 = {!! json_encode($labels7 ?? []) !!};
    const revenueLast7 = {!! json_encode($revenueLast7 ?? []) !!};
    const usersLast7 = {!! json_encode($usersLast7 ?? []) !!};

    // Local clock: updates every second using user's computer time
    function updateLocalClock() {
        const now = new Date();

        // Date: weekday, DD Month YYYY (localized)
        const dateOptions = { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' };
        const localDate = now.toLocaleDateString(undefined, dateOptions);

        // Time: HH:mm:ss
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        const localTime = now.toLocaleTimeString(undefined, timeOptions);

        const elDate = document.getElementById('local-date');
        const elTime = document.getElementById('local-time');
        if (elDate) elDate.textContent = localDate;
        if (elTime) elTime.textContent = localTime;
    }

    // Initialize clock and refresh every second
    updateLocalClock();
    setInterval(updateLocalClock, 1000);

    // Revenue Chart
    const ctxRevenue = document.getElementById('revenueChart');
    if (ctxRevenue) {
        new Chart(ctxRevenue.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels7,
                datasets: [{
                    label: '{{ __('admin.dashboard.revenue_label') }}',
                    data: revenueLast7,
                    backgroundColor: 'rgba(250, 204, 21, 0.08)',
                    borderColor: 'rgba(250, 204, 21, 1)',
                    pointBackgroundColor: 'rgba(250, 204, 21, 1)',
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) { return 'Rp ' + Number(value).toLocaleString('id-ID'); }
                        }
                    }
                }
            }
        });
    }

    // Users Chart
    const ctxUsers = document.getElementById('usersChart');
    if (ctxUsers) {
        new Chart(ctxUsers.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels7,
                datasets: [{
                    label: '{{ __('admin.dashboard.new_users_label') }}',
                    data: usersLast7,
                    backgroundColor: 'rgba(59,130,246,0.8)',
                    borderColor: 'rgba(59,130,246,1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, precision: 0 } }
            }
        });
    }
</script>
@endsection
