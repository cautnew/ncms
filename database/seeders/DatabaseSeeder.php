<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GenderSeeder::class,
            UserSeeder::class,
            RequestMethodSeeder::class,
            ResponseTypeSeeder::class,
            RouteSeeder::class,
        ]);
    }
}
