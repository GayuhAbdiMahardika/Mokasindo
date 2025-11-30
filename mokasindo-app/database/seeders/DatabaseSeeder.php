<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProvinceSeeder::class,
            CitySeeder::class,
            DistrictSeeder::class,
            SubDistrictSeeder::class,
            SettingSeeder::class,
            AdminSeeder::class,
            CompanySeeder::class,
            PageRevisionSeeder::class,
            InquirySeeder::class,
            JobApplicationSeeder::class,
            NotificationSeeder::class,
            SubscriptionPlanSeeder::class,
            RoleQuotaSeeder::class,
            UserQuotaOverrideSeeder::class,
            UserSubscriptionSeeder::class,
            MarketplaceSeeder::class,
        ]);
    }
}
