@extends('admin.layout')

@section('title', 'Edit Auction Schedule')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Edit Auction Schedule</h1>
        <a href="{{ route('admin.auction-schedules.index') }}" class="text-blue-600">Back to schedules</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="list-disc list-inside text-red-600 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-600 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.auction-schedules.update', $schedule) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Title</label>
                    <input type="text" name="title" value="{{ old('title', $schedule->title) }}" class="w-full border rounded px-3 py-2" required />
                </div>

                <div>
                    <label class="text-sm text-gray-600">Location</label>
                    <input type="text" name="location" value="{{ old('location', $schedule->location) }}" class="w-full border rounded px-3 py-2" required placeholder="Contoh: Jakarta Selatan" />
                </div>

                <div>
                    <label class="text-sm text-gray-600">Start Date</label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date', $schedule->start_date->format('Y-m-d\TH:i')) }}" class="w-full border rounded px-3 py-2" required />
                </div>

                <div>
                    <label class="text-sm text-gray-600">End Date</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date', $schedule->end_date->format('Y-m-d\TH:i')) }}" class="w-full border rounded px-3 py-2" required />
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600">Description</label>
                    <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description', $schedule->description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" class="form-checkbox" {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-600">Active</span>
                    </label>
                </div>
            </div>

            <div class="mt-4 text-right">
                <button class="px-4 py-2 bg-green-600 text-white rounded">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@endsection
