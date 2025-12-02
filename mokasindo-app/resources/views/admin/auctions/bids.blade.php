@extends('admin.layout')

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <a href="{{ route('admin.auctions.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-6">
        <i class="fas fa-arrow-left mr-2"></i>Back to Auctions
    </a>

    <!-- Auction Info Card -->
    <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
        <div class="p-6">
            <div class="flex items-start gap-6">
                @if($auction->vehicle->photos->first())
                <img src="{{ Storage::url($auction->vehicle->photos->first()->path) }}" alt="Vehicle" class="w-48 h-32 rounded object-cover">
                @endif
                
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $auction->vehicle->brand }} {{ $auction->vehicle->model }} ({{ $auction->vehicle->year }})</h1>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                        <div>
                            <p class="text-sm text-gray-600">Starting Price</p>
                            <p class="text-lg font-bold text-gray-900">Rp {{ number_format($auction->starting_price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Current Price</p>
                            <p class="text-lg font-bold text-green-600">Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Bids</p>
                            <p class="text-lg font-bold text-blue-600">{{ $auction->bids()->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold 
                                {{ $auction->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($auction->status === 'upcoming' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($auction->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bid Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Unique Bidders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $auction->bids()->distinct('user_id')->count('user_id') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3 mr-4">
                    <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Highest Bid</p>
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 rounded-full p-3 mr-4">
                    <i class="fas fa-percentage text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Price Increase</p>
                    <p class="text-xl font-bold text-gray-900">
                        {{ $auction->starting_price > 0 ? number_format((($auction->current_price - $auction->starting_price) / $auction->starting_price) * 100, 1) : 0 }}%
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 rounded-full p-3 mr-4">
                    <i class="fas fa-trophy text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Leading Bidder</p>
                    <p class="text-sm font-bold text-gray-900">{{ $auction->bids()->latest('amount')->first()?->user->name ?? 'None' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bids Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">Bid History</h2>
            <button onclick="window.print()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="fas fa-print mr-2"></i>Print Report
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bidder</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bid Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Increase</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bids as $index => $bid)
                    <tr class="hover:bg-gray-50 {{ $loop->first ? 'bg-green-50' : '' }}">
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $bids->firstItem() + $index }}
                            @if($loop->first)
                                <i class="fas fa-crown text-yellow-500 ml-2"></i>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-indigo-100 rounded-full w-10 h-10 flex items-center justify-center mr-3">
                                    <span class="text-indigo-600 font-bold">{{ substr($bid->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $bid->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $bid->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-lg font-bold text-gray-900">Rp {{ number_format($bid->amount, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($loop->last)
                                <span class="text-sm text-gray-500">Initial bid</span>
                            @else
                                @php
                                    $prevBid = $bids[$index + 1] ?? null;
                                    $increase = $prevBid ? $bid->amount - $prevBid->amount : 0;
                                @endphp
                                <span class="text-sm font-semibold text-green-600">+Rp {{ number_format($increase, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $bid->created_at->format('d M Y, H:i:s') }}
                            <br>
                            <span class="text-xs text-gray-400">{{ $bid->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($loop->first && $auction->status === 'active')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Leading
                                </span>
                            @elseif($loop->first && $auction->status === 'ended')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                    <i class="fas fa-trophy mr-1"></i>Winner
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                    Outbid
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                            <p>No bids yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bids->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $bids->links() }}
        </div>
        @endif
    </div>
</div>

<style>
@media print {
    .no-print { display: none; }
    body { background: white; }
}
</style>
@endsection
