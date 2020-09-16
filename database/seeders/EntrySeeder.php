<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('entries')->insert([
            [
              'user_id' => 1,
              'meal' => '1 bread',
              'calories' => 22.5,
              'extra_field' => true,
              'created_at' => now(),
              'updated_at' => now()
            ],
            [
              'user_id' => 2,
              'meal' => '2 eggs',
              'calories' => 20.1,
              'extra_field' => false,
              'created_at' => now(),
              'updated_at' => now()
            ],
            [
              'user_id' => 3,
              'meal' => '3 slice of butter',
              'calories' => 50.25,
              'extra_field' => true,
              'created_at' => now(),
              'updated_at' => now()
            ],
          ]);
    }
}
