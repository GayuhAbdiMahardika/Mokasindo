<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\AuctionSchedule;
use App\Models\Bid;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Demo Seeder for client presentation.
 * Creates realistic auction data spanning Dec 2025 - Dec 2026 (13 months).
 */
class DemoSeeder extends Seeder
{
    private array $cities = [
        ['province' => 'DKI Jakarta', 'city' => 'Jakarta Selatan', 'district' => 'Kebayoran Baru', 'sub' => 'Senayan'],
        ['province' => 'DKI Jakarta', 'city' => 'Jakarta Pusat', 'district' => 'Menteng', 'sub' => 'Gondangdia'],
        ['province' => 'DKI Jakarta', 'city' => 'Jakarta Utara', 'district' => 'Kelapa Gading', 'sub' => 'Kelapa Gading Timur'],
        ['province' => 'Jawa Barat', 'city' => 'Bandung', 'district' => 'Coblong', 'sub' => 'Dago'],
        ['province' => 'Jawa Barat', 'city' => 'Bekasi', 'district' => 'Bekasi Selatan', 'sub' => 'Jaka Setia'],
        ['province' => 'Jawa Barat', 'city' => 'Bogor', 'district' => 'Bogor Tengah', 'sub' => 'Babakan'],
        ['province' => 'Jawa Timur', 'city' => 'Surabaya', 'district' => 'Tegalsari', 'sub' => 'Dr. Soetomo'],
        ['province' => 'Jawa Timur', 'city' => 'Malang', 'district' => 'Klojen', 'sub' => 'Oro-oro Dowo'],
        ['province' => 'Jawa Tengah', 'city' => 'Semarang', 'district' => 'Banyumanik', 'sub' => 'Pedalangan'],
        ['province' => 'Banten', 'city' => 'Tangerang', 'district' => 'Cipondoh', 'sub' => 'Poris Gaga'],
        ['province' => 'Bali', 'city' => 'Denpasar', 'district' => 'Denpasar Selatan', 'sub' => 'Sanur'],
        ['province' => 'Yogyakarta', 'city' => 'Yogyakarta', 'district' => 'Gondokusuman', 'sub' => 'Terban'],
    ];

    private array $auctionLocations = [
        'Jakarta Selatan' => 'Balai Lelang Jakarta Selatan, Jl. Fatmawati No. 12',
        'Jakarta Pusat' => 'Convention Hall Menteng, Jl. HOS Cokroaminoto No. 88',
        'Jakarta Utara' => 'Kelapa Gading Exhibition Center, Mall of Indonesia Lt. 3',
        'Bandung' => 'Graha Lelang Bandung, Jl. Dago No. 156',
        'Surabaya' => 'Surabaya Auction Center, Jl. Basuki Rahmat No. 100',
        'Semarang' => 'Semarang Trade Center, Jl. Pemuda No. 72',
        'Malang' => 'Malang Convention Center, Jl. Ijen No. 45',
        'Yogyakarta' => 'Jogja Expo Center, Jl. Janti No. 99',
        'Denpasar' => 'Bali Convention Center, Jl. Bypass Ngurah Rai',
        'Tangerang' => 'BSD Convention Center, Jl. Pahlawan Seribu',
    ];

    public function run(): void
    {
        $this->command->info('🚀 Starting Demo Seeder for Dec 2025 - Dec 2026...');

        // Create demo users
        $users = $this->createDemoUsers();
        $this->command->info("✓ Created {$users->count()} demo users");

        // Create auction schedules for 13 months
        $schedules = $this->createAuctionSchedules();
        $this->command->info("✓ Created {$schedules->count()} auction schedules");

        // Create vehicles and auctions
        $vehicleCount = $this->createVehiclesAndAuctions($users, $schedules);
        $this->command->info("✓ Created {$vehicleCount} vehicles with auctions");

        $this->command->info('🎉 Demo data seeding completed!');
    }

    private function createDemoUsers(): \Illuminate\Support\Collection
    {
        $users = collect();

        // Create 100 demo bidders/sellers
        for ($i = 1; $i <= 100; $i++) {
            $location = $this->cities[array_rand($this->cities)];
            $role = $i <= 80 ? 'member' : 'anggota';

            $users->push(User::factory()->create([
                'name' => fake('id_ID')->name(),
                'email' => "demo.user{$i}@mokasindo.test",
                'phone' => fake('id_ID')->phoneNumber(),
                'role' => $role,
                'province' => $location['province'],
                'city' => $location['city'],
                'district' => $location['district'],
                'sub_district' => $location['sub'],
                'created_at' => Carbon::create(2025, 12, 1)->subDays(fake()->numberBetween(30, 365)),
            ]));
        }

        return $users;
    }

    private function createAuctionSchedules(): \Illuminate\Support\Collection
    {
        $schedules = collect();
        $startMonth = Carbon::create(2025, 12, 1);
        $endMonth = Carbon::create(2026, 12, 31);

        $current = $startMonth->copy();
        $scheduleId = 1;

        while ($current <= $endMonth) {
            // Create 2-4 auction schedules per month
            $schedulesPerMonth = fake()->numberBetween(2, 4);

            for ($i = 0; $i < $schedulesPerMonth; $i++) {
                $city = array_rand($this->auctionLocations);
                $location = $this->auctionLocations[$city];

                // Random day in the month
                $day = fake()->numberBetween(1, min(28, $current->daysInMonth));
                $startDate = $current->copy()->day($day)->setTime(fake()->numberBetween(9, 14), 0);
                $endDate = $startDate->copy()->addDays(fake()->numberBetween(1, 3));

                // Determine status based on current date (Dec 15, 2025)
                $now = Carbon::create(2025, 12, 15);
                $isActive = $now->between($startDate, $endDate);

                $schedules->push(AuctionSchedule::create([
                    'title' => $this->generateScheduleTitle($city, $startDate),
                    'description' => "Lelang kendaraan bermotor resmi di {$city}. Berbagai pilihan mobil dan motor berkualitas dengan harga kompetitif.",
                    'location' => $location,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'is_active' => true,
                ]));

                $scheduleId++;
            }

            $current->addMonth();
        }

        return $schedules;
    }

