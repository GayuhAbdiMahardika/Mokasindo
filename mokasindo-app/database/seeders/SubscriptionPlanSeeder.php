<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Free',
                'price' => 0,
                'duration_days' => 30,
                'features' => ['Basic listing', 'Limited support'],
                'is_active' => true,
            ],
            [
                'name' => 'Pro',
                'price' => 199000,
                'duration_days' => 30,
                'features' => ['Priority listing', 'Image gallery', 'Email support'],
                'is_active' => true,
            ],
            [
                'name' => 'Dealer',
                'price' => 799000,
                'duration_days' => 30,
                'features' => ['Unlimited listings', 'Dealer badge', 'Dedicated support'],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $p) {
            SubscriptionPlan::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
