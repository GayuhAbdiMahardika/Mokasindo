@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto pb-12">
        <div class="bg-gray-300 w-full flex justify-center h-96 relative rounded-b-3xl overflow-hidden">
            <img id="featured-image" src="https://i.postimg.cc/TYfwsY56/monaco-gp-background.jpg" alt="McLaren 720S"
                class="w-full h-full object-contain transition-opacity duration-300">

            <div class="absolute w-full h-full flex justify-between items-center px-4 bottom-0 pointer-events-none">
                <button onclick="prevImage()"
                    class="pointer-events-auto p-2 bg-gray-900/50 hover:bg-gray-900/70 text-white rounded-full transition hover:scale-110 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <button onclick="nextImage()"
                    class="pointer-events-auto p-2 bg-gray-900/50 hover:bg-gray-900/70 text-white rounded-full transition hover:scale-110 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex flex-row my-6 items-center justify-evenly gap-4 overflow-hidden px-4 h-48">
            @foreach (range(0, 4) as $i)
                <img src="https://i.postimg.cc/TYfwsY56/monaco-gp-background.jpg" alt="Thumbnail"
                    onclick="selectImage({{ $i }})"
                    class="thumbnail-item w-20 h-20 md:w-24 md:h-24 rounded-2xl object-cover cursor-pointer border-2 transition-all duration-200 ease-out {{ $i === 0 ? 'border-indigo-600 scale-110 ring-2 ring-indigo-200' : 'border-transparent opacity-70 hover:opacity-100' }}"
                    tabindex="0">
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 md:px-8">

            <div class="lg:col-span-2 space-y-8">

                <div class="border-b border-gray-200 pb-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span
                            class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                            Live Auction
                        </span>
                        <span
                            class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                            Bekas
                        </span>
                        <div class="flex items-center text-gray-500 text-sm ml-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            1.240 Views
                        </div>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">McLaren MCL39</h1>
                    <p class="text-lg text-gray-500 mt-1">2020 • Supercar</p>

                    <h2 class="text-4xl md:text-5xl text-indigo-700 font-bold mt-4">Rp 485.000.000</h2>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Spesifikasi Kendaraan</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                            <div class="p-3 bg-white rounded-lg shadow-sm text-indigo-600 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Jarak Tempuh</p>
                                <p class="font-semibold">4.500 km</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                            <div class="p-3 bg-white rounded-lg shadow-sm text-indigo-600 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Transmisi</p>
                                <p class="font-semibold">Manual</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                            <div class="p-3 bg-white rounded-lg shadow-sm text-indigo-600 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Bahan Bakar</p>
                                <p class="font-semibold">Pertamax Turbo</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                            <div class="p-3 bg-white rounded-lg shadow-sm text-indigo-600 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Mesin</p>
                                <p class="font-semibold">4.000 cc</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                            <div class="p-3 bg-white rounded-lg shadow-sm text-indigo-600 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Warna</p>
                                <p class="font-semibold">Papaya Orange</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                            <div class="p-3 bg-white rounded-lg shadow-sm text-indigo-600 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Plat Nomor</p>
                                <p class="font-semibold">B 1 SULTAN</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="prose max-w-none">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Deskripsi</h3>
                    <div class="text-gray-600 whitespace-pre-line leading-relaxed">
                        Mobil simpanan kolektor, sangat jarang dipakai (Low KM).
                        Kondisi istimewa seperti baru keluar dari dealer.

                        - Full Service Record di ATPM Resmi McLaren Jakarta.
                        - Ban Pirelli P Zero Corsa baru diganti 4 buah bulan lalu.
                        - Pajak panjang sampai Mei 2025.
                        - Coating ceramic 3 layer by TopCoat.
                        - Knalpot Titanium (Original part tersimpan rapi).

                        Lokasi unit ada di garasi pribadi di Surabaya Timur. Siap general check-up kapan saja. Nego halus
                        setelah lihat barang.
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 text-lg">Lokasi Kendaraan</h3>

                    <div class="flex items-start mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-3 shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Surabaya, Jawa Timur</p>
                            <p class="text-sm text-gray-500">Rungkut, Gunung Anyar</p>
                            <p class="text-sm text-gray-400 mt-1">Kode Pos: 60293</p>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-100">

                    <h3 class="font-bold text-gray-900 mb-4 text-lg">Informasi Penjual</h3>
                    <div class="flex items-center mb-6">
                        <div
                            class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xl mr-4">
                            S
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Sultan Andara</p>
                            <p class="text-xs text-gray-500">Member sejak 2023</p>
                        </div>
                    </div>

                    <button
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 flex items-center justify-center shadow-lg shadow-indigo-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Hubungi Penjual
                    </button>

                    <button
                        class="w-full mt-3 bg-white border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-bold py-3 px-4 rounded-xl transition duration-200">Tawar
                        Sekarang</button>
                </div>
            </div>

        </div>
    </div>

    {{-- Script untuk slider gambar manual --}}
    <script>
        // Array Gambar Dummy
        const galleryImages = [
            'https://i.postimg.cc/TYfwsY56/monaco-gp-background.jpg',
            'https://i.postimg.cc/TYfwsY56/monaco-gp-background.jpg',
            'https://i.postimg.cc/TYfwsY56/monaco-gp-background.jpg',
            'https://i.postimg.cc/TYfwsY56/monaco-gp-background.jpg',
            'https://i.postimg.cc/TYfwsY56/monaco-gp-background.jpg'
        ];

        let currentIndex = 0;
        const mainImage = document.getElementById('featured-image');
        const thumbnailItems = document.querySelectorAll('.thumbnail-item');

        function updateGallery() {
            // Update Gambar Utama
            mainImage.src = galleryImages[currentIndex];

            // Update Style Thumbnail (Active State)
            thumbnailItems.forEach((thumb, index) => {
                if (index === currentIndex) {
                    thumb.classList.remove('border-transparent', 'opacity-70', 'hover:opacity-100');
                    thumb.classList.add('border-indigo-600', 'scale-110', 'ring-2', 'ring-indigo-200');
                } else {
                    thumb.classList.add('border-transparent', 'opacity-70', 'hover:opacity-100');
                    thumb.classList.remove('border-indigo-600', 'scale-110', 'ring-2', 'ring-indigo-200');
                }
            });
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % galleryImages.length;
            updateGallery();
        }

        function prevImage() {
            currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
            updateGallery();
        }

        function selectImage(index) {
            currentIndex = index;
            updateGallery();
        }
    </script>
@endsection
