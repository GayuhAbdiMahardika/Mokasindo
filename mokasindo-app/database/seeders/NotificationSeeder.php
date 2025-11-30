<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::take(3)->get();

        foreach ($users as $user) {
            Notification::updateOrCreate(
                ['user_id' => $user->id, 'type' => 'auction_start', 'title' => 'Lelang Dimulai untuk Anda'],
                [
                    'message' => 'Auction mingguan dimulai. Segera pasang penawaran terbaik Anda!',
                    'data' => ['cta' => '/auctions'],
                    'is_read' => false,
                ]
            );

            Notification::updateOrCreate(
                ['user_id' => $user->id, 'type' => 'payment_reminder', 'title' => 'Pengingat Pelunasan'],
                [
                    'message' => 'Segera lakukan pelunasan sebelum tenggat untuk menghindari sanksi.',
                    'data' => ['deadline' => now()->addDay()->toDateTimeString()],
                    'is_read' => $user->id % 2 === 0,
                    'read_at' => $user->id % 2 === 0 ? now() : null,
                ]
            );
        }
    }
}
