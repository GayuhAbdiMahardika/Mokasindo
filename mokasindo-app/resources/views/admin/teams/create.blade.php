@extends('admin.layout')

@section('title', isset($team) ? 'Edit Team Member' : 'Add Team Member')
@section('page-title', isset($team) ? 'Edit Team Member' : 'Add Team Member')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ isset($team) ? route('admin.teams.update', $team) : route('admin.teams.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf
        @if(isset($team))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name', $team->name ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <!-- Position -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Position *</label>
                <input type="text" name="position" value="{{ old('position', $team->position ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $team->email ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $team->phone ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Order Number -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Number</label>
                <input type="number" name="order_number" value="{{ old('order_number', $team->order_number ?? 0) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Photo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Photo</label>
                <input type="file" name="photo" accept="image/*" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @if(isset($team) && $team->photo)
                    <img src="{{ asset('storage/' . $team->photo) }}" alt="{{ $team->name }}" class="mt-2 w-20 h-20 rounded-full object-cover">
                @endif
            </div>
        </div>

        <!-- Bio -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
            <textarea name="bio" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('bio', $team->bio ?? '') }}</textarea>
        </div>

        <!-- Social Media -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn</label>
                <input type="url" name="linkedin" value="{{ old('linkedin', $team->linkedin ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="https://linkedin.com/in/...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Twitter</label>
                <input type="url" name="twitter" value="{{ old('twitter', $team->twitter ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="https://twitter.com/...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                <input type="url" name="instagram" value="{{ old('instagram', $team->instagram ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="https://instagram.com/...">
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-6">
            <a href="{{ route('admin.teams.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md text-center">
                Cancel
            </a>
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                {{ isset($team) ? 'Update' : 'Create' }} Team Member
            </button>
        </div>
    </form>
</div>
@endsection
