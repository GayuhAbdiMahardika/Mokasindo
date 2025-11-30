@extends('admin.layout')

@section('title', 'Payments & Transactions')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Payments & Transactions</h1>
        <form method="GET" action="{{ route('admin.payments.index') }}" class="flex items-center space-x-2">
            <select name="status" class="border rounded px-3 py-2">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="success">Paid</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payments as $p)
                <tr>
                    <td class="px-6 py-4">{{ $p->id }}</td>
                    <td class="px-6 py-4">{{ $p->user->name ?? '—' }}</td>
                    <td class="px-6 py-4">Rp {{ number_format($p->amount,0,',','.') }}</td>
                    <td class="px-6 py-4">{{ ucfirst($p->type) }}</td>
                    <td class="px-6 py-4">{{ ucfirst($p->status) }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.payments.show', $p) }}" class="text-blue-600 mr-2">View</a>
                        <form method="POST" action="{{ route('admin.payments.verify', $p) }}" class="inline">@csrf<button class="text-green-600 mr-2">Verify</button></form>
                        <form method="POST" action="{{ route('admin.payments.reject', $p) }}" class="inline">@csrf<button class="text-red-600 mr-2">Reject</button></form>
                        <form method="POST" action="{{ route('admin.payments.refund', $p) }}" class="inline">@csrf<button class="text-yellow-600">Refund</button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No payments found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $payments->links() }}</div>
</div>

@endsection
