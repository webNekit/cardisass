<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class OrderOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Order::query()->sum('total_price');
        $formattedTotalRevenue = number_format($totalRevenue, 2, '.', ' ') . ' ₽';
        $startOfWeek = Carbon::now()->subDays(7)->startOfDay(); // Начало недели
        $endOfWeek = Carbon::now()->endOfDay(); // Конец недели (текущий момент)

        // Вычисляем выручку за неделю
        $weeklyRevenue = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('total_price');

        // Форматируем сумму для отображения
        $formattedWeeklyRevenue = number_format($weeklyRevenue, 2, '.', ' ') . ' ₽'; // Пример 

        $startOfMonth = Carbon::now()->startOfMonth()->startOfDay(); // Начало текущего месяца
        $endOfMonth = Carbon::now()->endOfDay(); // Текущая дата и время
        $monthlyRevenue = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_price');
        $formattedMonthlyRevenue = number_format($monthlyRevenue, 2, '.', ' ') . ' ₽';
        return [
            Stat::make('Общая выручка', $formattedTotalRevenue)
                ->description('Общая выручка от всех заказов')
                ->color('success'),
            Stat::make('Выручка за неделю', $formattedWeeklyRevenue)
                ->description('Выручка за последние 7 дней')
                ->color('success'), // Цвет статистики
            Stat::make('Выручка за месяц', $formattedMonthlyRevenue)
                ->description('Выручка за текущий месяц')
                ->color('warning'),
        ];
    }
}
