<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's users database.
     *
     * @return void
     */
    private function createUsers(): void
    {
        $users = [
            [
                'name' => 'Felipe Martins',
                'email' => 'cautnew@email.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'remember_token' => null
            ],
            [
                'name' => 'Leonardo Carvalho',
                'email' => 'leonardo@email.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'remember_token' => null
            ]
        ];

        foreach ($users as $user) {
            User::factory()->create($user);
        }
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->createUsers();
    }
}
