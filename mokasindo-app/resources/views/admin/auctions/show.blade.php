@extends('admin.layout')

@section('title', 'Auction Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Auction #{{ $auction->id }}</h1>
        <div>
            <form method="POST" action="{{ route('admin.auctions.force-end', $auction) }}" class="inline">@csrf<button class="px-3 py-2 bg-red-600 text-white rounded">Force End</button></form>
            <form method="POST" action="{{ route('admin.auctions.reopen', $auction) }}" class="inline">@csrf<button class="px-3 py-2 bg-green-600 text-white rounded">Reopen</button></form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-bold">Vehicle</h3>
        <p>{{ $auction->vehicle->brand ?? '—' }} {{ $auction->vehicle->model ?? '' }} ({{ $auction->vehicle->year ?? '' }})</p>
        <p class="text-xs text-gray-500">Owner: {{ $auction->vehicle->user->name ?? '—' }}</p>

        <hr class="my-4">

        <h3 class="font-bold">Recent Bids</h3>
        <div class="mt-2">
            @if($bids->count())
                <table class="min-w-full">
                    <thead><tr><th>User</th><th>Amount</th><th>Time</th></tr></thead>
                    <tbody>
                        @foreach($bids as $bid)
                        <tr>
                            <td class="py-2">{{ $bid->user->name ?? '—' }}</td>
                            <td class="py-2">Rp {{ number_format($bid->bid_amount,0,',','.') }}</td>
                            <td class="py-2 text-xs text-gray-500">{{ $bid->created_at->format('d M Y H:i:s') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500">No bids yet.</p>
            @endif
        </div>
    </div>
</div>

@endsection
