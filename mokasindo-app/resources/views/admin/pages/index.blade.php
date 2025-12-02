@extends('admin.layout')

@section('title', 'Pages Management')
@section('page-title', 'Dynamic Pages')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h3 class="text-2xl font-bold text-gray-900">Dynamic Pages</h3>
        <p class="text-gray-600">Manage website content pages (Terms, Privacy, etc.)</p>
    </div>
    <a href="{{ route('admin.pages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
        <i class="fas fa-plus mr-2"></i>Create Page
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Updated</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($pages as $page)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $page->title }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit(strip_tags($page->content), 80) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $page->slug }}</code>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('admin.pages.toggle-publish', $page) }}" method="POST" class="inline">
                            @csrf
                            @if($page->is_published)
                                <button type="submit" class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 hover:bg-green-200 transition cursor-pointer" title="Klik untuk unpublish">
                                    <i class="fas fa-check-circle mr-1"></i>Published
                                </button>
                            @else
                                <button type="submit" class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 hover:bg-gray-200 transition cursor-pointer" title="Klik untuk publish">
                                    <i class="fas fa-file-alt mr-1"></i>Draft
                                </button>
                            @endif
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $page->updated_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            {{-- Toggle Publish Button --}}
                            <form action="{{ route('admin.pages.toggle-publish', $page) }}" method="POST" class="inline">
                                @csrf
                                @if($page->is_published)
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Unpublish">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                @else
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Publish">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @endif
                            </form>
                            
                            {{-- View Public Page --}}
                            @if($page->is_published)
                                <a href="/page/{{ $page->slug }}" target="_blank" class="text-gray-600 hover:text-gray-900" title="View Page">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            @endif
                            
                            {{-- Edit --}}
                            <a href="{{ route('admin.pages.edit', $page) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            {{-- Revisions --}}
                            <a href="{{ route('admin.pages.revisions', $page) }}" class="text-purple-600 hover:text-purple-900" title="Revisions">
                                <i class="fas fa-history"></i>
                            </a>
                            
                            {{-- Delete --}}
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No pages found. <a href="{{ route('admin.pages.create') }}" class="text-blue-600 hover:text-blue-800">Create one now</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $pages->links() }}
</div>
@endsection
