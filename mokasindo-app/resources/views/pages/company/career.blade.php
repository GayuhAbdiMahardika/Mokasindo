@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800">Bergabung Bersama Mokasindo</h1>
        <p class="text-gray-600 mt-4">Temukan karir impianmu dan bertumbuh bersama kami.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
        @forelse($vacancies as $job)
            <div class="border border-gray-200 rounded-lg p-6 transition shadow-sm relative overflow-hidden 
                {{ $job->is_active ? 'bg-white hover:border-blue-500 hover:shadow-md' : 'bg-gray-50 opacity-75 grayscale' }}">
                
                @if(!$job->is_active)
                    <div class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg z-10">
                        DITUTUP
                    </div>
                @endif

                <div class="flex justify-between items-start">
                    <div class="flex-1 pr-4"> <h3 class="text-xl font-bold text-gray-800">{{ $job->title }}</h3>
                        <div class="text-gray-500 text-sm mt-1 space-x-2">
                            <span>📍 {{ $job->location }}</span>
                            <span>•</span>
                            <span class="capitalize">{{ $job->type }}</span>
                        </div>
                        
                        @if($job->is_active)
                            <p class="text-blue-600 font-semibold mt-3">{{ $job->salary_range }}</p>
                        @else
                            <p class="text-gray-400 font-medium mt-3 italic">Lamaran ditutup</p>
                        @endif
                    </div>

                    <div class="flex-shrink-0 mt-1">
                        @if($job->is_active)
                            <a href="{{ route('company.career.show', $job->id) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                                Detail
                            </a>
                        @else
                            <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-md text-sm font-medium cursor-not-allowed">
                                Penuh
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-16 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada lowongan</h3>
                <p class="mt-1 text-sm text-gray-500">Pantau terus halaman ini untuk update terbaru.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection