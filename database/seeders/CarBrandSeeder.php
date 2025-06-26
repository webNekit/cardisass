<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarBrand;

class CarBrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Toyota',
            'Honda',
            'Nissan',
            'BMW',
            'Mercedes-Benz',
            'Audi',
            'Ford',
            'Chevrolet',
            'Hyundai',
            'Kia',
        ];

        foreach ($brands as $brand) {
            CarBrand::create([
                'name' => $brand,
            ]);
        }
    }
}
