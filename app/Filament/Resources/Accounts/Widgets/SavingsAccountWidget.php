<?php

namespace App\Filament\Resources\Accounts\Widgets;

use App\Models\Account;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SavingsAccountWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        // Savings account specific statistics
        $savingsAccounts = $user->accounts()->where('type', 'savings')->get();
        $totalSavingsBalance = $savingsAccounts->sum('balance');
        $activeSavingsAccounts = $savingsAccounts->where('is_active', true)->count();
        $avgSavingsBalance = $savingsAccounts->avg('balance') ?? 0;
        $largestSavingsBalance = $savingsAccounts->max('balance') ?? 0;

        return [
            Stat::make('Total Savings', '$'.number_format($totalSavingsBalance, 2))
                ->description('Across '.$savingsAccounts->count().' savings accounts')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([50, 60, 70, 80, 90, 100, 110, 120]),

            Stat::make('Active Savings Accounts', $activeSavingsAccounts)
                ->description($savingsAccounts->count().' total savings accounts')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('primary')
                ->chart([1, 2, 3, 4, 3, 4, 3, 4]),

            Stat::make('Average Balance', '$'.number_format($avgSavingsBalance, 2))
                ->description('Per savings account')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info')
                ->chart([30, 35, 40, 45, 50, 55, 60, 65]),

            Stat::make('Largest Account', '$'.number_format($largestSavingsBalance, 2))
                ->description('Biggest savings balance')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->chart([80, 90, 100, 110, 120, 130, 140, 150]),
        ];
    }
}
