@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('deposits.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-6">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Deposit
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Top Up Saldo Deposit</h1>
                    <p class="text-gray-600 mb-6">Isi saldo deposit untuk mengikuti lelang</p>

                    <form method="POST" action="{{ route('deposits.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Amount Selection -->
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-3">Pilih Nominal Top Up</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                                <button type="button" onclick="selectAmount(100000)" class="amount-btn border-2 border-gray-300 rounded-lg p-4 hover:border-indigo-500 hover:bg-indigo-50 transition">
                                    <p class="text-lg font-bold text-gray-900">Rp 100.000</p>
                                </button>
                                <button type="button" onclick="selectAmount(250000)" class="amount-btn border-2 border-gray-300 rounded-lg p-4 hover:border-indigo-500 hover:bg-indigo-50 transition">
                                    <p class="text-lg font-bold text-gray-900">Rp 250.000</p>
                                </button>
                                <button type="button" onclick="selectAmount(500000)" class="amount-btn border-2 border-gray-300 rounded-lg p-4 hover:border-indigo-500 hover:bg-indigo-50 transition">
                                    <p class="text-lg font-bold text-gray-900">Rp 500.000</p>
                                </button>
                                <button type="button" onclick="selectAmount(1000000)" class="amount-btn border-2 border-gray-300 rounded-lg p-4 hover:border-indigo-500 hover:bg-indigo-50 transition">
                                    <p class="text-lg font-bold text-gray-900">Rp 1.000.000</p>
                                </button>
                                <button type="button" onclick="selectAmount(2500000)" class="amount-btn border-2 border-gray-300 rounded-lg p-4 hover:border-indigo-500 hover:bg-indigo-50 transition">
                                    <p class="text-lg font-bold text-gray-900">Rp 2.500.000</p>
                                </button>
                                <button type="button" onclick="selectAmount(5000000)" class="amount-btn border-2 border-gray-300 rounded-lg p-4 hover:border-indigo-500 hover:bg-indigo-50 transition">
                                    <p class="text-lg font-bold text-gray-900">Rp 5.000.000</p>
                                </button>
                            </div>

                            <label class="block text-gray-700 font-semibold mb-2">Atau Masukkan Nominal Lain</label>
                            <input type="number" name="amount" id="amountInput" min="50000" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-lg" 
                                   placeholder="Minimum Rp 50.000" required>
                            <p class="text-sm text-gray-500 mt-2">Minimal top up Rp 50.000</p>
                            @error('amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-3">Metode Pembayaran</label>
                            <div class="space-y-3">
                                <!-- Bank Transfer -->
                                <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 hover:bg-indigo-50">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="mr-3" checked>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">Transfer Bank</p>
                                        <p class="text-sm text-gray-600">BCA, Mandiri, BNI, BRI</p>
                                    </div>
                                    <i class="fas fa-university text-2xl text-indigo-600"></i>
                                </label>

                                <!-- E-Wallet -->
                                <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 hover:bg-indigo-50">
                                    <input type="radio" name="payment_method" value="ewallet" class="mr-3">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">E-Wallet</p>
                                        <p class="text-sm text-gray-600">GoPay, OVO, DANA, ShopeePay</p>
                                    </div>
                                    <i class="fas fa-mobile-alt text-2xl text-indigo-600"></i>
                                </label>

                                <!-- QRIS -->
                                <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 hover:bg-indigo-50">
                                    <input type="radio" name="payment_method" value="qris" class="mr-3">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">QRIS</p>
                                        <p class="text-sm text-gray-600">Scan QR Code untuk bayar</p>
                                    </div>
                                    <i class="fas fa-qrcode text-2xl text-indigo-600"></i>
                                </label>

                                <!-- Credit Card -->
                                <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 hover:bg-indigo-50">
                                    <input type="radio" name="payment_method" value="credit_card" class="mr-3">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">Kartu Kredit</p>
                                        <p class="text-sm text-gray-600">Visa, MasterCard, JCB</p>
                                    </div>
                                    <i class="fas fa-credit-card text-2xl text-indigo-600"></i>
                                </label>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Catatan (Opsional)</label>
                            <textarea name="notes" rows="3" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" 
                                      placeholder="Tambahkan catatan jika diperlukan"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            <i class="fas fa-check-circle mr-2"></i>Lanjutkan Pembayaran
                        </button>
                    </form>
                </div>
            </div>

            <!-- Info Section -->
            <div class="lg:col-span-1">
                <!-- Current Balance -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-lg p-6 mb-6 text-white">
                    <p class="text-sm opacity-90 mb-1">Saldo Deposit Saat Ini</p>
                    <h3 class="text-3xl font-bold mb-4">Rp {{ number_format(auth()->user()->deposit_balance ?? 0, 0, ',', '.') }}</h3>
                    <a href="{{ route('deposits.index') }}" class="text-sm underline hover:no-underline">
                        Lihat Riwayat →
                    </a>
                </div>

                <!-- Info Box -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                        Informasi Top Up
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Minimal top up Rp 50.000</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Saldo otomatis masuk setelah verifikasi (maks 1x24 jam)</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Deposit digunakan untuk mengikuti lelang (5% dari harga)</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Deposit dikembalikan jika tidak menang lelang</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Bisa ditarik kembali jika tidak digunakan</span>
                        </li>
                    </ul>
                </div>

                <!-- Help -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="font-bold text-blue-900 mb-2 flex items-center">
                        <i class="fas fa-question-circle mr-2"></i>
                        Butuh Bantuan?
                    </h3>
                    <p class="text-sm text-blue-800 mb-3">Hubungi customer service kami</p>
                    <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-semibold">
                        <i class="fab fa-whatsapp mr-2"></i>
                        WhatsApp Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectAmount(amount) {
    document.getElementById('amountInput').value = amount;
    
    // Update active state
    document.querySelectorAll('.amount-btn').forEach(btn => {
        btn.classList.remove('border-indigo-500', 'bg-indigo-50');
        btn.classList.add('border-gray-300');
    });
    event.target.closest('.amount-btn').classList.add('border-indigo-500', 'bg-indigo-50');
    event.target.closest('.amount-btn').classList.remove('border-gray-300');
}

// Format number input
document.getElementById('amountInput')?.addEventListener('input', function(e) {
    // Clear active amount buttons
    document.querySelectorAll('.amount-btn').forEach(btn => {
        btn.classList.remove('border-indigo-500', 'bg-indigo-50');
        btn.classList.add('border-gray-300');
    });
});
</script>
@endsection
