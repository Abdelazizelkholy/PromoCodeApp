<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Models\PromCode;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PromCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->word, // Generate a unique promo code
            'type' => fake()->randomElement(['value', 'percentage']), // Randomly choose promo code type
            'amount' => fake()->randomFloat(2, 1, 100), // Generate a random amount between 1 and 100
            'expiry_date' => fake()->dateTimeBetween('+1 day', '+1 month'), // Generate a random expiry date between 1 day and 1 month from now
            'max_usages' => fake()->randomElement([null, fake()->numberBetween(1, 10)]), // Randomly set max usages or null
            'max_usages_per_user' => fake()->randomElement([null, fake()->numberBetween(1, 5)]), // Randomly set max usages per user or null
            'user_ids' => fake()->randomElement([null, json_encode([fake()->numberBetween(1, 10), fake()->numberBetween(11, 20)])]), // Randomly set user ids or null
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
