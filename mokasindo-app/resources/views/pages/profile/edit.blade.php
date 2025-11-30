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
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5" placeholder="0812...">
                        </div>

                        <div class="col-span-2 pt-4 border-t border-gray-50">
                            <h3 class="text-base font-bold text-gray-900 mb-4">Detail Alamat</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                    <select name="province_id" id="province_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 bg-white">
                                        <option value="" disabled selected>Pilih Provinsi</option>
                                    </select>
                                    @error('province_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                                    <select name="city_id" id="city_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 bg-white">
                                        <option value="" disabled selected>Pilih Kota/Kabupaten</option>
                                    </select>
                                    @error('city_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                                    <select name="district_id" id="district_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 bg-white">
                                        <option value="" disabled selected>Pilih Kecamatan</option>
                                    </select>
                                    @error('district_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan</label>
                                    <select name="sub_district_id" id="sub_district_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 bg-white">
                                        <option value="" disabled selected>Pilih Kelurahan</option>
                                    </select>
                                    @error('sub_district_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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

    // 2. Load Wilayah Indonesia (Provinsi -> Kota -> Kecamatan -> Kelurahan)
    document.addEventListener('DOMContentLoaded', function () {
        const provinceSelect = document.getElementById('province_id');
        const citySelect = document.getElementById('city_id');
        const districtSelect = document.getElementById('district_id');
        const subDistrictSelect = document.getElementById('sub_district_id');

        // Data dari Database (Untuk edit mode)
        const oldProvince = @json(old('province_id', $user->province_id));
        const oldCity = @json(old('city_id', $user->city_id));
        const oldDistrict = @json(old('district_id', $user->district_id));
        const oldSubDistrict = @json(old('sub_district_id', $user->sub_district_id));

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

        // LOAD PROVINCES
        if(provinceSelect){
            setLoading(provinceSelect, 'Memuat provinsi...');
            fetch('https://kanglerian.my.id/api-wilayah-indonesia/api/provinces.json')
                .then(r => r.json())
                .then(provinces => {
                    provinces.sort((a,b) => getName(a).localeCompare(getName(b), 'id'));
                    provinceSelect.innerHTML = '<option value="" disabled selected>Pilih Provinsi</option>';
                    
                    provinces.forEach(p => {
                        const id = getId(p);
                        const name = getName(p);
                        const opt = document.createElement('option');
                        opt.value = id;
                        opt.textContent = name;
                        // Auto-select jika ada data user
                        if(oldProvince && String(oldProvince) === String(id)) opt.selected = true;
                        provinceSelect.appendChild(opt);
                    });

                    if(oldProvince && citySelect){
                        loadRegencies(String(oldProvince));
                    }
                })
                .catch(err => setLoading(provinceSelect, 'Gagal memuat'));
        }

        // LOAD CITIES
        function loadRegencies(provinceId){
            if(!citySelect) return;
            clearSelect(citySelect, 'Memuat kota...');
            
            fetch(`https://kanglerian.my.id/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
                .then(r => r.json())
                .then(regencies => {
                    regencies.sort((a,b) => (a.name ?? '').localeCompare(b.name ?? '', 'id'));
                    citySelect.innerHTML = '<option value="" disabled selected>Pilih Kota/Kabupaten</option>';
                    
                    regencies.forEach(r => {
                        const opt = document.createElement('option');
                        opt.value = r.id ?? r.kabupaten_id ?? r.code ?? r.name;
                        opt.textContent = r.name ?? r.title ?? String(r);
                        if(oldCity && String(oldCity) === String(opt.value)) opt.selected = true;
                        citySelect.appendChild(opt);
                    });

                    if(oldCity){
                        loadDistricts(String(oldCity));
                    }
                });
        }

        // LOAD DISTRICTS
        function loadDistricts(cityId){
            if(!districtSelect) return;
            clearSelect(districtSelect, 'Memuat kecamatan...');

            fetch(`https://kanglerian.my.id/api-wilayah-indonesia/api/districts/${cityId}.json`)
                .then(r => r.json())
                .then(districts => {
                    districts.sort((a,b) => (a.name ?? '').localeCompare(b.name ?? '', 'id'));
                    districtSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';
                    
                    districts.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d.id ?? d.district_id ?? d.code ?? d.name;
                        opt.textContent = d.name ?? d.title ?? String(d);
                        if(oldDistrict && String(oldDistrict) === String(opt.value)) opt.selected = true;
                        districtSelect.appendChild(opt);
                    });

                    if(oldDistrict){
                        loadVillages(String(oldDistrict));
                    }
                });
        }

        // LOAD VILLAGES
        function loadVillages(districtId){
            if(!subDistrictSelect) return;
            clearSelect(subDistrictSelect, 'Memuat kelurahan...');

            fetch(`https://kanglerian.my.id/api-wilayah-indonesia/api/villages/${districtId}.json`)
                .then(r => r.json())
                .then(villages => {
                    villages.sort((a,b) => (a.name ?? '').localeCompare(b.name ?? '', 'id'));
                    subDistrictSelect.innerHTML = '<option value="" disabled selected>Pilih Kelurahan</option>';
                    
                    villages.forEach(v => {
                        const opt = document.createElement('option');
                        opt.value = v.id ?? v.village_id ?? v.code ?? v.name;
                        opt.textContent = v.name ?? v.title ?? String(v);
                        if(oldSubDistrict && String(oldSubDistrict) === String(opt.value)) opt.selected = true;
                        subDistrictSelect.appendChild(opt);
                    });
                });
        }

        // Event Listeners (Change)
        provinceSelect?.addEventListener('change', function(){ loadRegencies(this.value); });
        citySelect?.addEventListener('change', function(){ loadDistricts(this.value); });
        districtSelect?.addEventListener('change', function(){ loadVillages(this.value); });
    });
</script>
@endsection