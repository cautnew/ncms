<?php

namespace Database\Seeders;

use App\Models\NCMS\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
}
