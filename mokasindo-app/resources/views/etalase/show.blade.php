@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li><a href="/" class="text-gray-500 hover:text-indigo-600">Beranda</a></li>
            <li class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                <a href="{{ route('etalase.index') }}" class="text-gray-500 hover:text-indigo-600">Etalase</a>
            </li>
            <li class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                <span class="text-gray-700 font-medium">{{ $vehicle->brand }} {{ $vehicle->model }}</span>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Images -->
        <div class="lg:col-span-2">
            <!-- Main Image Gallery -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6" x-data="{ activeImage: 0 }">
                <!-- Main Image -->
                <div class="aspect-[16/10] bg-gray-100 relative">
                    @if($vehicle->images && $vehicle->images->count() > 0)
                        @foreach($vehicle->images as $index => $image)
                            <img x-show="activeImage === {{ $index }}" 
                                 src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                                 class="w-full h-full object-cover"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100">
                        @endforeach
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-car text-gray-300 text-6xl"></i>
                        </div>
                    @endif

                    <!-- Navigation Arrows -->
                    @if($vehicle->images && $vehicle->images->count() > 1)
                        <button @click="activeImage = activeImage === 0 ? {{ $vehicle->images->count() - 1 }} : activeImage - 1" 
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 rounded-full flex items-center justify-center hover:bg-white shadow">
                            <i class="fas fa-chevron-left text-gray-700"></i>
                        </button>
                        <button @click="activeImage = activeImage === {{ $vehicle->images->count() - 1 }} ? 0 : activeImage + 1" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 rounded-full flex items-center justify-center hover:bg-white shadow">
                            <i class="fas fa-chevron-right text-gray-700"></i>
                        </button>
                    @endif
                </div>

                <!-- Thumbnails -->
                @if($vehicle->images && $vehicle->images->count() > 1)
                    <div class="p-4 flex gap-2 overflow-x-auto">
                        @foreach($vehicle->images as $index => $image)
                            <button @click="activeImage = {{ $index }}"
                                    :class="activeImage === {{ $index }} ? 'ring-2 ring-indigo-500' : 'opacity-70 hover:opacity-100'"
                                    class="flex-shrink-0 w-20 h-16 rounded-lg overflow-hidden transition">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="Thumbnail {{ $index + 1 }}"
                                     class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Description -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi</h2>
                <div class="prose prose-sm max-w-none text-gray-600">
                    {!! nl2br(e($vehicle->description)) !!}
                </div>
            </div>

            <!-- Specifications -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Spesifikasi</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-500 text-sm">Merek</p>
                        <p class="font-semibold text-gray-900">{{ $vehicle->brand }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-500 text-sm">Model</p>
                        <p class="font-semibold text-gray-900">{{ $vehicle->model }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-500 text-sm">Tahun</p>
                        <p class="font-semibold text-gray-900">{{ $vehicle->year }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-500 text-sm">Kilometer</p>
                        <p class="font-semibold text-gray-900">{{ number_format($vehicle->mileage) }} km</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-500 text-sm">Transmisi</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst($vehicle->transmission) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-500 text-sm">Bahan Bakar</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst($vehicle->fuel_type) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-500 text-sm">Warna</p>
                        <p class="font-semibold text-gray-900">{{ $vehicle->color }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-500 text-sm">Kondisi</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst($vehicle->condition) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-500 text-sm">Kategori</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst($vehicle->category) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Price & Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                <!-- Title & Price -->
                <div class="mb-6">
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full mb-3
                        {{ $vehicle->category == 'mobil' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                        {{ ucfirst($vehicle->category) }}
                    </span>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $vehicle->brand }} {{ $vehicle->model }}</h1>
                    <p class="text-gray-500">{{ $vehicle->year }} • {{ number_format($vehicle->mileage) }} km</p>
                </div>

                <!-- Price -->
                <div class="bg-indigo-50 rounded-lg p-4 mb-6">
                    <p class="text-gray-600 text-sm mb-1">Harga Mulai</p>
                    <p class="text-3xl font-bold text-indigo-600">Rp {{ number_format($vehicle->starting_price, 0, ',', '.') }}</p>
                </div>

                <!-- Auction Status -->
                @if($vehicle->auction)
                    @if($vehicle->auction->status == 'active')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-gavel text-red-500 mr-2"></i>
                                <span class="font-semibold text-red-700">Lelang Sedang Berlangsung!</span>
                            </div>
                            <p class="text-sm text-red-600 mb-3">Bid tertinggi: Rp {{ number_format($vehicle->auction->current_price, 0, ',', '.') }}</p>
                            <a href="{{ route('auctions.show', $vehicle->auction->id) }}" 
                               class="block w-full text-center bg-red-500 text-white py-3 rounded-lg font-semibold hover:bg-red-600 transition">
                                <i class="fas fa-gavel mr-2"></i>Ikut Lelang Sekarang
                            </a>
                        </div>
                    @elseif($vehicle->auction->status == 'scheduled')
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-clock text-yellow-500 mr-2"></i>
                                <span class="font-semibold text-yellow-700">Lelang Dijadwalkan</span>
                            </div>
                            <p class="text-sm text-yellow-600">Mulai: {{ $vehicle->auction->start_time->format('d M Y, H:i') }}</p>
                        </div>
                    @endif
                @endif

                <!-- Actions -->
                <div class="space-y-3">
                    @auth
                        <form action="{{ route('wishlist.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                            <button type="submit" class="w-full flex items-center justify-center bg-pink-50 text-pink-600 py-3 rounded-lg font-semibold hover:bg-pink-100 transition">
                                <i class="far fa-heart mr-2"></i>Tambah ke Wishlist
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center bg-pink-50 text-pink-600 py-3 rounded-lg font-semibold hover:bg-pink-100 transition">
                            <i class="far fa-heart mr-2"></i>Tambah ke Wishlist
                        </a>
                    @endauth

                    <a href="{{ route('company.contact') }}" class="block w-full text-center bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition">
                        <i class="fas fa-phone mr-2"></i>Hubungi Kami
                    </a>
                </div>

                <!-- Seller Info -->
                @if($vehicle->user)
                    <div class="border-t mt-6 pt-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Penjual</h3>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-indigo-500 text-lg"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $vehicle->user->name }}</p>
                                <p class="text-sm text-gray-500">Member sejak {{ $vehicle->user->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Location -->
                <div class="border-t mt-6 pt-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Lokasi</h3>
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-indigo-500 mt-1 mr-3"></i>
                        <div>
                            <p class="text-gray-700">{{ $vehicle->city ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $vehicle->province->name ?? '' }}</p>
                            @if($vehicle->address)
                                <p class="text-sm text-gray-500 mt-1">{{ $vehicle->address }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Views Counter -->
                <div class="border-t mt-6 pt-6 flex items-center justify-center text-gray-500 text-sm">
                    <i class="fas fa-eye mr-2"></i>
                    <span>{{ number_format($vehicle->views ?? 0) }} kali dilihat</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
