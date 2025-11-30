<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vacancy;
use App\Models\JobApplication;

class JobApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $vacancy = Vacancy::first();
        if (!$vacancy) {
            return;
        }

        $applications = [
            [
                'name' => 'Rina Pratiwi',
                'email' => 'rina.pratiwi@example.com',
                'phone' => '081233221144',
                'cover_letter' => 'Saya memiliki pengalaman 5 tahun sebagai backend engineer dan tertarik bergabung.',
                'cv_path' => 'storage/cv/rina-pratiwi.pdf',
                'status' => 'reviewed',
            ],
            [
                'name' => 'Andi Kurniawan',
                'email' => 'andi.k@example.com',
                'phone' => '082167889900',
                'cover_letter' => 'Pengalaman inspeksi mobil lebih dari 8 tahun, siap ditempatkan di seluruh Indonesia.',
                'cv_path' => 'storage/cv/andi-kurniawan.pdf',
                'status' => 'pending',
            ],
        ];

        foreach ($applications as $app) {
            JobApplication::updateOrCreate(
                ['email' => $app['email'], 'vacancy_id' => $vacancy->id],
                array_merge($app, ['vacancy_id' => $vacancy->id])
            );
        }
    }
}
