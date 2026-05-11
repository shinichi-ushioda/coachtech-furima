<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;

class DatabaseSeeder extends Seeder
{
        public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(ConditionsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ItemsTableSeeder::class);
    }
}
