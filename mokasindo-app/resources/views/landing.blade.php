@extends('layouts.app')

@section('content')
    <section class="relative bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full mb-6">
                    <span class="text-sm font-medium">🚗 Platform Lelang Terpercaya Indonesia</span>
                </div>

                <h1 class="text-5xl md:text-6xl font-bold mb-6">
                    Lelang Mobil & Motor<br>Bekas Terpercaya
                </h1>

                <p class="text-xl md:text-2xl text-indigo-100 mb-8 max-w-3xl mx-auto">
                    Temukan kendaraan impian Anda dengan harga terbaik melalui sistem lelang yang aman dan transparan
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition shadow-lg">
                        Mulai Lelang
                    </a>
                    <a href="#" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white/10 transition">
                        Lihat Kendaraan
                    </a>
                </div>

<div class="mt-8 w-full max-w-md mx-auto">
    <form action="{{ url('/vehicle-search') }}" method="GET" class="relative" id="searchForm">
        <div class="flex items-center bg-white rounded-full shadow-lg overflow-hidden border border-gray-200">
            <div class="pl-4 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input 
                type="text" 
                name="q" 
                class="w-full px-4 py-3 text-gray-700 leading-tight focus:outline-none" 
                placeholder="Cari kendaraan (cth: Avanza, Honda Jazz)..."
            >
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 font-semibold hover:bg-blue-700 transition duration-300">
                Cari
            </button>
        </div>
        
        <input type="hidden" name="province" id="input_province">
        <input type="hidden" name="city" id="input_city">
        <input type="hidden" name="district" id="input_district">
        <input type="hidden" name="subdistrict" id="input_subdistrict">
        <input type="hidden" name="postal_code" id="input_postal_code">
    </form>
    
    <div id="location-status" class="mt-2 text-xs text-center text-white opacity-0 transition-opacity">
        Mencari di area: <span id="current-area" class="font-bold text-yellow-300">Semua Area</span>
    </div>
