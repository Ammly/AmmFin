<?php

namespace App\Filament\Admin\Resources\Currencies\Pages;

use App\Filament\Admin\Resources\Currencies\CurrencyResource;
use App\Filament\Admin\Resources\Currencies\Widgets\CurrencyStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCurrencies extends ManageRecords
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CurrencyStatsWidget::class,
        ];
    }
}
