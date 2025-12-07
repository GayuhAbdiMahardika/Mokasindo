<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    /**
     * Handle Telegram webhook updates.
     */
    public function webhook(Request $request)
    {
        $botToken = Config::get('services.telegram.bot_token');
        if (!$botToken) {
            return response()->json(['error' => 'Telegram bot token not configured'], 500);
        }

        $message = $request->input('message');
        if (!$message) {
            return response()->json(['ok' => true]);
        }

        $text = $message['text'] ?? '';
        $chatId = $message['chat']['id'] ?? null;
        $fromUsername = $message['from']['username'] ?? null;

        // Expecting: /start user_123
        if ($chatId && preg_match('/^\/start\s+user_(\d+)/', trim($text), $matches)) {
            $userId = (int) $matches[1];
            $user = User::find($userId);

            if ($user) {
                $user->update([
                    'telegram_chat_id' => $chatId,
                    'telegram_username' => $fromUsername,
                ]);

                $this->sendMessage($botToken, $chatId, "Akun Telegram Anda berhasil terhubung dengan Website Lelang! \u2705");
            } else {
                $this->sendMessage($botToken, $chatId, 'User tidak ditemukan. Silakan coba lagi.');
            }
        } elseif ($chatId) {
            $this->sendMessage($botToken, $chatId, 'Kirim perintah /start dari tombol "Connect Telegram" di profil Anda untuk menghubungkan akun.');
        }

        return response()->json(['ok' => true]);
    }

    private function sendMessage(string $botToken, int|string $chatId, string $text): void
    {
        try {
            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed sending Telegram message', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
