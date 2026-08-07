<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database. AdminSeeder and WebsiteSeeder are
     * also each called internally by whichever seeder depends on them, so
     * running any of the three individually (e.g. via --class=) is safe.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            WebsiteSeeder::class,
            DemoContentSeeder::class,
        ]);
    }
}
