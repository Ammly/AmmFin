<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->currencyCode(),
            'name' => $this->faker->word(),
            'symbol' => $this->faker->randomElement(['$', '€', '£', '¥']),
            'exchange_rate' => $this->faker->randomFloat(8, 0.01, 100),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
