<?php

// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CurrencySeeder::class,
            AccountSeeder::class,
            TransactionSeeder::class,
            InvestmentSeeder::class,
            ActivitySeeder::class,
        ]);
    }
}
