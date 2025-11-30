@extends('layouts.app')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<!-- Header -->
<section class="relative bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-24">
        <div class="flex justify-between items-start">
            <!-- Title & Dropdown Menu -->
            <div>
                <h1 class="text-4xl font-bold mb-3">Owner Dashboard</h1>
                
                <p class="text-indigo-100 text-xl mb-2">Manajemen sistem Mokasindo</p>
                
                <!-- Dropdown Navigation -->
                <div class="relative inline-block" onmouseenter="showDropdown()" onmouseleave="hideDropdown()">
                    <button class="flex items-center space-x-2 text-indigo-100 hover:text-white transition-colors font-medium">
                        <i class="bi bi-chevron-down"></i>
                            <span id="currentPage">
                                @if(request()->is('owner/overview'))
                                    Overview
                                @elseif(request()->is('owner/reports'))
                                    Laporan & Analitik
                                @elseif(request()->is('owner/settings'))
                                    Pengaturan
                                @else
                                    Overview
                                @endif
                            </span>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="dropdownMenu" class="absolute top-full left-0 mt-2 w-56 bg-white rounded-lg shadow-xl opacity-0 invisible transition-all duration-200 z-50">
                        <div class="py-2">
                            <a href="/owner/overview"
                            class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors {{ request()->is('owner/overview') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                                <i class="bi bi-speedometer2 text-xl"></i>
                                <span class="font-medium">Overview</span>
                            </a>

                            <a href="/owner/reports"
                            class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors {{ request()->is('owner/reports') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                                <i class="bi bi-graph-up text-xl"></i>
                                <span class="font-medium">Laporan &amp; Analitik</span>
                            </a>

                            <a href="/owner/settings"
                            class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors {{ request()->is('owner/settings') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                                <i class="bi bi-gear text-xl"></i>
                                <span class="font-medium">Pengaturan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Info & Actions -->
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div>
                        <p class="font-medium text-right">{{ auth()->user()->name }}</p>
                        <p class="text-indigo-200 text-sm capitalize text-right">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="absolute bottom-0 w-full">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-24">
            <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="#F9FAFB"/>
        </svg>
    </div>
</section>

<!-- Main Content -->
<main class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('owner-content')
    </div>
</main>

<script>
let dropdownTimeout;

function showDropdown() {
    clearTimeout(dropdownTimeout);
    const menu = document.getElementById('dropdownMenu');
    menu.classList.remove('opacity-0', 'invisible');
    menu.classList.add('opacity-100', 'visible');
}

function hideDropdown() {
    dropdownTimeout = setTimeout(() => {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.remove('opacity-100', 'visible');
        menu.classList.add('opacity-0', 'invisible');
    }, 150);
}
</script>

@yield('owner-scripts')

@endsection