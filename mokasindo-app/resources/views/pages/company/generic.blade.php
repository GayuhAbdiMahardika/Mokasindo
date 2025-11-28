@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto bg-white shadow p-10 rounded">
        <h1 class="text-3xl font-bold mb-6 border-b pb-4">{{ $page->title }}</h1>
        <div class="prose prose-blue max-w-none text-gray-700">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection