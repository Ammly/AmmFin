<?php

// database/seeders/TransactionSeeder.php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $accounts = Account::where('user_id', $user->id)->get();

        $transactions = [
            [
                'order_id' => 'INV_000076',
                'account_id' => $accounts->where('type', 'wallet')->first()->id,
                'type' => 'debit',
                'category' => 'payment',
                'amount' => 25500,
                'description' => 'Software License',
                'status' => 'completed',
                'processed_at' => now()->subDays(1),
            ],
            [
                'order_id' => 'INV_000075',
                'account_id' => $accounts->where('type', 'wallet')->first()->id,
                'type' => 'debit',
                'category' => 'payment',
                'amount' => 23750,
                'description' => 'Flight Ticket',
                'status' => 'pending',
                'processed_at' => null,
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::updateOrCreate(array_merge($transaction, [
                'user_id' => $user->id,
                'created_at' => $transaction['processed_at'] ?? now(),
            ]));
        }
    }
}
