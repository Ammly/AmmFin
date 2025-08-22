<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => $this->faker->unique()->uuid(),
            'user_id' => User::factory(),
            'account_id' => Account::factory(),
            'type' => $this->faker->randomElement(['debit', 'credit']),
            'category' => $this->faker->randomElement(['food', 'transport', 'entertainment', 'bills']),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'metadata' => [],
            'processed_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
