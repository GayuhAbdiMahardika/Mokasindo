@extends('admin.layout')

@section('title', __('admin.pages_management.title'))
@section('page-title', __('admin.pages_management.page_title'))

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h3 class="text-2xl font-bold text-gray-900">{{ __('admin.pages_management.heading') }}</h3>
        <p class="text-gray-600">{{ __('admin.pages_management.subtitle') }}</p>
    </div>
    <a href="{{ route('admin.pages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
        <i class="fas fa-plus mr-2"></i>{{ __('admin.pages_management.create') }}
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.pages_management.table.title') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.pages_management.table.slug') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.pages_management.table.status') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.pages_management.table.updated') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.pages_management.table.actions') }}</th>
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
                                <button type="submit" class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 hover:bg-green-200 transition cursor-pointer" title="{{ __('admin.pages_management.tooltip.unpublish') }}">
                                    <i class="fas fa-check-circle mr-1"></i>{{ __('admin.pages_management.status.published') }}
                                </button>
                            @else
                                <button type="submit" class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 hover:bg-gray-200 transition cursor-pointer" title="{{ __('admin.pages_management.tooltip.publish') }}">
                                    <i class="fas fa-file-alt mr-1"></i>{{ __('admin.pages_management.status.draft') }}
                                </button>
                            @endif
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $page->updated_at->locale(app()->getLocale())->translatedFormat('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            {{-- Toggle Publish Button --}}
                            <form action="{{ route('admin.pages.toggle-publish', $page) }}" method="POST" class="inline">
                                @csrf
                                @if($page->is_published)
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="{{ __('admin.pages_management.tooltip.unpublish') }}">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                @else
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="{{ __('admin.pages_management.tooltip.publish') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @endif
                            </form>
                            
                            {{-- View Public Page --}}
                            @if($page->is_published)
                                <a href="/page/{{ $page->slug }}" target="_blank" class="text-gray-600 hover:text-gray-900" title="{{ __('admin.pages_management.view_page') }}">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            @endif
                            
                            {{-- Edit --}}
                            <a href="{{ route('admin.pages.edit', $page) }}" class="text-blue-600 hover:text-blue-900" title="{{ __('admin.pages_management.edit') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            {{-- Revisions --}}
                            <a href="{{ route('admin.pages.revisions', $page) }}" class="text-purple-600 hover:text-purple-900" title="{{ __('admin.pages_management.revisions') }}">
                                <i class="fas fa-history"></i>
                            </a>
                            
                            {{-- Delete --}}
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('admin.pages_management.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="{{ __('admin.pages_management.delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        {{ __('admin.pages_management.empty') }} <a href="{{ route('admin.pages.create') }}" class="text-blue-600 hover:text-blue-800">{{ __('admin.pages_management.create_now') }}</a>
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
