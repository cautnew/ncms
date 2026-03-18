<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Core\RequestMethods\RequestMethod;
use App\Enums\RequestMethods;

class RequestMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            ['name' => 'GET', 'code' => (string) RequestMethods::GET->value, 'description' => 'GET Method'],
            ['name' => 'POST', 'code' => (string) RequestMethods::POST->value, 'description' => 'POST Method'],
            ['name' => 'PUT', 'code' => (string) RequestMethods::PUT->value, 'description' => 'PUT Method'],
            ['name' => 'PATCH', 'code' => (string) RequestMethods::PATCH->value, 'description' => 'PATCH Method'],
            ['name' => 'DELETE', 'code' => (string) RequestMethods::DELETE->value, 'description' => 'DELETE Method'],
            ['name' => 'OPTIONS', 'code' => (string) RequestMethods::OPTIONS->value, 'description' => 'OPTIONS Method'],
            ['name' => 'HEAD', 'code' => (string) RequestMethods::HEAD->value, 'description' => 'HEAD Method'],
            ['name' => 'TRACE', 'code' => (string) RequestMethods::TRACE->value, 'description' => 'TRACE Method'],
        ];

        foreach ($methods as $method) {
            RequestMethod::updateOrCreate(['name' => $method['name']], $method);
        }
    }
}
