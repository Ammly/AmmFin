<?php

namespace App\Filament\Admin\Resources\Accounts;

use App\Filament\Admin\Resources\Accounts\Pages\ManageAccounts;
use App\Models\Account;
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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Wallet;

    protected static string|UnitEnum|null $navigationGroup = 'Financial Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Select::make('currency_id')
                    ->relationship('currency', 'name')
                    ->required()
                    ->searchable(),
                Select::make('type')
                    ->options([
                        'wallet' => 'Wallet',
                        'savings' => 'Savings',
                        'investment' => 'Investment',
                    ])
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('balance')
                    ->numeric()
                    ->default(0),
                TextInput::make('limit_amount')
                    ->numeric(),
                Select::make('limit_period')
                    ->options([
                        'month' => 'Monthly',
                        'week' => 'Weekly',
                        'day' => 'Daily',
                    ]),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('name')
                    ->label('Account Name'),
                TextEntry::make('type')
                    ->label('Account Type')
                    ->badge(),
                TextEntry::make('currency.code')
                    ->label('Currency'),
                TextEntry::make('balance')
                    ->label('Balance')
                    ->money(fn ($record) => $record->currency->code),
                TextEntry::make('limit_amount')
                    ->label('Limit Amount')
                    ->money(fn ($record) => $record->currency->code),
                TextEntry::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive'),
                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->sortable()->searchable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('type')->badge(),
                TextColumn::make('currency.code'),
                TextColumn::make('balance')
                    ->money(fn ($record) => $record->currency->code)
                    ->sortable(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('type')
                    ->options([
                        'wallet' => 'Wallet',
                        'savings' => 'Savings',
                        'investment' => 'Investment',
                    ]),
                TernaryFilter::make('is_active'),
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable(),
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
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAccounts::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // Admin can see all accounts
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
