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
            <div class="flex items-center h-16">
                <!-- Logo (left) -->
                <div class="flex-shrink-0">
                    <a href="/" class="text-2xl font-bold text-indigo-600 tracking-tighter hover:text-indigo-700 transition">
                        Mokasindo
                    </a>
                </div>

                <!-- Center navigation (desktop) with active state -->
                <div class="hidden md:flex md:flex-1 md:justify-center md:space-x-8">
                    <a href="/"
                       class="font-medium transition px-2 py-1 {{ request()->is('/') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                        Beranda
                    </a>

                    <a href="/about"
                       class="font-medium transition px-2 py-1 {{ request()->is('about*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                        Tentang
                    </a>

                    <a href="/contact"
                       class="font-medium transition px-2 py-1 {{ request()->is('contact*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                        Kontak
                    </a>
                </div>

                <!-- Right controls: lokasi, login, register -->
                {{-- <div class="ml-auto flex items-center space-x-4">
                    <button onclick="detectLocation()" class="flex items-center text-gray-700 hover:text-blue-600 transition" title="Atur Lokasi Saya">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span id="user-location-label" class="text-sm font-medium hidden md:block">Lokasi Saya</span>
                    </button>

                    <a href="/login" class="{{ request()->is('login*') ? 'text-indigo-600 font-medium' : 'text-gray-700 hover:text-indigo-600 font-medium' }}">Masuk</a>
                    <a href="/register" class="{{ request()->is('register*') ? 'bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium' : 'bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium' }}">Daftar</a>
                </div> --}}
                <!-- Right controls: lokasi + auth (guest / logged in) -->
                <div class="ml-auto flex items-center space-x-4">
                    {{-- Tombol lokasi tetap --}}
                    <button onclick="detectLocation()" class="flex items-center text-gray-700 hover:text-blue-600 transition" title="Atur Lokasi Saya">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span id="user-location-label" class="text-sm font-medium hidden md:block">Lokasi Saya</span>
                    </button>

                    {{-- Kalau belum login --}}
                    @guest
                        <a href="{{ route('login.form') }}"
                        class="{{ request()->is('login*') ? 'text-indigo-600 font-medium' : 'text-gray-700 hover:text-indigo-600 font-medium' }}">
                            Masuk
                        </a>
                        <a href="{{ route('register.form') }}"
                        class="{{ request()->is('register*') ? 'bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium' : 'bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium' }}">
                            Daftar
                        </a>
                    @endguest

                    {{-- Kalau sudah login --}}
                    @auth
                        @php
                            $role = auth()->user()->role ?? null;
                        @endphp

                        {{-- Hanya role owner & admin yang melihat tombol ini --}}
                        @if(in_array($role, ['owner', 'admin']))
                            <a href="{{ route('owner.settings.edit') }}"
                            class="hidden md:inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100">
                                {{-- bisa juga route('owner.overview') kalau mau ke dashboard --}}
                                ⚙️ Pengaturan
                            </a>
                        @endif

                        <span class="text-sm text-gray-700 hidden md:inline">
                            Halo, <strong>{{ auth()->user()->name }}</strong>
                        </span>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600">
                                Logout
                            </button>
                        </form>
                    @endauth
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