    private function generateScheduleTitle(string $city, Carbon $date): string
    {
        $types = [
            'Lelang Reguler',
            'Lelang Premium',
            'Lelang Mobil & Motor',
            'Grand Auction',
            'Flash Auction',
        ];

        $type = $types[array_rand($types)];
        $monthName = $date->locale('id')->translatedFormat('F Y');

        return "{$type} {$city} - {$monthName}";
    }

    private function createVehiclesAndAuctions($users, $schedules): int
    {
        $vehicleCount = 0;
        $depositPercentage = Setting::get('deposit_percentage', 5);
        $now = Carbon::create(2025, 12, 15); // Current demo date

        foreach ($schedules as $schedule) {
            // 8-20 vehicles per schedule
            $vehiclesPerSchedule = fake()->numberBetween(8, 20);

            for ($i = 0; $i < $vehiclesPerSchedule; $i++) {
                $owner = $users->random();
                $location = $this->cities[array_rand($this->cities)];

                // Create vehicle
                $vehicle = Vehicle::factory()->create([
                    'user_id' => $owner->id,
                    'province' => $location['province'],
                    'city' => $location['city'],
                    'district' => $location['district'],
                    'sub_district' => $location['sub'],
                    'status' => 'approved',
                    'approved_at' => $schedule->start_date->copy()->subDays(fake()->numberBetween(3, 14)),
                    'approved_by' => 1, // Admin
                ]);

                $vehicleCount++;

                // Determine auction status based on schedule dates vs "now"
                $isScheduleEnded = $schedule->end_date < $now;
                $isScheduleActive = $now->between($schedule->start_date, $schedule->end_date);
                $isScheduleUpcoming = $schedule->start_date > $now;

                $auctionStatus = 'scheduled';
                if ($isScheduleEnded) {
                    $auctionStatus = fake()->randomElement(['ended', 'sold', 'sold', 'ended']); // 50% sold
                } elseif ($isScheduleActive) {
                    $auctionStatus = 'active';
                }

                // Create auction
                $auction = Auction::create([
                    'vehicle_id' => $vehicle->id,
                    'auction_schedule_id' => $schedule->id,
                    'starting_price' => $vehicle->starting_price,
                    'current_price' => $vehicle->starting_price,
                    'deposit_amount' => $vehicle->starting_price * ($depositPercentage / 100),
                    'deposit_percentage' => $depositPercentage,
                    'start_time' => $schedule->start_date,
                    'end_time' => $schedule->end_date,
                    'status' => $auctionStatus,
                    'payment_deadline' => $schedule->end_date->copy()->addHours(48),
                ]);

                // Create bids for active/ended/sold auctions
                if (in_array($auctionStatus, ['active', 'ended', 'sold'])) {
                    $this->createBidsForAuction($auction, $users, $auctionStatus);
                }
            }
        }

        return $vehicleCount;
    }

    private function createBidsForAuction(Auction $auction, $users, string $status): void
    {
        // Determine number of bidders (more for ended/sold)
        $bidderCount = match ($status) {
            'active' => fake()->numberBetween(2, 8),
            'ended', 'sold' => fake()->numberBetween(5, 15),
        };

        $bidders = $users->random(min($bidderCount, $users->count()));
        $currentPrice = $auction->starting_price;
        $bidCount = 0;

        // Each bidder might bid multiple times
        foreach ($bidders as $index => $bidder) {
            $bidsPerBidder = fake()->numberBetween(1, 3);

            for ($b = 0; $b < $bidsPerBidder; $b++) {
                // Increment between 250k - 2.5M (based on vehicle price)
                $incrementBase = $auction->starting_price > 100000000 ? 1000000 : 250000;
                $increment = fake()->numberBetween($incrementBase, $incrementBase * 5);

                $previousPrice = $currentPrice;
                $currentPrice += $increment;
                $bidCount++;

                $isLastBid = ($index === count($bidders) - 1) && ($b === $bidsPerBidder - 1);

                Bid::create([
                    'auction_id' => $auction->id,
                    'user_id' => $bidder->id,
                    'bid_amount' => $currentPrice,
                    'previous_amount' => $previousPrice,
                    'is_winner' => $isLastBid && $status === 'sold',
                    'is_auto_bid' => fake()->boolean(15),
                    'created_at' => $auction->start_time->copy()->addMinutes(fake()->numberBetween(10, 2880)),
                ]);
            }
        }

        // Update auction with final price and stats
        $updateData = [
            'current_price' => $currentPrice,
            'total_bids' => $bidCount,
            'total_participants' => $bidders->count(),
        ];

        if ($status === 'sold') {
            $winner = $bidders->last();
            $updateData['winner_id'] = $winner->id;
            $updateData['won_at'] = $auction->end_time;
        }

        $auction->update($updateData);
    }
}
