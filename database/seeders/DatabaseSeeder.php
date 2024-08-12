<?php

namespace Database\Seeders;

use App\Models\command;
use App\Models\consumption;
use App\Models\product;
use App\Models\reception;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
     //  User::factory(10)->create();
     //  product::factory(10)->create();
      // consumption::factory(10)->create();
      // reception::factory(10)->create();
      // command::factory(10)->create();
        User::factory(1)->create();
    }
}
