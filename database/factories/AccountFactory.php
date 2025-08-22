<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'currency_id' => Currency::factory(),
            'type' => $this->faker->randomElement(['checking', 'savings', 'credit']),
            'name' => $this->faker->words(2, true).' Account',
            'balance' => $this->faker->randomFloat(2, 0, 10000),
            'limit_amount' => $this->faker->optional()->randomFloat(2, 1000, 50000),
            'limit_period' => $this->faker->optional()->randomElement(['daily', 'weekly', 'monthly']),
            'is_active' => $this->faker->boolean(90),
            'metadata' => [],
        ];
    }
}
