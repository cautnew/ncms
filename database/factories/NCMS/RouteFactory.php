<?php

namespace Database\Factories\NCMS;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NCMS\Route>
 */
class RouteFactory extends Factory
{
    private array $availableMethods = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'PATCH',
        'OPTIONS',
    ];

    private array $availableRedirectTypes = [
        '301',
        '302',
    ];

    private function definitionForMethodAny(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'description' => fake()->sentence(),
            'method' => fake()->randomElement($this->availableMethods),
            'route' => fake()->filePath(),
            'is_active' => fake()->boolean(),
            'user_id' => fake()->numberBetween(1, 2),
        ];
    }

    private function definitionForMethodGet(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'description' => fake()->sentence(),
            'method' => 'GET',
            'route' => fake()->filePath(),
            'content_id' => fake()->numberBetween(1, 100),
            'controller_class' => fake()->word(),
            'is_active' => fake()->boolean(),
            'user_id' => fake()->numberBetween(1, 2),
        ];
    }

    private function definitionForRedirect(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'description' => fake()->sentence(),
            'method' => 'GET',
            'route' => fake()->filePath(),
            'content_id' => fake()->numberBetween(1, 100),
            'is_redirect' => true,
            'redirect_to' => fake()->filePath(),
            'redirect_type' => fake()->randomElement($this->availableRedirectTypes),
            'is_active' => fake()->boolean(),
            'user_id' => fake()->numberBetween(1, 2),
        ];
    }

    private function aleatoryDefinition(): array
    {
        $definitions = [
            $this->definitionForMethodAny(),
            $this->definitionForMethodGet(),
            $this->definitionForRedirect()
        ];

        return $definitions[fake()->numberBetween(0, count($definitions) - 1)];
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return $this->aleatoryDefinition();
    }
}
