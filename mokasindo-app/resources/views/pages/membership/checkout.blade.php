@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="mb-6">
        <a href="{{ route('membership.pricing') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600">
            <span class="mr-1">&larr;</span> Kembali ke daftar paket
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">
            Checkout Paket: {{ $plan->name }}
        </h1>

        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">Harga Paket</p>
                <p class="text-2xl font-extrabold text-gray-900">
                    Rp {{ number_format($plan->price, 0, ',', '.') }}
                    <span class="text-base font-medium text-gray-500">
                        / {{ $plan->billing_period === 'monthly' ? 'bulan' : 'tahun' }}
                    </span>
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Durasi aktif: {{ $plan->duration_days }} hari
                </p>
            </div>

            <div class="bg-indigo-50 rounded-xl p-4">
                <p class="text-sm font-semibold text-indigo-700 mb-2">Keuntungan Member PRO</p>
                <ul class="text-sm text-indigo-900 space-y-1">
                    <li>• Kuota posting lebih banyak</li>
                    <li>• Badge Member PRO di profil</li>
                    <li>• Prioritas dalam beberapa fitur lelang (bisa dikembangkan)</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-6 mt-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Metode Pembayaran</h2>
            <p class="text-sm text-gray-500 mb-4">
                Untuk saat ini, proses pembayaran masih mode demo. Klik tombol di bawah untuk
                mengaktifkan paket seolah-olah pembayaran berhasil.
            </p>

            <form method="POST" action="{{ route('membership.process', $plan->slug) }}">
                @csrf

                <button type="submit"
                    class="w-full md:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                    Aktifkan Paket (Mode Demo)
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
