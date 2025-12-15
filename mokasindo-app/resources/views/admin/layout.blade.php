<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('admin.panel')) - Mokasindo</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/mokasindo-logo.svg') }}">
    @php
        $viteHotFile = base_path('vite.hot');
        $viteManifestFile = public_path('build/manifest.json');
    @endphp
    @if (file_exists($viteHotFile) || file_exists($viteManifestFile))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.10/dist/tailwind.min.css">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0 flex flex-col">
            <div class="p-4 border-b border-gray-800">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold">
                        <i class="fas fa-gavel mr-2"></i>Mokasindo Admin
                    </h1>
                    <div class="flex space-x-1">
                        @foreach($availableLocales ?? [] as $localeKey => $label)
                            <a href="{{ route('locale.switch', $localeKey) }}" class="px-2 py-1 text-xs rounded {{ app()->getLocale() === $localeKey ? 'bg-indigo-500 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <nav class="p-4 space-y-2 flex-1 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> {{ __('admin.dashboard') }}
                </a>

                <!-- CMS Section -->
                <div class="mt-4">
                    <p class="text-xs text-gray-400 uppercase px-4 mb-2">{{ __('admin.content') }}</p>
                    <a href="{{ route('admin.pages.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.pages.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-file-alt mr-2"></i> {{ __('admin.pages') }}
                    </a>
                    <a href="{{ route('admin.vehicles.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.vehicles.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-car mr-2"></i> {{ __('admin.vehicles') }}
                    </a>
                    <a href="{{ route('admin.teams.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.teams.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-users mr-2"></i> {{ __('admin.team') }}
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.users.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-user-cog mr-2"></i> {{ __('admin.users') }}
                    </a>
                    <a href="{{ route('admin.faqs.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.faqs.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-question-circle mr-2"></i> {{ __('admin.faqs') }}
                    </a>
                </div>

                <!-- Marketplace / Auctions -->
                <div class="mt-4">
                    <p class="text-xs text-gray-400 uppercase px-4 mb-2">{{ __('admin.marketplace') }}</p>
                    <a href="{{ route('admin.auctions.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.auctions.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-gavel mr-2"></i> {{ __('admin.auctions') }}
                    </a>
                    <a href="{{ route('admin.auction-schedules.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.auction-schedules.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-calendar-alt mr-2"></i> {{ __('admin.schedules') }}
                    </a>
                </div>

                <!-- Financial -->
                <div class="mt-4">
                    <p class="text-xs text-gray-400 uppercase px-4 mb-2">{{ __('admin.financial') }}</p>
                    <a href="{{ route('admin.payments.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.payments.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-money-bill-wave mr-2"></i> {{ __('admin.payments') }}
                    </a>
                    <a href="{{ route('admin.deposits.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.deposits.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-wallet mr-2"></i> {{ __('admin.deposits') }}
                    </a>
                    <a href="{{ route('admin.user-subscriptions.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.user-subscriptions.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-users-cog mr-2"></i> {{ __('admin.subscriptions') }}
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.reports.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-chart-bar mr-2"></i> {{ __('admin.reports') }}
                    </a>
                    <a href="{{ route('admin.settings.edit') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.settings.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-sliders-h mr-2"></i> {{ __('admin.settings') }}
                    </a>
                </div>

                <!-- Career Section -->
                <div class="mt-4">
                    <p class="text-xs text-gray-400 uppercase px-4 mb-2">{{ __('admin.career_portal') }}</p>
                    <a href="{{ route('admin.vacancies.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.vacancies.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-briefcase mr-2"></i> {{ __('admin.vacancies') }}
                    </a>
                </div>

                <!-- Support Section -->
                <div class="mt-4">
                    <p class="text-xs text-gray-400 uppercase px-4 mb-2">{{ __('admin.support') }}</p>
                    <a href="{{ route('admin.inquiries.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 transition {{ request()->routeIs('admin.inquiries.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-envelope mr-2"></i> {{ __('admin.inquiries') }}
                    </a>
                </div>
            </nav>

            <!-- User Info -->
            <div class="mt-auto w-64 p-4 border-t border-gray-800 bg-gray-900">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="ml-2">
                            <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400">Admin</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-white">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between p-4">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <a href="{{ url('/') }}" target="_blank" class="text-sm text-gray-600 hover:text-gray-900">
                            <i class="fas fa-external-link-alt mr-1"></i>View Site
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
