<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Иван Иванов',
            'Петр Петров',
            'Сергей Смирнов',
            'Алексей Кузнецов',
            'Николай Попов',
            'Андрей Соколов',
            'Владимир Лебедев',
            'Дмитрий Новиков',
            'Евгений Морозов',
            'Анатолий Волков',
        ];

        foreach ($names as $index => $name) {
            Client::create([
                'name' => $name,
                'email' => 'client' . ($index + 1) . '@example.com',
                'phone' => '+7 999 000 00' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'status' => $index % 2 === 0 ? 'active' : 'inactive',
            ]);
        }
    }
}
