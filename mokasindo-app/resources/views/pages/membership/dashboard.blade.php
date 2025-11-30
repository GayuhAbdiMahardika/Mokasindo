@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Profil Membership</h2>

                <p class="text-sm text-gray-500 mb-1">Nama</p>
                <p class="font-semibold text-gray-900 mb-3">{{ $user->name }}</p>

                <p class="text-sm text-gray-500 mb-1">Status</p>
                <p class="mb-3">
                    @if($user->isActiveMember())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            PRO / Member Aktif
                        </span>
                    @elseif($user->isAnggota())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                            Anggota Biasa
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            {{ ucfirst($user->role) }}
                        </span>
                    @endif
                </p>

                <p class="text-sm text-gray-500 mb-1">Berlaku sampai</p>
                <p class="text-sm text-gray-800 mb-4">
                    @if($user->membership_expires_at)
                        {{ $user->membership_expires_at->format('d M Y') }}
                    @else
                        -
                    @endif
                </p>

                <a href="{{ route('membership.pricing') }}"
                   class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
                    Lihat paket lain
                    <span class="ml-1">&rarr;</span>
                </a>
            </div>
        </div>

        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Paket Aktif</h2>

                @if($activeSubscription)
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <p class="text-sm text-gray-500">Paket</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ $activeSubscription->plan->name ?? '-' }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Aktif: {{ $activeSubscription->starts_at->format('d M Y') }} &mdash;
                                {{ $activeSubscription->expires_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="text-sm text-gray-500">
                            <span class="block">Status: 
                                <span class="font-semibold text-green-600">
                                    {{ ucfirst($activeSubscription->status) }}
                                </span>
                            </span>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">
                        Kamu belum memiliki paket aktif. Yuk upgrade ke Member PRO untuk menikmati fitur premium.
                    </p>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Langganan</h2>

                @if($subscriptions->isEmpty())
                    <p class="text-sm text-gray-500">Belum ada riwayat langganan.</p>
                @else
                    <div class="overflow-x-auto -mx-4 md:mx-0">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 text-left text-gray-500">
                                    <th class="py-2 px-4">Paket</th>
                                    <th class="py-2 px-4">Mulai</th>
                                    <th class="py-2 px-4">Berakhir</th>
                                    <th class="py-2 px-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptions as $sub)
                                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                                        <td class="py-2 px-4 text-gray-900">
                                            {{ $sub->plan->name ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 text-gray-700">
                                            {{ $sub->starts_at->format('d M Y') }}
                                        </td>
                                        <td class="py-2 px-4 text-gray-700">
                                            {{ $sub->expires_at->format('d M Y') }}
                                        </td>
                                        <td class="py-2 px-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($sub->status === 'active')
                                                    bg-green-100 text-green-700
                                                @elseif($sub->status === 'expired')
                                                    bg-gray-100 text-gray-600
                                                @else
                                                    bg-yellow-100 text-yellow-700
                                                @endif
                                            ">
                                                {{ ucfirst($sub->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
