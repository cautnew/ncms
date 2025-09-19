<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Services\Auth\RegisterUserService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class DatabaseSeeder extends Seeder
{
    static string $password;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        static::$password = 'password';

        $users = [
            [
                'name' => 'Felipe Martins',
                'email' => 'felipe@email.com',
                'password' => static::$password
            ]
        ];

        foreach ($users as $user) {
            $request = new Request();
            if (!isset($user['password']))
                $user['password'] = 'password';
            $request->merge($user);

            RegisterUserService::register($request);
        }
    }
}
