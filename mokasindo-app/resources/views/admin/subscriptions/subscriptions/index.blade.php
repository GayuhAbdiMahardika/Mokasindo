@extends('admin.layout')

@section('title', 'User Subscriptions')
@section('page-title', 'User Subscriptions')

@section('content')
<div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">{{ __('admin.dashboard.user') }}</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">{{ __('admin.dashboard.role') }}</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">{{ __('admin.dashboard.status') }}</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">{{ __('admin.dashboard.period') }}</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">{{ __('admin.dashboard.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                @php
                    $subscription = $user->latestSubscription;
                    $isActive = $subscription && $subscription->status === 'active' && $subscription->end_date && $subscription->end_date->isFuture();
                    $statusLabel = $subscription ? ucfirst($subscription->status) : 'Tidak ada';
                    $statusClass = 'bg-gray-100 text-gray-700';
                    if ($isActive) {
                        $statusLabel = 'Active';
                        $statusClass = 'bg-green-100 text-green-700';
                    } elseif ($subscription && $subscription->status === 'expired') {
                        $statusClass = 'bg-yellow-100 text-yellow-700';
                    } elseif ($subscription && $subscription->status === 'cancelled') {
                        $statusClass = 'bg-red-100 text-red-700';
                    }
                @endphp
                <tr class="border-t">
                    <td class="px-4 py-2 text-sm text-gray-800">
                        <div class="font-medium">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ __('roles.' . $user->role) }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-800">
                        @if($subscription)
                            {{ optional($subscription->start_date)->toDateString() }} - {{ optional($subscription->end_date)->toDateString() }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-2 text-sm">
                        @if($subscription)
                            <a href="{{ route('admin.user-subscriptions.show', $subscription) }}" class="text-blue-600 mr-2">{{ __('admin.dashboard.view') }}</a>
                            @if($subscription->status === 'pending')
                                <form action="{{ route('admin.user-subscriptions.approve', $subscription) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button class="text-green-600">{{ __('admin.dashboard.approve') }}</button>
                                </form>
                            @endif
                            @if($subscription->status !== 'cancelled')
                                <form action="{{ route('admin.user-subscriptions.cancel', $subscription) }}" method="POST" class="inline-block ml-2">
                                    @csrf
                                    <button class="text-red-600">{{ __('admin.dashboard.cancel') }}</button>
                                </form>
                            @endif
                        @else
                            <span class="text-gray-500 text-xs">{{ __('admin.dashboard.no_subscription') }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="p-4">{{ $users->links() }}</div>
</div>

@endsection
