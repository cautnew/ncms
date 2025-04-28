<?php

namespace Database\Seeders;

use App\Models\NCMS\GlobalVars;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GlobalVarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $examples = [
            [
                'name' => 'site_name',
                'value' => 'NCMS',
                'description' => 'The site name',
                'user_id' => 1,
            ],
            [
                'name' => 'site_url',
                'value' => 'https://example.com',
                'description' => 'The site URL',
                'user_id' => 1,
            ],
            [
                'name' => 'site_email',
                'value' => fake()->safeEmail(),
                'description' => 'The site email',
                'user_id' => 1,
            ],
            [
                'name' => 'site_phone',
                'value' => fake()->phoneNumber(),
                'description' => 'The site phone number',
                'user_id' => 1,
            ],
            [
                'name' => 'site_address',
                'value' => fake()->address(),
                'description' => 'The site address',
                'user_id' => 1,
            ],
            [
                'name' => 'site_description',
                'value' => fake()->text(100),
                'description' => 'The site description',
                'user_id' => 1,
            ],
        ];

        foreach ($examples as $example) {
            GlobalVars::factory()->create($example);
        }

        GlobalVars::factory(10)->create();
    }
}
