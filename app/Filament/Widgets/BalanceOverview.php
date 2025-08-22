<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use App\Models\Activity;
use App\Models\Currency;
use App\Models\Investment;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BalanceOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $userId = auth()->id();

        // Account Statistics - Only user's accounts
        $totalAccounts = Account::where('user_id', $userId)->count();
        $activeAccounts = Account::where('user_id', $userId)->where('is_active', true)->count();
        $totalBalance = Account::where('user_id', $userId)->sum('balance');
        $walletBalance = Account::where('user_id', $userId)->where('type', 'wallet')->sum('balance');

        // Transaction Statistics - Only user's transactions
        $totalTransactions = Transaction::where('user_id', $userId)->count();
        $completedTransactions = Transaction::where('user_id', $userId)->where('status', 'completed')->count();
        $pendingTransactions = Transaction::where('user_id', $userId)->where('status', 'pending')->count();
        $totalTransactionVolume = Transaction::where('user_id', $userId)->where('status', 'completed')->sum('amount');

        // Investment Statistics - Only user's investments
        $totalInvestments = Investment::where('user_id', $userId)->count();
        $totalInvestmentValue = Investment::where('user_id', $userId)->sum('total_value');
        $totalProfitLoss = Investment::where('user_id', $userId)->sum('profit_loss');

        // Activity Statistics - Only user's activities
        $totalActivities = Activity::where('user_id', $userId)->count();
        $recentActivities = Activity::where('user_id', $userId)->whereDate('created_at', '>=', now()->subDays(7))->count();

        // Currency Statistics - Currencies are shared, so keep as is
        $activeCurrencies = Currency::where('is_active', true)->count();

        return [
            Stat::make('Total Accounts', $totalAccounts)
                ->description("{$activeAccounts} active accounts")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([3, 5, 7, 6, 8, 9, 7, 8]),

            Stat::make('Total Balance', '$'.number_format($totalBalance, 2))
                ->description('Across all accounts')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary')
                ->chart([10, 12, 8, 15, 18, 20, 17, 22]),

            Stat::make('Transactions', $totalTransactions)
                ->description("{$completedTransactions} completed, {$pendingTransactions} pending")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($pendingTransactions > 0 ? 'warning' : 'success')
                ->chart([5, 8, 12, 10, 15, 18, 20, 16]),

            Stat::make('Transaction Volume', '$'.number_format($totalTransactionVolume, 2))
                ->description('Total completed volume')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('info')
                ->chart([100, 120, 150, 180, 200, 175, 220, 250]),

            Stat::make('Investments', $totalInvestments)
                ->description('$'.number_format($totalInvestmentValue, 2).' total value')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($totalProfitLoss >= 0 ? 'success' : 'danger')
                ->chart([8, 10, 12, 9, 15, 18, 16, 20]),

            Stat::make('Recent Activities', $recentActivities)
                ->description('Last 7 days')
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray')
                ->chart([2, 4, 3, 6, 5, 8, 7, 5]),
        ];
    }
}
