<?php

namespace Database\Factories;

use App\Models\Layout;
use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Layout>
 */
class LayoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'website_id' => Website::factory(),
            'name' => ucfirst($name),
            'slug' => str($name)->slug(),
            'description' => fake()->optional()->sentence(),
            'schema' => [
                'regions' => ['header', 'main', 'footer'],
            ],
            'status' => 'active',
            'is_default' => false,
        ];
    }

    /**
     * Indicate that this is the website's default layout.
     */
    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }

    /**
     * Indicate that the layout is still a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the layout is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
        ]);
    }
}
