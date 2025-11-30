@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-3">
            Upgrade jadi <span class="text-indigo-600">Member PRO</span>
        </h1>
        <p class="text-gray-600 max-w-xl mx-auto">
            Dapatkan kuota posting lebih banyak, prioritas lelang, dan fitur premium lainnya
            dengan berlangganan paket membership Mokasindo.
        </p>
    </div>

    @if($plans->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-dashed border-gray-300 p-10 text-center">
            <p class="text-gray-500">Belum ada paket membership yang tersedia. Silakan hubungi admin.</p>
        </div>
    @else
        <div class="grid md:grid-cols-{{ $plans->count() >= 3 ? 3 : 2 }} gap-6">
            @foreach($plans as $plan)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-900 mb-1">
                            {{ $plan->name }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            {{ $plan->billing_period === 'monthly' ? 'Tagihan per bulan' : 'Tagihan per tahun' }}
                        </p>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <div class="mb-4">
                            <span class="text-3xl font-extrabold text-gray-900">
                                Rp {{ number_format($plan->price, 0, ',', '.') }}
                            </span>
                            <span class="text-gray-500">
                                / {{ $plan->billing_period === 'monthly' ? 'bulan' : 'tahun' }}
                            </span>
                        </div>

                        @if($plan->description)
                            <p class="text-sm text-gray-600 mb-6">
                                {{ $plan->description }}
                            </p>
                        @endif

                        <ul class="text-sm text-gray-700 space-y-2 mb-6">
                            <li class="flex items-center">
                                <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                                Masa aktif {{ $plan->duration_days }} hari
                            </li>
                            <li class="flex items-center">
                                <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                                Kuota posting lebih banyak
                            </li>
                        </ul>

                        <div class="mt-auto">
                            @auth
                                <a href="{{ route('membership.checkout', $plan->slug) }}"
                                   class="block w-full text-center bg-indigo-600 text-white font-semibold py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                                    Pilih Paket
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="block w-full text-center bg-indigo-600 text-white font-semibold py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                                    Login untuk Berlangganan
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
