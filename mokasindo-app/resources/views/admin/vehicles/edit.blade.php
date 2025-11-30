@extends('admin.layout')

@section('title', 'Edit Vehicle')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Edit Listing</h1>
        <a href="{{ route('admin.vehicles.index') }}" class="text-blue-600">Back to list</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.vehicles.update', $vehicle) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Brand</label>
                    <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <label class="text-sm text-gray-600">Model</label>
                    <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <label class="text-sm text-gray-600">Year</label>
                    <input type="text" name="year" value="{{ old('year', $vehicle->year) }}" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <label class="text-sm text-gray-600">Starting Price</label>
                    <input type="number" name="starting_price" value="{{ old('starting_price', $vehicle->starting_price) }}" class="w-full border rounded px-3 py-2" required />
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600">Status</label>
                    <select name="status" class="w-full border rounded px-3 py-2">
                        <option value="draft" {{ $vehicle->status=='draft'?'selected':'' }}>Draft</option>
                        <option value="pending" {{ $vehicle->status=='pending'?'selected':'' }}>Pending</option>
                        <option value="approved" {{ $vehicle->status=='approved'?'selected':'' }}>Approved</option>
                        <option value="rejected" {{ $vehicle->status=='rejected'?'selected':'' }}>Rejected</option>
                        <option value="sold" {{ $vehicle->status=='sold'?'selected':'' }}>Sold</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600">Rejection Reason (if any)</label>
                    <textarea name="rejection_reason" class="w-full border rounded px-3 py-2">{{ old('rejection_reason', $vehicle->rejection_reason) }}</textarea>
                </div>
            </div>

            <div class="mt-4 text-right">
                <button class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection
