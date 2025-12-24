@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        
        <div class="md:col-span-1">
            @include('pages.profile.sidebar', ['active' => 'bids'])
        </div>

        <div class="md:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900">Lelang yang Diikuti</h2>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($bids as $bid)
                        @php
                            // Hitung status real-time: apakah bid ini adalah bid tertinggi di lelang?
                            $isWinning = $bid->auction && $bid->bid_amount >= ($bid->auction->current_price ?? 0);
                            // Atau cek apakah bid ini sama dengan bid tertinggi di auction
                            $highestBid = $bid->auction ? $bid->auction->bids()->max('bid_amount') : 0;
                            $isWinning = $bid->bid_amount >= $highestBid && $highestBid > 0;
                        @endphp
                        <div class="p-6 flex flex-col sm:flex-row gap-5 hover:bg-gray-50 transition">
                            <div class="w-full sm:w-32 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                @if($bid->auction && $bid->auction->vehicle && $bid->auction->vehicle->primaryImage)
                                    <img src="{{ asset('storage/' . $bid->auction->vehicle->primaryImage->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-bold text-gray-900">
                                        {{ $bid->auction->vehicle->brand ?? 'Unknown' }} {{ $bid->auction->vehicle->model ?? '' }}
                                    </h3>
                                    @if($bid->auction->status == 'ended' && $bid->auction->winner_id == Auth::id())
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">
                                            🏆 Pemenang
                                        </span>
                                    @elseif($bid->auction->status == 'active')
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $bid->status == 'winning' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $bid->status == 'winning' ? 'Memimpin' : 'Tertinggal' }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600">
                                            {{ ucfirst($bid->auction->status) }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mt-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Tawaran Anda</p>
                                        <p class="text-indigo-600 font-bold">Rp {{ number_format($bid->bid_amount, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">
                                            @if($bid->auction->status == 'ended')
                                                Harga Final
                                            @else
                                                Posisi Saat Ini
                                            @endif
                                        </p>
                                        <p class="text-gray-900 font-bold">Rp {{ number_format($bid->auction->final_price ?? $bid->auction->current_price ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                {{-- Show payment button if user won and hasn't paid --}}
                                @if($bid->auction->status == 'ended' && $bid->auction->winner_id == Auth::id())
                                    @php
                                        $payment = \App\Models\Payment::where('auction_id', $bid->auction->id)
                                            ->where('user_id', Auth::id())
                                            ->first();
                                    @endphp
                                    
                                    <div class="mt-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg">
                                        @if($payment && $payment->status == 'paid')
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-bold text-green-700">✅ Pembayaran Lunas</p>
                                                    <p class="text-xs text-green-600 mt-1">Terima kasih, pembayaran Anda sudah diterima</p>
                                                </div>
                                                <a href="{{ route('payments.invoice', $payment->id) }}" class="text-sm text-green-700 hover:underline font-medium">
                                                    Lihat Invoice
                                                </a>
                                            </div>
                                        @elseif($payment && $payment->status == 'verifying')
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-bold text-orange-700">⏳ Menunggu Verifikasi</p>
                                                    <p class="text-xs text-orange-600 mt-1">Bukti pembayaran sedang diverifikasi admin</p>
                                                </div>
                                                <a href="{{ route('payments.invoice', $payment->id) }}" class="text-sm text-orange-700 hover:underline font-medium">
                                                    Lihat Status
                                                </a>
                                            </div>
                                        @elseif($payment && $payment->status == 'pending')
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-bold text-red-700">⚠️ Menunggu Pembayaran</p>
                                                    <p class="text-xs text-red-600 mt-1">Segera selesaikan pembayaran sebelum deadline</p>
                                                </div>
                                                <a href="{{ route('payments.invoice', $payment->id) }}" class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 transition">
                                                    Bayar Sekarang
                                                </a>
                                            </div>
                                        @elseif($payment && $payment->status == 'expired')
                                            <div>
                                                <p class="text-sm font-bold text-red-700">❌ Pembayaran Kadaluarsa</p>
                                                <p class="text-xs text-red-600 mt-1">Deposit Anda hangus. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                                            </div>
                                        @else
                                            {{-- No payment record, show payment button --}}
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-bold text-green-700">🎉 Selamat! Anda Memenangkan Lelang</p>
                                                    <p class="text-xs text-green-600 mt-1">Silakan lakukan pembayaran sisa untuk menyelesaikan transaksi</p>
                                                </div>
                                                <a href="{{ route('payments.show', $bid->auction->id) }}" class="px-4 py-2 bg-green-600 text-white text-sm font-bold rounded-lg hover:bg-green-700 transition">
                                                    Bayar Sekarang
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center">
                            <p class="text-gray-500">Anda belum mengikuti lelang apapun.</p>
                            <a href="{{ route('etalase.index') }}" class="text-indigo-600 font-bold hover:underline text-sm mt-2 inline-block">Cari Mobil Lelang &rarr;</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection