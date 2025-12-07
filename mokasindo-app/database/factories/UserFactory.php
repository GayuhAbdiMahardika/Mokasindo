<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = ['anggota', 'member', 'admin'];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => fake()->randomElement($roles),
            'phone' => '08' . fake()->numerify('##########'),
            'address' => fake()->address(),
            'postal_code' => fake()->postcode(),
            'avatar' => null,
            'is_active' => true,
            'email_verified_at' => now(),
            'verified_at' => fake()->boolean(70) ? now()->subDays(fake()->numberBetween(1, 90)) : null,
            'weekly_post_count' => 0,
            'last_post_reset' => now()->subDays(fake()->numberBetween(1, 7)),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
