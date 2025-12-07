@extends('admin.layout')

@section('title', 'Create Auction Schedule')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Create Auction Schedule</h1>
        <a href="{{ route('admin.auction-schedules.index') }}" class="text-blue-600">Back to schedules</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.auction-schedules.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded px-3 py-2" required />
                </div>

                <div>
                    <label class="text-sm text-gray-600">Location</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full border rounded px-3 py-2" required placeholder="Contoh: Jakarta Selatan" />
                </div>

                <div>
                    <label class="text-sm text-gray-600">Start Date</label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" class="w-full border rounded px-3 py-2" required />
                </div>

                <div>
                    <label class="text-sm text-gray-600">End Date</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" class="w-full border rounded px-3 py-2" required />
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600">Description</label>
                    <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" class="form-checkbox" {{ old('is_active') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-600">Active</span>
                    </label>
                </div>
            </div>

            <div class="mt-4 text-right">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Create Schedule</button>
            </div>
        </form>
    </div>
</div>

@endsection
