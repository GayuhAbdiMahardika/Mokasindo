@extends('admin.layout')

@section('title', 'Edit Plan')
@section('page-title', 'Edit Subscription Plan')

@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.subscription-plans.update', $plan) }}" method="POST" class="bg-white rounded p-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $plan->name) }}" required>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium">Price</label>
                <input type="number" step="0.01" name="price" class="w-full border rounded px-3 py-2" value="{{ old('price', $plan->price) }}">
            </div>
            <div>
                <label class="block text-sm font-medium">Duration (days)</label>
                <input type="number" name="duration_days" class="w-full border rounded px-3 py-2" value="{{ old('duration_days', $plan->duration_days) }}">
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium">Features (one per line)</label>
            <textarea name="features" rows="4" class="w-full border rounded px-3 py-2">{{ old('features', is_array($plan->features) ? implode("\n", $plan->features) : '') }}</textarea>
        </div>

        <div class="mt-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" class="form-checkbox" {{ $plan->is_active ? 'checked' : '' }}>
                <span class="ml-2">Active</span>
            </label>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.subscription-plans.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
        </div>
    </form>
</div>
@endsection
