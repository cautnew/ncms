<?php

namespace Database\Factories;

use App\Enums\ReviewDecision;
use App\Models\PageVersion;
use App\Models\PageVersionReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageVersionReview>
 */
class PageVersionReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'page_version_id' => PageVersion::factory(),
            'decision' => ReviewDecision::Approved,
            'qa_user_id' => User::factory(),
            'notes' => null,
        ];
    }

    /**
     * Indicate that this review approved the version.
     */
    public function approved(): static
    {
        return $this->state(fn () => [
            'decision' => ReviewDecision::Approved,
        ]);
    }

    /**
     * Indicate that this review rejected the version.
     */
    public function rejected(): static
    {
        return $this->state(fn () => [
            'decision' => ReviewDecision::Rejected,
            'notes' => 'Needs changes.',
        ]);
    }
}
