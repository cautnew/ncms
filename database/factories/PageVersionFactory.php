<?php

namespace Database\Factories;

use App\Enums\PageVersionStatus;
use App\Models\Layout;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\PageVersionReview;
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
            'layout_snapshot' => fn (array $attributes) => Layout::find($attributes['layout_id'])
                ->only(['name', 'slug', 'description', 'schema']),
            'seo_snapshot' => [
                'title' => fake()->sentence(6),
                'description' => fake()->sentence(15),
                'canonical_url' => fake()->url(),
            ],
            'created_by' => User::factory(),
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
     * Indicate that the version is approved by QA — records an approved
     * entry in its review history (see PageVersionReview). Pass $qa to
     * control who made the decision; otherwise a random user is generated.
     */
    public function approved(?User $qa = null, ?string $notes = null): static
    {
        return $this->state(fn () => [
            'status' => PageVersionStatus::Approved,
        ])->afterCreating(function (PageVersion $version) use ($qa, $notes): void {
            PageVersionReview::factory()->approved()->create([
                'page_version_id' => $version->id,
                'qa_user_id' => $qa?->id ?? User::factory(),
                'notes' => $notes,
            ]);
        });
    }

    /**
     * Indicate that the version was rejected by QA — records a rejected
     * entry in its review history (see PageVersionReview). Pass $qa to
     * control who made the decision; otherwise a random user is generated.
     */
    public function rejected(?User $qa = null, ?string $notes = null): static
    {
        return $this->state(fn () => [
            'status' => PageVersionStatus::Rejected,
        ])->afterCreating(function (PageVersion $version) use ($qa, $notes): void {
            PageVersionReview::factory()->rejected()->create([
                'page_version_id' => $version->id,
                'qa_user_id' => $qa?->id ?? User::factory(),
                'notes' => $notes ?? 'Needs changes.',
            ]);
        });
    }

    /**
     * Indicate that the version is published. A published version must have
     * already been approved — if this state is used on its own (without
     * ->approved() chained first), an approved review is backfilled here.
     */
    public function published(?User $qa = null): static
    {
        return $this->state(fn () => [
            'status' => PageVersionStatus::Published,
            'published_at' => now(),
            'published_by' => User::factory(),
        ])->afterCreating(function (PageVersion $version) use ($qa): void {
            if (! $version->reviews()->exists()) {
                PageVersionReview::factory()->approved()->create([
                    'page_version_id' => $version->id,
                    'qa_user_id' => $qa?->id ?? User::factory(),
                ]);
            }
        });
    }

    /**
     * Indicate that the version has been superseded by a newer publication.
     */
    public function archived(?User $qa = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PageVersionStatus::Archived,
            'published_at' => $attributes['published_at'] ?? now(),
            'published_by' => $attributes['published_by'] ?? User::factory(),
        ])->afterCreating(function (PageVersion $version) use ($qa): void {
            if (! $version->reviews()->exists()) {
                PageVersionReview::factory()->approved()->create([
                    'page_version_id' => $version->id,
                    'qa_user_id' => $qa?->id ?? User::factory(),
                ]);
            }
        });
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
        ]);
    }
}
