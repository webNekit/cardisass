<?php

namespace Database\Seeders;

use App\Models\DismantledCar;
use App\Models\InventoryPart;
use Illuminate\Database\Seeder;

class InventoryPartSeeder extends Seeder
{
    public function run(): void
    {
        // Группируем запчасти по [марка, модель, название запчасти]
        $cars = DismantledCar::with('carBrand', 'dismantledParts')->get();

        $grouped = [];

        foreach ($cars as $car) {
            foreach ($car->dismantledParts as $part) {
                $key = $car->car_brand_id . '|' . $car->model . '|' . $part->name;
                if (!isset($grouped[$key])) {
                    $grouped[$key] = [
                        'car_brand_id' => $car->car_brand_id,
                        'model' => $car->model,
                        'name' => $part->name,
                        'quantity' => 0,
                    ];
                }

                $grouped[$key]['quantity']++;
            }
        }

        // Записываем в inventory_parts
        foreach ($grouped as $data) {
            InventoryPart::updateOrCreate(
                [
                    'car_brand_id' => $data['car_brand_id'],
                    'model' => $data['model'],
                    'name' => $data['name'],
                ],
                [
                    'quantity' => $data['quantity'],
                ]
            );
        }
    }
}
