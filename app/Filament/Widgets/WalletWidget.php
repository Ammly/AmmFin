<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class WalletWidget extends BaseWidget
{
    protected static ?string $heading = 'My Wallet';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Account::query()
                    ->where('user_id', auth()->id())
                    ->where('type', 'wallet')
                    ->with('currency')
            )
            ->columns([
                Tables\Columns\ImageColumn::make('currency.code')
                    ->label('Flag')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://flagcdn.com/24x18/'.strtolower(substr($record->currency->code, 0, 2)).'.png'),

                Tables\Columns\TextColumn::make('currency.code')
                    ->label('Currency')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('balance')
                    ->label('Balance')
                    ->money(fn ($record) => $record->currency->code)
                    ->weight('bold')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('limit_amount')
                    ->label('Limit')
                    ->formatStateUsing(fn ($state, $record) => $state ? 'Limit is '.$record->currency->symbol.number_format($state, 2).' a '.$record->limit_period : 'No limit'
                    )
                    ->color('gray'),

                Tables\Columns\BadgeColumn::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                    ->color(fn ($state) => $state ? 'success' : 'danger'),
            ])
            ->actions([
                Action::make('add_funds')
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->action(fn () => null),
            ]);
    }
}
