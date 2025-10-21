<?php

namespace App\Filament\Resources\DesignFiles\Pages;

use App\Filament\Resources\DesignFiles\DesignFileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDesignFiles extends ManageRecords
{
    protected static string $resource = DesignFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
