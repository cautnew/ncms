<?php

namespace Database\Factories;

use App\Models\Layout;
use App\Models\Page;
use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $slug = fake()->unique()->slug();

        return [
            'website_id' => Website::factory(),
            'layout_id' => fn (array $attributes) => Layout::factory()->create([
                'website_id' => $attributes['website_id'],
            ])->id,
            'published_version_id' => null,
            'slug' => $slug,
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the page is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
        ]);
    }
}
