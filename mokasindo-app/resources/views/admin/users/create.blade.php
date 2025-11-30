@extends('admin.layout')

@section('title', 'Create Admin User')

@section('content')
<div>
    <h1 class="text-2xl font-bold mb-4">Create Admin User</h1>

    <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white p-6 rounded shadow max-w-md">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" class="mt-1 block w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" class="mt-1 block w-full border px-3 py-2 rounded" required>
        </div>

        <p class="text-sm text-gray-500 mb-4">Users created here will automatically be assigned the <strong>admin</strong> role.</p>

        <div class="flex items-center space-x-3">
            <button class="px-4 py-2 bg-green-600 text-white rounded">Create Admin</button>
            <a href="{{ route('admin.users.index') }}" class="text-gray-600">Cancel</a>
        </div>
    </form>
</div>
@endsection
