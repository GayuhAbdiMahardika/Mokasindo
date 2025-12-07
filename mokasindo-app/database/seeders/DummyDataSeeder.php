<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\AuctionSchedule;
use App\Models\Bid;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $pick = static fn (array $list) => !empty($list) ? $list[array_rand($list)] : null;
        $provinces = ['Jawa Barat', 'DKI Jakarta', 'Jawa Timur', 'Jawa Tengah', 'Banten'];
        $cities = ['Bandung', 'Jakarta Selatan', 'Surabaya', 'Semarang', 'Tangerang'];
        $districts = ['Coblong', 'Kebayoran Baru', 'Tegalsari', 'Banyumanik', 'Cipondoh'];
        $subs = ['Dago', 'Senayan', 'Dr. Soetomo', 'Pedalangan', 'Poris Gaga'];

        $users = User::factory()
            ->count(80)
            ->state(fn () => [
                'province' => $pick($provinces),
                'city' => $pick($cities),
                'district' => $pick($districts),
                'sub_district' => $pick($subs),
            ])
            ->create();

        $schedules = AuctionSchedule::factory()
            ->count(10)
            ->create();

        $vehicles = Vehicle::factory()
            ->count(200)
            ->state(fn () => [
                'user_id' => $users->random()->id,
                'province' => $pick($provinces),
                'city' => $pick($cities),
                'district' => $pick($districts),
                'sub_district' => $pick($subs),
            ])
            ->create();

        foreach ($vehicles as $vehicle) {
            $schedule = $schedules->random();
            $start = Carbon::instance(fake()->dateTimeBetween('-10 days', '+2 days'));
            $end = (clone $start)->addHours(fake()->numberBetween(12, 48));

            $auction = Auction::factory()->create([
                'vehicle_id' => $vehicle->id,
                'auction_schedule_id' => $schedule->id,
                'start_time' => $start,
                'end_time' => $end,
                'status' => now()->between($start, $end) ? 'active' : 'ended',
                'payment_deadline' => (clone $end)->addHours(24),
            ]);

            $bidders = $users->random(fake()->numberBetween(5, 12));
            $currentPrice = $auction->starting_price;

            foreach ($bidders as $index => $user) {
                $increment = fake()->numberBetween(250000, 2500000);
                $previous = $currentPrice;
                $currentPrice += $increment;

                Bid::factory()->create([
                    'auction_id' => $auction->id,
                    'user_id' => $user->id,
                    'bid_amount' => $currentPrice,
                    'previous_amount' => $previous,
                    'is_winner' => $index === (count($bidders) - 1),
                    'is_auto_bid' => fake()->boolean(10),
                ]);
            }

            $auction->update([
                'current_price' => $currentPrice,
                'total_bids' => $bidders->count(),
                'total_participants' => $bidders->count(),
                'winner_id' => $bidders->last()->id,
                'won_at' => $end,
            ]);
        }
    }
}
