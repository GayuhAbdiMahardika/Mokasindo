@extends('layouts.app')

@section('title', 'Pembayaran - Mokasindo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Congratulations Banner -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-8 mb-8 text-white text-center">
            <i class="fas fa-trophy text-6xl mb-4 text-yellow-300"></i>
            <h1 class="text-3xl font-bold mb-2">Selamat! Anda Pemenang Lelang</h1>
            <p class="text-green-100">{{ $auction->vehicle->brand }} {{ $auction->vehicle->model }}</p>
        </div>

        <!-- Payment Deadline Warning -->
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-clock text-orange-600 text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold text-orange-900">Selesaikan Pembayaran Sebelum:</p>
                    <p class="text-xl font-bold text-orange-600">{{ $paymentDeadline->format('d M Y, H:i') }}</p>
                    <p class="text-sm text-orange-800 mt-1">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Jika melewati batas waktu, deposit Anda akan <strong>hangus</strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- Vehicle Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Kendaraan yang Anda Menangkan</h2>
            <div class="flex gap-4">
                @if($auction->vehicle->primaryImage)
                    <img src="{{ asset('storage/' . $auction->vehicle->primaryImage->image_path) }}" 
                         alt="{{ $auction->vehicle->brand }}"
                         class="w-40 h-40 object-cover rounded-lg">
                @endif
                <div class="flex-1">
                    <h3 class="font-bold text-xl mb-2">{{ $auction->vehicle->brand }} {{ $auction->vehicle->model }}</h3>
                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                        <div><i class="fas fa-calendar mr-1"></i> {{ $auction->vehicle->year }}</div>
                        <div><i class="fas fa-tachometer-alt mr-1"></i> {{ number_format($auction->vehicle->mileage ?? 0) }} km</div>
                        <div><i class="fas fa-cog mr-1"></i> {{ ucfirst($auction->vehicle->transmission ?? 'N/A') }}</div>
                        <div><i class="fas fa-gas-pump mr-1"></i> {{ ucfirst($auction->vehicle->fuel_type ?? 'N/A') }}</div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        {{ $auction->vehicle->city->name ?? '' }}, {{ $auction->vehicle->province->name ?? '' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Rincian Pembayaran</h2>
            <div class="space-y-3">
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">Harga Akhir Lelang:</span>
                    <span class="font-semibold text-gray-900">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">Deposit Terbayar:</span>
                    <span class="font-semibold text-green-600">- Rp {{ number_format($depositPaid, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 border-t">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-semibold text-gray-900">Rp {{ number_format($finalPrice - $depositPaid, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">
                        Biaya Platform (2.5%):
                        <i class="fas fa-info-circle text-gray-400 ml-1" title="Biaya layanan platform"></i>
                    </span>
                    <span class="font-semibold text-gray-900">+ Rp {{ number_format($platformFee, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-3 border-t-2 border-gray-300">
                    <span class="text-lg font-bold text-gray-900">Total Pembayaran:</span>
                    <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <form method="POST" action="{{ route('payments.pay', $auction->id) }}" class="bg-white rounded-lg shadow-md p-6">
            @csrf

            <h2 class="text-xl font-semibold text-gray-900 mb-4">Pilih Metode Pembayaran</h2>

            <div class="space-y-3 mb-6">
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

                <!-- Credit Card -->
                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                    <input type="radio" 
                           name="payment_method" 
                           value="credit_card" 
                           class="text-blue-600 focus:ring-blue-500"
                           required>
                    <div class="ml-3 flex-1">
                        <p class="font-medium text-gray-900">Kartu Kredit</p>
                        <p class="text-sm text-gray-600">Visa, Mastercard, JCB</p>
                    </div>
                    <i class="fas fa-credit-card text-2xl text-gray-400"></i>
                </label>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Penting
                </h3>
                <ul class="space-y-1 text-sm text-blue-800">
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                        <span>Selesaikan pembayaran sebelum {{ $paymentDeadline->format('d M Y, H:i') }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                        <span>Deposit akan dipotong dari total pembayaran</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-orange-600 mr-2 mt-0.5"></i>
                        <span>Jika melewati batas waktu, deposit akan hangus dan kendaraan akan dilelang ulang</span>
                    </li>
                </ul>
            </div>

            <!-- Terms -->
            <div class="mb-6">
                <label class="flex items-start">
                    <input type="checkbox" 
                           name="agree_terms" 
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 mt-1"
                           required>
                    <span class="ml-2 text-sm text-gray-700">
                        Saya mengerti dan menyetujui syarat dan ketentuan transaksi Mokasindo
                    </span>
                </label>
            </div>

            <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-md transition duration-150">
                <i class="fas fa-credit-card mr-2"></i>Lanjut ke Pembayaran
            </button>
        </form>
    </div>
</div>
@endsection
