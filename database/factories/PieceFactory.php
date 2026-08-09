<?php

namespace Database\Factories;

use App\Enums\PieceType;
use App\Models\Asset;
use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Piece>
 */
class PieceFactory extends Factory
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
            'parent_piece_id' => null,
            'asset_id' => null,
            'type' => PieceType::Paragraph,
            'slot' => null,
            'region' => null,
            'position' => 0,
            'content' => [
                'text' => fake()->paragraph(),
            ],
            'settings' => [],
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that this piece is a heading (H1-H6).
     */
    public function heading(int $level = 1): static
    {
        return $this->state(fn () => [
            'type' => PieceType::Heading,
            'content' => [
                'level' => $level,
                'text' => fake()->sentence(4),
            ],
        ]);
    }

    /**
     * Indicate that this piece is a paragraph.
     */
    public function paragraph(): static
    {
        return $this->state(fn () => [
            'type' => PieceType::Paragraph,
            'content' => [
                'text' => fake()->paragraph(),
            ],
        ]);
    }

    /**
     * Indicate that this piece is an image. Pass the Asset it should
     * reference — it must belong to the same website as the piece's page
     * version, which the factory has no way to guarantee on its own; left
     * null (as before) when no asset is given.
     */
    public function image(?Asset $asset = null): static
    {
        return $this->state(fn () => [
            'type' => PieceType::Image,
            'asset_id' => $asset?->id,
            'content' => [
                'caption' => fake()->sentence(3),
            ],
        ]);
    }

    /**
     * Indicate that this piece is a list.
     */
    public function list(): static
    {
        return $this->state(fn () => [
            'type' => PieceType::ListBlock,
            'content' => [
                'items' => fake()->words(3),
            ],
        ]);
    }

    /**
     * Indicate that this piece is a two-columns wrapper (container).
     */
    public function twoColumnsWrapper(): static
    {
        return $this->state(fn () => [
            'type' => PieceType::TwoColumnsWrapper,
            'content' => [],
            'settings' => ['gap' => '16px'],
        ]);
    }

    /**
     * Indicate that this piece is a text+image wrapper (container).
     */
    public function textImageWrapper(): static
    {
        return $this->state(fn () => [
            'type' => PieceType::TextImageWrapper,
            'content' => [],
            'settings' => ['image_position' => 'left'],
        ]);
    }

    /**
     * Indicate that this piece is nested under a given parent piece.
     */
    public function childOf(Piece $parent, ?string $slot = null): static
    {
        return $this->state(fn (array $attributes) => [
            'page_version_id' => $parent->page_version_id,
            'parent_piece_id' => $parent->id,
            'slot' => $slot,
            'region' => null,
        ]);
    }

    /**
     * Indicate that this root-level piece is assigned to a given layout region.
     */
    public function inRegion(string $region): static
    {
        return $this->state(fn () => [
            'region' => $region,
        ]);
    }
}
