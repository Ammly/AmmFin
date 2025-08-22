<?php

// database/seeders/AccountSeeder.php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $currencies = Currency::all();

        // Create wallet accounts for each currency
        foreach ($currencies as $currency) {
            Account::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'currency_id' => $currency->id,
                    'type' => 'wallet',
                ],
                [
                    'name' => $currency->name.' Wallet',
                    'balance' => match ($currency->code) {
                        'KES' => 100000.00,
                        'USD' => 22678.00,
                        'EUR' => 18345.00,
                        'BDT' => 1226780.00,
                        'GBP' => 15000.00,
                        'TZS' => 2300000.00,
                        'UGX' => 3700000.00,
                        'RWF' => 12000000.00,
                        default => 10000.00,
                    },
                    'limit_amount' => match ($currency->code) {
                        'KES' => 100000.00,
                        'USD' => 10000.00,
                        'EUR' => 8000.00,
                        'BDT' => 1000000.00,
                        'GBP' => 7500.00,
                        'TZS' => 230000.00,
                        'UGX' => 300000.00,
                        'RWF' => 1000000.00,
                        default => 50000.00,
                    },
                    'limit_period' => 'month',
                    'is_active' => match ($currency->code) {
                        'GBP' => false,
                        default => true,
                    },
                ]
            );
        }

        // Create savings account
        Account::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => 'savings',
                'name' => 'Steady Growth Savings',
            ],
            [
                'currency_id' => Currency::where('code', 'KES')->first()->id,
                'balance' => 15800.45,
                'is_active' => true,
            ]
        );

        // Create investment account
        Account::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => 'investment',
                'name' => 'Investment Portfolio',
            ],
            [
                'currency_id' => Currency::where('code', 'KES')->first()->id,
                'balance' => 50120.78,
                'is_active' => true,
            ]
        );
    }
}
