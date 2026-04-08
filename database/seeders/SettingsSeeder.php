<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::query()->updateOrCreate(
            ['key' => 'locales.available'],
            ['value' => [
                ['code' => 'pt', 'label' => 'Portuguese'],
                ['code' => 'en', 'label' => 'English'],
            ]]
        );

        Setting::query()->updateOrCreate(
            ['key' => 'locales.default'],
            ['value' => 'pt']
        );
    }
}

