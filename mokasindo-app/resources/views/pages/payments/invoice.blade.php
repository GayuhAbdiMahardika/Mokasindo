@extends('layouts.app')

@section('title', 'Invoice Pembayaran - Mokasindo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Status Badge -->
        <div class="mb-6">
            @if($payment->status === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-yellow-600 text-2xl mr-3"></i>
                            <div>
                                <p class="font-semibold text-yellow-900">Menunggu Pembayaran</p>
                                <p class="text-sm text-yellow-800">Batas waktu: {{ $payment->expired_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-yellow-700">Sisa waktu:</p>
                            <p id="countdown" class="text-lg font-bold text-yellow-900" data-end="{{ $payment->expired_at->toIso8601String() }}">
                                Loading...
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($payment->status === 'verifying')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-hourglass-half text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-blue-900">Sedang Diverifikasi</p>
                            <p class="text-sm text-blue-800">Bukti pembayaran Anda sedang diverifikasi oleh tim kami (maksimal 1x24 jam)</p>
                        </div>
                    </div>
                </div>
            @elseif($payment->status === 'paid')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-green-900">Pembayaran Berhasil</p>
                            <p class="text-sm text-green-800">Terima kasih! Pembayaran Anda telah dikonfirmasi.</p>
                        </div>
                    </div>
                </div>
            @elseif($payment->status === 'expired')
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle text-red-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-red-900">Pembayaran Kedaluwarsa</p>
                            <p class="text-sm text-red-800">Waktu pembayaran telah habis. Deposit Anda telah hangus.</p>
                        </div>
                    </div>
                </div>
            @elseif($payment->status === 'rejected')
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-ban text-red-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-red-900">Pembayaran Ditolak</p>
                            <p class="text-sm text-red-800">{{ $payment->rejection_reason ?? 'Silakan upload bukti pembayaran yang valid.' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Invoice -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <!-- Invoice Header -->
            <div class="bg-blue-600 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">INVOICE</h1>
                        <p class="text-blue-100">No: {{ $payment->order_number }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-blue-100">Tanggal:</p>
                        <p class="font-semibold">{{ $payment->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Invoice Body -->
            <div class="p-6">
                <!-- Buyer Info -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Pembeli:</h3>
                        <p class="text-gray-700">{{ $payment->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $payment->user->email }}</p>
                        <p class="text-sm text-gray-600">{{ $payment->user->phone }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Penjual:</h3>
                        <p class="text-gray-700">{{ $payment->auction->vehicle->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $payment->auction->vehicle->user->email }}</p>
                    </div>
                </div>

                <!-- Vehicle Info -->
                <div class="border-t border-b py-4 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Detail Kendaraan:</h3>
                    <div class="flex gap-4">
                        @if($payment->auction->vehicle->primaryImage)
                            <img src="{{ asset('storage/' . $payment->auction->vehicle->primaryImage->image_path) }}" 
                                 alt="{{ $payment->auction->vehicle->brand }}"
                                 class="w-32 h-32 object-cover rounded-lg">
                        @endif
                        <div>
                            <p class="font-bold text-lg">{{ $payment->auction->vehicle->brand }} {{ $payment->auction->vehicle->model }}</p>
                            <div class="grid grid-cols-2 gap-2 mt-2 text-sm text-gray-600">
                                <div><i class="fas fa-calendar mr-1"></i> {{ $payment->auction->vehicle->year }}</div>
                                <div><i class="fas fa-tachometer-alt mr-1"></i> {{ number_format($payment->auction->vehicle->mileage ?? 0) }} km</div>
                                <div><i class="fas fa-cog mr-1"></i> {{ ucfirst($payment->auction->vehicle->transmission ?? 'N/A') }}</div>
                                <div><i class="fas fa-palette mr-1"></i> {{ $payment->auction->vehicle->color ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">Harga Kendaraan:</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($payment->vehicle_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">Deposit Terbayar:</span>
                        <span class="font-semibold text-green-600">- Rp {{ number_format($payment->deposit_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">Biaya Platform (2.5%):</span>
                        <span class="font-semibold text-gray-900">+ Rp {{ number_format($payment->platform_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-t-2 border-gray-300">
                        <span class="text-lg font-bold text-gray-900">Total:</span>
                        <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-2 text-sm">
                        <span class="text-gray-600">Metode Pembayaran:</span>
                        <span class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($payment->status === 'pending' || $payment->status === 'rejected')
            <!-- Payment Instructions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Instruksi Pembayaran</h2>
                
                @if($payment->payment_method === 'bank_transfer')
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="font-semibold text-gray-900 mb-3">Transfer ke salah satu rekening berikut:</p>
                            
                            <div class="space-y-3">
                                <!-- BCA -->
                                <div class="border rounded-lg p-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-bold text-gray-900">Bank BCA</span>
                                        <span class="text-sm text-gray-600">a.n. PT Mokasindo</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-bold text-blue-600">1234567890</span>
                                        <button onclick="copyText('1234567890')" class="text-sm text-blue-600 hover:text-blue-700">
                                            <i class="fas fa-copy mr-1"></i>Salin
                                        </button>
                                    </div>
                                </div>

                                <!-- Mandiri -->
                                <div class="border rounded-lg p-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-bold text-gray-900">Bank Mandiri</span>
                                        <span class="text-sm text-gray-600">a.n. PT Mokasindo</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-bold text-blue-600">9876543210</span>
                                        <button onclick="copyText('9876543210')" class="text-sm text-blue-600 hover:text-blue-700">
                                            <i class="fas fa-copy mr-1"></i>Salin
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Upload Proof Form -->
                <form method="POST" action="{{ route('payments.confirm', $payment->id) }}" enctype="multipart/form-data" class="mt-6">
                    @csrf

                    <h3 class="font-semibold text-gray-900 mb-4">Upload Bukti Pembayaran</h3>

                    <div class="space-y-4">
                        <!-- Payment Proof -->
                        <div>
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                Bukti Transfer <span class="text-red-500">*</span>
                            </label>
                            <input type="file" 
                                   id="payment_proof" 
                                   name="payment_proof" 
                                   accept="image/*"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG (max 2MB)</p>
                        </div>

                        <!-- Account Name -->
                        <div>
                            <label for="account_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Pengirim <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="account_name" 
                                   name="account_name" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Nama sesuai rekening"
                                   required>
                        </div>

                        <!-- Account Number -->
                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Rekening/HP <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="account_number" 
                                   name="account_number" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Nomor rekening pengirim"
                                   required>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition duration-150">
                            <i class="fas fa-upload mr-2"></i>Upload Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        @elseif($payment->status === 'paid')
            <!-- Success Actions -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <i class="fas fa-check-circle text-green-600 text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Transaksi Selesai!</h2>
                <p class="text-gray-600 mb-2">Terima kasih atas pembayaran Anda</p>
                <p class="text-sm text-gray-500 mb-6">Kami akan menghubungi Anda untuk proses pengiriman kendaraan</p>
                
                <div class="flex gap-4 justify-center">
                    <button onclick="window.print()" 
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-md transition duration-150">
                        <i class="fas fa-print mr-2"></i>Cetak Invoice
                    </button>
                    <a href="/" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-150">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function copyText(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Nomor rekening berhasil disalin!');
    });
}

// Countdown timer
const countdownElement = document.getElementById('countdown');
if (countdownElement) {
    function updateCountdown() {
        const endTime = new Date(countdownElement.dataset.end);
        const now = new Date();
        const diff = endTime - now;

        if (diff <= 0) {
            countdownElement.textContent = 'Kedaluwarsa';
            location.reload();
            return;
        }

        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        countdownElement.textContent = `${hours}j ${minutes}m ${seconds}s`;
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();
}
</script>
@endpush
@endsection
