<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Администратор
        User::create([
            'name' => 'Админ',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Замени на сложный пароль!
            'role' => 'admin',
        ]);
    }
}
