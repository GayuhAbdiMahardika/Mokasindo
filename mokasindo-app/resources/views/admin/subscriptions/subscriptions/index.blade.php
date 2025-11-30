@extends('admin.layout')

@section('title', 'User Subscriptions')
@section('page-title', 'User Subscriptions')

@section('content')
<div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left">User</th>
                <th class="px-4 py-2 text-left">Plan</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Period</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $s)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $s->user->name ?? 'User #' . $s->user_id }}</td>
                    <td class="px-4 py-2">{{ $s->plan->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ ucfirst($s->status) }}</td>
                    <td class="px-4 py-2">{{ optional($s->start_date)->toDateString() }} - {{ optional($s->end_date)->toDateString() }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.user-subscriptions.show', $s) }}" class="text-blue-600 mr-2">View</a>
                        @if($s->status === 'pending')
                            <form action="{{ route('admin.user-subscriptions.approve', $s) }}" method="POST" class="inline-block">
                                @csrf
                                <button class="text-green-600">Approve</button>
                            </form>
                        @endif
                        @if($s->status !== 'cancelled')
                            <form action="{{ route('admin.user-subscriptions.cancel', $s) }}" method="POST" class="inline-block ml-2">
                                @csrf
                                <button class="text-red-600">Cancel</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.user-subscriptions.force-cancel', $s) }}" method="POST" class="inline-block ml-2">
                            @csrf
                            <button class="text-red-800">Force Cancel</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="p-4">{{ $subscriptions->links() }}</div>
</div>

@endsection
