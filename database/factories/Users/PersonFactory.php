<?php

namespace Database\Factories\Users;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Users\User;
use App\Models\Users\Person;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users\Person>
 */
class PersonFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $user = User::factory()->create();

    return [
      'user_id' => $user->id,
      'name' => $this->faker->name(),
      'lastname' => $this->faker->lastName(),
      'birthdate' => $this->faker->date()
    ];
  }
}
