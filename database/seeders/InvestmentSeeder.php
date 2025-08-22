<?php

// database/seeders/InvestmentSeeder.php

namespace Database\Seeders;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvestmentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $investments = [
            [
                'symbol' => 'AAPL',
                'name' => 'Apple Inc.',
                'type' => 'stock',
                'quantity' => 50,
                'purchase_price' => 150.00,
                'current_price' => 175.50,
            ],
            [
                'symbol' => 'BTC',
                'name' => 'Bitcoin',
                'type' => 'crypto',
                'quantity' => 0.5,
                'purchase_price' => 45000.00,
                'current_price' => 52000.00,
            ],
            [
                'symbol' => 'GOOGL',
                'name' => 'Alphabet Inc.',
                'type' => 'stock',
                'quantity' => 25,
                'purchase_price' => 2500.00,
                'current_price' => 2750.00,
            ],
        ];

        foreach ($investments as $investment) {
            $totalValue = $investment['quantity'] * $investment['current_price'];
            $purchaseValue = $investment['quantity'] * $investment['purchase_price'];
            $profitLoss = $totalValue - $purchaseValue;
            $profitLossPercentage = (($investment['current_price'] - $investment['purchase_price']) / $investment['purchase_price']) * 100;

            Investment::updateOrCreate(array_merge($investment, [
                'user_id' => $user->id,
                'total_value' => $totalValue,
                'profit_loss' => $profitLoss,
                'profit_loss_percentage' => $profitLossPercentage,
                'purchased_at' => now()->subMonths(rand(1, 12)),
            ]));
        }
    }
}
