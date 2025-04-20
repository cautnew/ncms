<?php

namespace Database\Seeders;

use App\Models\NCMS\Route;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Route::factory(20)->create();
    }
}
