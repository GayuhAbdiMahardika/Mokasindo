@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        
        <div class="md:col-span-1">
            @include('pages.profile.sidebar')
        </div>

        <div class="md:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900">Edit Profil</h2>
                    <p class="text-sm text-gray-500 mt-1">Perbarui foto dan alamat lengkap Anda.</p>
                </div>

                @php
                    $botUsername = config('services.telegram.bot_username');
                    $telegramConnected = !empty($user->telegram_chat_id);
                @endphp


                @if(session('success'))
                    <div class="px-6 pt-6">
                        <div class="bg-green-50 text-green-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2 border border-green-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
                    @csrf
                    @method('PATCH')

                    <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-gray-50">
                        <div class="relative group h-24 w-24 flex-shrink-0">
                            <div class="h-24 w-24 rounded-full bg-gray-100 overflow-hidden border-4 border-white shadow-md">
                                @if($user->avatar)
                                    <img id="avatarPreview" src="{{ asset('storage/' . $user->avatar) }}" class="h-full w-full object-cover">
                                @else
                                    <img id="avatarPreview" src="#" class="h-full w-full object-cover hidden">
                                    <div id="avatarPlaceholder" class="h-full w-full flex items-center justify-center bg-indigo-100 text-indigo-500 font-bold text-2xl">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <label for="avatar" class="absolute bottom-0 right-0 bg-white p-2 rounded-full shadow-sm border border-gray-200 cursor-pointer hover:bg-gray-50 transition text-gray-600 hover:text-indigo-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </label>
                            <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="previewImage(this)">
                        </div>
                        <div class="text-center sm:text-left">
                            <h3 class="font-bold text-gray-900 text-lg">Foto Profil</h3>
                            <p class="text-sm text-gray-500 mb-3">Format: JPG, PNG. Maks 2MB.</p>
                            <label for="avatar" class="inline-block px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 cursor-pointer transition">
                                Pilih Foto Baru
                            </label>
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 px-4 py-2.5 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bergabung Sejak</label>
                            <div class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-500 px-4 py-2.5 flex items-center gap-2 cursor-not-allowed">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $user->created_at->format('d F Y') }}
                            </div>
                        </div>

                        <div class="col-span-2">
                            <div class="flex flex-col md:flex-row md:items-center md:gap-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5" placeholder="0812...">
                                </div>
                                <div class="pt-3 md:pt-7 flex items-center gap-2">
                                    <i class="fab fa-telegram text-sky-500"></i>
                                    @if($telegramConnected)
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Terhubung</span>
                                        <span class="text-xs text-gray-500">Chat ID: {{ $user->telegram_chat_id }}</span>
                                    @elseif($botUsername)
                                        <a href="https://t.me/{{ $botUsername }}?start=user_{{ $user->id }}" target="_blank"
                                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-sky-500 hover:bg-sky-600 rounded-lg shadow-sm">
                                            <i class="fab fa-telegram-plane"></i> Connect Telegram
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-500">Set TELEGRAM_BOT_USERNAME di .env</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2 pt-4 border-t border-gray-50">
                            <h3 class="text-base font-bold text-gray-900 mb-4">Detail Alamat</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                    <select id="provinceSelect" name="province" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 bg-white">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                    @error('province') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                                    <select id="citySelect" name="city" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 bg-white">
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                    @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                                    <select id="districtSelect" name="district" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 bg-white">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                    @error('district') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan</label>
                                    <select id="subDistrictSelect" name="sub_district" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 bg-white">
                                        <option value="">Pilih Kelurahan</option>
                                    </select>
                                    @error('sub_district') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5" placeholder="12345">
                                    @error('postal_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                    <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5" placeholder="Nama Jalan, No. Rumah, RT/RW">{{ old('address', $user->address) }}</textarea>
                                    @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-50">
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Preview Image
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPlaceholder')?.classList.add('hidden');
                const preview = document.getElementById('avatarPreview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Lokasi sekarang diisi bebas; bisa ditambah integrasi fetch API eksternal di frontend jika diperlukan.
</script>
<script>
    (function() {
        const apiBase = '/api/locations';
        const provinceSelect = document.getElementById('provinceSelect');
        const citySelect = document.getElementById('citySelect');
        const districtSelect = document.getElementById('districtSelect');
        const subDistrictSelect = document.getElementById('subDistrictSelect');

        const existing = {
            province: @json(old('province', $user->province)),
            city: @json(old('city', $user->city)),
            district: @json(old('district', $user->district)),
            sub_district: @json(old('sub_district', $user->sub_district)),
        };

        function setOptions(select, items, placeholder) {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            items.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                select.appendChild(opt);
            });
        }

        function findAndSelect(select, id) {
            if (!id) return;
            const opt = Array.from(select.options).find(o => o.value == id);
            if (opt) select.value = opt.value;
        }

        async function fetchJson(url) {
            const res = await fetch(url);
            if (!res.ok) throw new Error('Failed to load');
            const data = await res.json();
            return data.data || [];
        }

        async function loadProvinces() {
            const provinces = await fetchJson(`${apiBase}/provinces`);
            setOptions(provinceSelect, provinces, 'Pilih Provinsi');
            findAndSelect(provinceSelect, existing.province);
            if (provinceSelect.value) await loadCities();
        }

        async function loadCities() {
            const provinceId = provinceSelect.value;
            setOptions(citySelect, [], 'Memuat...');
            setOptions(districtSelect, [], 'Pilih Kecamatan');
            setOptions(subDistrictSelect, [], 'Pilih Kelurahan');
            if (!provinceId) return;
            const cities = await fetchJson(`${apiBase}/cities/${provinceId}`);
            setOptions(citySelect, cities, 'Pilih Kota/Kabupaten');
            findAndSelect(citySelect, existing.city);
            if (citySelect.value) await loadDistricts();
        }

        async function loadDistricts() {
            const cityId = citySelect.value;
            setOptions(districtSelect, [], 'Memuat...');
            setOptions(subDistrictSelect, [], 'Pilih Kelurahan');
            if (!cityId) return;
            const districts = await fetchJson(`${apiBase}/districts/${cityId}`);
            setOptions(districtSelect, districts, 'Pilih Kecamatan');
            findAndSelect(districtSelect, existing.district);
            if (districtSelect.value) await loadSubDistricts();
        }

        async function loadSubDistricts() {
            const districtId = districtSelect.value;
            setOptions(subDistrictSelect, [], 'Memuat...');
            if (!districtId) return;
            const subs = await fetchJson(`${apiBase}/sub-districts/${districtId}`);
            setOptions(subDistrictSelect, subs, 'Pilih Kelurahan');
            findAndSelect(subDistrictSelect, existing.sub_district);
        }

        provinceSelect.addEventListener('change', () => {
            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            subDistrictSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            loadCities();
        });

        citySelect.addEventListener('change', () => {
            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            subDistrictSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            loadDistricts();
        });

        districtSelect.addEventListener('change', () => {
            subDistrictSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            loadSubDistricts();
        });

        loadProvinces().catch(() => {
            provinceSelect.innerHTML = '<option value="">Gagal memuat provinsi</option>';
        });
    })();
</script>
@endsection