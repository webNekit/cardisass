<?php

namespace Database\Seeders;

use App\Models\DismantledCar;
use App\Models\DismantledPart;
use Illuminate\Database\Seeder;

class DismantledPartSeeder extends Seeder
{
    public function run(): void
    {
        $partNames = ['Бампер', 'Фара', 'Капот', 'Крыло', 'Дверь', 'Руль', 'Радиатор', 'Генератор'];

        $qualities = ['S', 'E', 'D', 'C'];

        // Для каждого автомобиля создаем от 2 до 5 запчастей
        foreach (DismantledCar::all() as $car) {
            $count = rand(2, 5);
            for ($i = 0; $i < $count; $i++) {
                DismantledPart::create([
                    'dismantled_car_id' => $car->id,
                    'name' => $partNames[array_rand($partNames)],
                    'image' => null, // Можно подставить путь к картинке, если нужно
                    'price' => rand(1000, 10000),
                    'quality' => $qualities[array_rand($qualities)],
                ]);
            }
        }
    }
}
