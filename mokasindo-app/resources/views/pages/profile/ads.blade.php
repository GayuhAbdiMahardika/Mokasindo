@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        
        <div class="md:col-span-1">
            @include('pages.profile.sidebar')
        </div>

        <div class="md:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">Daftar Iklan Saya</h2>
                    <a href="{{ route('vehicles.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                        + Tambah Kendaraan
                    </a>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($vehicles as $vehicle)
                        <div class="p-6 flex flex-col sm:flex-row gap-5 hover:bg-gray-50 transition group">
                            <div class="w-full sm:w-48 h-32 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0 relative">
                                @if($vehicle->primaryImage)
                                    <img src="{{ asset('storage/' . $vehicle->primaryImage->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm bg-gray-100">No Image</div>
                                @endif
                                
                                <span class="absolute top-2 left-2 px-2 py-1 bg-black/60 text-white text-[10px] font-bold uppercase rounded backdrop-blur-sm">
                                    {{ ucfirst($vehicle->status) }}
                                </span>
                            </div>

                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-bold text-gray-900 text-lg line-clamp-1">{{ $vehicle->brand }} {{ $vehicle->model }} {{ $vehicle->year }}</h3>
                                        <p class="text-indigo-600 font-bold whitespace-nowrap">Rp {{ number_format($vehicle->starting_price, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ Str::limit($vehicle->description, 100) }}</p>
                                    
                                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-400">
                                        <span>Dibuat: {{ $vehicle->created_at->format('d M Y') }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $vehicle->city->name ?? 'Lokasi -' }}</span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex items-center gap-3">
                                    <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="flex-1 sm:flex-none text-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:border-indigo-300 hover:text-indigo-600 transition">
                                        Edit
                                    </a>
                                    <a href="{{ url('/etalase/vehicles/' . $vehicle->id) }}" class="flex-1 sm:flex-none text-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-white hover:border-indigo-300 hover:text-indigo-600 transition">
                                        Lihat Detail
                                    </a>
                                    <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?')" class="flex-1 sm:flex-none">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full px-4 py-2 bg-red-50 border border-red-300 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Belum ada iklan</h3>
                            <p class="text-gray-500 mt-1">Anda belum memiliki riwayat iklan yang dipublikasikan.</p>
                        </div>
                    @endforelse
                </div>
                
                @if($vehicles->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">
                        {{ $vehicles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection