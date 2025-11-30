@extends('admin.layout')

@section('title', 'Subscription Detail')
@section('page-title', 'Subscription Detail')

@section('content')
<div class="bg-white rounded p-6">
    <h3 class="text-lg font-medium">Subscription for {{ $subscription->user->name ?? ('User #' . $subscription->user_id) }}</h3>
    <p class="text-sm text-gray-600">Plan: {{ $subscription->plan->name ?? '-' }}</p>
    <p class="mt-2">Status: <strong>{{ $subscription->status }}</strong></p>
    <p>Start: {{ optional($subscription->start_date)->toDateTimeString() }}</p>
    <p>End: {{ optional($subscription->end_date)->toDateTimeString() }}</p>
    <p>Paid: Rp {{ number_format($subscription->price_paid ?? 0, 2) }}</p>

    <div class="mt-4 flex gap-3">
        @if($subscription->status === 'pending')
            <form action="{{ route('admin.user-subscriptions.approve', $subscription) }}" method="POST">
                @csrf
                <button class="px-4 py-2 bg-green-600 text-white rounded">Approve</button>
            </form>
        @endif

        <form action="{{ route('admin.user-subscriptions.cancel', $subscription) }}" method="POST">
            @csrf
            <button class="px-4 py-2 bg-yellow-600 text-white rounded">Cancel</button>
        </form>

        <form action="{{ route('admin.user-subscriptions.force-cancel', $subscription) }}" method="POST">
            @csrf
            <button class="px-4 py-2 bg-red-600 text-white rounded">Force Cancel</button>
        </form>
    </div>
</div>

@endsection
