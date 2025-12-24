@extends('layouts.app')

@section('content')
    <section class="relative bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-[7rem]">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('register.hero_title') }}</h1>
                <p class="text-indigo-100 max-w-2xl mx-auto">{{ __('register.hero_subtitle') }}</p>
            </div>
        </div>

        <div class="absolute bottom-0 w-full">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="#F9FAFB"/>
            </svg>
        </div>
    </section>

    <section class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl p-8 md:p-10">
                @if(session('status'))
                    <div class="mb-4 p-3 rounded bg-green-50 border border-green-100 text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ url('/register') }}" class="space-y-6">
                    @csrf

                    <div>
                           <label class="block text-sm font-medium text-gray-700">{{ __('register.name') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="{{ __('register.name_placeholder') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('register.email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                placeholder="{{ __('register.email_placeholder') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('register.phone') }}</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                placeholder="{{ __('register.phone_placeholder') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('register.password') }}</label>
                            <input type="password" name="password" required
                                   placeholder="{{ __('register.password_placeholder') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('register.password_confirmation') }}</label>
                            <input type="password" name="password_confirmation" required
                                   placeholder="{{ __('register.password_confirmation_placeholder') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('register.account_type') }}</label>
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="account_type" value="regular" {{ old('account_type', 'regular') === 'regular' ? 'checked' : '' }} required>
                                <span>{{ __('register.account_regular') }}</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="account_type" value="member" {{ old('account_type') === 'member' ? 'checked' : '' }}>
                                <span>{{ __('register.account_member', ['price' => number_format($memberPrice ?? 0, 0, ',', '.')]) }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('register.province') }}</label>
                            <select name="province" required
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled selected>{{ __('register.choose_province') }}</option>
                                {{-- Populate options dinamis dari API --}}
                            </select>
                            @error('province') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('register.city') }}</label>
                            <select name="city" required
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled selected>{{ __('register.choose_city') }}</option>
                                {{-- Populate options dinamis berdasarkan provinsi --}}
                            </select>
                            @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('register.district') }}</label>
                            <select name="district" required
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled selected>{{ __('register.choose_district') }}</option>
                                {{-- Populate options dinamis berdasarkan kota --}}
                            </select>
                            @error('district') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('register.sub_district') }}</label>
                            <select name="sub_district" required
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled selected>{{ __('register.choose_sub_district') }}</option>
                                {{-- Populate options dinamis berdasarkan kecamatan --}}
                            </select>
                            @error('sub_district') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('register.postal_code') }}</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" required
                                placeholder="{{ __('register.postal_code_placeholder') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('postal_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('register.address') }}</label>
                            <textarea name="address" rows="3" required
                                      placeholder="{{ __('register.address_placeholder') }}"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address') }}</textarea>
                            @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 font-semibold shadow">
                            {{ __('register.submit') }}
                        </button>
                    </div>

                    <div class="text-center text-sm text-gray-500">
                        {{ __('register.login_prompt') }} <a href="{{ url('/login') }}" class="text-indigo-600 hover:underline">{{ __('register.login_cta') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- populate provinces and regencies from external API -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const texts = {
            loadingProvince: "{{ __('register.loading_province') }}",
            loadingCity: "{{ __('register.loading_city') }}",
            loadingDistrict: "{{ __('register.loading_district') }}",
            loadingSubDistrict: "{{ __('register.loading_sub_district') }}",
            chooseProvince: "{{ __('register.choose_province') }}",
            chooseCity: "{{ __('register.choose_city') }}",
            chooseDistrict: "{{ __('register.choose_district') }}",
            chooseSubDistrict: "{{ __('register.choose_sub_district') }}",
            errorProvince: "{{ __('register.error_province') }}",
            errorCity: "{{ __('register.error_city') }}",
            errorDistrict: "{{ __('register.error_district') }}",
            errorSubDistrict: "{{ __('register.error_sub_district') }}",
            noData: "{{ __('register.no_data') }}"
        };

        const provinceSelect = document.querySelector('select[name="province"]');
        const citySelect = document.querySelector('select[name="city"]');
        const districtSelect = document.querySelector('select[name="district"]');
        const subDistrictSelect = document.querySelector('select[name="sub_district"]');

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
            
            // Try primary API first, fallback to alternatives
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
