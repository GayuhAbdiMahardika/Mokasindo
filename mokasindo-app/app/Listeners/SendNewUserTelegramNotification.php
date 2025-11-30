<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;

class SendNewUserTelegramNotification
{
    protected $telegram;

    /**
     * Create the event listener.
     */
    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        // $event->user berisi data user yang baru saja mendaftar
        $user = $event->user;

        // --- BAGIAN 1: KIRIM KE MEMBER BARU ---
        // Cek apakah user punya ID Telegram (diisi saat registrasi)
        if ($user->telegram_chat_id) {
            $pesanMember = "<b>🎉 SELAMAT DATANG DI MOKASINDO!</b>\n\n" .
                           "Halo <b>{$user->name}</b>,\n" .
                           "Terima kasih telah bergabung. Sekarang Anda bisa mengikuti lelang mobil & motor impian.\n\n" .
                           "<i>Happy Bidding!</i> 🛵🚗";

            // Kirim pesan ke member
            $this->telegram->sendToUser($user, $pesanMember);
            
            // Catat di log biar programmer tau
            Log::info("Notifikasi Telegram dikirim ke user baru: " . $user->email);
        } else {
            Log::info("User baru daftar tapi tidak punya Telegram ID: " . $user->email);
        }

        // --- BAGIAN 2: KIRIM LAPORAN KE ADMIN ---
        
        // // GANTI ID INI DENGAN ID TELEGRAM SIAPAPUN YANG MAU TES ambil di @userinfobot
        $adminChatId = '6179231520';
        
        $pesanAdmin = "<b>👮 LAPORAN ADMIN</b>\n\n" .
                      "Ada user baru mendaftar:\n" .
                      "👤 Nama: <b>{$user->name}</b>\n" .
                      "📧 Email: {$user->email}\n" .
                      "⏰ Waktu: " . date('d M Y, H:i') . " WIB";
                      
        // Kirim pesan ke admin
        $this->telegram->sendMessage($adminChatId, $pesanAdmin);
    }
}