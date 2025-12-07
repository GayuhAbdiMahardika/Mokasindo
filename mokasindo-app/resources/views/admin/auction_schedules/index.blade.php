@extends('admin.layout')

@section('title', __('admin.auction_schedules.title'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ __('admin.auction_schedules.heading') }}</h1>
        <a href="{{ route('admin.auction-schedules.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">{{ __('admin.auction_schedules.new') }}</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.auction_schedules.table.title') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.auction_schedules.table.location') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.auction_schedules.table.start') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.auction_schedules.table.end') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.auction_schedules.table.action') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($schedules as $s)
                <tr>
                    <td class="px-6 py-4">{{ $s->title }}</td>
                    <td class="px-6 py-4">{{ $s->location }}</td>
                    <td class="px-6 py-4">{{ $s->start_date->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4">{{ $s->end_date->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.auction-schedules.edit', $s) }}" class="text-blue-600 mr-2">{{ __('admin.auction_schedules.action.edit') }}</a>
                        <form method="POST" action="{{ route('admin.auction-schedules.destroy', $s) }}" class="inline">@csrf @method('DELETE')<button class="text-red-600">{{ __('admin.auction_schedules.action.delete') }}</button></form>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">{{ __('admin.auction_schedules.empty') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $schedules->links() }}</div>
</div>

@endsection
