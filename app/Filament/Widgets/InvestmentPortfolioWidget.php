<?php

namespace App\Filament\Widgets;

use App\Models\Investment;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class InvestmentPortfolioWidget extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Investment::query()->where('user_id', auth()->id()))
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('symbol')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('purchase_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('current_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_value')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('profit_loss')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('profit_loss_percentage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('purchased_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
