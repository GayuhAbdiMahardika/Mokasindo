<?php

namespace Database\Factories;

use App\Models\Auction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<\App\Models\Auction>
 */
class AuctionFactory extends Factory
{
    protected $model = Auction::class;

    public function definition(): array
    {
        $startingPrice = fake()->numberBetween(10000000, 250000000);
        $duration = fake()->numberBetween(12, 48);
        $start = Carbon::now()->addHours(fake()->numberBetween(-120, 96));
        $end = (clone $start)->addHours($duration);

        return [
            'vehicle_id' => null,
            'auction_schedule_id' => null,
            'starting_price' => $startingPrice,
            'current_price' => $startingPrice,
            'reserve_price' => $startingPrice + fake()->numberBetween(1000000, 10000000),
            'deposit_percentage' => 5.0,
            'deposit_amount' => round($startingPrice * 0.05, 2),
            'start_time' => $start,
            'end_time' => $end,
            'duration_hours' => $duration,
            'status' => fake()->randomElement(['scheduled', 'active', 'ended']),
            'winner_id' => null,
            'won_at' => null,
            'payment_deadline' => null,
            'payment_deadline_hours' => 24,
            'payment_completed' => false,
            'payment_completed_at' => null,
            'total_bids' => 0,
            'total_participants' => 0,
            'notes' => fake()->sentence(8),
        ];
    }
}
