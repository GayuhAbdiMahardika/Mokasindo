@extends('admin.layout')

@section('title', 'Subscription Plans')
@section('page-title', 'Subscription Plans')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.subscription-plans.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">New Plan</a>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Price</th>
                <th class="px-4 py-2 text-left">Duration (days)</th>
                <th class="px-4 py-2 text-left">Active</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plans as $plan)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $plan->name }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($plan->price, 2) }}</td>
                    <td class="px-4 py-2">{{ $plan->duration_days }}</td>
                    <td class="px-4 py-2">{{ $plan->is_active ? 'Yes' : 'No' }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.subscription-plans.edit', $plan) }}" class="text-blue-600 mr-2">Edit</a>
                        <form action="{{ route('admin.subscription-plans.destroy', $plan) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete plan?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
