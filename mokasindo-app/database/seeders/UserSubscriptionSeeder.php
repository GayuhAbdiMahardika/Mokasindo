<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserSubscription;
use App\Models\User;
use App\Models\SubscriptionPlan;

class UserSubscriptionSeeder extends Seeder
{
    public function run()
    {
        // Create a sample active subscription for user 1 if both exist
        $user = User::find(1);
        $plan = SubscriptionPlan::where('name', 'Pro')->first();

        if ($user && $plan) {
            UserSubscription::updateOrCreate(
                ['user_id' => $user->id, 'subscription_plan_id' => $plan->id],
                [
                    'start_date' => now(),
                    'end_date' => now()->addDays($plan->duration_days),
                    'status' => 'active',
                    'price_paid' => $plan->price,
                ]
            );
        }
    }
}
