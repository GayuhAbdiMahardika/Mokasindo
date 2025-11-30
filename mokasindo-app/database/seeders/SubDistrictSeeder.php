<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\SubDistrict;

class SubDistrictSeeder extends Seeder
{
    public function run(): void
    {
        $subDistricts = [
            ['district_code' => '317101', 'code' => '31710101', 'name' => 'Senayan', 'postal_code' => '12190'],
            ['district_code' => '317101', 'code' => '31710102', 'name' => 'Gunung', 'postal_code' => '12120'],
            ['district_code' => '317102', 'code' => '31710201', 'name' => 'Kebon Baru', 'postal_code' => '12830'],
            ['district_code' => '327321', 'code' => '32732101', 'name' => 'Dago', 'postal_code' => '40135'],
            ['district_code' => '357801', 'code' => '35780101', 'name' => 'Dr. Soetomo', 'postal_code' => '60264'],
        ];

        foreach ($subDistricts as $subDistrict) {
            $district = District::where('code', $subDistrict['district_code'])->first() ?? District::first();
            if (!$district) {
                continue;
            }

            SubDistrict::updateOrCreate(
                ['code' => $subDistrict['code']],
                [
                    'district_id' => $district->id,
                    'name' => $subDistrict['name'],
                    'postal_code' => $subDistrict['postal_code'],
                ]
            );
        }
    }
}
