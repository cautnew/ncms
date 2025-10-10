<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use App\Services\Auth\RegisterUserService;

class UserSeeder extends Seeder
{
    static string $password;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        static::$password = 'password';

        $users = [
            [
                'name' => 'Felipe Martins',
                'email' => 'felipe@email.com',
                'password' => static::$password,
                'birthdate' => '1991-08-19',
                'gender_id' => '2',
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
