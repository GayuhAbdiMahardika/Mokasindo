<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin Mokasindo',
            'email' => 'admin@mokasindo.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
            'verified_at' => now(),
        ]);

        \App\Models\User::create([
            'name' => 'Test Member',
            'email' => 'member@test.com',
            'password' => bcrypt('password'),
            'role' => 'member',
            'is_active' => true,
            'email_verified_at' => now(),
            'verified_at' => now(),
        ]);

        \App\Models\User::create([
            'name' => 'Test Anggota',
            'email' => 'anggota@test.com',
            'password' => bcrypt('password'),
            'role' => 'anggota',
            'is_active' => true,
            'email_verified_at' => now(),
            'verified_at' => now(),
        ]);
    }
}
