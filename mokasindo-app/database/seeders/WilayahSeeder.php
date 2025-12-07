<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;

class WilayahSeeder extends Seeder
{
    private string $baseUrl = 'https://kanglerian.my.id/api-wilayah-indonesia/api';

    public function run(): void
    {
        DB::transaction(function () {
            $this->seedProvinces();
            $this->seedCities();
            $this->seedDistricts();
            $this->seedSubDistricts();
        });
    }

    private function seedProvinces(): void
    {
        $provinces = $this->fetchJson("{$this->baseUrl}/provinces.json");

        foreach ($provinces as $province) {
            Province::updateOrCreate(
                ['code' => (string) $province['id']],
                ['name' => $province['name'] ?? $province['province'] ?? '']
            );
        }
    }

    private function seedCities(): void
    {
        $provinces = Province::all(['id', 'code']);

        foreach ($provinces as $province) {
            $cities = $this->fetchJson("{$this->baseUrl}/regencies/{$province->code}.json");

            foreach ($cities as $city) {
                $name = $city['name'] ?? $city['regency'] ?? '';
                $type = Str::startsWith(Str::lower($name), 'kota') ? 'kota' : 'kabupaten';

                City::updateOrCreate(
                    ['code' => (string) $city['id']],
                    [
                        'province_id' => $province->id,
                        'name' => $name,
                        'type' => $type,
                    ]
                );
            }
        }
    }

    private function seedDistricts(): void
    {
        $cities = City::all(['id', 'code']);

        foreach ($cities as $city) {
            $districts = $this->fetchJson("{$this->baseUrl}/districts/{$city->code}.json");

            foreach ($districts as $district) {
                District::updateOrCreate(
                    ['code' => (string) $district['id']],
                    [
                        'city_id' => $city->id,
                        'name' => $district['name'] ?? $district['district'] ?? '',
                    ]
                );
            }
        }
    }

    private function seedSubDistricts(): void
    {
        $districts = District::all(['id', 'code']);

        foreach ($districts as $district) {
            $villages = $this->fetchJson("{$this->baseUrl}/villages/{$district->code}.json");

            foreach ($villages as $village) {
                SubDistrict::updateOrCreate(
                    ['code' => (string) $village['id']],
                    [
                        'district_id' => $district->id,
                        'name' => $village['name'] ?? $village['village'] ?? '',
                        'postal_code' => $village['postal_code'] ?? null,
                    ]
                );
            }
        }
    }

    private function fetchJson(string $url): array
    {
        $response = Http::timeout(60)->retry(3, 500)->get($url);

        if ($response->failed()) {
            $this->command?->warn("Gagal fetch: {$url}");
            return [];
        }

        $data = $response->json();
        return is_array($data) ? $data : [];
    }
}
