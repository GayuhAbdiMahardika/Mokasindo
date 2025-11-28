@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-10">Pusat Bantuan (FAQ)</h1>

    <div class="max-w-3xl mx-auto space-y-6">
        @foreach($faqs as $category => $items)
            <div class="mb-8">
                <h3 class="text-2xl font-semibold text-blue-800 mb-4 capitalize">{{ $category }}</h3>
                <div class="space-y-4">
                    @foreach($items as $faq)
                    <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                        <h4 class="font-bold text-lg text-gray-800 mb-2">Q: {{ $faq->question }}</h4>
                        <p class="text-gray-600">A: {{ $faq->answer }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection