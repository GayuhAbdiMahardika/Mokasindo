@extends('admin.layout')

@section('title', 'Auctions')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Auctions</h1>
        <form method="GET" action="{{ route('admin.auctions.index') }}" class="flex items-center space-x-2">
            <select name="status" class="border rounded px-3 py-2">
                <option value="">All Status</option>
                <option value="scheduled">Scheduled</option>
                <option value="active">Active</option>
                <option value="ended">Ended</option>
            </select>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Schedule</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($auctions as $a)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $a->id }}</td>
                    <td class="px-6 py-4 text-sm">
                        {{ $a->vehicle->brand ?? '—' }} {{ $a->vehicle->model ?? '' }}<br>
                        <span class="text-xs text-gray-500">by {{ $a->vehicle->user->name ?? '—' }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ optional($a->start_time)->format('d M Y H:i') }} — {{ optional($a->end_time)->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm">{{ ucfirst($a->status) }}</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.auctions.show', $a) }}" class="text-blue-600 mr-3">View</a>
                        <form method="POST" action="{{ route('admin.auctions.force-end', $a) }}" class="inline">
                            @csrf
                            <button class="text-red-600 mr-2">Force End</button>
                        </form>
                        <form method="POST" action="{{ route('admin.auctions.reopen', $a) }}" class="inline">
                            @csrf
                            <button class="text-green-600">Reopen</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No auctions found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $auctions->links() }}</div>
</div>

@endsection
