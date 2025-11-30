<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserQuotaOverride;
use App\Models\User;

class UserQuotaOverrideSeeder extends Seeder
{
    public function run()
    {
        // If user #1 exists, give them a generous override for testing
        $user = User::find(1);
        if ($user) {
            UserQuotaOverride::updateOrCreate(
                ['user_id' => $user->id],
                ['post_limit' => 100]
            );
        }
    }
}
