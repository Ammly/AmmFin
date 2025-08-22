<?php

namespace App\Filament\Resources\Investments\Widgets;

use App\Models\Investment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InvestmentStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        // Investment type distribution - Only user's investments
        $stockInvestments = Investment::where('user_id', $userId)->where('type', 'stock')->count();
        $cryptoInvestments = Investment::where('user_id', $userId)->where('type', 'crypto')->count();
        $bondInvestments = Investment::where('user_id', $userId)->where('type', 'bond')->count();
        $fundInvestments = Investment::where('user_id', $userId)->where('type', 'fund')->count();

        // Performance statistics - Only user's investments
        $totalInvestments = Investment::where('user_id', $userId)->count();
        $totalValue = Investment::where('user_id', $userId)->sum('total_value');
        $totalProfitLoss = Investment::where('user_id', $userId)->sum('profit_loss');
        $avgProfitLossPercentage = Investment::where('user_id', $userId)->avg('profit_loss_percentage') ?? 0;

        // Profitable vs Loss-making - Only user's investments
        $profitableInvestments = Investment::where('user_id', $userId)->where('profit_loss', '>', 0)->count();
        $lossInvestments = Investment::where('user_id', $userId)->where('profit_loss', '<', 0)->count();

        // Value statistics - Only user's investments
        $avgInvestmentValue = Investment::where('user_id', $userId)->avg('total_value') ?? 0;
        $largestInvestment = Investment::where('user_id', $userId)->max('total_value') ?? 0;

        return [
            Stat::make('Total Investments', $totalInvestments)
                ->description('$'.number_format($totalValue, 2).' total value')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary')
                ->chart([5, 8, 12, 15, 18, 20, 22, 25]),

            Stat::make('Portfolio P&L', '$'.number_format($totalProfitLoss, 2))
                ->description(number_format($avgProfitLossPercentage, 2).'% average return')
                ->descriptionIcon($totalProfitLoss >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($totalProfitLoss >= 0 ? 'success' : 'danger')
                ->chart($totalProfitLoss >= 0 ? [10, 12, 15, 18, 20, 22, 25, 28] : [25, 22, 18, 15, 12, 10, 8, 5]),

            Stat::make('Profitable Investments', $profitableInvestments)
                ->description($lossInvestments.' making losses')
                ->descriptionIcon('heroicon-m-trophy')
                ->color($profitableInvestments > $lossInvestments ? 'success' : 'warning')
                ->chart([3, 5, 7, 8, 10, 12, 14, 16]),

            Stat::make('Stock Investments', $stockInvestments)
                ->description($cryptoInvestments.' crypto, '.$bondInvestments.' bonds, '.$fundInvestments.' funds')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('info')
                ->chart([2, 4, 6, 8, 7, 9, 8, 10]),

            Stat::make('Average Investment', '$'.number_format($avgInvestmentValue, 2))
                ->description('Per investment position')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('gray')
                ->chart([50, 60, 55, 70, 65, 80, 75, 85]),

            Stat::make('Largest Position', '$'.number_format($largestInvestment, 2))
                ->description('Biggest single investment')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->chart([100, 120, 150, 180, 200, 250, 300, 350]),
        ];
    }
}
