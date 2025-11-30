@extends('layouts.app')

@section('title', 'Lelang Aktif - Mokasindo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Lelang Aktif</h1>
        <p class="text-gray-600">Ikuti lelang kendaraan bekas berkualitas dengan harga terbaik</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('auctions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    <option value="mobil" {{ request('category') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                    <option value="motor" {{ request('category') == 'motor' ? 'selected' : '' }}>Motor</option>
                </select>
            </div>

            <!-- City Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                <select name="city_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Kota</option>
                    @foreach(\App\Models\City::orderBy('name')->get() as $city)
                        <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                <select name="sort" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="ending_soon" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>Segera Berakhir</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="highest_bid" {{ request('sort') == 'highest_bid' ? 'selected' : '' }}>Bid Tertinggi</option>
                    <option value="lowest_price" {{ request('sort') == 'lowest_price' ? 'selected' : '' }}>Harga Terendah</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Auctions Grid -->
    @if($auctions->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Lelang Aktif</h3>
            <p class="text-gray-600">Belum ada lelang yang sedang berlangsung saat ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($auctions as $auction)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                    <!-- Image -->
                    <div class="relative h-48 bg-gray-200">
                        @if($auction->vehicle->primaryImage)
                            <img src="{{ asset('storage/' . $auction->vehicle->primaryImage->image_path) }}" 
                                 alt="{{ $auction->vehicle->brand }} {{ $auction->vehicle->model }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Category Badge -->
                        <span class="absolute top-2 left-2 bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                            {{ ucfirst($auction->vehicle->category) }}
                        </span>

                        <!-- Time Left Badge -->
                        <div class="absolute top-2 right-2 bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                            <span class="countdown" data-end="{{ $auction->end_time->toIso8601String() }}">
                                {{ $auction->end_time->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <h3 class="font-bold text-lg text-gray-900 mb-1">
                            {{ $auction->vehicle->brand }} {{ $auction->vehicle->model }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $auction->vehicle->city->name ?? 'Unknown' }}, {{ $auction->vehicle->province->name ?? '' }}
                        </p>

                        <!-- Vehicle Info -->
                        <div class="grid grid-cols-2 gap-2 mb-4 text-xs text-gray-600">
                            <div><i class="fas fa-calendar mr-1"></i> {{ $auction->vehicle->year }}</div>
                            <div><i class="fas fa-tachometer-alt mr-1"></i> {{ number_format($auction->vehicle->mileage ?? 0) }} km</div>
                            <div><i class="fas fa-cog mr-1"></i> {{ ucfirst($auction->vehicle->transmission ?? 'N/A') }}</div>
                            <div><i class="fas fa-gas-pump mr-1"></i> {{ ucfirst($auction->vehicle->fuel_type ?? 'N/A') }}</div>
                        </div>

                        <!-- Price Info -->
                        <div class="border-t pt-3">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Bid Saat Ini:</span>
                                <span class="text-xl font-bold text-blue-600">
                                    Rp {{ number_format($auction->current_price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm text-gray-600">
                                <span><i class="fas fa-gavel mr-1"></i> {{ $auction->bid_count }} bid</span>
                                <span><i class="fas fa-users mr-1"></i> {{ $auction->bids->unique('user_id')->count() }} peserta</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('auctions.show', $auction->id) }}" 
                           class="mt-4 block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-medium py-2 px-4 rounded-md transition duration-150">
                            Ikut Lelang
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $auctions->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
// Countdown timer
function updateCountdowns() {
    document.querySelectorAll('.countdown').forEach(element => {
        const endTime = new Date(element.dataset.end);
        const now = new Date();
        const diff = endTime - now;

        if (diff <= 0) {
            element.textContent = 'Berakhir';
            element.parentElement.classList.remove('bg-red-600');
            element.parentElement.classList.add('bg-gray-600');
            return;
        }

        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        if (hours > 24) {
            const days = Math.floor(hours / 24);
            element.textContent = `${days} hari lagi`;
        } else if (hours > 0) {
            element.textContent = `${hours}j ${minutes}m`;
        } else {
            element.textContent = `${minutes}m ${seconds}s`;
            element.parentElement.classList.add('animate-pulse');
        }
    });
}

// Update every second
setInterval(updateCountdowns, 1000);
updateCountdowns();
</script>
@endpush
@endsection
