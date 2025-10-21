<?php

namespace App\Filament\Resources\SalesReports;

use App\Filament\Resources\SalesReports\Pages\ManageSalesReports;
use App\Models\SalesReport;
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
use Filament\Forms\Components\DatePicker;
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

class SalesReportResource extends Resource
{
    protected static ?string $model = SalesReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    
    protected static string | UnitEnum | null $navigationGroup = 'Laporan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('report_number')
                    ->required(),
                DatePicker::make('report_date')
                    ->required(),
                DatePicker::make('period_start')
                    ->required(),
                DatePicker::make('period_end')
                    ->required(),
                Select::make('report_period')
                    ->options([
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'yearly' => 'Yearly',
            'custom' => 'Custom',
        ])
                    ->required(),
                TextInput::make('total_sales')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_cost')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_profit')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_discount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_tax')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_orders')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('completed_orders')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('cancelled_orders')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('pending_orders')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_customers')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('new_customers')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('generated_by')
                    ->numeric()
                    ->default(null),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('report_number'),
                TextEntry::make('report_date')
                    ->date(),
                TextEntry::make('period_start')
                    ->date(),
                TextEntry::make('period_end')
                    ->date(),
                TextEntry::make('report_period'),
                TextEntry::make('total_sales')
                    ->numeric(),
                TextEntry::make('total_cost')
                    ->numeric(),
                TextEntry::make('total_profit')
                    ->numeric(),
                TextEntry::make('total_discount')
                    ->numeric(),
                TextEntry::make('total_tax')
                    ->numeric(),
                TextEntry::make('total_orders')
                    ->numeric(),
                TextEntry::make('completed_orders')
                    ->numeric(),
                TextEntry::make('cancelled_orders')
                    ->numeric(),
                TextEntry::make('pending_orders')
                    ->numeric(),
                TextEntry::make('total_customers')
                    ->numeric(),
                TextEntry::make('new_customers')
                    ->numeric(),
                TextEntry::make('generated_by')
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
                TextColumn::make('report_number')
                    ->searchable(),
                TextColumn::make('report_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('period_start')
                    ->date()
                    ->sortable(),
                TextColumn::make('period_end')
                    ->date()
                    ->sortable(),
                TextColumn::make('report_period'),
                TextColumn::make('total_sales')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_cost')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_profit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_discount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_tax')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_orders')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('completed_orders')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cancelled_orders')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pending_orders')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_customers')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('new_customers')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('generated_by')
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
            'index' => ManageSalesReports::route('/'),
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
