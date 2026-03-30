<?php

namespace Database\Seeders;

use App\Models\Users\Gender;
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
        self::$password = 'password';

        $gender = new Gender();
        $genderM = $gender->findBySymbol('M');
        $genderF = $gender->findBySymbol('F');

        $users = [
            [
                'name' => 'Felipe Martins',
                'email' => 'felipe@email.com',
                'password' => static::$password,
                'birthdate' => '1991-08-19',
                'gender_id' => $genderM->id,
            ],
            [
                'name' => 'Taíse Marques',
                'email' => 'taise@email.com',
                'password' => static::$password,
                'birthdate' => '1992-11-18',
                'gender_id' => $genderF->id,
            ]
        ];

        foreach ($users as $user) {
            $request = new Request();
            $request->merge($user);

            RegisterUserService::register($request);
        }
    }
}