</div>

                <div class="grid grid-cols-3 gap-8 mt-16 max-w-3xl mx-auto">
                    <div>
                        <div class="text-4xl font-bold mb-2">1000+</div>
                        <div class="text-indigo-200">Kendaraan Terjual</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold mb-2">5000+</div>
                        <div class="text-indigo-200">Member Aktif</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold mb-2">100%</div>
                        <div class="text-indigo-200">Aman & Terpercaya</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 w-full">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="#F9FAFB"/>
            </svg>
        </div>
    </section>

    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Mengapa Mokasindo?</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Platform lelang kendaraan bekas yang memberikan pengalaman terbaik untuk pembeli dan penjual</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Aman & Terpercaya</h3>
                    <p class="text-gray-600">Sistem deposit dan pembayaran yang aman dengan jaminan transaksi transparan</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Proses Cepat</h3>
                    <p class="text-gray-600">Lelang real-time dengan notifikasi instan melalui Telegram, Email & WhatsApp</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Harga Kompetitif</h3>
                    <p class="text-gray-600">Dapatkan kendaraan dengan harga terbaik melalui sistem lelang yang fair</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Cara Kerja</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Mudah dan sederhana, ikuti 4 langkah berikut</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">1</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Daftar & Verifikasi</h3>
                    <p class="text-gray-600">Buat akun dan lengkapi data untuk mulai lelang</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">2</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Pilih Kendaraan</h3>
                    <p class="text-gray-600">Cari dan pilih kendaraan yang Anda inginkan</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">3</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Ikut Lelang</h3>
                    <p class="text-gray-600">Bayar deposit 5% dan mulai bid real-time</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">4</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Bayar & Ambil</h3>
                    <p class="text-gray-600">Lunasi dalam 24 jam dan kendaraan siap dikirim</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Siap Memulai Lelang?</h2>
            <p class="text-xl text-indigo-100 mb-8">Daftar sekarang dan dapatkan akses ke ribuan kendaraan berkualitas</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition shadow-lg">
                    Daftar Gratis
                </a>
                {{-- <a href="{{ route('company.about') }}" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white/10 transition">
                    Pelajari Lebih Lanjut
                </a> --}}
            </div>
        </div>
    </section>
    <div id="locationModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Konfirmasi Lokasi Anda</h3>
            <button onclick="closeLocationModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4">
            <div id="map" class="w-full h-64 bg-gray-200 rounded mb-4"></div>
            <p class="text-sm text-gray-600 mb-2">Lokasi terdeteksi:</p>
            <p id="address-preview" class="font-semibold text-gray-800 mb-4">Sedang memuat...</p>
        </div>
        <div class="p-4 border-t bg-gray-50 flex justify-end">
            <button onclick="closeLocationModal()" class="mr-3 px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
            <button onclick="saveLocation()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-bold">
                Konfirmasi Lokasi
            </button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map, marker;
    let currentLocation = {
        province: '', city: '', district: '', subdistrict: '', postal_code: ''
    };

    function detectLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    // Tampilkan Modal
                    document.getElementById('locationModal').classList.remove('hidden');
                    
                    // Init Map jika belum ada
                    if (!map) {
                        // Inisialisasi Peta (Leaflet)
                        map = L.map('map').setView([lat, lng], 15);
                        
                        // Tambahkan Tile Layer (Peta Gambar dari OpenStreetMap)
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        // Tambahkan Marker yang bisa digeser
                        marker = L.marker([lat, lng], {draggable: true}).addTo(map);

                        // Event saat marker digeser
                        marker.on('dragend', function(event) {
                            var position = marker.getLatLng();
                            reverseGeocode(position.lat, position.lng);
                        });
                    } else {
                        map.setView([lat, lng], 15);
                        marker.setLatLng([lat, lng]);
                    }
                    
                    // Cari nama alamat awal
                    reverseGeocode(lat, lng);
                },
                () => {
                    alert("Gagal mendeteksi lokasi. Pastikan GPS aktif.");
                }
            );
        } else {
            alert("Browser Anda tidak mendukung Geolocation.");
        }
    }

    // Fungsi Reverse Geocoding (Koordinat -> Alamat) menggunakan Nominatim (Gratis)
    function reverseGeocode(lat, lng) {
        document.getElementById('address-preview').innerText = "Sedang memuat alamat...";
        
        // Menggunakan API Nominatim (Gratis dari OpenStreetMap)
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if(data && data.address) {
                    document.getElementById('address-preview').innerText = data.display_name;
                    
                    // Parsing Data Alamat
                    currentLocation.postal_code = data.address.postcode || '';
                    currentLocation.province = data.address.state || '';
                    currentLocation.city = data.address.city || data.address.town || data.address.regency || '';
                    currentLocation.district = data.address.district || ''; // Kadang kecamatan masuk sini
                    currentLocation.subdistrict = data.address.village || data.address.suburb || '';
                } else {
                    document.getElementById('address-preview').innerText = "Alamat tidak ditemukan";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('address-preview').innerText = "Gagal memuat alamat";
            });
    }

    function saveLocation() {
        localStorage.setItem('user_location', JSON.stringify(currentLocation));
        const label = currentLocation.subdistrict || currentLocation.city || 'Lokasi Tersimpan';
        document.getElementById('user-location-label').innerText = label;
        updateSearchInputs();
        closeLocationModal();
        
        // Feedback visual
        const statusDiv = document.getElementById('location-status');
        const areaSpan = document.getElementById('current-area');
        if(statusDiv && areaSpan) {
            areaSpan.innerText = `${currentLocation.subdistrict}, ${currentLocation.city}`;
            statusDiv.classList.remove('opacity-0');
        }
    }

    function updateSearchInputs() {
        const storedLoc = JSON.parse(localStorage.getItem('user_location'));
        if (storedLoc) {
            if(document.getElementById('input_province')) document.getElementById('input_province').value = storedLoc.province;
            if(document.getElementById('input_city')) document.getElementById('input_city').value = storedLoc.city;
            // dst... sesuaikan dengan ID input hidden Anda
        }
    }

    function closeLocationModal() {
        document.getElementById('locationModal').classList.add('hidden');
    }
</script>
@endsection