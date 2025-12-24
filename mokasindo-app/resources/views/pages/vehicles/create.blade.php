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
document.addEventListener('DOMContentLoaded', function () {
    const texts = {
        loadingProvince: "Memuat provinsi...",
        loadingCity: "Memuat kota...",
        loadingDistrict: "Memuat kecamatan...",
        loadingSubDistrict: "Memuat kelurahan...",
        chooseProvince: @json(__('vehicles.create.placeholders.province')),
        chooseCity: @json(__('vehicles.create.placeholders.city')),
        chooseDistrict: @json(__('vehicles.create.placeholders.district')),
        chooseSubDistrict: @json(__('vehicles.create.placeholders.sub_district')),
        errorProvince: "Gagal memuat provinsi",
        errorCity: "Gagal memuat kota",
        errorDistrict: "Gagal memuat kecamatan",
        errorSubDistrict: "Gagal memuat kelurahan",
        noData: "Data tidak tersedia"
    };

    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const districtSelect = document.getElementById('district');
    const subDistrictSelect = document.getElementById('sub_district');

    const oldProvince = @json(old('province'));
    const oldCity = @json(old('city'));
    const oldDistrict = @json(old('district'));
    const oldSubDistrict = @json(old('sub_district'));

    function getId(p){ return p.id ?? p.code ?? p.kode ?? p.province_id ?? p.kd ?? p.name ?? ''; }
    function getName(p){ return p.name ?? p.province ?? p.nama ?? String(p); }

    function setLoading(select, text){
        if(!select) return;
        select.innerHTML = `<option value="" disabled selected>${text}</option>`;
    }

    function clearSelect(select, placeholder){
        if(!select) return;
        select.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;
    }

    // load provinces
    if(provinceSelect){
        setLoading(provinceSelect, texts.loadingProvince);
        
        const apis = [
            'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json',
            'https://kanglerian.my.id/api-wilayah-indonesia/api/provinces.json',
            'https://ibnux.github.io/data-indonesia/provinsi.json'
        ];
        
        async function loadProvincesWithFallback() {
            for (const apiUrl of apis) {
                try {
                    console.log('Trying API:', apiUrl);
                    const response = await fetch(apiUrl);
                    if (!response.ok) continue;
                    
                    const provinces = await response.json();
                    if(!Array.isArray(provinces) || provinces.length === 0){
                        console.warn('Invalid data from', apiUrl);
                        continue;
                    }
                    
                    console.log('Success loading provinces from:', apiUrl);
                    provinces.sort((a,b) => getName(a).localeCompare(getName(b), 'id'));
                    provinceSelect.innerHTML = `<option value="" disabled selected>${texts.chooseProvince}</option>`;
                    provinces.forEach(p => {
                        const id = getId(p);
                        const name = getName(p);
                        const opt = document.createElement('option');
                        opt.value = name;
                        opt.textContent = name;
                        opt.dataset.id = id;
                        if(oldProvince && String(oldProvince) === String(name)) opt.selected = true;
                        provinceSelect.appendChild(opt);
                    });

                    if(oldProvince && citySelect){
                        const selectedOption = provinceSelect.querySelector(`option[value="${oldProvince}"]`);
                        if(selectedOption && selectedOption.dataset.id){
                            loadRegencies(selectedOption.dataset.id);
                        }
                    }
                    return;
                } catch (err) {
                    console.warn('Failed to load from', apiUrl, err);
                }
            }
            console.error('All province APIs failed');
            setLoading(provinceSelect, texts.errorProvince + ' - Silakan refresh halaman');
        }
        
        loadProvincesWithFallback();
    }

    // load regencies when province changes
    async function loadRegencies(provinceId){
        if(!citySelect) return;
        clearSelect(citySelect, texts.loadingCity);
        clearSelect(districtSelect, texts.chooseDistrict);
        clearSelect(subDistrictSelect, texts.chooseSubDistrict);

        const apis = [
            `https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`,
            `https://kanglerian.my.id/api-wilayah-indonesia/api/regencies/${provinceId}.json`,
            `https://ibnux.github.io/data-indonesia/kabupaten/${provinceId}.json`
        ];
        
        for (const apiUrl of apis) {
            try {
                const response = await fetch(apiUrl);
                if (!response.ok) continue;
                
                const regencies = await response.json();
                if(!Array.isArray(regencies) || regencies.length === 0) continue;
                
                regencies.sort((a,b) => (a.name ?? '').localeCompare(b.name ?? '', 'id'));
                citySelect.innerHTML = `<option value="" disabled selected>${texts.chooseCity}</option>`;
                regencies.forEach(r => {
                    const opt = document.createElement('option');
                    const cityName = r.name ?? r.title ?? String(r);
                    opt.value = cityName;
                    opt.textContent = cityName;
                    opt.dataset.id = r.id ?? r.kabupaten_id ?? r.code;
                    if(oldCity && String(oldCity) === String(cityName)) opt.selected = true;
                    citySelect.appendChild(opt);
                });

                if(oldCity){
                    const selectedOption = citySelect.querySelector(`option[value="${oldCity}"]`);
                    if(selectedOption && selectedOption.dataset.id){
                        loadDistricts(selectedOption.dataset.id);
                    }
                }
                return;
            } catch (err) {
                console.warn('Failed to load regencies from', apiUrl);
            }
        }
        clearSelect(citySelect, texts.errorCity);
    }

    // load districts (kecamatan) when city/regency selected
    async function loadDistricts(cityId){
        if(!districtSelect) return;
        clearSelect(districtSelect, texts.loadingDistrict);
        clearSelect(subDistrictSelect, texts.chooseSubDistrict);

        const apis = [
            `https://www.emsifa.com/api-wilayah-indonesia/api/districts/${cityId}.json`,
            `https://kanglerian.my.id/api-wilayah-indonesia/api/districts/${cityId}.json`,
            `https://ibnux.github.io/data-indonesia/kecamatan/${cityId}.json`
        ];
        
        for (const apiUrl of apis) {
            try {
                const response = await fetch(apiUrl);
                if (!response.ok) continue;
                
                const districts = await response.json();
                if(!Array.isArray(districts) || districts.length === 0) continue;
                
                districts.sort((a,b) => (a.name ?? '').localeCompare(b.name ?? '', 'id'));
                districtSelect.innerHTML = `<option value="" disabled selected>${texts.chooseDistrict}</option>`;
                districts.forEach(d => {
                    const opt = document.createElement('option');
                    const districtName = d.name ?? d.title ?? String(d);
                    opt.value = districtName;
                    opt.textContent = districtName;
                    opt.dataset.id = d.id ?? d.district_id ?? d.code;
                    if(oldDistrict && String(oldDistrict) === String(districtName)) opt.selected = true;
                    districtSelect.appendChild(opt);
                });

                if(oldDistrict){
                    const selectedOption = districtSelect.querySelector(`option[value="${oldDistrict}"]`);
                    if(selectedOption && selectedOption.dataset.id){
                        loadVillages(selectedOption.dataset.id);
                    }
                }
                return;
            } catch (err) {
                console.warn('Failed to load districts from', apiUrl);
            }
        }
        clearSelect(districtSelect, texts.errorDistrict);
    }

    // load villages (kelurahan) when district selected
    async function loadVillages(districtId){
        if(!subDistrictSelect) return;
        clearSelect(subDistrictSelect, texts.loadingSubDistrict);

        const apis = [
            `https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`,
            `https://kanglerian.my.id/api-wilayah-indonesia/api/villages/${districtId}.json`,
            `https://ibnux.github.io/data-indonesia/kelurahan/${districtId}.json`
        ];
        
        for (const apiUrl of apis) {
            try {
                const response = await fetch(apiUrl);
                if (!response.ok) continue;
                
                const villages = await response.json();
                if(!Array.isArray(villages) || villages.length === 0) continue;
                
                villages.sort((a,b) => (a.name ?? '').localeCompare(b.name ?? '', 'id'));
                subDistrictSelect.innerHTML = `<option value="" disabled selected>${texts.chooseSubDistrict}</option>`;
                villages.forEach(v => {
                    const opt = document.createElement('option');
                    const villageName = v.name ?? v.title ?? String(v);
                    opt.value = villageName;
                    opt.textContent = villageName;
                    if(oldSubDistrict && String(oldSubDistrict) === String(villageName)) opt.selected = true;
                    subDistrictSelect.appendChild(opt);
                });
                return;
            } catch (err) {
                console.warn('Failed to load villages from', apiUrl);
            }
        }
        clearSelect(subDistrictSelect, texts.errorSubDistrict);
    }

    // Event listeners
    if(provinceSelect){
        provinceSelect.addEventListener('change', function(){
            const selectedOption = this.options[this.selectedIndex];
            const provinceId = selectedOption.dataset.id || this.value;
            if(provinceId) loadRegencies(provinceId);
        });
    }

    if(citySelect){
        citySelect.addEventListener('change', function(){
            const selectedOption = this.options[this.selectedIndex];
            const cityId = selectedOption.dataset.id || this.value;
            if(cityId) loadDistricts(cityId);
        });
    }

    if(districtSelect){
        districtSelect.addEventListener('change', function(){
            const selectedOption = this.options[this.selectedIndex];
            const districtId = selectedOption.dataset.id || this.value;
            if(districtId) loadVillages(districtId);
        });
    }
});
</script>
@endsection
