<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Layout;
use App\Models\PageVersion;
use App\Models\User;
use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'website_id' => Website::factory(),
            'layout_id' => fn (array $attributes) => Layout::factory()->create(['website_id' => $attributes['website_id']])->id,
            'page_version_id' => null,
            'disk' => 'public',
            'path' => 'assets/'.fake()->uuid().'.jpg',
            'filename' => fake()->word().'.jpg',
            'mime_type' => 'image/jpeg',
            'size' => fake()->numberBetween(1_000, 500_000),
            'width' => 800,
            'height' => 600,
            'alt_text' => fake()->sentence(3),
            'metadata' => null,
            'uploaded_by' => User::factory(),
        ];
    }

    /**
     * Indicate that this asset belongs to a page version instead of a layout.
     */
    public function forPageVersion(PageVersion $pageVersion): static
    {
        return $this->state(fn (array $attributes) => [
            'website_id' => $pageVersion->page->website_id,
            'layout_id' => null,
            'page_version_id' => $pageVersion->id,
        ]);
    }

    /**
     * Indicate that this asset belongs to a specific existing layout (rather
     * than the auto-created one the default state would otherwise generate).
     */
    public function forLayout(Layout $layout): static
    {
        return $this->state(fn (array $attributes) => [
            'website_id' => $layout->website_id,
            'layout_id' => $layout->id,
            'page_version_id' => null,
        ]);
    }

    /**
     * Indicate that this asset is an SVG (no pixel dimensions).
     */
    public function svg(): static
    {
        return $this->state(fn (array $attributes) => [
            'path' => 'assets/'.fake()->uuid().'.svg',
            'filename' => fake()->word().'.svg',
            'mime_type' => 'image/svg+xml',
            'width' => null,
            'height' => null,
        ]);
    }

    /**
     * Indicate that this asset is a PDF document (no pixel dimensions).
     */
    public function document(): static
    {
        return $this->state(fn (array $attributes) => [
            'path' => 'assets/'.fake()->uuid().'.pdf',
            'filename' => fake()->word().'.pdf',
            'mime_type' => 'application/pdf',
            'width' => null,
            'height' => null,
            'alt_text' => null,
        ]);
    }

    /**
     * Indicate that this asset is a video (mp4).
     */
    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'path' => 'assets/'.fake()->uuid().'.mp4',
            'filename' => fake()->word().'.mp4',
            'mime_type' => 'video/mp4',
            'width' => 1920,
            'height' => 1080,
            'size' => fake()->numberBetween(500_000, 20_000_000),
        ]);
    }
}
