<?php

namespace Database\Factories\NCMS;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NCMS\UserType>
 */
class UserTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(2),
            'description' => $this->faker->sentence(),
            'acronym' => strtoupper($this->faker->word(1)),
        ];
    }
}
