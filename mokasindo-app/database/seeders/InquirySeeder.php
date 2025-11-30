<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inquiry;

class InquirySeeder extends Seeder
{
    public function run(): void
    {
        $inquiries = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '081234567890',
                'subject' => 'Pertanyaan Jadwal Lelang',
                'message' => 'Kapan jadwal lelang mobil SUV berikutnya di Jakarta?',
                'status' => 'read',
                'admin_reply' => 'Halo Pak Budi, jadwal berikutnya ada di tanggal ' . now()->addDays(3)->format('d M Y'),
            ],
            [
                'name' => 'Citra Lestari',
                'email' => 'citra@example.com',
                'phone' => '081298765432',
                'subject' => 'Refund Deposit',
                'message' => 'Saya kalah lelang, kapan deposit saya dikembalikan?',
                'status' => 'replied',
                'admin_reply' => 'Halo Bu Citra, refund akan diproses maksimal 1x24 jam ke rekening Anda.',
            ],
            [
                'name' => 'Dimas Saputra',
                'email' => 'dimas@example.com',
                'phone' => '082123456789',
                'subject' => 'Permintaan Inspeksi',
                'message' => 'Apakah bisa request foto tambahan untuk unit Honda HR-V?',
                'status' => 'new',
                'admin_reply' => null,
            ],
        ];

        foreach ($inquiries as $inquiry) {
            Inquiry::updateOrCreate(
                ['email' => $inquiry['email'], 'subject' => $inquiry['subject']],
                $inquiry
            );
        }
    }
}
