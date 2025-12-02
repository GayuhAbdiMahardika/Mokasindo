@extends('admin.layout')

@section('title', 'Auctions Management')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Auctions Management</h1>
            <p class="text-gray-600 mt-1">Monitor and manage all auction activities</p>
        </div>
        <button onclick="window.location.href='{{ route('admin.auction-schedules.create') }}'" 
                class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus mr-2"></i>Create Schedule
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-calendar-alt text-3xl opacity-80"></i>
                <span class="text-sm opacity-80">Scheduled</span>
            </div>
            <p class="text-3xl font-bold">{{ $auctions->where('status', 'scheduled')->count() }}</p>
            <p class="text-sm opacity-90 mt-1">Scheduled auctions</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-play-circle text-3xl opacity-80"></i>
                <span class="text-sm opacity-80">Active</span>
            </div>
            <p class="text-3xl font-bold">{{ $auctions->where('status', 'active')->count() }}</p>
            <p class="text-sm opacity-90 mt-1">Currently running</p>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-hourglass-half text-3xl opacity-80"></i>
                <span class="text-sm opacity-80">Ending Soon</span>
            </div>
            <p class="text-3xl font-bold">{{ $auctions->where('status', 'active')->filter(fn($a) => $a->end_time && $a->end_time->diffInHours(now()) < 2)->count() }}</p>
            <p class="text-sm opacity-90 mt-1">< 2 hours left</p>
        </div>

        <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-flag-checkered text-3xl opacity-80"></i>
                <span class="text-sm opacity-80">Ended Today</span>
            </div>
            <p class="text-3xl font-bold">{{ $auctions->where('status', 'ended')->filter(fn($a) => $a->end_time && $a->end_time->isToday())->count() }}</p>
            <p class="text-sm opacity-90 mt-1">Completed today</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <form method="GET" action="{{ route('admin.auctions.index') }}" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Search by vehicle or owner..." 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <option value="">All Status</option>
                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Ended</option>
                <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <select name="schedule" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <option value="">All Schedules</option>
                @foreach(\App\Models\AuctionSchedule::all() as $schedule)
                <option value="{{ $schedule->id }}" {{ request('schedule') == $schedule->id ? 'selected' : '' }}>
                    {{ $schedule->name }}
                </option>
                @endforeach
            </select>

            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.auctions.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                <i class="fas fa-redo mr-2"></i>Reset
            </a>
        </form>
    </div>

    <!-- Auctions Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" class="rounded">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price Range</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bids</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Remaining</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($auctions as $auction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="auction-checkbox rounded" value="{{ $auction->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($auction->vehicle && $auction->vehicle->images && $auction->vehicle->images->count() > 0)
                                <img src="{{ asset('storage/' . $auction->vehicle->images->first()->image_path) }}" 
                                     alt="{{ $auction->vehicle->brand }}" 
                                     class="w-16 h-16 rounded-lg object-cover mr-3">
                                @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg mr-3 flex items-center justify-center">
                                    <i class="fas fa-car text-gray-400 text-2xl"></i>
                                </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $auction->vehicle->brand ?? 'N/A' }} {{ $auction->vehicle->model ?? '' }}</p>
                                    <p class="text-sm text-gray-500">{{ $auction->vehicle->year ?? '' }}</p>
                                    <p class="text-xs text-gray-400">Owner: {{ $auction->vehicle->user->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <p class="font-medium text-gray-900">{{ $auction->start_time ? $auction->start_time->format('d M Y') : 'N/A' }}</p>
                            <p class="text-gray-600">{{ $auction->start_time ? $auction->start_time->format('H:i') : '' }} - {{ $auction->end_time ? $auction->end_time->format('H:i') : '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <p class="font-bold text-green-600">Rp {{ number_format($auction->starting_price ?? 0, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">Start price</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-gavel mr-1"></i>
                                {{ $auction->bids()->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($auction->status == 'active' && $auction->end_time)
                                @php
                                    $remaining = $auction->end_time->diff(now());
                                    $isExpired = $auction->end_time < now();
                                @endphp
                                @if(!$isExpired)
                                    <p class="font-semibold text-orange-600">{{ $remaining->d }}d {{ $remaining->h }}h {{ $remaining->i }}m</p>
                                @else
                                    <p class="font-semibold text-red-600">Expired</p>
                                @endif
                            @else
                                <p class="text-gray-400">—</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($auction->status == 'scheduled')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-clock mr-1"></i>Scheduled
                                </span>
                            @elseif($auction->status == 'active')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-play-circle mr-1"></i>Active
                                </span>
                            @elseif($auction->status == 'ended')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <i class="fas fa-flag-checkered mr-1"></i>Ended
                                </span>
                            @elseif($auction->status == 'sold')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    <i class="fas fa-check-circle mr-1"></i>Sold
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>{{ ucfirst($auction->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.auctions.show', $auction) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.auctions.bids', $auction) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="View Bids">
                                    <i class="fas fa-gavel"></i>
                                </a>
                                <a href="{{ route('admin.auctions.edit', $auction) }}" 
                                   class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($auction->status == 'active')
                                <button onclick="forceEndAuction({{ $auction->id }})" 
                                        class="text-red-600 hover:text-red-900" title="Force End">
                                    <i class="fas fa-stop-circle"></i>
                                </button>
                                <button onclick="adjustTimer({{ $auction->id }})" 
                                        class="text-orange-600 hover:text-orange-900" title="Adjust Timer">
                                    <i class="fas fa-clock"></i>
                                </button>
                                @elseif($auction->status == 'ended')
                                <button onclick="reopenAuction({{ $auction->id }})" 
                                        class="text-green-600 hover:text-green-900" title="Reopen">
                                    <i class="fas fa-redo"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">No auctions found</p>
                            <p class="text-sm">Try adjusting your filters or create a new auction schedule.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $auctions->links() }}
        </div>
    </div>
</div>

<!-- Force End Modal -->
<div id="forceEndModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Force End Auction</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to force end this auction? This action cannot be undone.</p>
        <form id="forceEndForm" method="POST" action="">
            @csrf
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('forceEndModal')" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Force End
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Adjust Timer Modal -->
<div id="adjustTimerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Adjust Auction Timer</h3>
        <form id="adjustTimerForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">New End Time</label>
                <input type="datetime-local" name="end_time" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('adjustTimerModal')" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Update Timer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reopen Modal -->
<div id="reopenModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Reopen Auction</h3>
        <p class="text-gray-600 mb-6">Reopen this auction? You'll need to set a new end time.</p>
        <form id="reopenForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">New End Time</label>
                <input type="datetime-local" name="end_time" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('reopenModal')" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Reopen Auction
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function forceEndAuction(id) {
    document.getElementById('forceEndForm').action = `/admin/auctions/${id}/force-end`;
    document.getElementById('forceEndModal').classList.remove('hidden');
}

function adjustTimer(id) {
    document.getElementById('adjustTimerForm').action = `/admin/auctions/${id}/adjust-timer`;
    document.getElementById('adjustTimerModal').classList.remove('hidden');
}

function reopenAuction(id) {
    document.getElementById('reopenForm').action = `/admin/auctions/${id}/reopen`;
    document.getElementById('reopenModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Select all functionality
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.auction-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>
@endsection
