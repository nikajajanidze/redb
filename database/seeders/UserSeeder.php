<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
              'name' => 'Nika Jajanidze',
              'email' => 'nikajajanidze@gmail.com',
              'password' => bcrypt('nika'),
              'email_verified_at' => now(),
              'role' => 'admin',
              'expected_calories' => 19,
              'created_at' => now(),
              'updated_at' => now()
            ],
            [
              'name' => 'Nikolozi Jajanidze',
              'email' => 'nikolozi.jajanidze@gmail.com',
              'password' => bcrypt('nikolozi'),
              'email_verified_at' => now(),
              'role' => 'manager',
              'expected_calories' => 20,
              'created_at' => now(),
              'updated_at' => now()
            ],
            [
              'name' => 'Nino Jajanidze',
              'email' => 'ninojajanidze@gmail.com',
              'password' => bcrypt('nino'),
              'email_verified_at' => now(),
              'role' => 'user',
              'expected_calories' => 21,
              'created_at' => now(),
              'updated_at' => now()
            ],
          ]);
    }
}
