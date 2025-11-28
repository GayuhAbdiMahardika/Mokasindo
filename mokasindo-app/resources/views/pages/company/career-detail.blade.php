@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden border border-gray-100">
        
        <div class="p-8 border-b bg-gray-50">
            <h1 class="text-3xl font-bold text-gray-800">{{ $vacancy->title }}</h1>
            <div class="mt-2 flex flex-wrap gap-4 text-sm text-gray-600">
                <span class="flex items-center">📍 {{ $vacancy->location }}</span>
                <span class="flex items-center">💼 <span class="capitalize ml-1">{{ $vacancy->type }}</span></span>
                <span class="flex items-center font-semibold text-blue-600">💰 {{ $vacancy->salary_range }}</span>
            </div>
        </div>
        
        <div class="p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                    <p class="font-bold">Terjadi Kesalahan:</p>
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="prose max-w-none text-gray-700">
                <h3 class="font-bold text-xl text-gray-800 mb-3">Deskripsi Pekerjaan</h3>
                <div class="mb-6 whitespace-pre-line">{{ $vacancy->description }}</div>

                <h3 class="font-bold text-xl text-gray-800 mb-3">Persyaratan</h3>
                <div class="mb-8 whitespace-pre-line">{{ $vacancy->requirements }}</div>
            </div>

            <hr class="border-gray-200 my-8">

            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                <h3 class="font-bold text-2xl mb-6 text-center text-gray-800">Lamar Posisi Ini</h3>
                
                <form action="{{ route('company.career.store', $vacancy->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 border" placeholder="Contoh: Budi Santoso" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 border" placeholder="budi@email.com" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 border" placeholder="0812..." required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cover Letter (Opsional)</label>
                            <textarea name="cover_letter" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 border" placeholder="Ceritakan singkat tentang diri Anda...">{{ old('cover_letter') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload CV (PDF) <span class="text-red-500">*</span></label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg bg-white hover:bg-gray-50 transition cursor-pointer relative">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="text-sm text-gray-600">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span>Upload file</span>
                                            <input id="file-upload" name="cv" type="file" class="sr-only" accept=".pdf" required onchange="document.getElementById('file-name').innerText = this.files[0].name">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF maksimal 2MB</p>
                                    <p id="file-name" class="text-sm font-bold text-blue-800 mt-2"></p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-bold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition shadow-lg mt-4">
                            🚀 Kirim Lamaran Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection