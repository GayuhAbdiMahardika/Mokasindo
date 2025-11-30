@extends('layouts.app')

@section('content')
    <section class="relative bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-[7rem]">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Daftar Akun</h1>
                <p class="text-indigo-100 max-w-2xl mx-auto">
                    Buat akun untuk memulai lelang kendaraan Anda. Lengkapi data dan alamat dengan benar.
                </p>
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

                @if($errors->any())
                    <div class="mb-4 p-3 rounded bg-red-50 border border-red-100 text-red-700 text-sm">
                        Terdapat beberapa kesalahan pada input Anda. Silakan periksa kembali form di bawah.
                    </div>
                @endif

                <form method="POST" action="{{ route('register.process') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="Nama lengkap (sesuai KTP)"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="email@domain.com"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                   placeholder="0812xxxxxxxx (tanpa +62)"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" required
                                   placeholder="Minimal 6 karakter"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required
                                   placeholder="Ketik ulang password"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    {{-- PROVINSI DARI DATABASE --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                            <select name="province_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled {{ old('province_id') ? '' : 'selected' }}>Pilih provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}"
                                        {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- KOTA/KABUPATEN --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kota/Kabupaten</label>
                            <select name="city_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled {{ old('city_id') ? '' : 'selected' }}>Pilih kota/kabupaten</option>
                                @foreach($cities ?? [] as $city)
                                    <option value="{{ $city->id }}"
                                        {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- KECAMATAN & KELURAHAN --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kecamatan</label>
                            <select name="district_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled {{ old('district_id') ? '' : 'selected' }}>Pilih kecamatan</option>
                                @foreach($districts ?? [] as $district)
                                    <option value="{{ $district->id }}"
                                        {{ old('district_id') == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('district_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kelurahan</label>
                            <select name="sub_district_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled {{ old('sub_district_id') ? '' : 'selected' }}>Pilih kelurahan</option>
                                @foreach($subDistricts ?? [] as $sub)
                                    <option value="{{ $sub->id }}"
                                        {{ old('sub_district_id') == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sub_district_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kode Pos</label>
                            <select name="postal_code" required
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled {{ old('postal_code') ? '' : 'selected' }}>Pilih kode pos</option>
                                @if(old('postal_code'))
                                    <option value="{{ old('postal_code') }}" selected>{{ old('postal_code') }}</option>
                                @endif
                            </select>
                            @error('postal_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat lengkap</label>
                            <textarea name="address" rows="3"
                                      placeholder="Alamat jalan, nomor gedung, RT/RW, dsb"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address') }}</textarea>
                            @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 font-semibold shadow">
                            Daftar
                        </button>
                    </div>

                    <div class="text-center text-sm text-gray-500">
                        Sudah punya akun?
                        <a href="{{ route('login.form') }}" class="text-indigo-600 hover:underline">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- JS: ambil kota/kecamatan/kelurahan dari DB via route Laravel --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const provinceSelect    = document.querySelector('select[name="province_id"]');
            const citySelect        = document.querySelector('select[name="city_id"]');
            const districtSelect    = document.querySelector('select[name="district_id"]');
            const subDistrictSelect = document.querySelector('select[name="sub_district_id"]');
            const postalSelect      = document.querySelector('select[name="postal_code"]');

            const citiesUrl        = "{{ route('wilayah.cities') }}";
            const districtsUrl     = "{{ route('wilayah.districts') }}";
            const subDistrictsUrl  = "{{ route('wilayah.subdistricts') }}";
            let subDistrictPostalMap = {};

            function setLoading(select, text){
                if(!select) return;
                select.innerHTML = `<option value="" disabled selected>${text}</option>`;
            }

            function clearSelect(select, placeholder){
                if(!select) return;
                select.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;
            }

            function populateOptions(select, items, oldValue){
                if(!select) return;
                select.innerHTML = '<option value="" disabled selected>Pilih</option>';
                items.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.name;
                    if (oldValue && String(oldValue) === String(item.id)) {
                        opt.selected = true;
                    }
                    select.appendChild(opt);
                });
            }

            provinceSelect?.addEventListener('change', function () {
                const provinceId = this.value;
                if (!provinceId) return;

                setLoading(citySelect, 'Memuat kota/kabupaten...');
                clearSelect(districtSelect, 'Pilih kecamatan');
                clearSelect(subDistrictSelect, 'Pilih kelurahan');

                fetch(`${citiesUrl}?province_id=${provinceId}`)
                    .then(r => r.json())
                    .then(cities => {
                        populateOptions(citySelect, cities, null);
                    })
                    .catch(err => {
                        console.error(err);
                        clearSelect(citySelect, 'Gagal memuat kota/kabupaten');
                    });
            });

            citySelect?.addEventListener('change', function () {
                const cityId = this.value;
                if (!cityId) return;

                setLoading(districtSelect, 'Memuat kecamatan...');
                clearSelect(subDistrictSelect, 'Pilih kelurahan');

                fetch(`${districtsUrl}?city_id=${cityId}`)
                    .then(r => r.json())
                    .then(districts => {
                        populateOptions(districtSelect, districts, null);
                    })
                    .catch(err => {
                        console.error(err);
                        clearSelect(districtSelect, 'Gagal memuat kecamatan');
                    });
            });

            districtSelect?.addEventListener('change', function () {
                const districtId = this.value;
                if (!districtId) return;

                setLoading(subDistrictSelect, 'Memuat kelurahan...');
                clearSelect(postalSelect, 'Pilih kode pos');

                fetch(`${subDistrictsUrl}?district_id=${districtId}`)
                    .then(r => r.json())
                    .then(subs => {
                        subDistrictPostalMap = {};
                        // isi dropdown kelurahan + simpan map id -> postal_code
                        subDistrictSelect.innerHTML = '<option value="" disabled selected>Pilih kelurahan</option>';

                        subs.forEach(sub => {
                            subDistrictPostalMap[sub.id] = sub.postal_code || '';

                            const opt = document.createElement('option');
                            opt.value = sub.id;
                            opt.textContent = sub.name;
                            subDistrictSelect.appendChild(opt);
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        clearSelect(subDistrictSelect, 'Gagal memuat kelurahan');
                    });
            });

            subDistrictSelect?.addEventListener('change', function () {
                const subId = this.value;
                if (!subId || !postalSelect) return;

                const code = subDistrictPostalMap[subId];

                postalSelect.innerHTML = '<option value="" disabled>Pilih kode pos</option>';

                if (code) {
                    const opt = document.createElement('option');
                    opt.value = code;
                    opt.textContent = code;
                    opt.selected = true;
                    postalSelect.appendChild(opt);
                }
            });
        });
    </script>
@endsection
