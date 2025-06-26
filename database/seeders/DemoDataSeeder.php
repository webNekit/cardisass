<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Реальные марки автомобилей
        $brands = ['Toyota', 'BMW', 'Mercedes-Benz', 'Volkswagen', 'Audi', 'Ford', 'Honda', 'Hyundai', 'Nissan', 'Kia'];
        foreach ($brands as $brand) {
            DB::table('car_brands')->insert([
                'name' => $brand,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Реальные модели
        $models = [
            'Camry', 'Corolla', '3 Series', 'C-Class', 'Golf', 'A4', 'Focus', 'Civic', 'Elantra', 'Altima'
        ];

        // Состояния, повреждённые части, доступные детали
        $conditions = ['S', 'E', 'D', 'C'];
        $damagedPartsSamples = [
            ['дверь', 'бампер'],
            ['капот', 'крыло'],
            ['крыша', 'багажник'],
            ['стекло', 'зеркало'],
        ];
        $partsForSaleSamples = [
            ['двигатель', 'коробка передач'],
            ['колесо', 'подвеска'],
            ['аккумулятор', 'радиатор'],
            ['стартер', 'генератор'],
        ];

        // Статусы для dismantled_cars
        $statuses = ['arrived', 'dismantling', 'dismantled', 'rejected'];
        $rejectionReasonsSamples = [
            'Сильные повреждения кузова',
            'Двигатель нерабочий',
            'Неисправна электроника',
            'Проблемы с документами',
        ];

        foreach (range(1, 10) as $i) {
            $status = collect($statuses)->random();
            $rejectionReason = null;
            if ($status === 'rejected') {
                $rejectionReason = collect($rejectionReasonsSamples)->random();
            }

            DB::table('dismantled_cars')->insert([
                'car_brand_id' => $i,
                'model' => $models[$i - 1],
                'vin' => strtoupper(Str::random(17)),
                'image' => 'https://via.placeholder.com/300x200?text=' . urlencode($brands[$i - 1] . ' ' . $models[$i - 1]),
                'mileage' => rand(30000, 180000),
                'power' => rand(80, 300),
                'condition' => collect($conditions)->random(),
                'status' => $status,
                'rejection_reason' => $rejectionReason,
                'damaged_parts' => json_encode(collect($damagedPartsSamples)->random()),
                'parts_for_sale' => json_encode(collect($partsForSaleSamples)->random()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Русские названия запчастей
        $partNames = [
            'Двигатель', 'Коробка передач', 'Передний бампер', 'Задний бампер', 'Капот',
            'Левая фара', 'Правая фара', 'Левое зеркало', 'Правое зеркало', 'Радиатор'
        ];

        foreach (range(1, 10) as $i) {
            DB::table('dismantled_parts')->insert([
                'dismantled_car_id' => rand(1, 10),
                'name' => $partNames[$i - 1],
                'image' => 'https://via.placeholder.com/300x300?text=' . urlencode($partNames[$i - 1]),
                'price' => rand(5000, 100000) / 100,
                'quality' => collect($conditions)->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Клиенты
        $clientNames = [
            'Иван Петров',
            'Алексей Смирнов',
            'Мария Иванова',
            'Дмитрий Кузнецов',
            'Ольга Соколова',
            'Екатерина Попова',
            'Сергей Волков',
            'Анна Лебедева',
            'Павел Морозов',
            'Наталья Новикова',
        ];

        foreach (range(1, 10) as $i) {
            DB::table('clients')->insert([
                'name' => $clientNames[$i - 1],
                'email' => "client{$i}@example.com",
                'phone' => '+7' . rand(9000000000, 9999999999),
                'status' => collect(['active', 'inactive'])->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Заказы
        $paymentMethods = ['наличные', 'карта', 'банковский перевод'];
        $statusesOrders = ['new', 'completed', 'cancelled'];

        foreach (range(1, 10) as $i) {
            DB::table('orders')->insert([
                'client_id' => rand(1, 10),
                'total_price' => rand(5000, 200000) / 100,
                'payment_method' => collect($paymentMethods)->random(),
                'status' => collect($statusesOrders)->random(),
                'notes' => 'Заказ №' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Запчасти в заказах
        foreach (range(1, 10) as $i) {
            DB::table('order_parts')->insert([
                'order_id' => rand(1, 10),
                'car_brand_id' => rand(1, 10),
                'dismantled_part_id' => rand(1, 10),
                'quantity' => rand(1, 3),
                'unit_amount' => rand(1000, 30000) / 100,
                'total_amount' => rand(3000, 90000) / 100,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Пользователи (1 админ, 9 обычных)
        DB::table('users')->insert([
            'name' => 'Администратор',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // пароль: password
            'role' => 'admin',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (range(1, 9) as $i) {
            DB::table('users')->insert([
                'name' => 'Пользователь ' . $i,
                'email' => "user{$i}@example.com",
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // пароль: password
                'role' => 'user',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Создание агрегированных данных для inventory_parts
        $inventoryParts = [];

        $cars = DB::table('dismantled_cars')->get();

        foreach ($cars as $car) {
            $parts = DB::table('dismantled_parts')->where('dismantled_car_id', $car->id)->get();

            foreach ($parts as $part) {
                $key = $car->car_brand_id . '|' . $car->model . '|' . $part->name;

                if (!isset($inventoryParts[$key])) {
                    $inventoryParts[$key] = [
                        'car_brand_id' => $car->car_brand_id,
                        'model' => $car->model,
                        'name' => $part->name,
                        'quantity' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $inventoryParts[$key]['quantity']++;
            }
        }

        foreach ($inventoryParts as $part) {
            DB::table('inventory_parts')->insert($part);
        }
    }
}
