<?php

namespace Database\Factories;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Bid>
 */
class BidFactory extends Factory
{
    protected $model = Bid::class;

    public function definition(): array
    {
        $amount = fake()->numberBetween(1000000, 25000000);

        return [
            'auction_id' => Auction::factory(),
            'user_id' => User::factory(),
            'bid_amount' => $amount,
            'previous_amount' => null,
            'is_winner' => false,
            'is_auto_bid' => fake()->boolean(15),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }
}
