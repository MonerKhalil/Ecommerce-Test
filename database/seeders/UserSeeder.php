<?php

namespace Database\Seeders;

use App\HelperClasses\MyApp;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'super_admin',
                'first_name' => 'super',
                'last_name' => 'admin',
                'email' => 'super_admin@admin.com',
                'password' => User::PASSWORD,
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'created_at' => now()
            ],
            [
                'name' => 'moner_khalil',
                'first_name' => 'moner',
                'last_name' => 'khalil',
                'email' => 'moner_khalil@user.com',
                'password' => User::PASSWORD,
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now()
            ],
            [
                'name' => 'moner_khalil1',
                'first_name' => 'moner',
                'last_name' => 'khalil1',
                'email' => 'moner_khalil1@user.com',
                'password' => User::PASSWORD,
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now()
            ],
        ];
        foreach ($users as $user){
            $user = User::create($user);
            $user->attachRole($user->role);
        }
    }
}
