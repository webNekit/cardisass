<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class OrderTotalOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $startOfWeek = Carbon::now()->subDays(7)->startOfDay(); // Начало недели
        $endOfWeek = Carbon::now()->endOfDay(); // Конец недели (текущий момент)

        // Вычисляем выручку за неделю
        $weeklyRevenue = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('total_price');

        // Форматируем сумму для отображения
        $formattedWeeklyRevenue = number_format($weeklyRevenue, 2, '.', ' ') . ' ₽'; // Пример 
        return [
            Stat::make('Выручка за неделю', $formattedWeeklyRevenue)
                ->description('Выручка за последние 7 дней')
                ->color('success'), // Цвет статистики
        ];
    }
}
