<?php

namespace Database\Seeders;

use App\HelperClasses\MyApp;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1 ; $i <= 5 ; $i++){
            Order::create([
                "user_id" => rand(2,3),
                "order_code" => "Order-Code-" . \Str::random(5),
                "price_total" => ($i*100.00),
            ]);
        }
        $this->call(OrderProductSeeder::class);
    }
}
