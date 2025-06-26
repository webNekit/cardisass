<?php

namespace App\Filament\Resources\InventoryPartResource\Pages;

use App\Filament\Resources\InventoryPartResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryPart extends EditRecord
{
    protected static string $resource = InventoryPartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
