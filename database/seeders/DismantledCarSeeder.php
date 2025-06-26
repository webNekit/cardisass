<?php

namespace Database\Seeders;

use App\Models\CarBrand;
use App\Models\DismantledCar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DismantledCarSeeder extends Seeder
{
    public function run(): void
    {
        $modelsByBrand = [
            'Toyota' => ['Camry', 'Corolla', 'RAV4'],
            'Honda' => ['Civic', 'Accord', 'CR-V'],
            'BMW' => ['X5', '3 Series', '5 Series'],
            'Ford' => ['Focus', 'Mondeo', 'Explorer'],
            'Kia' => ['Rio', 'Sportage', 'Ceed'],
            'Audi' => ['A4', 'Q5', 'A6'],
            'Mercedes-Benz' => ['C-Class', 'E-Class', 'GLA'],
            'Chevrolet' => ['Malibu', 'Cruze', 'Tahoe'],
            'Hyundai' => ['Elantra', 'Tucson', 'Sonata'],
            'Nissan' => ['Altima', 'X-Trail', 'Qashqai'],
        ];

        $conditions = ['S', 'E', 'D', 'C'];
        $parts = ['Бампер', 'Капот', 'Фара', 'Крыло', 'Зеркало', 'Дверь'];

        foreach (CarBrand::all() as $brand) {
            $models = $modelsByBrand[$brand->name] ?? ['Model X'];

            foreach ($models as $model) {
                DismantledCar::create([
                    'car_brand_id' => $brand->id,
                    'model' => $model,
                    'vin' => strtoupper(Str::random(17)),
                    'image' => null,
                    'mileage' => rand(50000, 250000),
                    'power' => rand(70, 300),
                    'condition' => $conditions[array_rand($conditions)],
                    'damaged_parts' => json_encode(collect($parts)->random(rand(1, 3))->values()),
                    'parts_for_sale' => json_encode(collect($parts)->random(rand(2, 5))->values()),
                ]);
            }
        }
    }
}
