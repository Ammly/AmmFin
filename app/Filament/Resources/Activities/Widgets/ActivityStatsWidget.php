<?php

namespace App\Filament\Resources\Activities\Widgets;

use App\Models\Activity;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActivityStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        // Activity type distribution - Only user's activities
        $transactionActivities = Activity::where('user_id', $userId)->where('type', 'transaction')->count();
        $investmentActivities = Activity::where('user_id', $userId)->where('type', 'investment')->count();
        $systemActivities = Activity::where('user_id', $userId)->where('type', 'system')->count();

        // Status distribution - assuming common status values - Only user's activities
        $completedActivities = Activity::where('user_id', $userId)->where('status', 'completed')->count();
        $pendingActivities = Activity::where('user_id', $userId)->where('status', 'pending')->count();
        $failedActivities = Activity::where('user_id', $userId)->where('status', 'failed')->count();

        // Time-based statistics - Only user's activities
        $todayActivities = Activity::where('user_id', $userId)->whereDate('created_at', today())->count();
        $thisWeekActivities = Activity::where('user_id', $userId)->whereDate('created_at', '>=', now()->startOfWeek())->count();
        $thisMonthActivities = Activity::where('user_id', $userId)->whereDate('created_at', '>=', now()->startOfMonth())->count();

        // Amount statistics (where applicable) - Only user's activities
        $totalActivityAmount = Activity::where('user_id', $userId)->whereNotNull('amount')->sum('amount');
        $avgActivityAmount = Activity::where('user_id', $userId)->whereNotNull('amount')->avg('amount') ?? 0;
        $activitiesWithAmount = Activity::where('user_id', $userId)->whereNotNull('amount')->count();

        return [
            Stat::make('Total Activities', Activity::count())
                ->description($thisMonthActivities.' this month')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary')
                ->chart([10, 15, 20, 18, 25, 22, 30, 28]),

            Stat::make('Transaction Activities', $transactionActivities)
                ->description($investmentActivities.' investment, '.$systemActivities.' system')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('success')
                ->chart([8, 12, 10, 15, 12, 18, 15, 20]),

            Stat::make('Completed Activities', $completedActivities)
                ->description($pendingActivities.' pending, '.$failedActivities.' failed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($failedActivities > 0 ? 'warning' : 'success')
                ->chart([5, 8, 12, 15, 18, 20, 22, 25]),

            Stat::make('Today\'s Activities', $todayActivities)
                ->description($thisWeekActivities.' this week')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info')
                ->chart([1, 3, 2, 5, 4, 6, 5, 7]),

            Stat::make('Activities with Amount', $activitiesWithAmount)
                ->description('$'.number_format($totalActivityAmount, 2).' total value')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning')
                ->chart([3, 5, 7, 6, 9, 8, 11, 10]),

            Stat::make('Average Activity Value', '$'.number_format($avgActivityAmount, 2))
                ->description('Per activity with amount')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('gray')
                ->chart([20, 25, 30, 28, 35, 32, 40, 38]),
        ];
    }
}
