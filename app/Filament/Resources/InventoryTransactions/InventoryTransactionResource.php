<?php

namespace App\Filament\Resources\InventoryTransactions;

use App\Filament\Resources\InventoryTransactions\Pages\ManageInventoryTransactions;
use App\Models\InventoryTransaction;
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
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class InventoryTransactionResource extends Resource
{
    protected static ?string $model = InventoryTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPath;

    protected static string | UnitEnum | null $navigationGroup = 'Transaksi';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('transaction_number')
                    ->required(),
                TextInput::make('material_id')
                    ->required()
                    ->numeric(),
                Select::make('transaction_type')
                    ->options([
            'in' => 'In',
            'out' => 'Out',
            'adjustment' => 'Adjustment',
            'return' => 'Return',
            'waste' => 'Waste',
        ])
                    ->default('out')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                TextInput::make('price_per_unit')
                    ->numeric()
                    ->default(null),
                TextInput::make('total_cost')
                    ->numeric()
                    ->default(null),
                TextInput::make('stock_before')
                    ->numeric()
                    ->default(null),
                TextInput::make('stock_after')
                    ->numeric()
                    ->default(null),
                TextInput::make('order_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('item_id')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('transaction_date')
                    ->required(),
                TextInput::make('reference_number')
                    ->default(null),
                TextInput::make('supplier_invoice')
                    ->default(null),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('handled_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('approved_by')
                    ->numeric()
                    ->default(null),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('transaction_number'),
                TextEntry::make('material_id')
                    ->numeric(),
                TextEntry::make('transaction_type'),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('price_per_unit')
                    ->numeric(),
                TextEntry::make('total_cost')
                    ->numeric(),
                TextEntry::make('stock_before')
                    ->numeric(),
                TextEntry::make('stock_after')
                    ->numeric(),
                TextEntry::make('order_id')
                    ->numeric(),
                TextEntry::make('item_id')
                    ->numeric(),
                TextEntry::make('transaction_date')
                    ->dateTime(),
                TextEntry::make('reference_number'),
                TextEntry::make('supplier_invoice'),
                TextEntry::make('handled_by')
                    ->numeric(),
                TextEntry::make('approved_by')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_number')
                    ->searchable(),
                TextColumn::make('material_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('transaction_type'),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_per_unit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_cost')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock_before')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock_after')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('order_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('item_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('transaction_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('reference_number')
                    ->searchable(),
                TextColumn::make('supplier_invoice')
                    ->searchable(),
                TextColumn::make('handled_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_by')
                    ->numeric()
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
                TrashedFilter::make(),
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
            'index' => ManageInventoryTransactions::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
