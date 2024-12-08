<?php

namespace Database\Factories\EndPoints;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EndPoints\EndPoint>
 */
class EndPointFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => $this->faker->name(),
      'description' => $this->faker->sentence(),
      'method' => $this->faker->randomElement(['GET', 'POST', 'PUT', 'PATCH', 'DELETE']),
      'route' => '/' . $this->faker->unique()->slug(2)
    ];
  }
}
