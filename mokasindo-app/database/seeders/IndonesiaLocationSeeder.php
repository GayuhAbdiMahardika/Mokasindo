<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndonesiaLocationSeeder extends Seeder
{
    public function run(): void
    {
        // 34 Provinces of Indonesia
        $provinces = [
            ['code' => '11', 'name' => 'Aceh'],
            ['code' => '12', 'name' => 'Sumatera Utara'],
            ['code' => '13', 'name' => 'Sumatera Barat'],
            ['code' => '14', 'name' => 'Riau'],
            ['code' => '15', 'name' => 'Jambi'],
            ['code' => '16', 'name' => 'Sumatera Selatan'],
            ['code' => '17', 'name' => 'Bengkulu'],
            ['code' => '18', 'name' => 'Lampung'],
            ['code' => '19', 'name' => 'Kepulauan Bangka Belitung'],
            ['code' => '21', 'name' => 'Kepulauan Riau'],
            ['code' => '31', 'name' => 'DKI Jakarta'],
            ['code' => '32', 'name' => 'Jawa Barat'],
            ['code' => '33', 'name' => 'Jawa Tengah'],
            ['code' => '34', 'name' => 'DI Yogyakarta'],
            ['code' => '35', 'name' => 'Jawa Timur'],
            ['code' => '36', 'name' => 'Banten'],
            ['code' => '51', 'name' => 'Bali'],
            ['code' => '52', 'name' => 'Nusa Tenggara Barat'],
            ['code' => '53', 'name' => 'Nusa Tenggara Timur'],
            ['code' => '61', 'name' => 'Kalimantan Barat'],
            ['code' => '62', 'name' => 'Kalimantan Tengah'],
            ['code' => '63', 'name' => 'Kalimantan Selatan'],
            ['code' => '64', 'name' => 'Kalimantan Timur'],
            ['code' => '65', 'name' => 'Kalimantan Utara'],
            ['code' => '71', 'name' => 'Sulawesi Utara'],
            ['code' => '72', 'name' => 'Sulawesi Tengah'],
            ['code' => '73', 'name' => 'Sulawesi Selatan'],
            ['code' => '74', 'name' => 'Sulawesi Tenggara'],
            ['code' => '75', 'name' => 'Gorontalo'],
            ['code' => '76', 'name' => 'Sulawesi Barat'],
            ['code' => '81', 'name' => 'Maluku'],
            ['code' => '82', 'name' => 'Maluku Utara'],
            ['code' => '91', 'name' => 'Papua'],
            ['code' => '92', 'name' => 'Papua Barat'],
        ];

        foreach ($provinces as $province) {
            DB::table('provinces')->updateOrInsert(
                ['code' => $province['code']],
                array_merge($province, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // Cities data (100+ major cities)
        $cities = [
            // DKI Jakarta (province_code: 31)
            ['province_code' => '31', 'code' => '3171', 'name' => 'Jakarta Pusat', 'type' => 'Kota'],
            ['province_code' => '31', 'code' => '3172', 'name' => 'Jakarta Utara', 'type' => 'Kota'],
            ['province_code' => '31', 'code' => '3173', 'name' => 'Jakarta Barat', 'type' => 'Kota'],
            ['province_code' => '31', 'code' => '3174', 'name' => 'Jakarta Selatan', 'type' => 'Kota'],
            ['province_code' => '31', 'code' => '3175', 'name' => 'Jakarta Timur', 'type' => 'Kota'],
            ['province_code' => '31', 'code' => '3101', 'name' => 'Kepulauan Seribu', 'type' => 'Kabupaten'],
            // Jawa Barat (province_code: 32)
            ['province_code' => '32', 'code' => '3201', 'name' => 'Kabupaten Bogor', 'type' => 'Kabupaten'],
            ['province_code' => '32', 'code' => '3271', 'name' => 'Kota Bogor', 'type' => 'Kota'],
            ['province_code' => '32', 'code' => '3273', 'name' => 'Kota Bandung', 'type' => 'Kota'],
            ['province_code' => '32', 'code' => '3204', 'name' => 'Kabupaten Bandung', 'type' => 'Kabupaten'],
            ['province_code' => '32', 'code' => '3277', 'name' => 'Kota Cimahi', 'type' => 'Kota'],
            ['province_code' => '32', 'code' => '3275', 'name' => 'Kota Bekasi', 'type' => 'Kota'],
            ['province_code' => '32', 'code' => '3216', 'name' => 'Kabupaten Bekasi', 'type' => 'Kabupaten'],
            ['province_code' => '32', 'code' => '3276', 'name' => 'Kota Depok', 'type' => 'Kota'],
            ['province_code' => '32', 'code' => '3209', 'name' => 'Kabupaten Cirebon', 'type' => 'Kabupaten'],
            ['province_code' => '32', 'code' => '3274', 'name' => 'Kota Cirebon', 'type' => 'Kota'],
            ['province_code' => '32', 'code' => '3211', 'name' => 'Kabupaten Sumedang', 'type' => 'Kabupaten'],
            ['province_code' => '32', 'code' => '3207', 'name' => 'Kabupaten Ciamis', 'type' => 'Kabupaten'],
            ['province_code' => '32', 'code' => '3278', 'name' => 'Kota Tasikmalaya', 'type' => 'Kota'],
            ['province_code' => '32', 'code' => '3272', 'name' => 'Kota Sukabumi', 'type' => 'Kota'],
            // Jawa Tengah (province_code: 33)
            ['province_code' => '33', 'code' => '3374', 'name' => 'Kota Semarang', 'type' => 'Kota'],
            ['province_code' => '33', 'code' => '3372', 'name' => 'Kota Surakarta', 'type' => 'Kota'],
            ['province_code' => '33', 'code' => '3311', 'name' => 'Kabupaten Sukoharjo', 'type' => 'Kabupaten'],
            ['province_code' => '33', 'code' => '3313', 'name' => 'Kabupaten Karanganyar', 'type' => 'Kabupaten'],
            ['province_code' => '33', 'code' => '3312', 'name' => 'Kabupaten Wonogiri', 'type' => 'Kabupaten'],
            ['province_code' => '33', 'code' => '3310', 'name' => 'Kabupaten Klaten', 'type' => 'Kabupaten'],
            ['province_code' => '33', 'code' => '3314', 'name' => 'Kabupaten Sragen', 'type' => 'Kabupaten'],
            ['province_code' => '33', 'code' => '3375', 'name' => 'Kota Pekalongan', 'type' => 'Kota'],
            ['province_code' => '33', 'code' => '3376', 'name' => 'Kota Tegal', 'type' => 'Kota'],
            ['province_code' => '33', 'code' => '3373', 'name' => 'Kota Salatiga', 'type' => 'Kota'],
            ['province_code' => '33', 'code' => '3371', 'name' => 'Kota Magelang', 'type' => 'Kota'],
            // DI Yogyakarta (province_code: 34)
            ['province_code' => '34', 'code' => '3471', 'name' => 'Kota Yogyakarta', 'type' => 'Kota'],
            ['province_code' => '34', 'code' => '3402', 'name' => 'Kabupaten Bantul', 'type' => 'Kabupaten'],
            ['province_code' => '34', 'code' => '3403', 'name' => 'Kabupaten Gunungkidul', 'type' => 'Kabupaten'],
            ['province_code' => '34', 'code' => '3401', 'name' => 'Kabupaten Kulon Progo', 'type' => 'Kabupaten'],
            ['province_code' => '34', 'code' => '3404', 'name' => 'Kabupaten Sleman', 'type' => 'Kabupaten'],
            // Jawa Timur (province_code: 35)
            ['province_code' => '35', 'code' => '3578', 'name' => 'Kota Surabaya', 'type' => 'Kota'],
            ['province_code' => '35', 'code' => '3573', 'name' => 'Kota Malang', 'type' => 'Kota'],
            ['province_code' => '35', 'code' => '3507', 'name' => 'Kabupaten Malang', 'type' => 'Kabupaten'],
            ['province_code' => '35', 'code' => '3525', 'name' => 'Kabupaten Gresik', 'type' => 'Kabupaten'],
            ['province_code' => '35', 'code' => '3515', 'name' => 'Kabupaten Sidoarjo', 'type' => 'Kabupaten'],
            ['province_code' => '35', 'code' => '3579', 'name' => 'Kota Batu', 'type' => 'Kota'],
            ['province_code' => '35', 'code' => '3571', 'name' => 'Kota Kediri', 'type' => 'Kota'],
            ['province_code' => '35', 'code' => '3574', 'name' => 'Kota Probolinggo', 'type' => 'Kota'],
            ['province_code' => '35', 'code' => '3576', 'name' => 'Kota Mojokerto', 'type' => 'Kota'],
            ['province_code' => '35', 'code' => '3577', 'name' => 'Kota Madiun', 'type' => 'Kota'],
            // Banten (province_code: 36)
            ['province_code' => '36', 'code' => '3671', 'name' => 'Kota Tangerang', 'type' => 'Kota'],
            ['province_code' => '36', 'code' => '3674', 'name' => 'Kota Tangerang Selatan', 'type' => 'Kota'],
            ['province_code' => '36', 'code' => '3603', 'name' => 'Kabupaten Tangerang', 'type' => 'Kabupaten'],
            ['province_code' => '36', 'code' => '3672', 'name' => 'Kota Cilegon', 'type' => 'Kota'],
            ['province_code' => '36', 'code' => '3673', 'name' => 'Kota Serang', 'type' => 'Kota'],
            ['province_code' => '36', 'code' => '3601', 'name' => 'Kabupaten Pandeglang', 'type' => 'Kabupaten'],
            ['province_code' => '36', 'code' => '3602', 'name' => 'Kabupaten Lebak', 'type' => 'Kabupaten'],
            // Sumatera Utara (province_code: 12)
            ['province_code' => '12', 'code' => '1271', 'name' => 'Kota Medan', 'type' => 'Kota'],
            ['province_code' => '12', 'code' => '1272', 'name' => 'Kota Binjai', 'type' => 'Kota'],
            ['province_code' => '12', 'code' => '1207', 'name' => 'Kabupaten Deli Serdang', 'type' => 'Kabupaten'],
            ['province_code' => '12', 'code' => '1275', 'name' => 'Kota Pematangsiantar', 'type' => 'Kota'],
            ['province_code' => '12', 'code' => '1277', 'name' => 'Kota Padang Sidimpuan', 'type' => 'Kota'],
            // Sumatera Barat (province_code: 13)
            ['province_code' => '13', 'code' => '1371', 'name' => 'Kota Padang', 'type' => 'Kota'],
            ['province_code' => '13', 'code' => '1372', 'name' => 'Kota Solok', 'type' => 'Kota'],
            ['province_code' => '13', 'code' => '1373', 'name' => 'Kota Sawahlunto', 'type' => 'Kota'],
            ['province_code' => '13', 'code' => '1375', 'name' => 'Kota Bukittinggi', 'type' => 'Kota'],
            ['province_code' => '13', 'code' => '1376', 'name' => 'Kota Payakumbuh', 'type' => 'Kota'],
            // Riau (province_code: 14)
            ['province_code' => '14', 'code' => '1471', 'name' => 'Kota Pekanbaru', 'type' => 'Kota'],
            ['province_code' => '14', 'code' => '1472', 'name' => 'Kota Dumai', 'type' => 'Kota'],
            // Sumatera Selatan (province_code: 16)
            ['province_code' => '16', 'code' => '1671', 'name' => 'Kota Palembang', 'type' => 'Kota'],
            ['province_code' => '16', 'code' => '1672', 'name' => 'Kota Prabumulih', 'type' => 'Kota'],
            ['province_code' => '16', 'code' => '1673', 'name' => 'Kota Pagar Alam', 'type' => 'Kota'],
            ['province_code' => '16', 'code' => '1674', 'name' => 'Kota Lubuklinggau', 'type' => 'Kota'],
            // Lampung (province_code: 18)
            ['province_code' => '18', 'code' => '1871', 'name' => 'Kota Bandar Lampung', 'type' => 'Kota'],
            ['province_code' => '18', 'code' => '1872', 'name' => 'Kota Metro', 'type' => 'Kota'],
            // Bali (province_code: 51)
            ['province_code' => '51', 'code' => '5171', 'name' => 'Kota Denpasar', 'type' => 'Kota'],
            ['province_code' => '51', 'code' => '5103', 'name' => 'Kabupaten Badung', 'type' => 'Kabupaten'],
            ['province_code' => '51', 'code' => '5106', 'name' => 'Kabupaten Gianyar', 'type' => 'Kabupaten'],
            ['province_code' => '51', 'code' => '5108', 'name' => 'Kabupaten Buleleng', 'type' => 'Kabupaten'],
            // Kalimantan Timur (province_code: 64)
            ['province_code' => '64', 'code' => '6471', 'name' => 'Kota Balikpapan', 'type' => 'Kota'],
            ['province_code' => '64', 'code' => '6472', 'name' => 'Kota Samarinda', 'type' => 'Kota'],
            ['province_code' => '64', 'code' => '6474', 'name' => 'Kota Bontang', 'type' => 'Kota'],
            // Kalimantan Selatan (province_code: 63)
            ['province_code' => '63', 'code' => '6371', 'name' => 'Kota Banjarmasin', 'type' => 'Kota'],
            ['province_code' => '63', 'code' => '6372', 'name' => 'Kota Banjarbaru', 'type' => 'Kota'],
            // Kalimantan Barat (province_code: 61)
            ['province_code' => '61', 'code' => '6171', 'name' => 'Kota Pontianak', 'type' => 'Kota'],
            ['province_code' => '61', 'code' => '6172', 'name' => 'Kota Singkawang', 'type' => 'Kota'],
            // Sulawesi Selatan (province_code: 73)
            ['province_code' => '73', 'code' => '7371', 'name' => 'Kota Makassar', 'type' => 'Kota'],
            ['province_code' => '73', 'code' => '7372', 'name' => 'Kota Parepare', 'type' => 'Kota'],
            ['province_code' => '73', 'code' => '7373', 'name' => 'Kota Palopo', 'type' => 'Kota'],
            // Sulawesi Utara (province_code: 71)
            ['province_code' => '71', 'code' => '7171', 'name' => 'Kota Manado', 'type' => 'Kota'],
            ['province_code' => '71', 'code' => '7172', 'name' => 'Kota Bitung', 'type' => 'Kota'],
            ['province_code' => '71', 'code' => '7174', 'name' => 'Kota Kotamobagu', 'type' => 'Kota'],
            // Aceh (province_code: 11)
            ['province_code' => '11', 'code' => '1171', 'name' => 'Kota Banda Aceh', 'type' => 'Kota'],
            ['province_code' => '11', 'code' => '1172', 'name' => 'Kota Sabang', 'type' => 'Kota'],
            ['province_code' => '11', 'code' => '1173', 'name' => 'Kota Langsa', 'type' => 'Kota'],
            ['province_code' => '11', 'code' => '1174', 'name' => 'Kota Lhokseumawe', 'type' => 'Kota'],
            // NTB (province_code: 52)
            ['province_code' => '52', 'code' => '5271', 'name' => 'Kota Mataram', 'type' => 'Kota'],
            ['province_code' => '52', 'code' => '5272', 'name' => 'Kota Bima', 'type' => 'Kota'],
            // Papua (province_code: 91)
            ['province_code' => '91', 'code' => '9171', 'name' => 'Kota Jayapura', 'type' => 'Kota'],
            // Kepulauan Riau (province_code: 21)
            ['province_code' => '21', 'code' => '2171', 'name' => 'Kota Batam', 'type' => 'Kota'],
            ['province_code' => '21', 'code' => '2172', 'name' => 'Kota Tanjung Pinang', 'type' => 'Kota'],
            // Jambi (province_code: 15)
            ['province_code' => '15', 'code' => '1571', 'name' => 'Kota Jambi', 'type' => 'Kota'],
            ['province_code' => '15', 'code' => '1572', 'name' => 'Kota Sungai Penuh', 'type' => 'Kota'],
            // Bengkulu (province_code: 17)
            ['province_code' => '17', 'code' => '1771', 'name' => 'Kota Bengkulu', 'type' => 'Kota'],
            // Bangka Belitung (province_code: 19)
            ['province_code' => '19', 'code' => '1971', 'name' => 'Kota Pangkal Pinang', 'type' => 'Kota'],
            // Gorontalo (province_code: 75)
            ['province_code' => '75', 'code' => '7571', 'name' => 'Kota Gorontalo', 'type' => 'Kota'],
            // Sulawesi Tengah (province_code: 72)
            ['province_code' => '72', 'code' => '7271', 'name' => 'Kota Palu', 'type' => 'Kota'],
            // Sulawesi Tenggara (province_code: 74)
            ['province_code' => '74', 'code' => '7471', 'name' => 'Kota Kendari', 'type' => 'Kota'],
            ['province_code' => '74', 'code' => '7472', 'name' => 'Kota Baubau', 'type' => 'Kota'],
            // Maluku (province_code: 81)
            ['province_code' => '81', 'code' => '8171', 'name' => 'Kota Ambon', 'type' => 'Kota'],
            ['province_code' => '81', 'code' => '8172', 'name' => 'Kota Tual', 'type' => 'Kota'],
            // Maluku Utara (province_code: 82)
            ['province_code' => '82', 'code' => '8271', 'name' => 'Kota Ternate', 'type' => 'Kota'],
            ['province_code' => '82', 'code' => '8272', 'name' => 'Kota Tidore Kepulauan', 'type' => 'Kota'],
            // Papua Barat (province_code: 92)
            ['province_code' => '92', 'code' => '9271', 'name' => 'Kota Sorong', 'type' => 'Kota'],
            // NTT (province_code: 53)
            ['province_code' => '53', 'code' => '5371', 'name' => 'Kota Kupang', 'type' => 'Kota'],
            // Kalimantan Tengah (province_code: 62)
            ['province_code' => '62', 'code' => '6271', 'name' => 'Kota Palangka Raya', 'type' => 'Kota'],
        ];

        foreach ($cities as $city) {
            $province = DB::table('provinces')->where('code', $city['province_code'])->first();
            if ($province) {
                DB::table('cities')->updateOrInsert(
                    ['code' => $city['code']],
                    [
                        'province_id' => $province->id,
                        'code' => $city['code'],
                        'name' => $city['name'],
                        'type' => $city['type'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // Districts for major cities
        $districts = [
            // Jakarta Pusat
            ['city_code' => '3171', 'code' => '317101', 'name' => 'Gambir'],
            ['city_code' => '3171', 'code' => '317102', 'name' => 'Sawah Besar'],
            ['city_code' => '3171', 'code' => '317103', 'name' => 'Kemayoran'],
            ['city_code' => '3171', 'code' => '317104', 'name' => 'Senen'],
            ['city_code' => '3171', 'code' => '317105', 'name' => 'Cempaka Putih'],
            ['city_code' => '3171', 'code' => '317106', 'name' => 'Menteng'],
            ['city_code' => '3171', 'code' => '317107', 'name' => 'Tanah Abang'],
            ['city_code' => '3171', 'code' => '317108', 'name' => 'Johar Baru'],
            // Jakarta Selatan
            ['city_code' => '3174', 'code' => '317401', 'name' => 'Tebet'],
            ['city_code' => '3174', 'code' => '317402', 'name' => 'Setiabudi'],
            ['city_code' => '3174', 'code' => '317403', 'name' => 'Mampang Prapatan'],
            ['city_code' => '3174', 'code' => '317404', 'name' => 'Pasar Minggu'],
            ['city_code' => '3174', 'code' => '317405', 'name' => 'Kebayoran Lama'],
            ['city_code' => '3174', 'code' => '317406', 'name' => 'Kebayoran Baru'],
            ['city_code' => '3174', 'code' => '317407', 'name' => 'Cilandak'],
            ['city_code' => '3174', 'code' => '317408', 'name' => 'Pesanggrahan'],
            ['city_code' => '3174', 'code' => '317409', 'name' => 'Jagakarsa'],
            ['city_code' => '3174', 'code' => '317410', 'name' => 'Pancoran'],
            // Jakarta Barat
            ['city_code' => '3173', 'code' => '317301', 'name' => 'Grogol Petamburan'],
            ['city_code' => '3173', 'code' => '317302', 'name' => 'Taman Sari'],
            ['city_code' => '3173', 'code' => '317303', 'name' => 'Tambora'],
            ['city_code' => '3173', 'code' => '317304', 'name' => 'Kebon Jeruk'],
            ['city_code' => '3173', 'code' => '317305', 'name' => 'Palmerah'],
            ['city_code' => '3173', 'code' => '317306', 'name' => 'Cengkareng'],
            ['city_code' => '3173', 'code' => '317307', 'name' => 'Kalideres'],
            ['city_code' => '3173', 'code' => '317308', 'name' => 'Kembangan'],
            // Jakarta Timur
            ['city_code' => '3175', 'code' => '317501', 'name' => 'Matraman'],
            ['city_code' => '3175', 'code' => '317502', 'name' => 'Pulo Gadung'],
            ['city_code' => '3175', 'code' => '317503', 'name' => 'Jatinegara'],
            ['city_code' => '3175', 'code' => '317504', 'name' => 'Duren Sawit'],
            ['city_code' => '3175', 'code' => '317505', 'name' => 'Kramat Jati'],
            ['city_code' => '3175', 'code' => '317506', 'name' => 'Makasar'],
            ['city_code' => '3175', 'code' => '317507', 'name' => 'Pasar Rebo'],
            ['city_code' => '3175', 'code' => '317508', 'name' => 'Ciracas'],
            ['city_code' => '3175', 'code' => '317509', 'name' => 'Cipayung'],
            ['city_code' => '3175', 'code' => '317510', 'name' => 'Cakung'],
            // Jakarta Utara
            ['city_code' => '3172', 'code' => '317201', 'name' => 'Penjaringan'],
            ['city_code' => '3172', 'code' => '317202', 'name' => 'Pademangan'],
            ['city_code' => '3172', 'code' => '317203', 'name' => 'Tanjung Priok'],
            ['city_code' => '3172', 'code' => '317204', 'name' => 'Koja'],
            ['city_code' => '3172', 'code' => '317205', 'name' => 'Kelapa Gading'],
            ['city_code' => '3172', 'code' => '317206', 'name' => 'Cilincing'],
            // Surabaya
            ['city_code' => '3578', 'code' => '357801', 'name' => 'Tegalsari'],
            ['city_code' => '3578', 'code' => '357802', 'name' => 'Genteng'],
            ['city_code' => '3578', 'code' => '357803', 'name' => 'Bubutan'],
            ['city_code' => '3578', 'code' => '357804', 'name' => 'Simokerto'],
            ['city_code' => '3578', 'code' => '357805', 'name' => 'Gubeng'],
            ['city_code' => '3578', 'code' => '357806', 'name' => 'Wonokromo'],
            ['city_code' => '3578', 'code' => '357807', 'name' => 'Rungkut'],
            ['city_code' => '3578', 'code' => '357808', 'name' => 'Tenggilis Mejoyo'],
            // Bandung
            ['city_code' => '3273', 'code' => '327301', 'name' => 'Bandung Kulon'],
            ['city_code' => '3273', 'code' => '327302', 'name' => 'Babakan Ciparay'],
            ['city_code' => '3273', 'code' => '327303', 'name' => 'Bojongloa Kaler'],
            ['city_code' => '3273', 'code' => '327304', 'name' => 'Bojongloa Kidul'],
            ['city_code' => '3273', 'code' => '327305', 'name' => 'Astanaanyar'],
            ['city_code' => '3273', 'code' => '327306', 'name' => 'Regol'],
            ['city_code' => '3273', 'code' => '327307', 'name' => 'Lengkong'],
            ['city_code' => '3273', 'code' => '327308', 'name' => 'Bandung Kidul'],
            // Medan
            ['city_code' => '1271', 'code' => '127101', 'name' => 'Medan Tuntungan'],
            ['city_code' => '1271', 'code' => '127102', 'name' => 'Medan Johor'],
            ['city_code' => '1271', 'code' => '127103', 'name' => 'Medan Amplas'],
            ['city_code' => '1271', 'code' => '127104', 'name' => 'Medan Denai'],
            ['city_code' => '1271', 'code' => '127105', 'name' => 'Medan Kota'],
            ['city_code' => '1271', 'code' => '127106', 'name' => 'Medan Polonia'],
        ];

        foreach ($districts as $district) {
            $city = DB::table('cities')->where('code', $district['city_code'])->first();
            if ($city) {
                DB::table('districts')->updateOrInsert(
                    ['code' => $district['code']],
                    [
                        'city_id' => $city->id,
                        'code' => $district['code'],
                        'name' => $district['name'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // Sub-districts with postal codes for popular areas
        $subDistricts = [
            // Gambir, Jakarta Pusat
            ['district_code' => '317101', 'code' => '3171011001', 'name' => 'Gambir', 'postal_code' => '10110'],
            ['district_code' => '317101', 'code' => '3171011002', 'name' => 'Cideng', 'postal_code' => '10150'],
            ['district_code' => '317101', 'code' => '3171011003', 'name' => 'Petojo Utara', 'postal_code' => '10130'],
            ['district_code' => '317101', 'code' => '3171011004', 'name' => 'Petojo Selatan', 'postal_code' => '10160'],
            ['district_code' => '317101', 'code' => '3171011005', 'name' => 'Kebon Kelapa', 'postal_code' => '10120'],
            ['district_code' => '317101', 'code' => '3171011006', 'name' => 'Duri Pulo', 'postal_code' => '10140'],
            // Menteng, Jakarta Pusat
            ['district_code' => '317106', 'code' => '3171061001', 'name' => 'Menteng', 'postal_code' => '10310'],
            ['district_code' => '317106', 'code' => '3171061002', 'name' => 'Pegangsaan', 'postal_code' => '10320'],
            ['district_code' => '317106', 'code' => '3171061003', 'name' => 'Cikini', 'postal_code' => '10330'],
            ['district_code' => '317106', 'code' => '3171061004', 'name' => 'Gondangdia', 'postal_code' => '10350'],
            ['district_code' => '317106', 'code' => '3171061005', 'name' => 'Kebon Sirih', 'postal_code' => '10340'],
            // Kebayoran Baru, Jakarta Selatan
            ['district_code' => '317406', 'code' => '3174061001', 'name' => 'Melawai', 'postal_code' => '12160'],
            ['district_code' => '317406', 'code' => '3174061002', 'name' => 'Gunung', 'postal_code' => '12120'],
            ['district_code' => '317406', 'code' => '3174061003', 'name' => 'Kramat Pela', 'postal_code' => '12130'],
            ['district_code' => '317406', 'code' => '3174061004', 'name' => 'Selong', 'postal_code' => '12110'],
            ['district_code' => '317406', 'code' => '3174061005', 'name' => 'Rawa Barat', 'postal_code' => '12180'],
            ['district_code' => '317406', 'code' => '3174061006', 'name' => 'Senayan', 'postal_code' => '12190'],
            ['district_code' => '317406', 'code' => '3174061007', 'name' => 'Pulo', 'postal_code' => '12170'],
            ['district_code' => '317406', 'code' => '3174061008', 'name' => 'Petogogan', 'postal_code' => '12140'],
            ['district_code' => '317406', 'code' => '3174061009', 'name' => 'Gandaria Utara', 'postal_code' => '12140'],
            ['district_code' => '317406', 'code' => '3174061010', 'name' => 'Cipete Utara', 'postal_code' => '12150'],
            // Setiabudi, Jakarta Selatan
            ['district_code' => '317402', 'code' => '3174021001', 'name' => 'Setia Budi', 'postal_code' => '12910'],
            ['district_code' => '317402', 'code' => '3174021002', 'name' => 'Karet', 'postal_code' => '12920'],
            ['district_code' => '317402', 'code' => '3174021003', 'name' => 'Karet Semanggi', 'postal_code' => '12930'],
            ['district_code' => '317402', 'code' => '3174021004', 'name' => 'Karet Kuningan', 'postal_code' => '12940'],
            ['district_code' => '317402', 'code' => '3174021005', 'name' => 'Kuningan Timur', 'postal_code' => '12950'],
            // Kelapa Gading, Jakarta Utara
            ['district_code' => '317205', 'code' => '3172051001', 'name' => 'Kelapa Gading Barat', 'postal_code' => '14240'],
            ['district_code' => '317205', 'code' => '3172051002', 'name' => 'Kelapa Gading Timur', 'postal_code' => '14240'],
            ['district_code' => '317205', 'code' => '3172051003', 'name' => 'Pegangsaan Dua', 'postal_code' => '14250'],
            // Genteng, Surabaya
            ['district_code' => '357802', 'code' => '3578021001', 'name' => 'Embong Kaliasin', 'postal_code' => '60271'],
            ['district_code' => '357802', 'code' => '3578021002', 'name' => 'Ketabang', 'postal_code' => '60272'],
            ['district_code' => '357802', 'code' => '3578021003', 'name' => 'Genteng', 'postal_code' => '60275'],
            ['district_code' => '357802', 'code' => '3578021004', 'name' => 'Peneleh', 'postal_code' => '60274'],
            ['district_code' => '357802', 'code' => '3578021005', 'name' => 'Kapasari', 'postal_code' => '60273'],
            // Lengkong, Bandung
            ['district_code' => '327307', 'code' => '3273071001', 'name' => 'Cikawao', 'postal_code' => '40261'],
            ['district_code' => '327307', 'code' => '3273071002', 'name' => 'Lingkar Selatan', 'postal_code' => '40263'],
            ['district_code' => '327307', 'code' => '3273071003', 'name' => 'Burangrang', 'postal_code' => '40262'],
            ['district_code' => '327307', 'code' => '3273071004', 'name' => 'Paledang', 'postal_code' => '40264'],
            ['district_code' => '327307', 'code' => '3273071005', 'name' => 'Turangga', 'postal_code' => '40264'],
            ['district_code' => '327307', 'code' => '3273071006', 'name' => 'Malabar', 'postal_code' => '40262'],
            // Medan Kota
            ['district_code' => '127105', 'code' => '1271051001', 'name' => 'Kesawan', 'postal_code' => '20111'],
            ['district_code' => '127105', 'code' => '1271051002', 'name' => 'Pusat Pasar', 'postal_code' => '20212'],
            ['district_code' => '127105', 'code' => '1271051003', 'name' => 'Pasar Baru', 'postal_code' => '20212'],
        ];

        foreach ($subDistricts as $subDistrict) {
            $district = DB::table('districts')->where('code', $subDistrict['district_code'])->first();
            if ($district) {
                DB::table('sub_districts')->updateOrInsert(
                    ['code' => $subDistrict['code']],
                    [
                        'district_id' => $district->id,
                        'code' => $subDistrict['code'],
                        'name' => $subDistrict['name'],
                        'postal_code' => $subDistrict['postal_code'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        $this->command->info('Indonesia location data seeded successfully!');
        $this->command->info('- 34 Provinces');
        $this->command->info('- ' . count($cities) . ' Cities');
        $this->command->info('- ' . count($districts) . ' Districts');
        $this->command->info('- ' . count($subDistricts) . ' Sub-districts with postal codes');
    }
}
