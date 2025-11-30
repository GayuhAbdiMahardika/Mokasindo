@extends('layouts.app')

@section('title', $auction->vehicle->brand . ' ' . $auction->vehicle->model . ' - Lelang')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Vehicle Details -->
        <div class="lg:col-span-2">
            <!-- Images -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                @if($auction->vehicle->images->isNotEmpty())
                    <div class="relative">
                        <img id="mainImage" 
                             src="{{ asset('storage/' . $auction->vehicle->images->first()->image_path) }}" 
                             alt="{{ $auction->vehicle->brand }}"
                             class="w-full h-96 object-cover">
                        
                        <!-- Image thumbnails -->
                        <div class="flex gap-2 p-4 overflow-x-auto">
                            @foreach($auction->vehicle->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="Thumbnail"
                                     class="w-20 h-20 object-cover rounded cursor-pointer hover:opacity-75 transition"
                                     onclick="document.getElementById('mainImage').src = this.src">
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="h-96 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">Tidak ada gambar</span>
                    </div>
                @endif
            </div>

            <!-- Vehicle Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Informasi Kendaraan</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="border-b pb-3">
                        <span class="text-sm text-gray-600">Merek</span>
                        <p class="font-semibold text-gray-900">{{ $auction->vehicle->brand }}</p>
                    </div>
                    <div class="border-b pb-3">
                        <span class="text-sm text-gray-600">Model</span>
                        <p class="font-semibold text-gray-900">{{ $auction->vehicle->model }}</p>
                    </div>
                    <div class="border-b pb-3">
                        <span class="text-sm text-gray-600">Tahun</span>
                        <p class="font-semibold text-gray-900">{{ $auction->vehicle->year }}</p>
                    </div>
                    <div class="border-b pb-3">
                        <span class="text-sm text-gray-600">Warna</span>
                        <p class="font-semibold text-gray-900">{{ $auction->vehicle->color ?? 'N/A' }}</p>
                    </div>
                    <div class="border-b pb-3">
                        <span class="text-sm text-gray-600">Transmisi</span>
                        <p class="font-semibold text-gray-900">{{ ucfirst($auction->vehicle->transmission ?? 'N/A') }}</p>
                    </div>
                    <div class="border-b pb-3">
                        <span class="text-sm text-gray-600">Bahan Bakar</span>
                        <p class="font-semibold text-gray-900">{{ ucfirst($auction->vehicle->fuel_type ?? 'N/A') }}</p>
                    </div>
                    <div class="border-b pb-3">
                        <span class="text-sm text-gray-600">Kilometer</span>
                        <p class="font-semibold text-gray-900">{{ number_format($auction->vehicle->mileage ?? 0) }} km</p>
                    </div>
                    <div class="border-b pb-3">
                        <span class="text-sm text-gray-600">Kapasitas Mesin</span>
                        <p class="font-semibold text-gray-900">{{ $auction->vehicle->engine_capacity ?? 'N/A' }} cc</p>
                    </div>
                    <div class="border-b pb-3">
                        <span class="text-sm text-gray-600">Plat Nomor</span>
                        <p class="font-semibold text-gray-900">{{ $auction->vehicle->license_plate ?? 'N/A' }}</p>
                    </div>
                    <div class="border-b pb-3">
                        <span class="text-sm text-gray-600">Kondisi</span>
                        <p class="font-semibold text-gray-900">{{ ucfirst($auction->vehicle->condition ?? 'Bekas') }}</p>
                    </div>
                </div>

                <!-- Location -->
                <div class="mt-4 pt-4 border-t">
                    <span class="text-sm text-gray-600">Lokasi</span>
                    <p class="font-semibold text-gray-900">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        {{ $auction->vehicle->district->name ?? '' }}, 
                        {{ $auction->vehicle->city->name ?? '' }}, 
                        {{ $auction->vehicle->province->name ?? '' }}
                        {{ $auction->vehicle->postal_code ? '(' . $auction->vehicle->postal_code . ')' : '' }}
                    </p>
                </div>

                <!-- Description -->
                <div class="mt-4 pt-4 border-t">
                    <span class="text-sm text-gray-600">Deskripsi</span>
                    <p class="mt-2 text-gray-900 whitespace-pre-line">{{ $auction->vehicle->description }}</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Bidding Section -->
        <div class="lg:col-span-1">
            <!-- Auction Status -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 sticky top-4">
                <!-- Timer -->
                <div class="text-center mb-6">
                    <span class="text-sm text-gray-600">Waktu Tersisa</span>
                    <div id="countdown" class="text-4xl font-bold text-red-600 my-2" data-end="{{ $auction->end_time->toIso8601String() }}">
                        Loading...
                    </div>
                    <span class="text-xs text-gray-500">Berakhir: {{ $auction->end_time->format('d M Y, H:i') }}</span>
                </div>

                <!-- Current Price -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <span class="text-sm text-gray-600">Harga Saat Ini</span>
                    <p id="currentPrice" class="text-3xl font-bold text-blue-600 my-1">
                        Rp {{ number_format($auction->current_price, 0, ',', '.') }}
                    </p>
                    <div class="flex justify-between text-sm text-gray-600 mt-2">
                        <span><i class="fas fa-gavel mr-1"></i> <span id="bidCount">{{ $auction->bid_count }}</span> bid</span>
                        <span><i class="fas fa-users mr-1"></i> {{ $auction->bids->unique('user_id')->count() }} peserta</span>
                    </div>
                </div>

                <!-- User Status -->
                @auth
                    @if($isWinning)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <p class="text-green-800 font-semibold flex items-center">
                                <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                                Anda Sedang Menang!
                            </p>
                            <p class="text-sm text-green-700 mt-1">Bid Anda: Rp {{ number_format($userHighestBid->amount, 0, ',', '.') }}</p>
                        </div>
                    @elseif($userHighestBid)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <p class="text-yellow-800 font-semibold">Anda Ter-outbid!</p>
                            <p class="text-sm text-yellow-700 mt-1">Bid Anda: Rp {{ number_format($userHighestBid->amount, 0, ',', '.') }}</p>
                        </div>
                    @endif

                    <!-- Deposit Warning -->
                    @if(!$hasDeposit)
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                            <p class="text-orange-800 font-semibold mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Deposit Diperlukan
                            </p>
                            <p class="text-sm text-orange-700 mb-3">
                                Bayar deposit 5% (Rp {{ number_format($auction->deposit_amount, 0, ',', '.') }}) untuk ikut bid.
                            </p>
                            <a href="{{ route('deposits.show', $auction->id) }}" 
                               class="block w-full bg-orange-600 hover:bg-orange-700 text-white text-center font-medium py-2 px-4 rounded-md transition duration-150">
                                Bayar Deposit
                            </a>
                        </div>
                    @else
                        <!-- Bid Form -->
                        <form id="bidForm" class="mb-4">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Masukkan Bid Anda (Min: Rp {{ number_format($nextMinBid, 0, ',', '.') }})
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                <input type="text" 
                                       id="bidAmount" 
                                       name="amount"
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="{{ number_format($nextMinBid, 0, ',', '.') }}"
                                       required>
                            </div>
                            <button type="submit" 
                                    id="bidButton"
                                    class="mt-3 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md transition duration-150 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                <i class="fas fa-gavel mr-2"></i> Pasang Bid
                            </button>
                        </form>

                        <!-- Quick Bid Buttons -->
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <button onclick="quickBid({{ $nextMinBid }})" 
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-3 rounded text-sm transition">
                                +Rp {{ number_format(100000, 0, ',', '.') }}
                            </button>
                            <button onclick="quickBid({{ $nextMinBid + 500000 }})" 
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-3 rounded text-sm transition">
                                +Rp {{ number_format(500000, 0, ',', '.') }}
                            </button>
                        </div>
                    @endif
                @else
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-gray-700 mb-3 text-center">Silakan login untuk ikut lelang</p>
                        <a href="{{ route('login') }}" 
                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-medium py-2 px-4 rounded-md transition duration-150">
                            Login
                        </a>
                    </div>
                @endauth

                <!-- Bid History -->
                <div class="mt-6 pt-6 border-t">
                    <h3 class="font-semibold text-gray-900 mb-3">Riwayat Bid</h3>
                    <div id="bidHistory" class="space-y-2 max-h-64 overflow-y-auto">
                        @forelse($auction->bids->take(10) as $bid)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">
                                    {{ substr($bid->user->name, 0, 3) }}***
                                </span>
                                <span class="font-semibold text-gray-900">
                                    Rp {{ number_format($bid->amount, 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">Belum ada bid</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let countdownInterval;
let updateInterval;

// Format number with thousand separator
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Parse formatted number
function parseNumber(str) {
    return parseInt(str.replace(/\./g, ''));
}

// Countdown timer
function updateCountdown() {
    const element = document.getElementById('countdown');
    const endTime = new Date(element.dataset.end);
    const now = new Date();
    const diff = endTime - now;

    if (diff <= 0) {
        element.textContent = 'Lelang Berakhir';
        element.classList.remove('text-red-600');
        element.classList.add('text-gray-600');
        clearInterval(countdownInterval);
        clearInterval(updateInterval);
        document.getElementById('bidButton')?.setAttribute('disabled', 'true');
        return;
    }

    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

    if (days > 0) {
        element.textContent = `${days}d ${hours}h ${minutes}m`;
    } else if (hours > 0) {
        element.textContent = `${hours}h ${minutes}m ${seconds}s`;
    } else {
        element.textContent = `${minutes}m ${seconds}s`;
        element.classList.add('animate-pulse');
    }
}

// Format input as currency
document.getElementById('bidAmount')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\./g, '');
    if (value) {
        e.target.value = formatNumber(value);
    }
});

// Quick bid
function quickBid(amount) {
    document.getElementById('bidAmount').value = formatNumber(amount);
}

// Submit bid
document.getElementById('bidForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const button = document.getElementById('bidButton');
    const amountInput = document.getElementById('bidAmount');
    const amount = parseNumber(amountInput.value);

    // Disable button
    button.setAttribute('disabled', 'true');
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

    try {
        const response = await fetch('{{ route("auctions.bid", $auction->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ amount: amount })
        });

        const data = await response.json();

        if (response.ok) {
            // Success
            alert('Bid berhasil! Anda sekarang bid tertinggi.');
            updateAuctionData();
            amountInput.value = '';
        } else {
            // Error
            alert(data.error || 'Gagal memasang bid. Silakan coba lagi.');
        }
    } catch (error) {
        alert('Terjadi kesalahan. Silakan coba lagi.');
        console.error(error);
    } finally {
        // Re-enable button
        button.removeAttribute('disabled');
        button.innerHTML = '<i class="fas fa-gavel mr-2"></i> Pasang Bid';
    }
});

