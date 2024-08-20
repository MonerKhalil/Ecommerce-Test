<?php

namespace Database\Seeders;

use App\HelperClasses\MyApp;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1 ; $i<=10 ;$i++){
            Product::create([
                "name" => Str::random(10),
                "description" => Str::random(50),
                "price" => 100.45 * $i,
                "quantity" => 5 * $i,
            ]);
        }
    }
}
