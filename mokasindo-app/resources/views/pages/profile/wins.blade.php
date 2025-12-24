@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        
        <div class="md:col-span-1">
            @include('pages.profile.sidebar', ['active' => 'wins'])
        </div>

        <div class="md:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900">🏆 Lelang yang Dimenangkan</h2>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($wonAuctions as $auction)
                        <div class="p-6 flex flex-col sm:flex-row gap-5 hover:bg-gray-50 transition">
                            <div class="w-full sm:w-32 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                @if($auction->vehicle && $auction->vehicle->primaryImage)
                                    <img src="{{ asset('storage/' . $auction->vehicle->primaryImage->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-bold text-gray-900">
                                            {{ $auction->vehicle->brand ?? 'Unknown' }} {{ $auction->vehicle->model ?? '' }}
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ $auction->vehicle->year ?? '' }}</p>
                                    </div>
                                    @if($auction->payment_completed)
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">
                                            <i class="fas fa-check-circle mr-1"></i> Lunas
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-700">
                                            <i class="fas fa-clock mr-1"></i> Menunggu Pembayaran
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mt-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Harga Final</p>
                                        <p class="text-green-600 font-bold">Rp {{ number_format($auction->final_price, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Tanggal Menang</p>
                                        <p class="text-gray-900 font-bold">{{ $auction->won_at ? $auction->won_at->format('d M Y, H:i') : '-' }}</p>
                                    </div>
                                </div>

                                @if(!$auction->payment_completed)
                                    <div class="mt-4 flex items-center justify-between bg-orange-50 border border-orange-200 rounded-lg p-3">
                                        <div>
                                            <p class="text-sm text-orange-800 font-semibold">Batas Waktu Pembayaran:</p>
                                            <p class="text-sm text-orange-700">
                                                {{ $auction->payment_deadline ? $auction->payment_deadline->format('d M Y, H:i') : 'Segera' }}
                                                @if($auction->payment_deadline && $auction->payment_deadline->isFuture())
                                                    <span class="text-xs">({{ $auction->payment_deadline->diffForHumans() }})</span>
                                                @elseif($auction->payment_deadline && $auction->payment_deadline->isPast())
                                                    <span class="text-xs text-red-600 font-bold">(LEWAT BATAS WAKTU)</span>
                                                @endif
                                            </p>
                                        </div>
                                        <a href="{{ route('payments.show', $auction->id) }}" 
                                           class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded-md transition">
                                            <i class="fas fa-credit-card mr-1"></i> Bayar Sekarang
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-4 flex items-center bg-green-50 border border-green-200 rounded-lg p-3">
                                        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-green-800 font-semibold">Pembayaran Selesai</p>
                                            <p class="text-xs text-green-700">
                                                Dibayar: {{ $auction->payment_completed_at ? $auction->payment_completed_at->format('d M Y, H:i') : '-' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center">
                            <i class="fas fa-trophy text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 mb-2">Anda belum memenangkan lelang apapun.</p>
                            <a href="{{ route('auctions.index') }}" class="text-indigo-600 font-bold hover:underline text-sm mt-2 inline-block">
                                Ikuti Lelang Sekarang &rarr;
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
