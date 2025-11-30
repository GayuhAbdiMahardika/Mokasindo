@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600 mt-1">Welcome back, {{ auth()->user()->name }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-500">{{ now()->format('l, d F Y') }}</p>
            <p class="text-xs text-gray-400">{{ now()->format('H:i') }}</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Users</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_users']) }}</h3>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-arrow-up"></i> +{{ $stats['new_users_this_month'] }} this month
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
                    <p class="text-sm text-gray-600 mb-1">Total Vehicles</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_vehicles']) }}</h3>
                    <p class="text-xs text-yellow-600 mt-1">
                        <i class="fas fa-clock"></i> {{ $stats['pending_vehicles'] }} pending approval
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
                    <p class="text-sm text-gray-600 mb-1">Active Auctions</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['active_auctions']) }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ number_format($stats['total_auctions']) }} total auctions
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
                    <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                    <h3 class="text-3xl font-bold text-gray-800">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        All time revenue
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
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>Quick Actions
            </h3>
            <div class="space-y-2">
                <a href="{{ route('admin.vacancies.create') }}" class="block px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                    <i class="fas fa-briefcase text-blue-600 mr-2"></i>
                    <span class="text-sm font-medium">Post New Job</span>
                </a>
                <a href="{{ route('admin.pages.create') }}" class="block px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg transition">
                    <i class="fas fa-file-alt text-green-600 mr-2"></i>
                    <span class="text-sm font-medium">Create Page</span>
                </a>
                <a href="{{ route('admin.teams.create') }}" class="block px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                    <i class="fas fa-user-plus text-purple-600 mr-2"></i>
                    <span class="text-sm font-medium">Add Team Member</span>
                </a>
                <a href="{{ route('admin.faqs.create') }}" class="block px-4 py-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition">
                    <i class="fas fa-question-circle text-orange-600 mr-2"></i>
                    <span class="text-sm font-medium">Add FAQ</span>
                </a>
            </div>
        </div>

        <!-- Pending Items -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-clock text-orange-500 mr-2"></i>Pending Items
            </h3>
            <div class="space-y-3">
                <a href="#" class="block">
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Pending Vehicles</p>
                            <p class="text-xs text-gray-500">Awaiting approval</p>
                        </div>
                        <span class="bg-yellow-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                            {{ $stats['pending_vehicles'] }}
                        </span>
                    </div>
                </a>
                <a href="#" class="block">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Pending Deposits</p>
                            <p class="text-xs text-gray-500">Awaiting verification</p>
                        </div>
                        <span class="bg-blue-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                            {{ $stats['pending_deposits'] }}
                        </span>
                    </div>
                </a>
                <a href="{{ route('admin.inquiries.index') }}" class="block">
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg hover:bg-red-100 transition">
                        <div>
                            <p class="text-sm font-medium text-gray-800">New Inquiries</p>
                            <p class="text-xs text-gray-500">Customer support</p>
                        </div>
                        <span class="bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                            {{ $stats['new_inquiries'] }}
                        </span>
                    </div>
                </a>
                <a href="#" class="block">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <div>
                            <p class="text-sm font-medium text-gray-800">New Applications</p>
                            <p class="text-xs text-gray-500">Job applicants</p>
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
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>System Info
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Laravel Version</span>
                    <span class="font-medium">{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">PHP Version</span>
                    <span class="font-medium">{{ PHP_VERSION }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Environment</span>
                    <span class="font-medium">{{ config('app.env') }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">Timezone</span>
                    <span class="font-medium">{{ config('app.timezone') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-users text-blue-500 mr-2"></i>Recent Users
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
                                {{ ucfirst($user->role) }}
                            </span>
                            <p class="text-xs text-gray-400 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No users yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Vehicles -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-car text-green-500 mr-2"></i>Recent Vehicles
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
                    <p class="text-gray-500 text-center py-4">No vehicles yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-money-bill-wave text-yellow-500 mr-2"></i>Recent Payments
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recent_payments as $payment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $payment->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst($payment->type) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $payment->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $payment->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No payments yet</td>
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
                <i class="fas fa-hourglass-half text-orange-500 mr-2"></i>Pending Deposits (Requires Action)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
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
                                <i class="fas fa-check-circle mr-1"></i>Review
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
@endsection
