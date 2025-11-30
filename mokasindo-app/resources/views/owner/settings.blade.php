{{-- resources/views/owner/settings.blade.php --}}
@extends('layouts.owner')

@section('owner-content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-indigo-500 uppercase tracking-wider mb-1">
                    Owner • Pengaturan
                </p>
                <h2 class="text-2xl font-bold text-gray-900">Pengaturan Platform</h2>
                <p class="text-gray-500 mt-1 text-sm">
                    Kelola konfigurasi umum, lelang, member, dan notifikasi sistem Mokasindo.
                </p>
            </div>

            <div class="hidden sm:flex items-center space-x-3">
                <div class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-medium flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                    Konfigurasi Aktif
                </div>
            </div>
        </div>

        {{-- Alert sukses --}}
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle-fill text-emerald-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-emerald-800">
                            {{ session('success') }}
                        </p>
                        <p class="text-xs text-emerald-700 mt-1">
                            Perubahan sudah tersimpan dan segera digunakan oleh sistem.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form utama --}}
        <form action="{{ route('owner.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            @foreach($groups as $groupName => $settings)
                @php
                    $title = ucfirst($groupName);
                    $icon  = 'bi-sliders2-vertical';
                    $accent = 'indigo';

                    if ($groupName === 'auction') {
                        $title  = 'Pengaturan Lelang';
                        $icon   = 'bi-gavel';
                        $accent = 'amber';
                    } elseif ($groupName === 'general') {
                        $title  = 'Pengaturan Umum';
                        $icon   = 'bi-gear-wide-connected';
                        $accent = 'indigo';
                    } elseif ($groupName === 'member') {
                        $title  = 'Pengaturan Member';
                        $icon   = 'bi-people';
                        $accent = 'emerald';
                    } elseif ($groupName === 'notification') {
                        $title  = 'Pengaturan Notifikasi';
                        $icon   = 'bi-bell';
                        $accent = 'sky';
                    }
                @endphp

                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    {{-- Header group --}}
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                @if($accent === 'amber') bg-amber-100 text-amber-600
                                @elseif($accent === 'emerald') bg-emerald-100 text-emerald-600
                                @elseif($accent === 'sky') bg-sky-100 text-sky-600
                                @else bg-indigo-100 text-indigo-600 @endif">
                                <i class="bi {{ $icon }} text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">
                                    {{ $title }}
                                </h3>
                                <p class="text-xs text-gray-500">
                                    @if($groupName === 'auction')
                                        Kelola mekanisme lelang, deposit, dan durasi pembayaran.
                                    @elseif($groupName === 'general')
                                        Atur identitas aplikasi, informasi kontak, dan konfigurasi dasar.
                                    @elseif($groupName === 'member')
                                        Tentukan batasan dan aturan aktivitas member.
                                    @elseif($groupName === 'notification')
                                        Pilih channel notifikasi yang digunakan sistem.
                                    @else
                                        Pengaturan tambahan untuk sistem.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <span class="hidden md:inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-[11px] font-medium text-gray-500">
                            {{ count($settings) }} parameter
                        </span>
                    </div>
                    
                    {{-- Body group: 2 kolom, simple & jelas --}}
                    <div class="px-6 py-5 grid gap-5 md:grid-cols-2">
                        @foreach($settings as $setting)
                            @php
                                $value = old($setting->key, $setting->value);
                            @endphp

                            <div class="space-y-2">
                                {{-- Label + badge kecil --}}
                                <div class="flex items-center justify-between gap-2">
                                    <label class="text-sm font-medium text-gray-900"
                                           title="{{ $setting->key }}"> {{-- key sebagai tooltip --}}
                                        {{ $setting->description ?? $setting->key }}
                                    </label>

                                    @if(!$setting->is_public)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-50 text-red-600 border border-red-100">
                                            Internal
                                        </span>
                                    @endif
                                </div>

                                {{-- Input --}}
                                @if($setting->type === 'boolean')
                                    <select name="{{ $setting->key }}"
                                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 text-sm shadow-sm
                                                focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2">
                                        <option value="1" {{ $value == '1' || $value === 'true' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ $value == '0' || $value === 'false' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                @else
                                    <input
                                        type="text"
                                        name="{{ $setting->key }}"
                                        value="{{ $value }}"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 text-sm shadow-sm
                                            focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2"
                                    >
                                @endif

                                {{-- Info kecil --}}
                                <p class="text-[11px] text-gray-400">
                                    Tipe nilai: <span class="font-medium">{{ $setting->type }}</span>
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Tombol simpan --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="bi bi-save2-fill mr-2"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
@endsection
