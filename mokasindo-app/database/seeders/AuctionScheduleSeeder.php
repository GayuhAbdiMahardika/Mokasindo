<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuctionSchedule;
use App\Models\City;

class AuctionScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $jakarta = City::where('name', 'like', '%Jakarta%')->first();
        $surabaya = City::where('name', 'like', '%Surabaya%')->first();
        $bandung = City::where('name', 'like', '%Bandung%')->first();

        $schedules = [
            [
                'title' => 'Lelang Mingguan Jakarta',
                'description' => 'Lelang kendaraan bekas berkualitas setiap minggu di Jakarta',
                'location_id' => $jakarta?->id,
                'start_date' => now()->addDays(3)->setTime(10, 0),
                'end_date' => now()->addDays(3)->setTime(18, 0),
                'is_active' => true,
            ],
            [
                'title' => 'Lelang Premium Surabaya',
                'description' => 'Lelang kendaraan premium bulanan di Surabaya',
                'location_id' => $surabaya?->id,
                'start_date' => now()->addDays(7)->setTime(9, 0),
                'end_date' => now()->addDays(7)->setTime(17, 0),
                'is_active' => true,
            ],
            [
                'title' => 'Lelang Weekend Bandung',
                'description' => 'Lelang kendaraan akhir pekan di Bandung',
                'location_id' => $bandung?->id,
                'start_date' => now()->addDays(5)->setTime(10, 0),
                'end_date' => now()->addDays(5)->setTime(16, 0),
                'is_active' => true,
            ],
            [
                'title' => 'Lelang Akhir Tahun Jakarta',
                'description' => 'Lelang spesial akhir tahun dengan diskon besar',
                'location_id' => $jakarta?->id,
                'start_date' => now()->addDays(14)->setTime(9, 0),
                'end_date' => now()->addDays(14)->setTime(21, 0),
                'is_active' => true,
            ],
        ];

        foreach ($schedules as $schedule) {
            AuctionSchedule::updateOrCreate(
                ['title' => $schedule['title']],
                $schedule
            );
        }
    }
}
