<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $token;
    protected $baseUrl;

    public function __construct()
    {
        $this->token = env('TELEGRAM_BOT_TOKEN');
        $this->baseUrl = "https://api.telegram.org/bot{$this->token}/";
    }

    public function sendMessage($chatId, $message)
    {
        // Validasi Token
        if (!$this->token) {
            Log::warning('Telegram: Token kosong di .env');
            return ['success' => false, 'error' => 'Token kosong di .env'];
        }

        try {
            // Kirim request ke API Telegram dengan timeout 10 detik
            $response = Http::timeout(10)->post($this->baseUrl . 'sendMessage', [
                'chat_id' => $chatId,
                'text'    => $message,
                'parse_mode' => 'HTML'
            ]);

            // Cek apakah Telegram menjawab "OK"
            if ($response->successful()) {
                return ['success' => true];
            } else {
                Log::warning('Telegram Error: ' . $response->body());
                return ['success' => false, 'error' => 'Telegram Error: ' . $response->body()];
            }

        } catch (\Exception $e) {
            Log::warning('Telegram Koneksi Error: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Koneksi Error: ' . $e->getMessage()];
        }
    }
     //Kirim notifikasi ke User tertentu berdasarkan data database
    public function sendToUser($user, $message)
    {
        // Cek apakah user punya telegram_chat_id
        if (empty($user->telegram_chat_id)) {
            return ['success' => false, 'error' => 'User ini belum menautkan akun Telegram.'];
        }

        // Kalau ada, kirim pakai fungsi sendMessage yang lama
        return $this->sendMessage($user->telegram_chat_id, $message);
    }
    
    /**
     * Fungsi Khusus untuk Dipanggil Tim Lelang (Jobdesk 4)
     * Panggil ini saat waktu lelang habis & pemenang ditentukan.
     */
    public function sendAuctionWinnerNotif($user, $namaBarang, $hargaAkhir)
    {
        if (empty($user->telegram_chat_id)) return false;

        // Format Rupiah
        $hargaRupiah = "Rp " . number_format($hargaAkhir, 0, ',', '.');

        $pesan = "<b>🏆 SELAMAT! ANDA MEMENANGKAN LELANG</b>\n\n" .
                 "Halo <b>{$user->name}</b>,\n" .
                 "Anda adalah penawar tertinggi untuk unit:\n" .
                 "🏍️ <b>{$namaBarang}</b>\n" .
                 "💰 Harga Final: <b>{$hargaRupiah}</b>\n\n" .
                 "⚠️ <b>PENTING:</b>\n" .
                 "Segera lakukan pembayaran dalam 1x24 jam agar unit tidak hangus.\n\n" .
                 "<i>Tim Mokasindo</i>";

        return $this->sendMessage($user->telegram_chat_id, $pesan);
    }

}