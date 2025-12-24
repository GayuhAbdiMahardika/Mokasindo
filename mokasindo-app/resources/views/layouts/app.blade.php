<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Mokasindo - Lelang Mobil & Motor Bekas Indonesia' }}</title>
    
    @php
        $viteHotFile = base_path('vite.hot');
        $viteManifestFile = public_path('build/manifest.json');
    @endphp
    @if (file_exists($viteHotFile) || file_exists($viteManifestFile))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.10/dist/tailwind.min.css">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                        {{ __('nav.brand') }}
                    </a>
                </div>

                <!-- Center navigation (desktop) with active state -->
                <div class="hidden md:flex md:flex-1 md:justify-center md:space-x-8">
                    <a href="/"
                       class="font-medium transition px-2 py-1 {{ request()->is('/') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                        {{ __('nav.home') }}
                    </a>

                    <a href="{{ route('etalase.index') }}"
                       class="font-medium transition px-2 py-1 {{ request()->is('etalase*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                        {{ __('nav.showroom') }}
                    </a>

                    <a href="{{ route('auctions.index') }}"
                       class="font-medium transition px-2 py-1 {{ request()->is('auctions*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                        {{ __('nav.auctions') }}
                    </a>

                    <a href="/about"
                       class="font-medium transition px-2 py-1 {{ request()->is('about*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                        {{ __('nav.about') }}
                    </a>

                    <a href="/contact"
                       class="font-medium transition px-2 py-1 {{ request()->is('contact*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                        {{ __('nav.contact') }}
                    </a>
                </div>

                <!-- Right controls: lokasi, login, register -->
                <div class="ml-auto flex items-center space-x-4">
                    <button onclick="detectLocation()" class="flex items-center text-gray-700 hover:text-blue-600 transition" title="{{ __('nav.location') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span id="user-location-label" class="text-sm font-medium hidden md:block">{{ __('nav.location') }}</span>
                    </button>

                    <div class="flex items-center space-x-1">
                        @foreach($availableLocales ?? [] as $localeKey => $label)
                            <a href="{{ route('locale.switch', $localeKey) }}" class="px-2 py-1 text-sm rounded {{ app()->getLocale() === $localeKey ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:text-indigo-600' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    @auth
                        <!-- Notifications Bell -->
                        <div class="relative mr-4">
                            <a href="{{ route('notifications.index') }}" class="relative">
                                <i class="fas fa-bell text-2xl text-gray-700 hover:text-indigo-600"></i>
                                @php
                                    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
                                @endphp
                                @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                                @endif
                            </a>
                        </div>

                        <!-- User Menu Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600 font-medium">
                                <i class="fas fa-user-circle text-2xl"></i>
                                <span class="hidden md:block">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50" style="display: none;">
                                @if(auth()->user()->role === 'admin')
                                    <a href="/admin" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2"></i>{{ __('nav.admin_dashboard') }}
                                    </a>
                                @else
                                    <a href="/dashboard" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-th-large mr-2"></i>{{ __('nav.dashboard') }}
                                    </a>
                                    <a href="{{ route('my.ads') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-car mr-2"></i>{{ __('nav.my_vehicles') }}
                                    </a>
                                    <a href="{{ route('my.bids') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-gavel mr-2"></i>{{ __('nav.my_bids') }}
                                    </a>
                                    <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-heart mr-2"></i>{{ __('nav.wishlist') }}
                                    </a>
                                @endif
                                <hr class="my-2">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>{{ __('nav.settings') }}
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>{{ __('nav.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="/login" class="{{ request()->is('login*') ? 'text-indigo-600 font-medium' : 'text-gray-700 hover:text-indigo-600 font-medium' }}">{{ __('nav.login') }}</a>
                        <a href="/register" class="{{ request()->is('register*') ? 'bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium' : 'bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium' }}">{{ __('nav.register') }}</a>
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
                    <h3 class="text-2xl font-bold text-indigo-400 mb-4">{{ __('nav.brand') }}</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Platform lelang mobil dan motor bekas terpercaya di Indonesia dengan sistem yang aman dan transparan.
                    </p>
                </div>

                <!-- Links 1 -->
                <div>
                    <h4 class="font-semibold mb-4 text-lg">{{ __('footer.company') }}</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('company.about') }}" class="text-gray-400 hover:text-white transition">{{ __('footer.about') }}</a></li>
                        <li><a href="{{ route('company.career') }}" class="text-gray-400 hover:text-white transition">{{ __('footer.career') }}</a></li>
                        <li><a href="{{ route('pages.index') }}" class="text-gray-400 hover:text-white transition">{{ __('footer.blog') }}</a></li>
                    </ul>
                </div>

                <!-- Links 2 -->
                <div>
                    <h4 class="font-semibold mb-4 text-lg">{{ __('footer.help') }}</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('company.faq') }}" class="text-gray-400 hover:text-white transition">{{ __('footer.faq') }}</a></li>
                        <li><a href="{{ route('company.how_it_works')}}" class="text-gray-400 hover:text-white transition">{{ __('footer.how') }}</a></li>
                        <li><a href="{{ route('company.contact') }}" class="text-gray-400 hover:text-white transition">{{ __('footer.contact') }}</a></li>
                    </ul>
                </div>

                <!-- Links 3 -->
                <div>
                    <h4 class="font-semibold mb-4 text-lg">{{ __('footer.legal') }}</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('company.terms') }}" class="text-gray-400 hover:text-white transition">{{ __('footer.terms') }}</a></li>
                        <li><a href="{{ route('company.privacy') }}" class="text-gray-400 hover:text-white transition">{{ __('footer.privacy') }}</a></li>
                        <li><a href="{{ route('company.cookie_policy') }}" class="text-gray-400 hover:text-white transition">{{ __('footer.cookie') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} {{ __('nav.brand') }}. {{ __('footer.copy') }}</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>