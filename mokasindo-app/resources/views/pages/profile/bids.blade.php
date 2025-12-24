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
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $isWinning ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                        {{ $isWinning ? 'Memimpin' : 'Tertinggal' }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mt-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Tawaran Anda</p>
                                        <p class="text-indigo-600 font-bold">Rp {{ number_format($bid->bid_amount, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Posisi Saat Ini</p>
                                        <p class="text-gray-900 font-bold">Rp {{ number_format($bid->auction->current_price ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>
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