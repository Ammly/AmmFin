<?php

namespace App\Filament\Resources\Accounts\Widgets;

use App\Models\Account;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AccountStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        // Account type distribution - Only user's accounts
        $walletAccounts = Account::where('user_id', $userId)->where('type', 'wallet')->count();
        $savingsAccounts = Account::where('user_id', $userId)->where('type', 'savings')->count();
        $investmentAccounts = Account::where('user_id', $userId)->where('type', 'investment')->count();

        // Balance distribution - Only user's accounts
        $walletBalance = Account::where('user_id', $userId)->where('type', 'wallet')->sum('balance');
        $savingsBalance = Account::where('user_id', $userId)->where('type', 'savings')->sum('balance');
        $investmentBalance = Account::where('user_id', $userId)->where('type', 'investment')->sum('balance');

        // Account status - Only user's accounts
        $activeAccounts = Account::where('user_id', $userId)->where('is_active', true)->count();
        $inactiveAccounts = Account::where('user_id', $userId)->where('is_active', false)->count();

        // Currency distribution - Only user's accounts
        $accountsWithLimits = Account::where('user_id', $userId)->whereNotNull('limit_amount')->count();

        return [
            Stat::make('Wallet Accounts', $walletAccounts)
                ->description('$'.number_format($walletBalance, 2).' total balance')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('success')
                ->chart([3, 4, 5, 6, 5, 7, 6, 8]),

            Stat::make('Savings Accounts', $savingsAccounts)
                ->description('$'.number_format($savingsBalance, 2).' total balance')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary')
                ->chart([2, 3, 4, 5, 6, 7, 8, 9]),

            Stat::make('Investment Accounts', $investmentAccounts)
                ->description('$'.number_format($investmentBalance, 2).' total balance')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning')
                ->chart([1, 2, 3, 4, 5, 6, 7, 8]),

            Stat::make('Active Accounts', $activeAccounts)
                ->description($inactiveAccounts.' inactive accounts')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($inactiveAccounts > 0 ? 'warning' : 'success')
                ->chart([5, 6, 7, 8, 9, 10, 9, 11]),

            Stat::make('Accounts with Limits', $accountsWithLimits)
                ->description('Spending limit configured')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('info')
                ->chart([2, 3, 4, 3, 5, 4, 6, 5]),

            Stat::make('Average Balance', '$'.number_format(Account::where('user_id', $userId)->avg('balance') ?? 0, 2))
                ->description('Per account')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('gray')
                ->chart([50, 60, 70, 65, 80, 75, 90, 85]),
        ];
    }
}
