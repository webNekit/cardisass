<?php

namespace App\Filament\Resources\InventoryPartResource\Pages;

use App\Filament\Resources\InventoryPartResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryParts extends ListRecords
{
    protected static string $resource = InventoryPartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
