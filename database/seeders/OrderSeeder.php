<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $paymentMethods = ['stripe', 'cod'];
        $statuses = ['new', 'completed', 'cancelled'];

        $clients = Client::all();

        foreach ($clients as $client) {
            Order::create([
                'client_id' => $client->id,
                'total_price' => rand(5000, 30000),
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'status' => $statuses[array_rand($statuses)],
                'notes' => fake()->optional()->sentence(),
            ]);
        }
    }
}
