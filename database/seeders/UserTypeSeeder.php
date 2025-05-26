<?php

namespace Database\Seeders;

use App\Models\NCMS\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userTypes = [
            [
                'name' => 'Administrator',
                'description' => 'User is an Administrator',
                'acronym' => 'admin',
            ]
        ];

        foreach ($userTypes as $userType) {
            UserType::factory()->create($userType);
        }

        UserType::factory(10)->create();
    }
}
