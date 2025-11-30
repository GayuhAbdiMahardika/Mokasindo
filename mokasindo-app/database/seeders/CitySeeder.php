<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $citiesData = [
            // DKI Jakarta (province_id: 11)
            ['province_id' => 11, 'name' => 'Jakarta Pusat'],
            ['province_id' => 11, 'name' => 'Jakarta Utara'],
            ['province_id' => 11, 'name' => 'Jakarta Barat'],
            ['province_id' => 11, 'name' => 'Jakarta Selatan'],
            ['province_id' => 11, 'name' => 'Jakarta Timur'],
            ['province_id' => 11, 'name' => 'Kepulauan Seribu'],
            
            // Jawa Barat (province_id: 13)
            ['province_id' => 13, 'name' => 'Bandung'],
            ['province_id' => 13, 'name' => 'Kota Bandung'],
            ['province_id' => 13, 'name' => 'Bekasi'],
            ['province_id' => 13, 'name' => 'Kota Bekasi'],
            ['province_id' => 13, 'name' => 'Bogor'],
            ['province_id' => 13, 'name' => 'Kota Bogor'],
            ['province_id' => 13, 'name' => 'Depok'],
            ['province_id' => 13, 'name' => 'Cirebon'],
            ['province_id' => 13, 'name' => 'Kota Cirebon'],
            ['province_id' => 13, 'name' => 'Sukabumi'],
            ['province_id' => 13, 'name' => 'Kota Sukabumi'],
            ['province_id' => 13, 'name' => 'Tasikmalaya'],
            ['province_id' => 13, 'name' => 'Kota Tasikmalaya'],
            ['province_id' => 13, 'name' => 'Karawang'],
            ['province_id' => 13, 'name' => 'Purwakarta'],
            ['province_id' => 13, 'name' => 'Subang'],
            ['province_id' => 13, 'name' => 'Cianjur'],
            ['province_id' => 13, 'name' => 'Garut'],
            ['province_id' => 13, 'name' => 'Kuningan'],
            ['province_id' => 13, 'name' => 'Majalengka'],
            ['province_id' => 13, 'name' => 'Indramayu'],
            ['province_id' => 13, 'name' => 'Sumedang'],
            ['province_id' => 13, 'name' => 'Ciamis'],
            ['province_id' => 13, 'name' => 'Banjar'],
            ['province_id' => 13, 'name' => 'Pangandaran'],
            ['province_id' => 13, 'name' => 'Bandung Barat'],
            ['province_id' => 13, 'name' => 'Cimahi'],
            
            // Banten (province_id: 12)
            ['province_id' => 12, 'name' => 'Tangerang'],
            ['province_id' => 12, 'name' => 'Kota Tangerang'],
            ['province_id' => 12, 'name' => 'Tangerang Selatan'],
            ['province_id' => 12, 'name' => 'Serang'],
            ['province_id' => 12, 'name' => 'Kota Serang'],
            ['province_id' => 12, 'name' => 'Cilegon'],
            ['province_id' => 12, 'name' => 'Lebak'],
            ['province_id' => 12, 'name' => 'Pandeglang'],
            
            // Jawa Tengah (province_id: 14)
            ['province_id' => 14, 'name' => 'Semarang'],
            ['province_id' => 14, 'name' => 'Kota Semarang'],
            ['province_id' => 14, 'name' => 'Surakarta'],
            ['province_id' => 14, 'name' => 'Magelang'],
            ['province_id' => 14, 'name' => 'Kota Magelang'],
            ['province_id' => 14, 'name' => 'Salatiga'],
            ['province_id' => 14, 'name' => 'Pekalongan'],
            ['province_id' => 14, 'name' => 'Kota Pekalongan'],
            ['province_id' => 14, 'name' => 'Tegal'],
            ['province_id' => 14, 'name' => 'Kota Tegal'],
            ['province_id' => 14, 'name' => 'Purwokerto'],
            ['province_id' => 14, 'name' => 'Cilacap'],
            ['province_id' => 14, 'name' => 'Banyumas'],
            ['province_id' => 14, 'name' => 'Purbalingga'],
            ['province_id' => 14, 'name' => 'Banjarnegara'],
            ['province_id' => 14, 'name' => 'Kebumen'],
            ['province_id' => 14, 'name' => 'Purworejo'],
            ['province_id' => 14, 'name' => 'Wonosobo'],
            ['province_id' => 14, 'name' => 'Temanggung'],
            ['province_id' => 14, 'name' => 'Kendal'],
            ['province_id' => 14, 'name' => 'Batang'],
            ['province_id' => 14, 'name' => 'Pekalongan'],
            ['province_id' => 14, 'name' => 'Pemalang'],
            ['province_id' => 14, 'name' => 'Brebes'],
            ['province_id' => 14, 'name' => 'Boyolali'],
            ['province_id' => 14, 'name' => 'Klaten'],
            ['province_id' => 14, 'name' => 'Sukoharjo'],
            ['province_id' => 14, 'name' => 'Wonogiri'],
            ['province_id' => 14, 'name' => 'Karanganyar'],
            ['province_id' => 14, 'name' => 'Sragen'],
            ['province_id' => 14, 'name' => 'Grobogan'],
            ['province_id' => 14, 'name' => 'Blora'],
            ['province_id' => 14, 'name' => 'Rembang'],
            ['province_id' => 14, 'name' => 'Pati'],
            ['province_id' => 14, 'name' => 'Kudus'],
            ['province_id' => 14, 'name' => 'Jepara'],
            ['province_id' => 14, 'name' => 'Demak'],
            
            // DI Yogyakarta (province_id: 15)
            ['province_id' => 15, 'name' => 'Yogyakarta'],
            ['province_id' => 15, 'name' => 'Sleman'],
            ['province_id' => 15, 'name' => 'Bantul'],
            ['province_id' => 15, 'name' => 'Gunung Kidul'],
            ['province_id' => 15, 'name' => 'Kulon Progo'],
            
            // Jawa Timur (province_id: 16)
            ['province_id' => 16, 'name' => 'Surabaya'],
            ['province_id' => 16, 'name' => 'Malang'],
            ['province_id' => 16, 'name' => 'Kota Malang'],
            ['province_id' => 16, 'name' => 'Sidoarjo'],
            ['province_id' => 16, 'name' => 'Gresik'],
            ['province_id' => 16, 'name' => 'Mojokerto'],
            ['province_id' => 16, 'name' => 'Kota Mojokerto'],
            ['province_id' => 16, 'name' => 'Pasuruan'],
            ['province_id' => 16, 'name' => 'Kota Pasuruan'],
            ['province_id' => 16, 'name' => 'Probolinggo'],
            ['province_id' => 16, 'name' => 'Kota Probolinggo'],
            ['province_id' => 16, 'name' => 'Jember'],
            ['province_id' => 16, 'name' => 'Banyuwangi'],
            ['province_id' => 16, 'name' => 'Kediri'],
            ['province_id' => 16, 'name' => 'Kota Kediri'],
            ['province_id' => 16, 'name' => 'Madiun'],
            ['province_id' => 16, 'name' => 'Kota Madiun'],
            ['province_id' => 16, 'name' => 'Blitar'],
            ['province_id' => 16, 'name' => 'Kota Blitar'],
            ['province_id' => 16, 'name' => 'Tuban'],
            ['province_id' => 16, 'name' => 'Lamongan'],
            ['province_id' => 16, 'name' => 'Bojonegoro'],
            ['province_id' => 16, 'name' => 'Nganjuk'],
            ['province_id' => 16, 'name' => 'Jombang'],
            ['province_id' => 16, 'name' => 'Tulungagung'],
            ['province_id' => 16, 'name' => 'Trenggalek'],
            ['province_id' => 16, 'name' => 'Pacitan'],
            ['province_id' => 16, 'name' => 'Ponorogo'],
            ['province_id' => 16, 'name' => 'Magetan'],
            ['province_id' => 16, 'name' => 'Ngawi'],
            ['province_id' => 16, 'name' => 'Lumajang'],
            ['province_id' => 16, 'name' => 'Bondowoso'],
            ['province_id' => 16, 'name' => 'Situbondo'],
            ['province_id' => 16, 'name' => 'Pamekasan'],
            ['province_id' => 16, 'name' => 'Sampang'],
            ['province_id' => 16, 'name' => 'Sumenep'],
            ['province_id' => 16, 'name' => 'Bangkalan'],
            
            // Bali (province_id: 17)
            ['province_id' => 17, 'name' => 'Denpasar'],
            ['province_id' => 17, 'name' => 'Badung'],
            ['province_id' => 17, 'name' => 'Gianyar'],
            ['province_id' => 17, 'name' => 'Tabanan'],
            ['province_id' => 17, 'name' => 'Klungkung'],
            ['province_id' => 17, 'name' => 'Bangli'],
            ['province_id' => 17, 'name' => 'Karangasem'],
            ['province_id' => 17, 'name' => 'Buleleng'],
            ['province_id' => 17, 'name' => 'Jembrana'],
            
            // Sumatera Utara (province_id: 2)
            ['province_id' => 2, 'name' => 'Medan'],
            ['province_id' => 2, 'name' => 'Binjai'],
            ['province_id' => 2, 'name' => 'Tebing Tinggi'],
            ['province_id' => 2, 'name' => 'Pematang Siantar'],
            ['province_id' => 2, 'name' => 'Tanjung Balai'],
            ['province_id' => 2, 'name' => 'Sibolga'],
            ['province_id' => 2, 'name' => 'Padang Sidempuan'],
            ['province_id' => 2, 'name' => 'Gunung Sitoli'],
            ['province_id' => 2, 'name' => 'Deli Serdang'],
            ['province_id' => 2, 'name' => 'Langkat'],
            ['province_id' => 2, 'name' => 'Karo'],
            ['province_id' => 2, 'name' => 'Simalungun'],
            ['province_id' => 2, 'name' => 'Asahan'],
            ['province_id' => 2, 'name' => 'Labuhan Batu'],
            ['province_id' => 2, 'name' => 'Tapanuli Utara'],
            ['province_id' => 2, 'name' => 'Tapanuli Tengah'],
            ['province_id' => 2, 'name' => 'Tapanuli Selatan'],
            ['province_id' => 2, 'name' => 'Nias'],
            ['province_id' => 2, 'name' => 'Mandailing Natal'],
            ['province_id' => 2, 'name' => 'Toba Samosir'],
            
            // Sumatera Barat (province_id: 3)
            ['province_id' => 3, 'name' => 'Padang'],
            ['province_id' => 3, 'name' => 'Bukittinggi'],
            ['province_id' => 3, 'name' => 'Padang Panjang'],
            ['province_id' => 3, 'name' => 'Payakumbuh'],
            ['province_id' => 3, 'name' => 'Solok'],
            ['province_id' => 3, 'name' => 'Sawahlunto'],
            ['province_id' => 3, 'name' => 'Pariaman'],
            
            // Sulawesi Selatan (province_id: 29)
            ['province_id' => 29, 'name' => 'Makassar'],
            ['province_id' => 29, 'name' => 'Pare-Pare'],
            ['province_id' => 29, 'name' => 'Palopo'],
            ['province_id' => 29, 'name' => 'Gowa'],
            ['province_id' => 29, 'name' => 'Maros'],
            ['province_id' => 29, 'name' => 'Bone'],
            ['province_id' => 29, 'name' => 'Bulukumba'],
            ['province_id' => 29, 'name' => 'Sinjai'],
            ['province_id' => 29, 'name' => 'Wajo'],
            ['province_id' => 29, 'name' => 'Soppeng'],
            ['province_id' => 29, 'name' => 'Barru'],
            ['province_id' => 29, 'name' => 'Pangkajene dan Kepulauan'],
            ['province_id' => 29, 'name' => 'Takalar'],
            ['province_id' => 29, 'name' => 'Jeneponto'],
            ['province_id' => 29, 'name' => 'Bantaeng'],
            ['province_id' => 29, 'name' => 'Luwu'],
            ['province_id' => 29, 'name' => 'Tana Toraja'],
            ['province_id' => 29, 'name' => 'Enrekang'],
            ['province_id' => 29, 'name' => 'Pinrang'],
            ['province_id' => 29, 'name' => 'Sidenreng Rappang'],
            
            // Kalimantan Timur (province_id: 23)
            ['province_id' => 23, 'name' => 'Samarinda'],
            ['province_id' => 23, 'name' => 'Balikpapan'],
            ['province_id' => 23, 'name' => 'Bontang'],
            ['province_id' => 23, 'name' => 'Kutai Kartanegara'],
            ['province_id' => 23, 'name' => 'Kutai Barat'],
            ['province_id' => 23, 'name' => 'Kutai Timur'],
            ['province_id' => 23, 'name' => 'Berau'],
            ['province_id' => 23, 'name' => 'Paser'],
            ['province_id' => 23, 'name' => 'Penajam Paser Utara'],
            ['province_id' => 23, 'name' => 'Mahakam Ulu'],
        ];

        // Auto-generate codes
        $cities = [];
        foreach ($citiesData as $index => $city) {
            $cities[] = [
                'code' => str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'province_id' => $city['province_id'],
                'name' => $city['name'],
            ];
        }

        DB::table('cities')->insert($cities);
    }
}
