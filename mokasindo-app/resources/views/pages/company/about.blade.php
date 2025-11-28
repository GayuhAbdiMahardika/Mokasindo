@extends('layouts.app') 

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-6">{{ $page->title }}</h1>
        <div class="prose prose-lg mx-auto text-gray-600">
            {!! $page->content !!}
        </div>
    </div>

    <div class="mt-16">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-10">Tim Kami</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($teams as $team)
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                <img src="{{ $team->photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($team->name).'&background=random' }}" 
                     class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-blue-100">
                <h3 class="text-xl font-bold text-gray-800">{{ $team->name }}</h3>
                <p class="text-blue-600 font-medium">{{ $team->position }}</p>
                <p class="text-gray-500 mt-2 text-sm">{{ $team->bio }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection