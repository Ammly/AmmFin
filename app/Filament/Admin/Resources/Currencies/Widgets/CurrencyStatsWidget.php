<?php

namespace App\Filament\Admin\Resources\Currencies\Widgets;

use App\Models\Account;
use App\Models\Currency;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CurrencyStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Currency status
        $activeCurrencies = Currency::where('is_active', true)->count();
        $inactiveCurrencies = Currency::where('is_active', false)->count();
        $totalCurrencies = Currency::count();

        // Usage statistics
        $currenciesInUse = Currency::whereHas('accounts')->count();
        $unusedCurrencies = Currency::whereDoesntHave('accounts')->count();

        // Account distribution by currency
        $mostUsedCurrency = Currency::withCount('accounts')
            ->orderBy('accounts_count', 'desc')
            ->first();

        // Exchange rate statistics
        $avgExchangeRate = Currency::where('is_active', true)->avg('exchange_rate') ?? 0;
        $highestExchangeRate = Currency::where('is_active', true)->max('exchange_rate') ?? 0;
        $lowestExchangeRate = Currency::where('is_active', true)->min('exchange_rate') ?? 0;

        // Total value by currency (sum of all account balances - admin view)
        $totalValueAllCurrencies = Account::join('currencies', 'accounts.currency_id', '=', 'currencies.id')
            ->sum('accounts.balance');

        return [
            Stat::make('Total Currencies', $totalCurrencies)
                ->description($activeCurrencies.' active, '.$inactiveCurrencies.' inactive')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color($inactiveCurrencies > 0 ? 'warning' : 'success')
                ->chart([3, 4, 5, 6, 7, 8, 7, 8]),

            Stat::make('Currencies in Use', $currenciesInUse)
                ->description($unusedCurrencies.' unused currencies')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary')
                ->chart([2, 3, 4, 5, 4, 5, 4, 5]),

            Stat::make('Most Used Currency', $mostUsedCurrency?->code ?? 'N/A')
                ->description(($mostUsedCurrency?->accounts_count ?? 0).' accounts using it')
                ->descriptionIcon('heroicon-m-star')
                ->color('success')
                ->chart([1, 2, 3, 4, 5, 6, 7, 8]),

            Stat::make('Average Exchange Rate', number_format($avgExchangeRate, 4))
                ->description('For active currencies')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info')
                ->chart([1.0, 1.1, 1.05, 1.2, 1.15, 1.3, 1.25, 1.35]),

            Stat::make('Exchange Rate Range', number_format($lowestExchangeRate, 4).' - '.number_format($highestExchangeRate, 4))
                ->description('Min - Max rates')
                ->descriptionIcon('heroicon-m-arrows-right-left')
                ->color('warning')
                ->chart([0.5, 0.8, 1.0, 1.2, 1.5, 1.8, 2.0, 2.2]),

            Stat::make('Total Value', '$'.number_format($totalValueAllCurrencies, 2))
                ->description('All currencies combined')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('gray')
                ->chart([100, 150, 200, 250, 300, 350, 400, 450]),
        ];
    }
}
