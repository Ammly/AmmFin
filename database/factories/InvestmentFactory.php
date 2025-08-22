<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Investment>
 */
class InvestmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $purchasePrice = $this->faker->randomFloat(2, 1, 1000);
        $currentPrice = $this->faker->randomFloat(2, 1, 1000);
        $quantity = $this->faker->randomFloat(8, 0.1, 100);
        $totalValue = $quantity * $currentPrice;
        $profitLoss = $totalValue - ($quantity * $purchasePrice);
        $profitLossPercentage = (($currentPrice - $purchasePrice) / $purchasePrice) * 100;

        return [
            'user_id' => User::factory(),
            'symbol' => $this->faker->lexify('???'),
            'name' => $this->faker->company(),
            'type' => $this->faker->randomElement(['stock', 'crypto', 'bond', 'mutual_fund']),
            'quantity' => $quantity,
            'purchase_price' => $purchasePrice,
            'current_price' => $currentPrice,
            'total_value' => $totalValue,
            'profit_loss' => $profitLoss,
            'profit_loss_percentage' => $profitLossPercentage,
            'purchased_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
