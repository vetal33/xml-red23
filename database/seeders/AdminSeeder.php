<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * php artisan db:seed --class=AdminSeeder
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'vetal33',
            'email' => 'v.bitko@gmail.com',
            'password' => Hash::make('123456'),
            'email_verified_at'=>'2022-01-02 17:04:58',
            'avatar' => 'images/avatar-1.jpg',
            'created_at' => now(),
            ]);
        $user->assignRole(User::ROLE_ADMIN);
    }
}


