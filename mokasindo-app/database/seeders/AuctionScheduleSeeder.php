<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuctionSchedule;

class AuctionScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'title' => 'Lelang Mingguan Jakarta',
                'description' => 'Lelang kendaraan bekas berkualitas setiap minggu di Jakarta',
                'location' => 'Jakarta',
                'start_date' => now()->addDays(3)->setTime(10, 0),
                'end_date' => now()->addDays(3)->setTime(18, 0),
                'is_active' => true,
            ],
            [
                'title' => 'Lelang Premium Surabaya',
                'description' => 'Lelang kendaraan premium bulanan di Surabaya',
                'location' => 'Surabaya',
                'start_date' => now()->addDays(7)->setTime(9, 0),
                'end_date' => now()->addDays(7)->setTime(17, 0),
                'is_active' => true,
            ],
            [
                'title' => 'Lelang Weekend Bandung',
                'description' => 'Lelang kendaraan akhir pekan di Bandung',
                'location' => 'Bandung',
                'start_date' => now()->addDays(5)->setTime(10, 0),
                'end_date' => now()->addDays(5)->setTime(16, 0),
                'is_active' => true,
            ],
            [
                'title' => 'Lelang Akhir Tahun Jakarta',
                'description' => 'Lelang spesial akhir tahun dengan diskon besar',
                'location' => 'Jakarta',
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
