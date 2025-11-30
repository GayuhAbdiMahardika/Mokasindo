@extends('admin.layout')

@section('title', 'Edit User')

@section('content')
<div>
    <h1 class="text-2xl font-bold mb-4">Edit User</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Role</label>
            @php
                $currentUserIsOwner = auth()->check() && auth()->user()->role === 'owner';
            @endphp
            <select name="role" class="mt-1 block w-full border px-3 py-2 rounded" {{ !$currentUserIsOwner && $user->role === 'owner' ? 'disabled' : '' }}>
                @if($currentUserIsOwner)
                    <option value="owner" {{ $user->role === 'owner' ? 'selected' : '' }}>Owner</option>
                @endif
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="member" {{ $user->role === 'member' ? 'selected' : '' }}>Member</option>
                <option value="anggota" {{ $user->role === 'anggota' ? 'selected' : '' }}>Anggota</option>
            </select>
            @if(!$currentUserIsOwner && $user->role === 'owner')
                <p class="text-xs text-red-600 mt-2">Only owner can edit the owner role.</p>
                <input type="hidden" name="role" value="{{ $user->role }}">
            @endif
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="mr-2"> Active
            </label>
        </div>

        <div class="flex items-center space-x-3">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
            <a href="{{ route('admin.users.index') }}" class="text-gray-600">Cancel</a>
        </div>
    </form>
</div>
@endsection
