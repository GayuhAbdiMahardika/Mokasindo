@extends('admin.layout')

@section('title', 'Payment Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Payment #{{ $payment->id }}</h1>
        <div>
            <a href="{{ route('admin.payments.invoice', $payment) }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Download Invoice</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p><strong>User:</strong> {{ $payment->user->name ?? '—' }}</p>
        <p><strong>Amount:</strong> Rp {{ number_format($payment->amount,0,',','.') }}</p>
        <p><strong>Type:</strong> {{ ucfirst($payment->type) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
        <p class="text-xs text-gray-500 mt-3">Created at: {{ $payment->created_at->format('d M Y H:i') }}</p>
    </div>
</div>

@endsection
