@extends('layouts.app')

@section('title', $page->title)

@if($page->meta_description)
@section('meta_description', $page->meta_description)
@endif

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold">{{ $page->title }}</h1>
            @if($page->meta_description)
                <p class="mt-2 text-blue-100">{{ $page->meta_description }}</p>
            @endif
        </div>
    </div>

    {{-- Content --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <article class="prose prose-lg max-w-none">
                {!! $page->content !!}
            </article>
        </div>

        {{-- Last Updated --}}
        <div class="mt-6 text-center text-sm text-gray-500">
            <p>{{ __('blog.updated_at', ['date' => $page->updated_at->format('d M Y, H:i')]) }}</p>
        </div>
    </div>
</div>
@endsection
