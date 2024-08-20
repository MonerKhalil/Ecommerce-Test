<?php

namespace Database\Seeders;

use App\HelperClasses\MyApp;
use App\Models\OrderProduct;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1;$i<=5;$i++){
            OrderProduct::create([
                "order_id" => $i,
                "product_id" => rand(1,10),
                "price" => ($i * 100.9),
                "quantity" => 1,
            ]);
        }
    }
}