// Update auction data (polling)
async function updateAuctionData() {
    try {
        const response = await fetch('{{ route("auctions.data", $auction->id) }}');
        const data = await response.json();

        // Update current price
        document.getElementById('currentPrice').textContent = 'Rp ' + formatNumber(data.current_price);
        
        // Update bid count
        document.getElementById('bidCount').textContent = data.bid_count;

        // Update bid history
        const historyDiv = document.getElementById('bidHistory');
        if (data.bids && data.bids.length > 0) {
            historyDiv.innerHTML = data.bids.map(bid => `
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">${bid.user_name}</span>
                    <span class="font-semibold text-gray-900">Rp ${formatNumber(bid.amount)}</span>
                </div>
            `).join('');
        }

        // Check if auction ended
        if (data.status === 'ended') {
            clearInterval(countdownInterval);
            clearInterval(updateInterval);
            document.getElementById('bidButton')?.setAttribute('disabled', 'true');
            location.reload();
        }
    } catch (error) {
        console.error('Failed to update auction data:', error);
    }
}

// Start intervals
countdownInterval = setInterval(updateCountdown, 1000);
updateInterval = setInterval(updateAuctionData, 3000); // Update every 3 seconds

// Initial calls
updateCountdown();
updateAuctionData();
</script>
@endpush
@endsection
