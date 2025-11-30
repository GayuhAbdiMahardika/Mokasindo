<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // ==============================
            // AUCTION SETTINGS 
            // ==============================
            [
                'key'         => 'auction_deposit_percentage',
                'value'       => '5',
                'type'        => 'integer',
                'group'       => 'auction',
                'description' => 'Persentase deposit yang harus dibayar untuk ikut lelang',
                'is_public'   => true,
            ],
            [
                'key'         => 'payment_deadline_hours',
                'value'       => '24',
                'type'        => 'integer',
                'group'       => 'auction',
                'description' => 'Batas waktu pelunasan setelah menang lelang (dalam jam)',
                'is_public'   => true,
            ],
            [
                'key'         => 'max_auction_duration',
                'value'       => '7',
                'type'        => 'integer', //hari
                'group'       => 'auction',
                'description' => 'Durasi maksimum lelang (dalam hari)',
                'is_public'   => true,
            ],
            [
                'key'         => 'min_auction_duration',
                'value'       => '1',
                'type'        => 'integer', //hari
                'group'       => 'auction',
                'description' => 'Durasi minimum lelang (dalam hari)',
                'is_public'   => true,
            ],
            [
                'key'         => 'auction_extend_minutes',
                'value'       => '5',
                'type'        => 'integer',
                'group'       => 'auction',
                'description' => 'Perpanjangan waktu otomatis saat ada bid menjelang akhir (menit)',
                'is_public'   => true,
            ],
            [
                'key'         => 'min_bid_increment',
                'value'       => '100000',
                'type'        => 'integer',
                'group'       => 'auction',
                'description' => 'Kelipatan minimal kenaikan bid (dalam rupiah)',
                'is_public'   => true,
            ],
            [
                'key'         => 'max_images_per_vehicle',
                'value'       => '10',
                'type'        => 'integer',
                'group'       => 'auction',
                'description' => 'Jumlah maksimum gambar per kendaraan',
                'is_public'   => true,
            ],
            // ==============================
            // MEMBER SETTINGS
            // ==============================
            [
                'key'         => 'max_posts_per_user',
                'value'       => '10',
                'type'        => 'integer',
                'group'       => 'member',
                'description' => 'Maksimum jumlah posting kendaraan aktif per user',
                'is_public'   => true,
            ],
            [
                'key'         => 'anggota_weekly_post_limit',
                'value'       => '2',
                'type'        => 'integer',
                'group'       => 'member',
                'description' => 'Batas posting per minggu untuk anggota (versi lama)',
                'is_public'   => false,
            ],

            // ==============================
            // PLATFORM / GENERAL SETTINGS
            // ==============================
            [
                'key'         => 'platform_fee_percentage',
                'value'       => '2.5',
                'type'        => 'decimal',
                'group'       => 'general',
                'description' => 'Persentase fee platform dari setiap transaksi',
                'is_public'   => true,
            ],
            [
                'key'         => 'app_name',
                'value'       => 'Mokasindo',
                'type'        => 'string',
                'group'       => 'general',
                'description' => 'Nama aplikasi',
                'is_public'   => true,
            ],
            [
                'key'         => 'contact_email',
                'value'       => 'info@mokasindo.com',
                'type'        => 'string',
                'group'       => 'general',
                'description' => 'Email kontak resmi Mokasindo',
                'is_public'   => true,
            ],
            [
                'key'         => 'contact_phone',
                'value'       => '081234567890',
                'type'        => 'string',
                'group'       => 'general',
                'description' => 'Nomor telepon resmi Mokasindo',
                'is_public'   => true,
            ],
            [
                'key'         => 'contact_address',
                'value'       => 'Jl. Contoh Alamat No. 123, Surabaya',
                'type'        => 'string',
                'group'       => 'general',
                'description' => 'Alamat kantor / showroom utama Mokasindo',
                'is_public'   => true,
            ],
            // ==============================
            // NOTIFICATION SETTINGS
            // ==============================
            [
                'key'         => 'telegram_admin_chat_id',
                'value'       => '123456789',
                'type'        => 'string',
                'group'       => 'notification',
                'description' => 'Chat ID Telegram admin untuk menerima notifikasi sistem',
                'is_public'   => false,
            ],
            [
                'key'         => 'notification_email_enabled',
                'value'       => 'true',
                'type'        => 'boolean',
                'group'       => 'notification',
                'description' => 'Aktifkan notifikasi melalui email',
                'is_public'   => false,
            ],
            [
                'key'         => 'notification_whatsapp_enabled',
                'value'       => 'true',
                'type'        => 'boolean',
                'group'       => 'notification',
                'description' => 'Aktifkan notifikasi melalui WhatsApp',
                'is_public'   => false,
            ],
            [
                'key'         => 'notification_telegram_enabled',
                'value'       => 'true',
                'type'        => 'boolean',
                'group'       => 'notification',
                'description' => 'Aktifkan notifikasi melalui Telegram',
                'is_public'   => false,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
