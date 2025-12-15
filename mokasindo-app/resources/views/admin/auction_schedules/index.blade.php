@extends('admin.layout')

@section('title', __('admin.auction_schedules.title'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ __('admin.auction_schedules.heading') }}</h1>
        <a href="{{ route('admin.auction-schedules.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>{{ __('admin.auction_schedules.new') }}
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.auction-schedules.index') }}" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="{{ __('admin.auction_schedules.search_placeholder') }}" 
                   class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.auction_schedules.all_status') }}</option>
                <option value="running" {{ request('status') == 'running' ? 'selected' : '' }}>{{ __('admin.auction_schedules.status.running') }}</option>
                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>{{ __('admin.auction_schedules.status.upcoming') }}</option>
                <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>{{ __('admin.auction_schedules.status.ended') }}</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-search mr-2"></i>{{ __('admin.auction_schedules.filter') }}
            </button>
            <a href="{{ route('admin.auction-schedules.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                <i class="fas fa-redo mr-2"></i>{{ __('admin.auction_schedules.reset') }}
            </a>
        </form>
    </div>

    @if(session('status'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('status') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-10"></th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.auction_schedules.table.title') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.auction_schedules.table.location') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.auction_schedules.table.vehicles') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.auction_schedules.table.start') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.auction_schedules.table.end') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.auction_schedules.table.status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.auction_schedules.table.action') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" x-data="{ expanded: {} }">
                @forelse($schedules as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        @if($s->auctions_count > 0)
                        <button @click="expanded[{{ $s->id }}] = !expanded[{{ $s->id }}]" 
                                class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas" :class="expanded[{{ $s->id }}] ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                        </button>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium">{{ $s->title }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $s->location }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $s->auctions_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            <i class="fas fa-car mr-1"></i>
                            {{ $s->auctions_count }} {{ __('admin.auction_schedules.vehicles_label') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $s->start_date->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm">{{ $s->end_date->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $now = now();
                            $isRunning = $now->between($s->start_date, $s->end_date);
                            $isUpcoming = $s->start_date > $now;
                            $isEnded = $s->end_date < $now;
                        @endphp
                        @if($isRunning)
                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                <i class="fas fa-play-circle mr-1"></i>{{ __('admin.auction_schedules.status.running') }}
                            </span>
                        @elseif($isUpcoming)
                            <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                <i class="fas fa-clock mr-1"></i>{{ __('admin.auction_schedules.status.upcoming') }}
                            </span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">
                                <i class="fas fa-flag-checkered mr-1"></i>{{ __('admin.auction_schedules.status.ended') }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.auction-schedules.edit', $s) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.auction-schedules.destroy', $s) }}" class="inline" onsubmit="return confirm('{{ __('admin.auction_schedules.confirm_delete') }}')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <!-- Collapsible Vehicle List -->
                @if($s->auctions_count > 0)
                <tr x-show="expanded[{{ $s->id }}]" x-cloak class="bg-gray-50">
                    <td colspan="8" class="px-6 py-4">
                        <div class="border rounded-lg overflow-hidden">
                            <div class="bg-gray-100 px-4 py-2 font-medium text-sm text-gray-700">
                                <i class="fas fa-list mr-2"></i>{{ __('admin.auction_schedules.vehicles_in_schedule') }}
                            </div>
                            <div class="divide-y divide-gray-200">
                                @foreach($s->auctions as $auction)
                                <div class="flex items-center justify-between px-4 py-3 hover:bg-white">
                                    <div class="flex items-center space-x-3">
                                        @if($auction->vehicle && $auction->vehicle->images && $auction->vehicle->images->count() > 0)
                                        <img src="{{ asset('storage/' . $auction->vehicle->images->first()->image_path) }}" 
                                             alt="{{ $auction->vehicle->brand }}" 
                                             class="w-10 h-10 rounded object-cover">
                                        @else
                                        <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-car text-gray-400"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $auction->vehicle->brand ?? 'N/A' }} {{ $auction->vehicle->model ?? '' }}</p>
                                            <p class="text-xs text-gray-500">{{ $auction->vehicle->year ?? '' }} • {{ $auction->vehicle->license_plate ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-green-600">Rp {{ number_format($auction->starting_price ?? 0, 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-500">{{ $auction->bids()->count() }} bids</p>
                                        </div>
                                        <span class="px-2 py-1 rounded text-xs font-medium
                                            @if($auction->status == 'scheduled') bg-blue-100 text-blue-800
                                            @elseif($auction->status == 'active') bg-green-100 text-green-800
                                            @elseif($auction->status == 'ended') bg-gray-100 text-gray-800
                                            @elseif($auction->status == 'sold') bg-purple-100 text-purple-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($auction->status) }}
                                        </span>
                                        <a href="{{ route('admin.auctions.show', $auction) }}" class="text-indigo-600 hover:text-indigo-800">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
                @endif
                @empty
                    <tr><td colspan="8" class="px-6 py-4 text-center text-gray-500">{{ __('admin.auction_schedules.empty') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $schedules->links() }}</div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
