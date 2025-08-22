<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class CashFlowChart extends ChartWidget
{
    protected ?string $heading = 'Cash Flow Chart';

    protected function getData(): array
    {
        $user = auth()->user();
        $months = [];
        $cashFlow = [];

        for ($i = 6; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M');

            $income = $user->transactions()
                ->where('type', 'credit')
                ->where('status', 'completed')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('amount');

            $expenses = $user->transactions()
                ->where('type', 'debit')
                ->where('status', 'completed')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('amount');

            $cashFlow[] = $income - $expenses;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cash Flow',
                    'data' => $cashFlow,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
