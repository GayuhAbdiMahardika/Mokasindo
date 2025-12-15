@extends('admin.layout')

@section('title', __('admin.auctions.title'))

@section('content')
<div x-data="auctionManager()" class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold" style="color: #1f2937 !important;">{{ __('admin.auctions.heading') }}</h1>
            <p class="mt-1" style="color: #6b7280 !important;">{{ __('admin.auctions.subtitle') }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <button @click="showAddVehicleModal = true" 
                    class="px-6 py-3 rounded-xl transition-all shadow-lg inline-flex items-center font-medium" style="background-color: #f97316 !important; color: white !important;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('admin.auctions.add_vehicle') }}
            </button>
            <form method="POST" action="{{ route('admin.auctions.sync-status') }}" class="inline">
                @csrf
                <button type="submit" class="px-6 py-3 rounded-xl transition-all shadow-lg inline-flex items-center font-medium" style="background-color: #16a34a !important; color: white !important;">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('admin.auctions.sync_status') }}
                </button>
            </form>
        </div>
    </div>

    @if(session('status'))
    <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 px-6 py-4 rounded-r-xl mb-6 flex items-center shadow-sm">
        <svg class="w-6 h-6 mr-3 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('status') }}
    </div>
    @endif
    
    @if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 px-6 py-4 rounded-r-xl mb-6 flex items-center shadow-sm">
        <svg class="w-6 h-6 mr-3 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="rounded-2xl shadow-lg p-6" style="background-color: #6366f1 !important;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #c7d2fe !important;">{{ __('admin.auctions.stats.total') }}</p>
                    <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $stats['total'] ?? $auctions->total() }}</p>
                </div>
                <div class="rounded-xl p-3" style="background-color: rgba(255,255,255,0.2);">
                    <svg class="w-8 h-8" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl shadow-lg p-6" style="background-color: #16a34a !important;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #bbf7d0 !important;">{{ __('admin.auctions.stats.active') }}</p>
                    <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="rounded-xl p-3" style="background-color: rgba(255,255,255,0.2);">
                    <svg class="w-8 h-8" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl shadow-lg p-6" style="background-color: #f59e0b !important;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #fef3c7 !important;">{{ __('admin.auctions.stats.scheduled') }}</p>
                    <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $stats['scheduled'] ?? 0 }}</p>
                </div>
                <div class="rounded-xl p-3" style="background-color: rgba(255,255,255,0.2);">
                    <svg class="w-8 h-8" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl shadow-lg p-6" style="background-color: #64748b !important;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #e2e8f0 !important;">{{ __('admin.auctions.stats.ended') }}</p>
                    <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $stats['ended'] ?? 0 }}</p>
                </div>
                <div class="rounded-xl p-3" style="background-color: rgba(255,255,255,0.2);">
                    <svg class="w-8 h-8" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow-lg mb-6 p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center mb-4">
            <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('admin.common.filter') }}</h3>
        </div>
        <form method="GET" action="{{ route('admin.auctions.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.common.search') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ __('admin.auctions.search_placeholder') }}" 
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.auctions.status') }}</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition appearance-none bg-no-repeat bg-right pr-10" style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;); background-position: right 0.5rem center; background-size: 1.5em 1.5em;">
                    <option value="">{{ __('admin.common.all') }}</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>{{ __('admin.auctions.status_scheduled') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.auctions.status_active') }}</option>
                    <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>{{ __('admin.auctions.status_ended') }}</option>
                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>{{ __('admin.auctions.status_sold') }}</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('admin.auctions.status_cancelled') }}</option>
                </select>
            </div>

            <!-- Searchable Schedule Dropdown -->
            <div x-data="{ open: false, search: '', selected: '{{ request('schedule_id') }}', selectedText: '{{ request('schedule_id') && isset($schedules) ? $schedules->where('id', request('schedule_id'))->first()?->title : '' }}' }">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.auctions.schedule') }}</label>
                <div class="relative">
                    <button type="button" @click="open = !open" class="w-full text-left rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 flex items-center justify-between">
                        <span x-text="selectedText || '{{ __('admin.common.all') }}'"></span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <input type="hidden" name="schedule_id" :value="selected">
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="absolute z-50 w-full mt-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-xl max-h-60 overflow-auto">
                        <div class="p-2 sticky top-0 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <input type="text" x-model="search" placeholder="{{ __('admin.common.search') }}..." class="w-full rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div @click="selected = ''; selectedText = '{{ __('admin.common.all') }}'; open = false" class="px-4 py-3 cursor-pointer dark:text-white">
                            {{ __('admin.common.all') }}
                        </div>
                        @if(isset($schedules))
                        @foreach($schedules as $schedule)
                        <div x-show="!search || '{{ strtolower($schedule->title . ' ' . $schedule->location . ' ' . $schedule->start_date->format('d M Y')) }}'.includes(search.toLowerCase())"
                             @click="selected = '{{ $schedule->id }}'; selectedText = '{{ $schedule->title }} ({{ $schedule->start_date->format('d M Y') }})'; open = false"
                             class="px-4 py-3 cursor-pointer dark:text-white">
                            {{ $schedule->title }} ({{ $schedule->start_date->format('d M Y') }})
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl transition-all shadow-lg inline-flex items-center" style="background-color: #4f46e5 !important; color: white !important;">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    {{ __('admin.common.filter') }}
                </button>
                <a href="{{ route('admin.auctions.index') }}" class="px-6 py-2.5 rounded-xl transition inline-flex items-center" style="background-color: #e5e7eb; color: #374151;">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('admin.common.reset') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Auctions Table -->
    <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('admin.auctions.vehicle') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('admin.auctions.schedule') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('admin.auctions.price') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('admin.auctions.bids') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('admin.auctions.status') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('admin.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700" style="background-color: #1f2937 !important;">
                    @forelse($auctions as $auction)
                    <tr class="transition-colors" style="background-color: #1f2937;">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($auction->vehicle && $auction->vehicle->images && $auction->vehicle->images->count() > 0)
                                <img src="{{ Storage::url($auction->vehicle->images->first()->image_path) }}" 
                                     alt="{{ $auction->vehicle->brand }}" 
                                     class="w-12 h-12 rounded-lg object-cover mr-3">
                                @else
                                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-lg mr-3 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                    </svg>
                                </div>
                                @endif
                                <div>
                                    <p class="font-semibold" style="color: #f3f4f6 !important;">{{ $auction->vehicle->brand ?? 'N/A' }} {{ $auction->vehicle->model ?? '' }}</p>
                                    <p class="text-sm" style="color: #9ca3af !important;">{{ $auction->vehicle->license_plate ?? '-' }} | {{ $auction->vehicle->year ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <p class="font-medium" style="color: #f3f4f6 !important;">{{ $auction->schedule?->title ?? '-' }}</p>
                            <p style="color: #9ca3af !important;">{{ $auction->schedule?->start_date?->format('d M Y') ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <p class="font-bold" style="color: #22c55e !important;">Rp {{ number_format($auction->current_price ?? $auction->starting_price ?? 0, 0, ',', '.') }}</p>
                            <p class="text-xs" style="color: #9ca3af !important;">Start: Rp {{ number_format($auction->starting_price ?? 0, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $auction->bids_count ?? $auction->bids()->count() }} {{ __('admin.auctions.bids') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'scheduled' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'active' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'ended' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                    'sold' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    'reopened' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$auction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ __('admin.auctions.status_' . $auction->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.auctions.show', $auction) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400" title="{{ __('admin.common.view') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.auctions.edit', $auction) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400" title="{{ __('admin.common.edit') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if($auction->status == 'active')
                                <button @click="openForceEndModal({{ $auction->id }})" 
                                        class="text-red-600 hover:text-red-900 dark:text-red-400" title="{{ __('admin.auctions.action.force_end') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <p class="text-lg font-medium">{{ __('admin.common.no_data') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($auctions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $auctions->withQueryString()->links() }}
        </div>
        @endif
    </div>

    <!-- Add Vehicle Modal - Modern Design -->
    <div x-show="showAddVehicleModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showAddVehicleModal = false"></div>
            <div class="relative bg-gray-100 dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full p-6 transform transition-all" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="bg-orange-500 rounded-xl p-2 mr-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('admin.auctions.modal.add_vehicle_title') }}</h3>
                    </div>
                    <button @click="showAddVehicleModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form action="{{ route('admin.auctions.add-vehicles') }}" method="POST">
                    @csrf
                    
                    <!-- Schedule Selection -->
                    <div class="mb-5" x-data="{ open: false, search: '' }">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.auctions.modal.select_schedule') }}</label>
                        <div class="relative">
                            <button type="button" @click="open = !open" class="w-full text-left rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-3 flex items-center justify-between shadow-sm">
                                <span x-text="selectedScheduleText || '{{ __('admin.auctions.modal.choose_schedule') }}'"></span>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <input type="hidden" name="schedule_id" :value="selectedScheduleId" required>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 w-full mt-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-xl max-h-60 overflow-auto">
                                <div class="p-3 sticky top-0 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <input type="text" x-model="search" placeholder="{{ __('admin.common.search') }}..." class="w-full rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm px-4 py-2 focus:ring-2 focus:ring-orange-500">
                                </div>
                                @if(isset($activeSchedules))
                                @foreach($activeSchedules as $schedule)
                                <div x-show="!search || '{{ strtolower($schedule->title . ' ' . $schedule->location . ' ' . $schedule->start_date->format('d M Y')) }}'.includes(search.toLowerCase())"
                                     @click="selectedScheduleId = '{{ $schedule->id }}'; selectedScheduleText = '{{ $schedule->title }} ({{ $schedule->start_date->format('d M Y') }})'; open = false"
                                     class="px-4 py-3 cursor-pointer dark:text-white">
                                    <div class="font-medium">{{ $schedule->title }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $schedule->location }} | {{ $schedule->start_date->format('d M Y') }}</div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Selection -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.auctions.modal.select_vehicles') }}</label>
                        <div class="mb-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" x-model="vehicleSearch" placeholder="{{ __('admin.auctions.search_placeholder') }}" 
                                    class="w-full pl-10 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-orange-500">
                            </div>
                        </div>
                        <div class="max-h-60 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-xl shadow-inner bg-gray-50 dark:bg-gray-900">
                            @if(isset($availableVehicles))
                            @forelse($availableVehicles as $vehicle)
                            <label x-show="!vehicleSearch || '{{ strtolower($vehicle->brand . ' ' . $vehicle->model . ' ' . $vehicle->license_plate . ' ' . $vehicle->year . ' ' . ($vehicle->user->name ?? '')) }}'.includes(vehicleSearch.toLowerCase())"
                                   class="flex items-center p-4 border-b border-gray-100 dark:border-gray-700 cursor-pointer">
                                <input type="checkbox" name="vehicle_ids[]" value="{{ $vehicle->id }}" class="rounded-lg border-gray-300 text-orange-600 focus:ring-orange-500 mr-4 w-5 h-5">
                                @if($vehicle->images && $vehicle->images->first())
                                <img src="{{ Storage::url($vehicle->images->first()->image_path) }}" class="h-12 w-12 rounded-xl object-cover mr-4 shadow">
                                @else
                                <div class="h-12 w-12 rounded-xl bg-gray-200 dark:bg-gray-600 mr-4 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                @endif
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $vehicle->license_plate }} | <span class="text-green-600 font-medium">Rp {{ number_format($vehicle->starting_price, 0, ',', '.') }}</span></div>
                                </div>
                            </label>
                            @empty
                            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                {{ __('admin.auctions.no_available_vehicles') }}
                            </div>
                            @endforelse
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="showAddVehicleModal = false" class="px-6 py-2.5 rounded-xl transition font-medium" style="background-color: #e5e7eb; color: #374151;">
                            {{ __('admin.common.cancel') }}
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl transition shadow-lg font-medium" style="background-color: #f97316 !important; color: white !important;">
                            {{ __('admin.auctions.modal.add_to_auction') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Force End Modal - Modern Design -->
    <div x-show="showForceEndModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showForceEndModal = false"></div>
            <div class="relative bg-gray-100 dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all" x-transition>
                <div class="flex items-center mb-4">
                    <div class="bg-red-500 rounded-xl p-2 mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('admin.auctions.modal.force_end_title') }}</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-6 ml-12">{{ __('admin.auctions.modal.force_end_body') }}</p>
                <form :action="'/admin/auctions/' + forceEndAuctionId + '/force-end'" method="POST">
                    @csrf
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="showForceEndModal = false" 
                                class="px-6 py-2.5 rounded-xl transition font-medium"
                                style="background-color: #6b7280 !important; color: white !important;">
                            {{ __('admin.common.cancel') }}
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl transition shadow-lg font-medium"
                                style="background-color: #ef4444 !important; color: white !important;">
                            {{ __('admin.auctions.modal.force_end_submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function auctionManager() {
    return {
        showAddVehicleModal: false,
        showForceEndModal: false,
        forceEndAuctionId: null,
        selectedScheduleId: '',
        selectedScheduleText: '',
        vehicleSearch: '',
        
        openForceEndModal(auctionId) {
            this.forceEndAuctionId = auctionId;
            this.showForceEndModal = true;
        }
    }
}
</script>
@endsection
