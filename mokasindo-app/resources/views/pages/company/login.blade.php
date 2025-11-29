@extends('layouts.app')

@section('content')
    <section class="relative bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-[7rem]">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Masuk ke Akun</h1>
                <p class="text-indigo-100 max-w-2xl mx-auto">Masukkan kredensial Anda untuk mengakses dashboard dan fitur layanan.</p>
            </div>
        </div>

        <div class="absolute bottom-0 w-full">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="#F9FAFB"/>
            </svg>
        </div>
    </section>

    <section class="min-h-screen flex items-center justify-center bg-gray-50 py-12">
        <div class="max-w-md w-full px-4">
            <div class="bg-white shadow-lg rounded-2xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">Masuk ke Akun Anda</h2>
                <p class="text-sm text-gray-500 text-center mb-6">Masukkan email dan password untuk melanjutkan</p>

                @if(session('status'))
                    <div class="mb-4 p-3 rounded bg-green-50 border border-green-100 text-green-700 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded bg-red-50 border border-red-100 text-red-700 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ url('/login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                               placeholder="email@domain.com"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                   placeholder="Masukkan password"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 pr-12 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                                <!-- eye icon (toggle) -->
                                <svg id="iconEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="iconEyeOff" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.423M6.2 6.2A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.98 9.98 0 01-4.5 5.2M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="remember" class="h-4 w-4 text-indigo-600 rounded border-gray-300">
                            <span class="ml-2 text-gray-700">Remember me</span>
                        </label>

                        <div>
                            <a href="{{ url('/password/reset') }}" class="text-indigo-600 hover:underline">Lupa password?</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 font-semibold shadow">
                            Masuk
                        </button>
                    </div>

                    <div class="text-center text-sm text-gray-500">
                        Belum punya akun? <a href="{{ url('/register') }}" class="text-indigo-600 hover:underline">Daftar</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pwd = document.getElementById('password');
            const toggle = document.getElementById('togglePassword');
            const iconEye = document.getElementById('iconEye');
            const iconEyeOff = document.getElementById('iconEyeOff');

            if (toggle && pwd) {
                toggle.addEventListener('click', function () {
                    if (pwd.type === 'password') {
                        pwd.type = 'text';
                        iconEye.classList.add('hidden');
                        iconEyeOff.classList.remove('hidden');
                    } else {
                        pwd.type = 'password';
                        iconEye.classList.remove('hidden');
                        iconEyeOff.classList.add('hidden');
                    }
                });
            }
        });
    </script>
@endsection
