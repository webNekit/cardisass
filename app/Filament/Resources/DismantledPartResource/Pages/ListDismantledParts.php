<?php

namespace App\Filament\Resources\DismantledPartResource\Pages;

use App\Filament\Resources\DismantledPartResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDismantledParts extends ListRecords
{
    protected static string $resource = DismantledPartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
