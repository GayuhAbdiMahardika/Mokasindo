@extends('admin.layout')

@section('title', isset($vacancy) ? 'Edit Vacancy' : 'Post New Job')
@section('page-title', isset($vacancy) ? 'Edit Vacancy' : 'Post New Job')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ isset($vacancy) ? route('admin.vacancies.update', $vacancy) : route('admin.vacancies.store') }}" class="bg-white rounded-lg shadow p-6">
        @csrf
        @if(isset($vacancy))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Job Title *</label>
                <input type="text" name="title" value="{{ old('title', $vacancy->title ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., Senior Frontend Developer" required>
            </div>

            <!-- Department -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                <input type="text" name="department" value="{{ old('department', $vacancy->department ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., Engineering" required>
            </div>

            <!-- Location -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                <input type="text" name="location" value="{{ old('location', $vacancy->location ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., Jakarta" required>
            </div>

            <!-- Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Employment Type *</label>
                <select name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="full_time" {{ old('type', $vacancy->type ?? '') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                    <option value="part_time" {{ old('type', $vacancy->type ?? '') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                    <option value="contract" {{ old('type', $vacancy->type ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="internship" {{ old('type', $vacancy->type ?? '') == 'internship' ? 'selected' : '' }}>Internship</option>
                </select>
            </div>

            <!-- Salary Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Salary Range</label>
                <input type="text" name="salary_range" value="{{ old('salary_range', $vacancy->salary_range ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., Rp 8-12 juta/bulan">
            </div>

            <!-- Deadline -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Application Deadline</label>
                <input type="date" name="deadline" value="{{ old('deadline', isset($vacancy) ? $vacancy->deadline?->format('Y-m-d') : '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Active Status -->
            <div>
                <label class="flex items-center pt-6">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $vacancy->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Active (visible to candidates)</span>
                </label>
            </div>
        </div>

        <!-- Description -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Job Description *</label>
            <textarea name="description" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('description', $vacancy->description ?? '') }}</textarea>
        </div>

        <!-- Requirements -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Requirements *</label>
            <textarea name="requirements" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="List job requirements (one per line)" required>{{ old('requirements', $vacancy->requirements ?? '') }}</textarea>
        </div>

        <!-- Benefits -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Benefits</label>
            <textarea name="benefits" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="List employee benefits (one per line)">{{ old('benefits', $vacancy->benefits ?? '') }}</textarea>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-6">
            <a href="{{ route('admin.vacancies.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md text-center">
                Cancel
            </a>
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                {{ isset($vacancy) ? 'Update' : 'Post' }} Job
            </button>
        </div>
    </form>
</div>
@endsection
