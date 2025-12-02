@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-blue-100">Here's what's happening with your account today.</p>
            </div>
            <div class="hidden md:block">
                <div class="text-right">
                    <p class="text-sm text-blue-200">Member since</p>
                    <p class="text-lg font-semibold">{{ auth()->user()->created_at->format('M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <!-- My Vehicles -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-car text-3xl text-blue-500 opacity-50"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_vehicles'] }}</h3>
            <p class="text-xs text-gray-600">My Vehicles</p>
        </div>

        <!-- Active Vehicles -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-check-circle text-3xl text-green-500 opacity-50"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['active_vehicles'] }}</h3>
            <p class="text-xs text-gray-600">Active</p>
        </div>

        <!-- My Bids -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-gavel text-3xl text-purple-500 opacity-50"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_bids'] }}</h3>
            <p class="text-xs text-gray-600">Total Bids</p>
        </div>

        <!-- Won Auctions -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-trophy text-3xl text-yellow-500 opacity-50"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['won_auctions'] }}</h3>
            <p class="text-xs text-gray-600">Won</p>
        </div>

        <!-- Deposit Balance -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-wallet text-3xl text-orange-500 opacity-50"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Rp {{ number_format($stats['deposit_balance'], 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-600">Balance</p>
        </div>

        <!-- Wishlist -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-heart text-3xl text-red-500 opacity-50"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_wishlist'] }}</h3>
            <p class="text-xs text-gray-600">Wishlist</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow mb-8 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>Quick Actions
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('vehicles.create') }}" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition group">
                <i class="fas fa-plus-circle text-3xl text-blue-600 mb-2 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-700">Add Vehicle</span>
            </a>
            <a href="{{ route('auctions.index') }}" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition group">
                <i class="fas fa-search text-3xl text-green-600 mb-2 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-700">Browse Auctions</span>
            </a>
            <a href="{{ route('deposits.create') }}" class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition group">
                <i class="fas fa-money-bill-wave text-3xl text-orange-600 mb-2 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-700">Top Up Deposit</span>
            </a>
            <a href="{{ route('my.bids') }}" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition group">
                <i class="fas fa-history text-3xl text-purple-600 mb-2 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-700">My Bids History</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- My Recent Vehicles -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-car text-blue-500 mr-2"></i>My Recent Vehicles
                </h3>
                <a href="{{ route('my.ads') }}" class="text-sm text-blue-600 hover:text-blue-800">View All →</a>
            </div>
            <div class="p-6">
                @forelse($recent_vehicles as $vehicle)
                <div class="flex items-center space-x-4 pb-4 mb-4 border-b last:border-0">
                    @if($vehicle->primaryImage)
                    <img src="{{ asset('storage/' . $vehicle->primaryImage->image_path) }}" 
                         alt="{{ $vehicle->brand }} {{ $vehicle->model }}" 
                         class="w-20 h-20 object-cover rounded-lg">
                    @else
                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-car text-gray-400 text-2xl"></i>
                    </div>
                    @endif
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">{{ $vehicle->brand }} {{ $vehicle->model }}</h4>
                        <p class="text-sm text-gray-600">{{ $vehicle->year }} • {{ $vehicle->city->name ?? 'N/A' }}</p>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="text-xs px-2 py-1 rounded-full
                                {{ $vehicle->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $vehicle->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $vehicle->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($vehicle->status) }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $vehicle->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-car text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500 mb-4">You haven't added any vehicles yet.</p>
                    <a href="{{ route('vehicles.create') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i>Add Your First Vehicle
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Bids -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-gavel text-purple-500 mr-2"></i>Recent Bids
                </h3>
                <a href="{{ route('my.bids') }}" class="text-sm text-blue-600 hover:text-blue-800">View All →</a>
            </div>
            <div class="p-6">
                @forelse($recent_bids as $bid)
                <div class="flex items-center justify-between pb-4 mb-4 border-b last:border-0">
                    <div class="flex items-center space-x-3">
                        @if($bid->auction->vehicle->primaryImage)
                        <img src="{{ asset('storage/' . $bid->auction->vehicle->primaryImage->image_path) }}" 
                             alt="Vehicle" 
                             class="w-12 h-12 object-cover rounded">
                        @else
                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-car text-gray-400"></i>
                        </div>
                        @endif
                        <div>
                            <h4 class="font-medium text-gray-800 text-sm">
                                {{ $bid->auction->vehicle->brand }} {{ $bid->auction->vehicle->model }}
                            </h4>
                            <p class="text-xs text-gray-500">{{ $bid->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800 text-sm">Rp {{ number_format($bid->amount, 0, ',', '.') }}</p>
                        @if($bid->is_winner)
                        <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                            <i class="fas fa-trophy"></i> Won
                        </span>
                        @else
                        <span class="text-xs text-gray-500">{{ ucfirst($bid->auction->status) }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-gavel text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500 mb-4">You haven't placed any bids yet.</p>
                    <a href="{{ route('auctions.index') }}" class="inline-block px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-search mr-2"></i>Browse Auctions
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Active Auctions in My Area -->
    @if($area_auctions->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden mt-8">
        <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>Active Auctions Near You
                @if(auth()->user()->city)
                    <span class="text-sm font-normal text-gray-600">in {{ auth()->user()->city->name }}</span>
                @endif
            </h3>
            <a href="{{ route('auctions.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Browse All →</a>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($area_auctions as $auction)
                <div class="border rounded-lg overflow-hidden hover:shadow-lg transition cursor-pointer" onclick="window.location='{{ route('auctions.show', $auction->id) }}'">
                    @if($auction->vehicle->primaryImage)
                    <img src="{{ asset('storage/' . $auction->vehicle->primaryImage->image_path) }}" 
                         alt="{{ $auction->vehicle->brand }} {{ $auction->vehicle->model }}" 
                         class="w-full h-40 object-cover">
                    @else
                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-car text-gray-400 text-4xl"></i>
                    </div>
                    @endif
                    <div class="p-4">
                        <h4 class="font-bold text-gray-800 mb-1">{{ $auction->vehicle->brand }} {{ $auction->vehicle->model }}</h4>
                        <p class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-calendar mr-1"></i>{{ $auction->vehicle->year }}
                            <span class="mx-1">•</span>
                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $auction->vehicle->city->name ?? 'N/A' }}
                        </p>
                        <div class="space-y-1 mb-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Starting Bid:</span>
                                <span class="font-semibold text-gray-800">
                                    Rp {{ number_format($auction->starting_price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Current Bid:</span>
                                <span class="font-bold text-green-600">
                                    Rp {{ number_format($auction->current_price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Total Bids:</span>
                                <span class="font-semibold text-blue-600">{{ $auction->bids->count() }}</span>
                            </div>
                        </div>
                        <div class="pt-3 border-t">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    Ends: {{ $auction->end_time->format('d M Y, H:i') }}
                                </span>
                                @php
                                    $timeLeft = now()->diffInHours($auction->end_time, false);
                                @endphp
                                @if($timeLeft > 0 && $timeLeft < 24)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full font-semibold">
                                        <i class="fas fa-fire"></i> {{ round($timeLeft) }}h left
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Active Auctions for My Vehicles -->
    @if($my_active_auctions->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden mt-8">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-fire text-orange-500 mr-2"></i>My Vehicles in Active Auctions
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($my_active_auctions as $auction)
                <div class="border rounded-lg overflow-hidden hover:shadow-lg transition">
                    @if($auction->vehicle->primaryImage)
                    <img src="{{ asset('storage/' . $auction->vehicle->primaryImage->image_path) }}" 
                         alt="{{ $auction->vehicle->brand }} {{ $auction->vehicle->model }}" 
                         class="w-full h-40 object-cover">
                    @else
                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-car text-gray-400 text-4xl"></i>
                    </div>
                    @endif
                    <div class="p-4">
                        <h4 class="font-bold text-gray-800 mb-1">{{ $auction->vehicle->brand }} {{ $auction->vehicle->model }}</h4>
                        <p class="text-sm text-gray-600 mb-2">{{ $auction->vehicle->year }}</p>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Current Bid:</span>
                            <span class="font-bold text-green-600">
                                Rp {{ number_format($auction->current_price, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm mt-1">
                            <span class="text-gray-600">Total Bids:</span>
                            <span class="font-semibold text-blue-600">{{ $auction->bids->count() }}</span>
                        </div>
                        <div class="mt-3 pt-3 border-t">
                            <p class="text-xs text-gray-500">
                                <i class="far fa-clock mr-1"></i>
                                Ends: {{ $auction->end_time->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Payments -->
    @if($recent_payments->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden mt-8">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-receipt text-green-500 mr-2"></i>Recent Payments
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recent_payments as $payment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                            {{ $payment->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ ucfirst($payment->type) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $payment->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
