<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderPart;
use App\Models\DismantledPart;
use App\Models\CarBrand;
use Illuminate\Database\Seeder;

class OrderPartSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();
        $parts = DismantledPart::with('dismantledCar')->get();

        foreach ($orders as $order) {
            // Добавим 1–3 запчасти в каждый заказ
            $usedParts = $parts->random(rand(1, 3));

            foreach ($usedParts as $part) {
                $brand = $part->dismantledCar->carBrand ?? CarBrand::inRandomOrder()->first();

                $quantity = rand(1, 2);
                $unitAmount = $part->price ?? rand(1000, 8000);
                $totalAmount = $unitAmount * $quantity;

                OrderPart::create([
                    'order_id' => $order->id,
                    'car_brand_id' => $brand->id,
                    'dismantled_part_id' => $part->id,
                    'quantity' => $quantity,
                    'unit_amount' => $unitAmount,
                    'total_amount' => $totalAmount,
                ]);
            }
        }
    }
}
