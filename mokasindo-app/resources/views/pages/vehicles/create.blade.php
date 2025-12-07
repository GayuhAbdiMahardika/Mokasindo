@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('vehicles.create.title') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('vehicles.create.subtitle') }}</p>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6 space-y-6">
            @csrf

            <!-- Informasi Dasar -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('vehicles.create.basic_info') }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.title') }}</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="{{ __('vehicles.create.placeholders.title') }}">
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.type') }}</label>
                        <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">{{ __('vehicles.create.placeholders.type') }}</option>
                            <option value="mobil" {{ old('type') == 'mobil' ? 'selected' : '' }}>{{ __('vehicles.create.options.car') }}</option>
                            <option value="motor" {{ old('type') == 'motor' ? 'selected' : '' }}>{{ __('vehicles.create.options.motorcycle') }}</option>
                        </select>
                    </div>

                    <!-- Brand -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.brand') }}</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="{{ __('vehicles.create.placeholders.brand') }}">
                    </div>

                    <!-- Model -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.model') }}</label>
                        <input type="text" name="model" value="{{ old('model') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="{{ __('vehicles.create.placeholders.model') }}">
                    </div>

                    <!-- Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.year') }}</label>
                        <input type="number" name="year" value="{{ old('year') }}" required min="1900" max="{{ date('Y') + 1 }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="{{ date('Y') }}">
                    </div>

                    <!-- Condition -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.condition') }}</label>
                        <select name="condition" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">{{ __('vehicles.create.placeholders.condition') }}</option>
                            <option value="baru" {{ old('condition') == 'baru' ? 'selected' : '' }}>{{ __('vehicles.create.options.new') }}</option>
                            <option value="bekas" {{ old('condition') == 'bekas' ? 'selected' : '' }}>{{ __('vehicles.create.options.used') }}</option>
                        </select>
                    </div>

                    <!-- Mileage -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.mileage') }}</label>
                        <input type="number" name="mileage" value="{{ old('mileage') }}" required min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="{{ __('vehicles.create.placeholders.mileage') }}">
                    </div>
                </div>
            </div>

            <!-- Spesifikasi -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('vehicles.create.specs') }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Transmission -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.transmission') }}</label>
                        <select name="transmission" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">{{ __('vehicles.create.placeholders.transmission') }}</option>
                            <option value="manual" {{ old('transmission') == 'manual' ? 'selected' : '' }}>{{ __('vehicles.create.options.manual') }}</option>
                            <option value="automatic" {{ old('transmission') == 'automatic' ? 'selected' : '' }}>{{ __('vehicles.create.options.automatic') }}</option>
                            <option value="semi-automatic" {{ old('transmission') == 'semi-automatic' ? 'selected' : '' }}>{{ __('vehicles.create.options.semi_automatic') }}</option>
                        </select>
                    </div>

                    <!-- Fuel Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.fuel_type') }}</label>
                        <select name="fuel_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">{{ __('vehicles.create.placeholders.fuel_type') }}</option>
                            <option value="bensin" {{ old('fuel_type') == 'bensin' ? 'selected' : '' }}>{{ __('vehicles.create.options.petrol') }}</option>
                            <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>{{ __('vehicles.create.options.diesel') }}</option>
                            <option value="listrik" {{ old('fuel_type') == 'listrik' ? 'selected' : '' }}>{{ __('vehicles.create.options.electric') }}</option>
                            <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>{{ __('vehicles.create.options.hybrid') }}</option>
                        </select>
                    </div>

                    <!-- Color -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.color') }}</label>
                        <input type="text" name="color" value="{{ old('color') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="{{ __('vehicles.create.placeholders.color') }}">
                    </div>

                    <!-- Starting Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.starting_price') }}</label>
                        <input type="number" name="starting_price" value="{{ old('starting_price') }}" required min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="{{ __('vehicles.create.placeholders.starting_price') }}">
                    </div>
                </div>
            </div>

            <!-- Lokasi -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('vehicles.create.location') }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Province -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.province') }}</label>
                        <select id="province" name="province" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">{{ __('vehicles.create.placeholders.province') }}</option>
                        </select>
                    </div>

                    <!-- City -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.city') }}</label>
                        <select id="city" name="city" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">{{ __('vehicles.create.placeholders.city') }}</option>
                        </select>
                    </div>

                    <!-- District -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.district') }}</label>
                        <select id="district" name="district"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">{{ __('vehicles.create.placeholders.district') }}</option>
                        </select>
                    </div>

                    <!-- Sub District -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.sub_district') }}</label>
                        <select id="sub_district" name="sub_district"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">{{ __('vehicles.create.placeholders.sub_district') }}</option>
                        </select>
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('vehicles.create.fields.address') }}</label>
                        <textarea name="address" rows="3" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="{{ __('vehicles.create.placeholders.address') }}">{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('vehicles.create.description') }}</h2>
                <textarea name="description" rows="6" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    placeholder="{{ __('vehicles.create.placeholders.description') }}">{{ old('description') }}</textarea>
            </div>

            <!-- Upload Foto -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('vehicles.create.photos.title') }}</h2>
                <p class="text-sm text-gray-600 mb-4">{{ __('vehicles.create.photos.helper') }}</p>
                
                <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/jpg" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                
                <p class="text-xs text-gray-500 mt-2">{{ __('vehicles.create.photos.note') }}</p>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4">
                <button type="submit" 
                    class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
                    {{ __('vehicles.create.actions.submit') }}
                </button>
                <a href="{{ route('my.ads') }}" 
                    class="px-6 py-3 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition">
                    {{ __('vehicles.create.actions.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = 'https://kanglerian.my.id/api-wilayah-indonesia/api';

    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const districtSelect = document.getElementById('district');
    const subDistrictSelect = document.getElementById('sub_district');

    const placeholders = {
        province: @json(__('vehicles.create.placeholders.province')),
        city: @json(__('vehicles.create.placeholders.city')),
        district: @json(__('vehicles.create.placeholders.district')),
        subDistrict: @json(__('vehicles.create.placeholders.sub_district'))
    };

    const oldProvince = @json(old('province'));
    const oldCity = @json(old('city'));
    const oldDistrict = @json(old('district'));
    const oldSubDistrict = @json(old('sub_district'));

    const setOptions = (select, items, placeholder) => {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item.name;
            option.dataset.id = item.id;
            option.textContent = item.name;
            select.appendChild(option);
        });
    };

    const resetBelow = (startSelect) => {
        if (startSelect === provinceSelect) {
            setOptions(citySelect, [], placeholders.city);
            setOptions(districtSelect, [], placeholders.district);
            setOptions(subDistrictSelect, [], placeholders.subDistrict);
        } else if (startSelect === citySelect) {
            setOptions(districtSelect, [], placeholders.district);
            setOptions(subDistrictSelect, [], placeholders.subDistrict);
        } else if (startSelect === districtSelect) {
            setOptions(subDistrictSelect, [], placeholders.subDistrict);
        }
    };

    const loadProvinces = async () => {
        try {
            const res = await fetch(`${API_BASE}/provinces.json`);
            const data = await res.json();
            setOptions(provinceSelect, data, placeholders.province);

            if (oldProvince) {
                const match = Array.from(provinceSelect.options).find(opt => opt.value === oldProvince);
                if (match) {
                    match.selected = true;
                    await loadCities(match.dataset.id, oldCity);
                }
            }
        } catch (err) {
            console.error('Gagal memuat provinsi:', err);
        }
    };

    const loadCities = async (provinceId, preselectName = '') => {
        resetBelow(provinceSelect);
        if (!provinceId) return;
        try {
            const res = await fetch(`${API_BASE}/regencies/${provinceId}.json`);
            const data = await res.json();
            setOptions(citySelect, data, placeholders.city);

            if (preselectName) {
                const match = Array.from(citySelect.options).find(opt => opt.value === preselectName);
                if (match) {
                    match.selected = true;
                    await loadDistricts(match.dataset.id, oldDistrict);
                }
            }
        } catch (err) {
            console.error('Gagal memuat kota/kabupaten:', err);
        }
    };

    const loadDistricts = async (cityId, preselectName = '') => {
        resetBelow(citySelect);
        if (!cityId) return;
        try {
            const res = await fetch(`${API_BASE}/districts/${cityId}.json`);
            const data = await res.json();
            setOptions(districtSelect, data, placeholders.district);

            if (preselectName) {
                const match = Array.from(districtSelect.options).find(opt => opt.value === preselectName);
                if (match) {
                    match.selected = true;
                    await loadSubDistricts(match.dataset.id, oldSubDistrict);
                }
            }
        } catch (err) {
            console.error('Gagal memuat kecamatan:', err);
        }
    };

    const loadSubDistricts = async (districtId, preselectName = '') => {
        resetBelow(districtSelect);
        if (!districtId) return;
        try {
            const res = await fetch(`${API_BASE}/villages/${districtId}.json`);
            const data = await res.json();
            setOptions(subDistrictSelect, data, placeholders.subDistrict);

            if (preselectName) {
                const match = Array.from(subDistrictSelect.options).find(opt => opt.value === preselectName);
                if (match) {
                    match.selected = true;
                }
            }
        } catch (err) {
            console.error('Gagal memuat kelurahan:', err);
        }
    };

    provinceSelect.addEventListener('change', async (e) => {
        const selectedId = e.target.selectedOptions[0]?.dataset.id || '';
        await loadCities(selectedId);
    });

    citySelect.addEventListener('change', async (e) => {
        const selectedId = e.target.selectedOptions[0]?.dataset.id || '';
        await loadDistricts(selectedId);
    });

    districtSelect.addEventListener('change', async (e) => {
        const selectedId = e.target.selectedOptions[0]?.dataset.id || '';
        await loadSubDistricts(selectedId);
    });

    loadProvinces();
});

 </script>
@endsection
