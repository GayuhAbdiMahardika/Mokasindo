@extends('admin.layout')

@section('title', 'Create Plan')
@section('page-title', 'Create Subscription Plan')

@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.subscription-plans.store') }}" method="POST" class="bg-white rounded p-6">
        @csrf
        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium">Price</label>
                <input type="number" step="0.01" name="price" class="w-full border rounded px-3 py-2" value="0">
            </div>
            <div>
                <label class="block text-sm font-medium">Duration (days)</label>
                <input type="number" name="duration_days" class="w-full border rounded px-3 py-2" value="30">
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium">Features (one per line)</label>
            <textarea name="features" rows="4" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="mt-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" class="form-checkbox">
                <span class="ml-2">Active</span>
            </label>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.subscription-plans.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
        </div>
    </form>
</div>
@endsection
