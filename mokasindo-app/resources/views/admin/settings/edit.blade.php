@extends('admin.layout')

@section('title', __('admin.settings.title'))
@section('page-title', __('admin.settings.title'))

@section('content')
<div class="bg-white shadow rounded p-6 max-w-3xl">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('admin.settings.marketplace') }}</h3>
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="deposit_percentage" class="block text-sm font-medium text-gray-700">{{ __('admin.settings.deposit_percentage') }}</label>
                <input type="number" name="deposit_percentage" id="deposit_percentage" step="0.01" min="0" max="100" value="{{ old('deposit_percentage', $depositPercentage) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <p class="text-xs text-gray-500 mt-1">{{ __('admin.settings.deposit_percentage_hint') }}</p>
            </div>

            <div>
                <label for="deposit_deadline_hours" class="block text-sm font-medium text-gray-700">{{ __('admin.settings.deposit_deadline_hours') }}</label>
                <input type="number" name="deposit_deadline_hours" id="deposit_deadline_hours" min="1" max="168" value="{{ old('deposit_deadline_hours', $depositDeadlineHours) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <p class="text-xs text-gray-500 mt-1">{{ __('admin.settings.deposit_deadline_hint') }}</p>
            </div>

            <div>
                <label for="member_monthly_price" class="block text-sm font-medium text-gray-700">{{ __('admin.settings.member_monthly_price') }}</label>
                <input type="number" name="member_monthly_price" id="member_monthly_price" min="0" step="1000" value="{{ old('member_monthly_price', $memberMonthlyPrice) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <p class="text-xs text-gray-500 mt-1">{{ __('admin.settings.member_monthly_price_hint') }}</p>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('admin.settings.save') }}
            </button>
        </div>
    </form>
</div>
@endsection
