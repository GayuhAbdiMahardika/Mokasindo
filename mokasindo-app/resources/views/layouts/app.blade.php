<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Mokasindo - Lelang Mobil & Motor Bekas Indonesia' }}</title>
    
    <!-- Menggunakan CDN Tailwind sesuai file asli -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen"> <!-- Tambahkan flex agar footer di bawah -->

    <!-- Navbar  -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-indigo-600 tracking-tighter hover:text-indigo-700 transition">
                        Mokasindo
                    </a>
                </div>
                
                <!-- Navigation Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <!-- Link Home -->
                    <a href="/" class="text-gray-700 hover:text-indigo-600 font-medium transition">Beranda</a>
                    
                    <!-- Link Dinamis -->
                    {{-- <a href="{{ route('company.about') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">Tentang</a>
                    <a href="{{ route('company.faq') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">Bantuan</a>
                    <a href="{{ route('company.career') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">Karir</a>
                    <a href="{{ route('company.contact') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">Kontak</a> --}}
                </div>
                
             <!-- lokasi -->   
    <div class="flex items-center space-x-4 ml-auto">
        <button onclick="detectLocation()" class="flex items-center text-gray-700 hover:text-blue-600 transition" title="Atur Lokasi Saya">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        <span id="user-location-label" class="text-sm font-medium hidden md:block">Lokasi Saya</span>
    </button>

    <!-- Link Masuk & Daftar -->
    <a href="/login" class="text-gray-700 hover:text-indigo-600 font-medium transition">Masuk</a>
    <a href="/register" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium transition shadow-md shadow-indigo-200">Daftar</a>
</div>
            </div>
        </div>
    </nav>

    <!-- CONTENT (Tempat konten halaman lain masuk) -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <h3 class="text-2xl font-bold text-indigo-400 mb-4">Mokasindo</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Platform lelang mobil dan motor bekas terpercaya di Indonesia dengan sistem yang aman dan transparan.
                    </p>
                </div>

                <!-- Links 1 -->
                <div>
                    <h4 class="font-semibold mb-4 text-lg">Perusahaan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('company.about') }}" class="text-gray-400 hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="{{ route('company.career') }}" class="text-gray-400 hover:text-white transition">Karir</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Blog</a></li>
                    </ul>
                </div>

                <!-- Links 2 -->
                <div>
                    <h4 class="font-semibold mb-4 text-lg">Bantuan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('company.faq') }}" class="text-gray-400 hover:text-white transition">FAQ</a></li>
                        <li><a href="{{ route('company.how_it_works')}}" class="text-gray-400 hover:text-white transition">Cara Lelang</a></li>
                        <li><a href="{{ route('company.contact') }}" class="text-gray-400 hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>

                <!-- Links 3 -->
                <div>
                    <h4 class="font-semibold mb-4 text-lg">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('company.terms') }}" class="text-gray-400 hover:text-white transition">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('company.privacy') }}" class="text-gray-400 hover:text-white transition">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('company.cookie_policy') }}" class="text-gray-400 hover:text-white transition">Kebijakan Cookie</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} Mokasindo. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>