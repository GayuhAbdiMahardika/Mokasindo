@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-6">Manajemen Subscription Member</h1>

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if ($subscriptions->isEmpty())
        <p class="text-gray-600">Belum ada data subscription.</p>
    @else
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">User</th>
                        <th class="px-4 py-2 text-left">Plan</th>
                        <th class="px-4 py-2 text-left">Harga</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Masa Aktif</th>
                        <th class="px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subscriptions as $sub)
                        <tr class="border-b last:border-b-0">
                            <td class="px-4 py-2 align-top">
                                #{{ $sub->id }}
                            </td>
                            <td class="px-4 py-2 align-top">
                                @if ($sub->user)
                                    <div class="font-semibold">{{ $sub->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $sub->user->email }}</div>
                                @else
                                    <span class="text-xs text-gray-400 italic">User tidak ditemukan</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 align-top">
                                @if ($sub->plan)
                                    <div class="font-semibold">{{ $sub->plan->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $sub->plan->billing_period }}</div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Plan hilang</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 align-top">
                                @if ($sub->plan)
                                    Rp {{ number_format($sub->plan->price, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-2 align-top">
                                @php
                                    $status = $sub->status;
                                    $badgeClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        'active' => 'bg-green-100 text-green-800 border-green-300',
                                        'expired' => 'bg-gray-100 text-gray-700 border-gray-300',
                                    ][$status] ?? 'bg-gray-100 text-gray-700 border-gray-300';
                                @endphp

                                <span class="inline-block px-2 py-1 text-xs rounded border {{ $badgeClasses }}">
                                    {{ strtoupper($status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 align-top text-xs text-gray-600">
                                @if ($sub->starts_at)
                                    <div>Mulai: {{ $sub->starts_at->format('d M Y') }}</div>
                                @endif
                                @if ($sub->expires_at)
                                    <div>Habis: {{ $sub->expires_at->format('d M Y') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-2 align-top">
                                @if ($sub->status === 'pending')
                                    <form method="POST" action="{{ route('admin.subscriptions.approve', $sub) }}">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1 text-xs font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                            Approve & Aktifkan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400">Tidak ada aksi</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $subscriptions->links() }}
        </div>
    @endif
</div>
@endsection