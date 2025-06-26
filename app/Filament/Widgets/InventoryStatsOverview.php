<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\DismantledCar;
use App\Models\InventoryPart;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InventoryStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Всего запчастей на складе', InventoryPart::sum('quantity'))
                ->description('Количество агрегированных запчастей')
                ->icon('heroicon-o-cube'),

            Stat::make('Разобранных автомобилей', DismantledCar::count())
                ->description('Общее количество разобранных авто')
                ->icon('heroicon-o-truck'),

            Stat::make('Клиентов', Client::count())
                ->description('Зарегистрированные клиенты')
                ->icon('heroicon-o-users'),
        ];
    }
}
