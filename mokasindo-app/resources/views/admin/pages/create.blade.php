@extends('admin.layout')

@section('title', isset($page) ? 'Edit Page' : 'Create Page')
@section('page-title', isset($page) ? 'Edit Page' : 'Create Page')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}" class="bg-white rounded-lg shadow p-6">
        @csrf
        @if(isset($page))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Page Title *</label>
                <input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., Terms of Service" required>
            </div>

            <!-- Slug -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Auto-generated if empty">
                <p class="mt-1 text-xs text-gray-500">URL-friendly version of title</p>
            </div>
        </div>

        <!-- Meta Description -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
            <input type="text" name="meta_description" value="{{ old('meta_description', $page->meta_description ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="SEO description (optional)">
            <p class="mt-1 text-xs text-gray-500">Short description for search engines (150-160 characters)</p>
        </div>

        <!-- Content -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Page Content *</label>
            <textarea id="pageContent" name="content" rows="15" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono text-sm" required>{{ old('content', $page->content ?? '') }}</textarea>
            <p class="mt-1 text-xs text-gray-500">Supports HTML formatting</p>
        </div>

        <!-- Published Status -->
        <div class="mt-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $page->is_published ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Published (visible to public)</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-6">
            <a href="{{ route('admin.pages.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md text-center">
                Cancel
            </a>
            <button type="button" id="previewBtn" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md">Preview</button>
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                {{ isset($page) ? 'Update' : 'Create' }} Page
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#pageContent',
        height: 500,
        menubar: false,
        plugins: 'link image code lists table',
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
    });

    document.getElementById('previewBtn').addEventListener('click', function(){
        const content = tinymce.get('pageContent').getContent();
        const title = document.querySelector('input[name="title"]').value || '';
        const html = `<!doctype html><html><head><meta charset="utf-8"><title>${title}</title><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"></head><body class="p-6">${content}</body></html>`;
        const previewWindow = window.open('', '_blank');
        previewWindow.document.open();
        previewWindow.document.write(html);
        previewWindow.document.close();
    });
</script>
@endpush
