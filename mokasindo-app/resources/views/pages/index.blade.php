@extends('layouts.app')

@section('title', 'Blog & Artikel - Mokasindo')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold">Blog & Artikel</h1>
            <p class="mt-2 text-blue-100">Informasi terbaru seputar lelang mobil dan motor</p>
        </div>
    </div>

    {{-- Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($pages->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pages as $page)
                    <a href="{{ route('page.show', $page->slug) }}" class="block bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden group">
                        {{-- Placeholder image --}}
                        <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-newspaper text-white text-5xl opacity-50 group-hover:opacity-75 transition"></i>
                        </div>
                        
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition line-clamp-2">
                                {{ $page->title }}
                            </h2>
                            
                            @if($page->meta_description)
                                <p class="mt-2 text-gray-600 text-sm line-clamp-3">
                                    {{ $page->meta_description }}
                                </p>
                            @else
                                <p class="mt-2 text-gray-600 text-sm line-clamp-3">
                                    {{ Str::limit(strip_tags($page->content), 120) }}
                                </p>
                            @endif
                            
                            <div class="mt-4 flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                {{ $page->updated_at->format('d M Y') }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $pages->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-600">Belum ada artikel</h3>
                <p class="text-gray-500 mt-2">Artikel dan informasi terbaru akan segera hadir.</p>
            </div>
        @endif
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
