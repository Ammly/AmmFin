<?php

namespace App\Filament\Resources\Accounts\Pages;

use App\Filament\Resources\Accounts\AccountResource;
use App\Filament\Resources\Accounts\Widgets\AccountStatsWidget;
use App\Filament\Resources\Accounts\Widgets\SavingsAccountWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAccounts extends ManageRecords
{
    protected static string $resource = AccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();

                    return $data;
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AccountStatsWidget::class,
            SavingsAccountWidget::class,
        ];
    }
}
