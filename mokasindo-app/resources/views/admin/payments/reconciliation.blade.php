@extends('admin.layout')

@section('page-title', 'Reconciliation Notes')
@section('title', 'Reconciliation')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Reconciliation Notes</h1>
    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari kode/email/nama" class="border rounded px-2 py-1">
        <select name="status" class="border rounded px-2 py-1">
            <option value="">Semua Status</option>
            <option value="pending" @if(($filters['status'] ?? '')=='pending') selected @endif>Pending</option>
            <option value="success" @if(($filters['status'] ?? '')=='success') selected @endif>Success</option>
            <option value="failed" @if(($filters['status'] ?? '')=='failed') selected @endif>Failed</option>
            <option value="refunded" @if(($filters['status'] ?? '')=='refunded') selected @endif>Refunded</option>
        </select>
        <button class="bg-blue-600 text-white px-3 py-1 rounded">Filter</button>
    </form>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="px-2 py-1 border">Tanggal</th>
                    <th class="px-2 py-1 border">Kode</th>
                    <th class="px-2 py-1 border">User</th>
                    <th class="px-2 py-1 border">Plan</th>
                    <th class="px-2 py-1 border">Status</th>
                    <th class="px-2 py-1 border">Nominal</th>
                    <th class="px-2 py-1 border">Catatan Rekonsiliasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td class="border px-2 py-1 text-xs">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                        <td class="border px-2 py-1 text-xs">{{ $payment->payment_code }}</td>
                        <td class="border px-2 py-1 text-xs">
                            {{ $payment->user->name ?? '-' }}<br>
                            <span class="text-gray-500 text-xs">{{ $payment->user->email ?? '' }}</span>
                        </td>
                        <td class="border px-2 py-1 text-xs">{{ $payment->payable->plan->name ?? '-' }}</td>
                        <td class="border px-2 py-1 text-xs">{{ ucfirst($payment->status) }}</td>
                        <td class="border px-2 py-1 text-xs">Rp{{ number_format($payment->amount,0,',','.') }}</td>
                        <td class="border px-2 py-1 text-xs">
                            <form method="POST" action="{{ route('admin.payments.reconciliation.note', $payment) }}">
                                @csrf
                                <input type="text" name="note" value="{{ $payment->notes }}" class="border rounded px-2 py-1 w-40">
                                <button class="bg-green-600 text-white px-2 py-1 rounded ml-1 text-xs">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $payments->links() }}</div>
@endsection
