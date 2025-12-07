<?php

namespace Database\Factories;

use App\Models\AuctionSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<\App\Models\AuctionSchedule>
 */
class AuctionScheduleFactory extends Factory
{
    protected $model = AuctionSchedule::class;

    public function definition(): array
    {
        $start = Carbon::now()->addDays(fake()->numberBetween(-5, 5));
        $end = (clone $start)->addDays(fake()->numberBetween(1, 3));

        return [
            'title' => 'Lelang ' . fake()->city(),
            'description' => fake()->sentence(10),
            'location' => fake()->city(),
            'start_date' => $start,
            'end_date' => $end,
            'is_active' => true,
        ];
    }
}
