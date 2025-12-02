@extends('admin.layout')

@section('title', 'Edit Page')
@section('page-title', 'Edit Page')

@section('content')
<div class="max-w-4xl">
    <form id="pageForm" method="POST" action="{{ route('admin.pages.update', $page) }}" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Page Title *</label>
                <input type="text" name="title" value="{{ old('title', $page->title) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <!-- Slug -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="mt-1 text-xs text-gray-500">URL-friendly version of title</p>
            </div>
        </div>

        <!-- Meta Description -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
            <input type="text" name="meta_description" value="{{ old('meta_description', $page->meta_description) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="SEO description (optional)">
            <p class="mt-1 text-xs text-gray-500">Short description for search engines (150-160 characters)</p>
        </div>

        <!-- Content -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Page Content *</label>
            <textarea id="pageContent" name="content" rows="15" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono text-sm">{{ old('content', $page->content) }}</textarea>
            <p class="mt-1 text-xs text-gray-500">Supports HTML formatting</p>
        </div>

        <!-- Published Status -->
        <div class="mt-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $page->is_published) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Published (visible to public)</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-6">
            <a href="{{ route('admin.pages.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md text-center">
                Cancel
            </a>
            <button type="button" id="previewBtn" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md">Preview</button>
            <button type="submit" id="submitBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                Update Page
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
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
        const heroTitle = title || 'Preview Page';

        const template = `<!doctype html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <title>${heroTitle}</title>
                <meta name="viewport" content="width=device-width,initial-scale=1">
                <style>
                    :root { font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
                    body { margin: 0; background: #f8fafc; color: #0f172a; }
                    .hero { background: linear-gradient(120deg, #4f46e5, #7c3aed); color: #fff; padding: 4rem 1.5rem 5rem; }
                    .hero p { margin: 0; letter-spacing: 0.2em; font-size: 0.75rem; opacity: .7; text-transform: uppercase; }
                    .hero h1 { margin: 0.5rem 0 0; font-size: clamp(2rem, 4vw, 3.25rem); }
                    .hero span { display: block; margin-top: 1rem; opacity: .85; }
                    .card { max-width: 920px; margin: -3.5rem auto 3rem; background: #fff; border-radius: 1.5rem; box-shadow: 0 20px 60px rgba(15,23,42,0.15); padding: 2.5rem; }
                    .card p { line-height: 1.7; }
                    footer { text-align: center; font-size: .875rem; color: #475569; margin-bottom: 2rem; }
                    @media(max-width: 640px) { .card { padding: 1.5rem; border-radius: 1rem; } }
                </style>
            </head>
            <body>
                <section class="hero">
                    <div class="hero__container">
                        <p>Mokasindo • Preview</p>
                        <h1>${heroTitle}</h1>
                        <span>Beginilah halaman akan muncul untuk pengguna.</span>
                    </div>
                </section>

                <article class="card">
                    ${content || '<p style="color:#94a3b8">Belum ada konten.</p>'}
                </article>

                <footer>© ${new Date().getFullYear()} Mokasindo. Preview only.</footer>
            </body>
            </html>`;

        const previewWindow = window.open('', '_blank');
        previewWindow.document.open();
        previewWindow.document.write(template);
        previewWindow.document.close();
    });

    document.getElementById('pageForm').addEventListener('submit', function(event) {
        tinymce.triggerSave();
        const plainContent = tinymce.get('pageContent').getContent({ format: 'text' }).trim();
        if (!plainContent.length) {
            event.preventDefault();
            alert('Konten halaman wajib diisi.');
            tinymce.get('pageContent').focus();
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                Menyimpan...
            `;
        }
    });
</script>
@endpush
