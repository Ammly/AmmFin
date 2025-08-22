<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Account;
use App\Models\Activity;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-s-users')
                ->color('primary'),

            Stat::make('Total Accounts', Account::count())
                ->description('User accounts')
                ->descriptionIcon('heroicon-s-wallet')
                ->color('success'),

            Stat::make('Total Transactions', Transaction::count())
                ->description('All transactions')
                ->descriptionIcon('heroicon-s-arrows-right-left')
                ->color('info'),

            Stat::make('Total Investments', Investment::count())
                ->description('Investment accounts')
                ->descriptionIcon('heroicon-s-chart-bar')
                ->color('warning'),

            Stat::make('Recent Activities', Activity::where('created_at', '>=', now()->subDays(30))->count())
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-s-clock')
                ->color('gray'),

            Stat::make('Total Balance', '$'.number_format(Account::sum('balance'), 2))
                ->description('All accounts combined')
                ->descriptionIcon('heroicon-s-banknotes')
                ->color('success'),
        ];
    }
}
