@extends('layouts.app')

@section('title', 'Instruksi Pembayaran Deposit - Mokasindo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Status Badge -->
        <div class="mb-6">
            @if($deposit->status === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-yellow-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-yellow-900">Menunggu Pembayaran</p>
                            <p class="text-sm text-yellow-800">Silakan selesaikan pembayaran sebelum: {{ $deposit->expired_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            @elseif($deposit->status === 'verifying')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-hourglass-half text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-blue-900">Sedang Diverifikasi</p>
                            <p class="text-sm text-blue-800">Bukti pembayaran Anda sedang diverifikasi oleh tim kami</p>
                        </div>
                    </div>
                </div>
            @elseif($deposit->status === 'paid')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-green-900">Pembayaran Berhasil</p>
                            <p class="text-sm text-green-800">Deposit Anda telah terkonfirmasi. Anda dapat ikut bid sekarang!</p>
                        </div>
                    </div>
                </div>
            @elseif($deposit->status === 'expired')
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle text-red-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-red-900">Pembayaran Kedaluwarsa</p>
                            <p class="text-sm text-red-800">Waktu pembayaran telah habis. Silakan buat deposit baru.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Detail Pesanan</h2>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">No. Pesanan:</span>
                    <span class="font-semibold text-gray-900">{{ $deposit->order_number }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Kendaraan:</span>
                    <span class="font-semibold text-gray-900">{{ $deposit->auction->vehicle->brand }} {{ $deposit->auction->vehicle->model }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Metode Pembayaran:</span>
                    <span class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $deposit->payment_method)) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Jumlah:</span>
                    <span class="font-bold text-2xl text-blue-600">Rp {{ number_format($deposit->amount, 0, ',', '.') }}</span>
                </div>
                @if($deposit->status === 'pending')
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">Batas Waktu:</span>
                        <span class="font-semibold text-red-600">{{ $deposit->expired_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>

        @if($deposit->status === 'pending')
            <!-- Payment Instructions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Instruksi Pembayaran</h2>
                
                @if($deposit->payment_method === 'bank_transfer')
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="font-semibold text-gray-900 mb-2">Transfer ke salah satu rekening berikut:</p>
                            
                            <!-- BCA -->
                            <div class="border-b border-gray-200 py-3">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium text-gray-900">Bank BCA</span>
                                    <span class="text-sm text-gray-600">a.n. PT Mokasindo</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-blue-600">1234567890</span>
                                    <button onclick="copyText('1234567890')" class="text-sm text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-copy mr-1"></i>Salin
                                    </button>
                                </div>
                            </div>

                            <!-- Mandiri -->
                            <div class="border-b border-gray-200 py-3">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium text-gray-900">Bank Mandiri</span>
                                    <span class="text-sm text-gray-600">a.n. PT Mokasindo</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-blue-600">9876543210</span>
                                    <button onclick="copyText('9876543210')" class="text-sm text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-copy mr-1"></i>Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                            <li>Transfer sejumlah <strong>Rp {{ number_format($deposit->amount, 0, ',', '.') }}</strong></li>
                            <li>Simpan bukti transfer</li>
                            <li>Upload bukti transfer di form di bawah</li>
                            <li>Tunggu verifikasi dari admin (maksimal 1x24 jam)</li>
                        </ol>
                    </div>
                @elseif($deposit->payment_method === 'e_wallet')
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="font-semibold text-gray-900 mb-2">Scan QR Code untuk pembayaran</p>
                            <div class="inline-block bg-white p-4 rounded-lg">
                                <!-- Placeholder QR Code -->
                                <div class="w-48 h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-qrcode text-6xl text-gray-400"></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Gunakan aplikasi GoPay, OVO, Dana, atau ShopeePay</p>
                        </div>
                    </div>
                @elseif($deposit->payment_method === 'qris')
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="font-semibold text-gray-900 mb-2">Scan QRIS</p>
                            <div class="inline-block bg-white p-4 rounded-lg">
                                <!-- Placeholder QR Code -->
                                <div class="w-48 h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-qrcode text-6xl text-gray-400"></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Scan dengan aplikasi bank atau e-wallet Anda</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Upload Proof Form -->
            <form method="POST" action="{{ route('deposits.confirm', $deposit->id) }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
                @csrf

                <h2 class="text-xl font-semibold text-gray-900 mb-4">Upload Bukti Pembayaran</h2>

                <!-- Payment Proof -->
                <div class="mb-4">
                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Transfer <span class="text-red-500">*</span>
                    </label>
                    <input type="file" 
                           id="payment_proof" 
                           name="payment_proof" 
                           accept="image/*"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('payment_proof') border-red-500 @enderror"
                           required>
                    @error('payment_proof')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG (max 2MB)</p>
                </div>

                <!-- Account Name -->
                <div class="mb-4">
                    <label for="account_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Pengirim <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="account_name" 
                           name="account_name" 
                           value="{{ old('account_name') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('account_name') border-red-500 @enderror"
                           placeholder="Nama sesuai rekening"
                           required>
                    @error('account_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Number -->
                <div class="mb-6">
                    <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Rekening/HP <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="account_number" 
                           name="account_number" 
                           value="{{ old('account_number') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('account_number') border-red-500 @enderror"
                           placeholder="Nomor rekening pengirim"
                           required>
                    @error('account_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition duration-150">
                    <i class="fas fa-upload mr-2"></i>Upload Bukti Pembayaran
                </button>
            </form>
        @elseif($deposit->status === 'paid')
            <!-- Success Actions -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <i class="fas fa-check-circle text-green-600 text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Deposit Berhasil!</h2>
                <p class="text-gray-600 mb-6">Anda sekarang dapat memasang bid pada lelang ini</p>
                <a href="{{ route('auctions.show', $deposit->auction_id) }}" 
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-md transition duration-150">
                    Ikut Lelang Sekarang
                </a>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-6 text-center">
            <a href="{{ route('auctions.show', $deposit->auction_id) }}" class="text-blue-600 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Lelang
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyText(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Nomor rekening berhasil disalin!');
    });
}
</script>
@endpush
@endsection
