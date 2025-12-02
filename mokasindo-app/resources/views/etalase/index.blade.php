@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-white mb-4">Etalase Kendaraan</h1>
            <p class="text-indigo-100 text-lg">Temukan mobil & motor bekas berkualitas dengan harga terbaik</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Search & Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <form action="{{ route('etalase.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Kendaraan</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari merek, model, atau kata kunci..." 
                               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category" class="w-full border rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Kategori</option>
                        <option value="mobil" {{ request('category') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                        <option value="motor" {{ request('category') == 'motor' ? 'selected' : '' }}>Motor</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                    <select name="sort" class="w-full border rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="cheapest" {{ request('sort') == 'cheapest' ? 'selected' : '' }}>Termurah</option>
                        <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>Termahal</option>
                    </select>
                </div>
            </div>

            <!-- Price Range & City -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Minimum</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" 
                           placeholder="Rp 0" 
                           class="w-full border rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Maksimum</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" 
                           placeholder="Rp 999.999.999" 
                           class="w-full border rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                    <select name="city_id" class="w-full border rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Kota</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition font-medium">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600">
            Menampilkan <span class="font-semibold">{{ $vehicles->total() }}</span> kendaraan
        </p>
        @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'city_id']))
            <a href="{{ route('etalase.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm">
                <i class="fas fa-times mr-1"></i>Reset Filter
            </a>
        @endif
    </div>

    <!-- Vehicle Grid -->
    @if($vehicles->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($vehicles as $vehicle)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition group">
                    <!-- Image -->
                    <div class="relative aspect-[4/3] overflow-hidden">
                        @if($vehicle->primaryImage)
                            <img src="{{ asset('storage/' . $vehicle->primaryImage->image_path) }}" 
                                 alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-car text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                        
                        <!-- Category Badge -->
                        <span class="absolute top-3 left-3 px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $vehicle->category == 'mobil' ? 'bg-blue-500 text-white' : 'bg-green-500 text-white' }}">
                            {{ ucfirst($vehicle->category) }}
                        </span>

                        <!-- Wishlist Button -->
                        @auth
                            <button class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow hover:bg-red-50 transition wishlist-btn"
                                    data-vehicle-id="{{ $vehicle->id }}">
                                <i class="far fa-heart text-gray-400 hover:text-red-500"></i>
                            </button>
                        @endauth

                        <!-- Auction Badge -->
                        @if($vehicle->auction && $vehicle->auction->status == 'active')
                            <span class="absolute bottom-3 left-3 px-2 py-1 text-xs font-semibold bg-red-500 text-white rounded-full animate-pulse">
                                <i class="fas fa-gavel mr-1"></i>Lelang Aktif
                            </span>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-lg mb-1 truncate">
                            {{ $vehicle->brand }} {{ $vehicle->model }}
                        </h3>
                        <p class="text-gray-500 text-sm mb-3">{{ $vehicle->year }} • {{ number_format($vehicle->mileage) }} km</p>
                        
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-indigo-600 font-bold text-lg">
                                Rp {{ number_format($vehicle->starting_price, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center text-gray-400 text-sm mb-4">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            <span class="truncate">{{ $vehicle->city->name ?? 'N/A' }}</span>
                        </div>

                        <a href="{{ route('etalase.show', $vehicle->id) }}" 
                           class="block w-full text-center bg-indigo-50 text-indigo-600 py-2 rounded-lg font-medium hover:bg-indigo-100 transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $vehicles->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-car text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Kendaraan Ditemukan</h3>
            <p class="text-gray-500 mb-4">Coba ubah filter pencarian Anda atau cari dengan kata kunci lain</p>
            <a href="{{ route('etalase.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Reset Pencarian
            </a>
        </div>
    @endif
</div>
@endsection
