<?php

namespace App\Filament\Resources\ProductionProgress\Pages;

use App\Filament\Resources\ProductionProgress\ProductionProgressResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageProductionProgress extends ManageRecords
{
    protected static string $resource = ProductionProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
