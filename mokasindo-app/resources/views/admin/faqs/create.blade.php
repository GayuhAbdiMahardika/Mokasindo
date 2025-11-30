@extends('admin.layout')

@section('title', isset($faq) ? 'Edit FAQ' : 'Add FAQ')
@section('page-title', isset($faq) ? 'Edit FAQ' : 'Add FAQ')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ isset($faq) ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" class="bg-white rounded-lg shadow p-6">
        @csrf
        @if(isset($faq))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <input type="text" name="category" value="{{ old('category', $faq->category ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., General, Bidding, Payment" required>
                <p class="mt-1 text-xs text-gray-500">Group related questions together</p>
            </div>

            <!-- Order Number -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Number</label>
                <input type="number" name="order_number" value="{{ old('order_number', $faq->order_number ?? 0) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" min="0">
            </div>
        </div>

        <!-- Question -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Question *</label>
            <input type="text" name="question" value="{{ old('question', $faq->question ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>

        <!-- Answer -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Answer *</label>
            <textarea name="answer" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('answer', $faq->answer ?? '') }}</textarea>
        </div>

        <!-- Active Status -->
        <div class="mt-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Active (visible to users)</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-6">
            <a href="{{ route('admin.faqs.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md text-center">
                Cancel
            </a>
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                {{ isset($faq) ? 'Update' : 'Create' }} FAQ
            </button>
        </div>
    </form>
</div>
@endsection
