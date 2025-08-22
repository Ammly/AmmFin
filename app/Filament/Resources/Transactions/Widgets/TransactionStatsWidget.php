<?php

namespace App\Filament\Resources\Transactions\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransactionStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        // Transaction status distribution - Only user's transactions
        $pendingTransactions = Transaction::where('user_id', $userId)->where('status', 'pending')->count();
        $completedTransactions = Transaction::where('user_id', $userId)->where('status', 'completed')->count();
        $failedTransactions = Transaction::where('user_id', $userId)->where('status', 'failed')->count();
        $cancelledTransactions = Transaction::where('user_id', $userId)->where('status', 'cancelled')->count();

        // Transaction type distribution - Only user's transactions
        $creditTransactions = Transaction::where('user_id', $userId)->where('type', 'credit')->count();
        $debitTransactions = Transaction::where('user_id', $userId)->where('type', 'debit')->count();

        // Category distribution - Only user's transactions
        $transferTransactions = Transaction::where('user_id', $userId)->where('category', 'transfer')->count();
        $paymentTransactions = Transaction::where('user_id', $userId)->where('category', 'payment')->count();
        $depositTransactions = Transaction::where('user_id', $userId)->where('category', 'deposit')->count();
        $withdrawalTransactions = Transaction::where('user_id', $userId)->where('category', 'withdrawal')->count();

        // Volume statistics - Only user's transactions
        $totalVolume = Transaction::where('user_id', $userId)->where('status', 'completed')->sum('amount');
        $avgTransactionAmount = Transaction::where('user_id', $userId)->where('status', 'completed')->avg('amount') ?? 0;

        // Recent activity - Only user's transactions
        $todayTransactions = Transaction::where('user_id', $userId)->whereDate('created_at', today())->count();
        $thisWeekTransactions = Transaction::where('user_id', $userId)->whereDate('created_at', '>=', now()->startOfWeek())->count();

        return [
            Stat::make('Pending Transactions', $pendingTransactions)
                ->description('Awaiting processing')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingTransactions > 0 ? 'warning' : 'success')
                ->chart([2, 4, 3, 5, 6, 4, 7, 5]),

            Stat::make('Completed Transactions', $completedTransactions)
                ->description($failedTransactions.' failed, '.$cancelledTransactions.' cancelled')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([8, 12, 15, 18, 20, 22, 25, 28]),

            Stat::make('Credit vs Debit', $creditTransactions.' / '.$debitTransactions)
                ->description('Credit / Debit transactions')
                ->descriptionIcon('heroicon-m-arrows-right-left')
                ->color('primary')
                ->chart([5, 8, 6, 10, 7, 12, 9, 14]),

            Stat::make('Total Volume', '$'.number_format($totalVolume, 2))
                ->description('Completed transactions only')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('info')
                ->chart([100, 150, 200, 180, 250, 220, 300, 280]),

            Stat::make('Average Amount', '$'.number_format($avgTransactionAmount, 2))
                ->description('Per completed transaction')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('gray')
                ->chart([50, 55, 60, 58, 65, 62, 70, 68]),

            Stat::make('Today\'s Activity', $todayTransactions)
                ->description($thisWeekTransactions.' this week')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success')
                ->chart([1, 3, 2, 4, 3, 5, 4, 6]),
        ];
    }
}
