<?php

namespace Database\Factories\NCMS;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GlobalVars>
 */
class GlobalVarsFactory extends Factory
{
    private function getRandomName(): string
    {
        if (fake()->boolean(30)) {
            return fake()->unique()->slug(1);
        } elseif (fake()->boolean(20)) {
            return fake()->unique()->slug(3);
        }

        return fake()->unique()->slug(2);
    }

    private function getRandomValue(): string
    {
        if (fake()->boolean(30)) {
            return fake()->word();
        } elseif (fake()->boolean(25)) {
            return fake()->words(2, true);
        } elseif (fake()->boolean(20)) {
            return fake()->words(4, true);
        }

        return fake()->words(3, true);
    }

    private function getRandomDescription(): string
    {
        if (fake()->boolean(30)) {
            return fake()->sentence(12, true);
        } elseif (fake()->boolean(25)) {
            return fake()->sentence(15, true);
        } elseif (fake()->boolean(20)) {
            return fake()->sentence(10, true);
        }

        return fake()->sentence();
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->getRandomName(),
            'value' => $this->getRandomValue(),
            'description' => $this->getRandomDescription(),
            'is_read_only' => fake()->boolean(),
            'is_protected' => fake()->boolean(),
            'user_id' => fake()->numberBetween(1, 2),
        ];
    }
}
