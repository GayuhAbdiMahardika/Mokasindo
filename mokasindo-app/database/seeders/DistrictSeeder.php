<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\District;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            ['city' => 'Jakarta Selatan', 'code' => '317101', 'name' => 'Kebayoran Baru'],
            ['city' => 'Jakarta Selatan', 'code' => '317102', 'name' => 'Tebet'],
            ['city' => 'Jakarta Barat', 'code' => '317301', 'name' => 'Palmerah'],
            ['city' => 'Bandung', 'code' => '327321', 'name' => 'Coblong'],
            ['city' => 'Surabaya', 'code' => '357801', 'name' => 'Tegalsari'],
        ];

        foreach ($districts as $district) {
            $city = City::where('name', $district['city'])->first() ?? City::first();
            if (!$city) {
                continue;
            }

            District::updateOrCreate(
                ['code' => $district['code']],
                ['city_id' => $city->id, 'name' => $district['name']]
            );
        }
    }
}
