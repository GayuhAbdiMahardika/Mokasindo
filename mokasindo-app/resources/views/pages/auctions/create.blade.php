@extends('layouts.app')

@section('title', 'Buat Lelang - Mokasindo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Buat Lelang Baru</h1>
            <p class="text-gray-600">Buat lelang untuk kendaraan: {{ $vehicle->brand }} {{ $vehicle->model }}</p>
        </div>

        <!-- Vehicle Preview -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Preview Kendaraan</h2>
            <div class="flex gap-4">
                @if($vehicle->primaryImage)
                    <img src="{{ asset('storage/' . $vehicle->primaryImage->image_path) }}" 
                         alt="{{ $vehicle->brand }}"
                         class="w-32 h-32 object-cover rounded-lg">
                @endif
                <div>
                    <h3 class="font-bold text-lg">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                    <p class="text-gray-600">{{ $vehicle->year }} • {{ number_format($vehicle->mileage ?? 0) }} km</p>
                    <p class="text-gray-600">{{ $vehicle->city->name ?? '' }}</p>
                    <p class="text-sm text-gray-500 mt-2">Harga Awal: Rp {{ number_format($vehicle->starting_price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('auctions.store') }}" class="bg-white rounded-lg shadow-md p-6">
            @csrf
            <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

            <!-- Start Time -->
            <div class="mb-6">
                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                    Waktu Mulai Lelang <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" 
                       id="start_time" 
                       name="start_time" 
                       value="{{ old('start_time', now()->addHours(1)->format('Y-m-d\TH:i')) }}"
                       min="{{ now()->format('Y-m-d\TH:i') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('start_time') border-red-500 @enderror"
                       required>
                @error('start_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Lelang akan mulai pada waktu yang Anda tentukan</p>
            </div>

            <!-- Duration -->
            <div class="mb-6">
                <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">
                    Durasi Lelang (Hari) <span class="text-red-500">*</span>
                </label>
                <select id="duration_days" 
                        name="duration_days" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('duration_days') border-red-500 @enderror"
                        required>
                    @for($i = $minDuration; $i <= $maxDuration; $i++)
                        <option value="{{ $i }}" {{ old('duration_days', 3) == $i ? 'selected' : '' }}>
                            {{ $i }} Hari
                        </option>
                    @endfor
                </select>
                @error('duration_days')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Minimum {{ $minDuration }} hari, maksimum {{ $maxDuration }} hari</p>
            </div>

            <!-- Starting Price -->
            <div class="mb-6">
                <label for="starting_price" class="block text-sm font-medium text-gray-700 mb-2">
                    Harga Awal <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                    <input type="text" 
                           id="starting_price" 
                           name="starting_price" 
                           value="{{ old('starting_price', number_format($vehicle->starting_price, 0, ',', '.')) }}"
                           class="w-full pl-10 pr-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('starting_price') border-red-500 @enderror"
                           required>
                </div>
                @error('starting_price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Harga awal lelang (minimum Rp 1.000.000)</p>
            </div>

            <!-- Reserve Price (Optional) -->
            <div class="mb-6">
                <label for="reserve_price" class="block text-sm font-medium text-gray-700 mb-2">
                    Harga Minimum (Reserve Price) <span class="text-gray-500 text-xs">(Opsional)</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                    <input type="text" 
                           id="reserve_price" 
                           name="reserve_price" 
                           value="{{ old('reserve_price') }}"
                           class="w-full pl-10 pr-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('reserve_price') border-red-500 @enderror">
                </div>
                @error('reserve_price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">
                    Jika diisi, kendaraan tidak akan terjual jika bid tertinggi di bawah harga ini. Kosongkan jika tidak diperlukan.
                </p>
            </div>

            <!-- Deposit Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Deposit
                </h3>
                <p class="text-sm text-blue-800 mb-2">
                    Peserta lelang akan diminta membayar deposit sebesar <strong>{{ $depositPercentage }}%</strong> dari harga awal sebelum dapat memasang bid.
                </p>
                <p class="text-sm text-blue-800">
                    Deposit: <strong>Rp <span id="depositAmount">{{ number_format($vehicle->starting_price * $depositPercentage / 100, 0, ',', '.') }}</span></strong>
                </p>
            </div>

            <!-- Terms Agreement -->
            <div class="mb-6">
                <label class="flex items-start">
                    <input type="checkbox" 
                           name="agree_terms" 
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 mt-1"
                           required>
                    <span class="ml-2 text-sm text-gray-700">
                        Saya setuju dengan <a href="#" class="text-blue-600 hover:underline">syarat dan ketentuan</a> lelang Mokasindo
                    </span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('my.ads') }}" 
                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-4 rounded-md text-center transition duration-150">
                    Batal
                </a>
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition duration-150">
                    Buat Lelang
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Format number with thousand separator
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Parse formatted number
function parseNumber(str) {
    return parseInt(str.replace(/\./g, ''));
}

// Format price inputs
['starting_price', 'reserve_price'].forEach(id => {
    const input = document.getElementById(id);
    if (input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\./g, '');
            if (value) {
                e.target.value = formatNumber(value);
            }
        });
    }
});

// Update deposit amount when starting price changes
document.getElementById('starting_price').addEventListener('input', function() {
    const startingPrice = parseNumber(this.value || '0');
    const depositPercentage = {{ $depositPercentage }};
    const depositAmount = Math.floor(startingPrice * depositPercentage / 100);
    document.getElementById('depositAmount').textContent = formatNumber(depositAmount);
});

// Form submission - convert formatted numbers back to raw numbers
document.querySelector('form').addEventListener('submit', function(e) {
    const startingPrice = document.getElementById('starting_price');
    const reservePrice = document.getElementById('reserve_price');
    
    // Create hidden inputs with raw values
    const hiddenStarting = document.createElement('input');
    hiddenStarting.type = 'hidden';
    hiddenStarting.name = 'starting_price';
    hiddenStarting.value = parseNumber(startingPrice.value);
    
    // Disable original input
    startingPrice.disabled = true;
    
    // Add hidden input
    this.appendChild(hiddenStarting);
    
    // Same for reserve price if filled
    if (reservePrice.value) {
        const hiddenReserve = document.createElement('input');
        hiddenReserve.type = 'hidden';
        hiddenReserve.name = 'reserve_price';
        hiddenReserve.value = parseNumber(reservePrice.value);
        reservePrice.disabled = true;
        this.appendChild(hiddenReserve);
    }
});
</script>
@endpush
@endsection
