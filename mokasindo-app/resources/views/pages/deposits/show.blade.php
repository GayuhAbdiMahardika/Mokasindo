@extends('layouts.app')

@section('title', 'Bayar Deposit - Mokasindo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Bayar Deposit</h1>
            <p class="text-gray-600">Bayar deposit untuk ikut lelang kendaraan ini</p>
        </div>

        <!-- Vehicle Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Lelang</h2>
            <div class="flex gap-4">
                @if($auction->vehicle->primaryImage)
                    <img src="{{ asset('storage/' . $auction->vehicle->primaryImage->image_path) }}" 
                         alt="{{ $auction->vehicle->brand }}"
                         class="w-32 h-32 object-cover rounded-lg">
                @endif
                <div class="flex-1">
                    <h3 class="font-bold text-lg">{{ $auction->vehicle->brand }} {{ $auction->vehicle->model }}</h3>
                    <p class="text-gray-600">{{ $auction->vehicle->year }}</p>
                    <p class="text-gray-600">{{ $auction->vehicle->city ?? '' }}</p>
                    <div class="mt-2 pt-2 border-t">
                        <p class="text-sm text-gray-600">Harga Awal:</p>
                        <p class="font-bold text-lg text-blue-600">Rp {{ number_format($auction->starting_price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deposit Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-blue-900 mb-4">
                <i class="fas fa-info-circle mr-2"></i>Tentang Deposit
            </h2>
            <ul class="space-y-2 text-blue-800 text-sm">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 mr-2 mt-0.5"></i>
                    <span>Deposit sebesar 5% dari harga awal wajib dibayar sebelum Anda dapat memasang bid</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 mr-2 mt-0.5"></i>
                    <span>Deposit akan dikembalikan jika Anda tidak memenangkan lelang</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 mr-2 mt-0.5"></i>
                    <span>Jika Anda menang, deposit akan dipotong dari total pembayaran</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-orange-600 mr-2 mt-0.5"></i>
                    <span>Deposit akan hangus jika Anda tidak menyelesaikan pembayaran setelah memenangkan lelang</span>
                </li>
            </ul>
        </div>

        <!-- Payment Form -->
        <form method="POST" action="{{ route('deposits.pay', $auction->id) }}" class="bg-white rounded-lg shadow-md p-6">
            @csrf

            <!-- Amount -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700 font-medium">Jumlah Deposit:</span>
                    <span class="text-2xl font-bold text-blue-600">
                        Rp {{ number_format($auction->deposit_amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Metode Pembayaran <span class="text-red-500">*</span>
                </label>
                <div class="space-y-3">
                    <!-- Bank Transfer -->
                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" 
                               name="payment_method" 
                               value="bank_transfer" 
                               class="text-blue-600 focus:ring-blue-500"
                               required
                               checked>
                        <div class="ml-3 flex-1">
                            <p class="font-medium text-gray-900">Transfer Bank</p>
                            <p class="text-sm text-gray-600">BCA, Mandiri, BNI, BRI</p>
                        </div>
                        <i class="fas fa-university text-2xl text-gray-400"></i>
                    </label>

                    <!-- E-Wallet -->
                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" 
                               name="payment_method" 
                               value="e_wallet" 
                               class="text-blue-600 focus:ring-blue-500"
                               required>
                        <div class="ml-3 flex-1">
                            <p class="font-medium text-gray-900">E-Wallet</p>
                            <p class="text-sm text-gray-600">GoPay, OVO, Dana, ShopeePay</p>
                        </div>
                        <i class="fas fa-wallet text-2xl text-gray-400"></i>
                    </label>

                    <!-- QRIS -->
                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" 
                               name="payment_method" 
                               value="qris" 
                               class="text-blue-600 focus:ring-blue-500"
                               required>
                        <div class="ml-3 flex-1">
                            <p class="font-medium text-gray-900">QRIS</p>
                            <p class="text-sm text-gray-600">Scan QR untuk pembayaran instant</p>
                        </div>
                        <i class="fas fa-qrcode text-2xl text-gray-400"></i>
                    </label>
                </div>
                @error('payment_method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Terms -->
            <div class="mb-6">
                <label class="flex items-start">
                    <input type="checkbox" 
                           name="agree_terms" 
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 mt-1"
                           required>
                    <span class="ml-2 text-sm text-gray-700">
                        Saya mengerti dan menyetujui kebijakan deposit lelang Mokasindo
                    </span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('auctions.show', $auction->id) }}" 
                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-4 rounded-md text-center transition duration-150">
                    Kembali
                </a>
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition duration-150">
                    Lanjut Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
