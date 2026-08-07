<?php

namespace Database\Factories;

use App\Enums\PageVersionStatus;
use App\Models\Layout;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageVersion>
 */
class PageVersionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'page_id' => Page::factory(),
            'layout_id' => fn (array $attributes) => Layout::factory()->create([
                'website_id' => Page::find($attributes['page_id'])->website_id,
            ])->id,
            'version_number' => 1,
            'status' => PageVersionStatus::Draft,
            'data' => [
                'title' => fake()->sentence(4),
            ],
            'layout_snapshot' => fn (array $attributes) => Layout::find($attributes['layout_id'])
                ->only(['name', 'slug', 'description', 'schema']),
            'seo_snapshot' => [
                'title' => fake()->sentence(6),
                'description' => fake()->sentence(15),
                'canonical_url' => fake()->url(),
            ],
            'created_by' => User::factory(),
            'qa_user_id' => null,
            'qa_reviewed_at' => null,
            'qa_notes' => null,
            'published_at' => null,
            'published_by' => null,
        ];
    }

    /**
     * Indicate that the version has been submitted and is awaiting QA review.
     */
    public function underReview(): static
    {
        return $this->state(fn () => [
            'status' => PageVersionStatus::UnderReview,
        ]);
    }

    /**
     * Indicate that the version is approved by QA.
     */
    public function approved(): static
    {
        return $this->state(fn () => [
            'status' => PageVersionStatus::Approved,
            'qa_user_id' => User::factory(),
            'qa_reviewed_at' => now(),
        ]);
    }

    /**
     * Indicate that the version was rejected by QA.
     */
    public function rejected(): static
    {
        return $this->state(fn () => [
            'status' => PageVersionStatus::Rejected,
            'qa_user_id' => User::factory(),
            'qa_reviewed_at' => now(),
            'qa_notes' => 'Needs changes.',
        ]);
    }

    /**
     * Indicate that the version is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PageVersionStatus::Published,
            'qa_user_id' => $attributes['qa_user_id'] ?? User::factory(),
            'qa_reviewed_at' => $attributes['qa_reviewed_at'] ?? now(),
            'published_at' => now(),
            'published_by' => User::factory(),
        ]);
    }

    /**
     * Indicate that the version has been superseded by a newer publication.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PageVersionStatus::Archived,
            'qa_user_id' => $attributes['qa_user_id'] ?? User::factory(),
            'qa_reviewed_at' => $attributes['qa_reviewed_at'] ?? now(),
            'published_at' => $attributes['published_at'] ?? now(),
            'published_by' => $attributes['published_by'] ?? User::factory(),
        ]);
    }

    /**
     * Indicate that this version was auto-cloned from a published source, as
     * PageVersionCloningService produces on any write against a published
     * version — same page, same layout, matching the copy-on-write invariant.
     */
    public function clonedFrom(PageVersion $source): static
    {
        return $this->state(fn (array $attributes) => [
            'page_id' => $source->page_id,
            'layout_id' => $source->layout_id,
            'cloned_from_id' => $source->id,
            'layout_snapshot' => $source->layout_snapshot,
            'seo_snapshot' => $source->seo_snapshot,
            'data' => $source->data,
        ]);
    }
}
