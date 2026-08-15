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
     *
     * RouteSeeder is the exception: it depends on the pages/assets
     * DemoContentSeeder creates but doesn't call it itself, since
     * DemoContentSeeder isn't idempotent — it must run after DemoContentSeeder
     * in this same sequence, and can't safely be run standalone beforehand.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            WebsiteSeeder::class,
            DemoContentSeeder::class,
            RouteSeeder::class,
        ]);
    }
}
