<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoleQuota;

class RoleQuotaSeeder extends Seeder
{
    public function run()
    {
        $quotas = [
            ['role' => 'member', 'post_limit' => 5],
            ['role' => 'dealer', 'post_limit' => 50],
            ['role' => 'admin', 'post_limit' => 1000],
        ];

        foreach ($quotas as $q) {
            RoleQuota::updateOrCreate(['role' => $q['role']], $q);
        }
    }
}
