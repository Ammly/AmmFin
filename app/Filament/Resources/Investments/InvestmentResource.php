<?php

namespace App\Filament\Resources\Investments;

use App\Filament\Resources\Investments\Pages\ManageInvestments;
use App\Models\Investment;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class InvestmentResource extends Resource
{
    protected static ?string $model = Investment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Financial Management';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('symbol')
                    ->required()
                    ->maxLength(10),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->options([
                        'stock' => 'Stock',
                        'crypto' => 'Cryptocurrency',
                        'bond' => 'Bond',
                        'fund' => 'Fund',
                    ])
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                TextInput::make('purchase_price')
                    ->required()
                    ->numeric(),
                TextInput::make('current_price')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('purchased_at')
                    ->required(),
                TextInput::make('user_id')
                    ->default(auth()->id())
                    ->hidden(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('symbol')
                    ->label('Symbol'),
                TextEntry::make('name')
                    ->label('Investment Name'),
                TextEntry::make('type')
                    ->label('Type')
                    ->badge(),
                TextEntry::make('quantity')
                    ->label('Quantity')
                    ->numeric(decimalPlaces: 4),
                TextEntry::make('purchase_price')
                    ->label('Purchase Price')
                    ->money('USD'),
                TextEntry::make('current_price')
                    ->label('Current Price')
                    ->money('USD'),
                TextEntry::make('total_value')
                    ->label('Total Value')
                    ->money('USD'),
                TextEntry::make('profit_loss')
                    ->label('Profit/Loss')
                    ->money('USD')
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),
                TextEntry::make('profit_loss_percentage')
                    ->label('Profit/Loss %')
                    ->suffix('%')
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),
                TextEntry::make('purchased_at')
                    ->label('Purchased At')
                    ->dateTime(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('symbol')->sortable(),
                TextColumn::make('name')->sortable(),
                TextColumn::make('type')->badge(),
                TextColumn::make('quantity')->numeric(decimalPlaces: 4),
                TextColumn::make('purchase_price')->money('USD'),
                TextColumn::make('current_price')->money('USD'),
                TextColumn::make('total_value')->money('USD')->sortable(),
                TextColumn::make('profit_loss')
                    ->money('USD')
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),
                TextColumn::make('profit_loss_percentage')
                    ->suffix('%')
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('type')
                    ->options([
                        'stock' => 'Stock',
                        'crypto' => 'Cryptocurrency',
                        'bond' => 'Bond',
                        'fund' => 'Fund',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInvestments::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // Users can only see their own investments
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
