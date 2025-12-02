@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Jual Kendaraan Anda</h1>
            <p class="mt-2 text-gray-600">Isi form di bawah ini untuk menjual kendaraan Anda melalui lelang</p>
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
                <h2 class="text-xl font-semibold mb-4">Informasi Dasar</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Iklan *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Contoh: Toyota Avanza 2020 Veloz 1.5 AT">
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kendaraan *</label>
                        <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Pilih Jenis</option>
                            <option value="mobil" {{ old('type') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                            <option value="motor" {{ old('type') == 'motor' ? 'selected' : '' }}>Motor</option>
                        </select>
                    </div>

                    <!-- Brand -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Merek *</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="Contoh: Toyota, Honda">
                    </div>

                    <!-- Model -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Model *</label>
                        <input type="text" name="model" value="{{ old('model') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="Contoh: Avanza, Beat">
                    </div>

                    <!-- Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun *</label>
                        <input type="number" name="year" value="{{ old('year') }}" required min="1900" max="{{ date('Y') + 1 }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="{{ date('Y') }}">
                    </div>

                    <!-- Condition -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi *</label>
                        <select name="condition" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Pilih Kondisi</option>
                            <option value="baru" {{ old('condition') == 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="bekas" {{ old('condition') == 'bekas' ? 'selected' : '' }}>Bekas</option>
                        </select>
                    </div>

                    <!-- Mileage -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kilometer (KM) *</label>
                        <input type="number" name="mileage" value="{{ old('mileage') }}" required min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="Contoh: 50000">
                    </div>
                </div>
            </div>

            <!-- Spesifikasi -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold mb-4">Spesifikasi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Transmission -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transmisi *</label>
                        <select name="transmission" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Pilih Transmisi</option>
                            <option value="manual" {{ old('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="automatic" {{ old('transmission') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                            <option value="semi-automatic" {{ old('transmission') == 'semi-automatic' ? 'selected' : '' }}>Semi-Automatic</option>
                        </select>
                    </div>

                    <!-- Fuel Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bahan Bakar *</label>
                        <select name="fuel_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Pilih Bahan Bakar</option>
                            <option value="bensin" {{ old('fuel_type') == 'bensin' ? 'selected' : '' }}>Bensin</option>
                            <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="listrik" {{ old('fuel_type') == 'listrik' ? 'selected' : '' }}>Listrik</option>
                            <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>

                    <!-- Color -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Warna *</label>
                        <input type="text" name="color" value="{{ old('color') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="Contoh: Hitam, Putih">
                    </div>

                    <!-- Starting Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Awal (Rp) *</label>
                        <input type="number" name="starting_price" value="{{ old('starting_price') }}" required min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="Contoh: 150000000">
                    </div>
                </div>
            </div>

            <!-- Lokasi -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold mb-4">Lokasi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Province -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi *</label>
                        <select name="province_id" id="province" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- City -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten *</label>
                        <select name="city_id" id="city" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Pilih Kota</option>
                        </select>
                    </div>

                    <!-- District -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                        <select name="district_id" id="district" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>

                    <!-- Sub District -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelurahan/Desa</label>
                        <select name="sub_district_id" id="sub_district" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Pilih Kelurahan</option>
                        </select>
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                        <textarea name="address" rows="3" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="Jalan, nomor rumah, RT/RW, dll">{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold mb-4">Deskripsi</h2>
                <textarea name="description" rows="6" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    placeholder="Ceritakan tentang kendaraan Anda, kondisi, kelengkapan, dll">{{ old('description') }}</textarea>
            </div>

            <!-- Upload Foto -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold mb-4">Foto Kendaraan *</h2>
                <p class="text-sm text-gray-600 mb-4">Upload minimal 1 foto, maksimal 10 foto (JPEG/PNG, max 2MB per file)</p>
                
                <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/jpg" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                
                <p class="text-xs text-gray-500 mt-2">Foto pertama akan menjadi foto utama</p>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4">
                <button type="submit" 
                    class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
                    Submit untuk Direview
                </button>
                <a href="{{ route('my.ads') }}" 
                    class="px-6 py-3 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Cascade Dropdown for Location
const provinceSelect = document.getElementById('province');
const citySelect = document.getElementById('city');
const districtSelect = document.getElementById('district');
const subDistrictSelect = document.getElementById('sub_district');

// When province changes, load cities
provinceSelect.addEventListener('change', function() {
    const provinceId = this.value;
    
    // Reset dependent dropdowns
    citySelect.innerHTML = '<option value="">Pilih Kota</option>';
    districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
    subDistrictSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
    
    if (!provinceId) return;
    
    // Fetch cities
    fetch(`/api/locations/cities/${provinceId}`)
        .then(response => response.json())
        .then(response => {
            if (response.status === 'success' && response.data) {
                response.data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    citySelect.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error loading cities:', error));
});

// When city changes, load districts
citySelect.addEventListener('change', function() {
    const cityId = this.value;
    
    // Reset dependent dropdowns
    districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
    subDistrictSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
    
    if (!cityId) return;
    
    // Fetch districts
    fetch(`/api/locations/districts/${cityId}`)
        .then(response => response.json())
        .then(response => {
            if (response.status === 'success' && response.data) {
                response.data.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error loading districts:', error));
});

// When district changes, load sub districts
districtSelect.addEventListener('change', function() {
    const districtId = this.value;
    
    // Reset dependent dropdown
    subDistrictSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
    
    if (!districtId) return;
    
    // Fetch sub districts
    fetch(`/api/locations/sub-districts/${districtId}`)
        .then(response => response.json())
        .then(response => {
            if (response.status === 'success' && response.data) {
                response.data.forEach(subDistrict => {
                    const option = document.createElement('option');
                    option.value = subDistrict.id;
                    option.textContent = subDistrict.name;
                    subDistrictSelect.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error loading sub districts:', error));
});
</script>
@endsection
