<?php

namespace Database\Seeders;

use App\Models\Users\Gender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [
            [
                'name' => 'Female',
                'symbol' => 'F',
            ],
            [
                'name' => 'Male',
                'symbol' => 'M',
            ],
            [
                'name' => 'Other',
                'symbol' => 'O',
            ],
            [
                'name' => 'No gender',
                'symbol' => 'N',
            ],
        ];

        foreach ($genders as $gender) {
            Gender::create($gender);
        }
    }
}
