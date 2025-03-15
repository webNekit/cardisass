<?php

namespace App\Filament\Resources\DismantledCarResource\Pages;

use App\Filament\Resources\DismantledCarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDismantledCars extends ListRecords
{
    protected static string $resource = DismantledCarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
