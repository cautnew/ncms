<?php

namespace Database\Factories\Pages;

use App\Models\Pages\PageLayout;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageLayout>
 */
class PageLayoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'slug' => $this->faker->unique()->slug(),
            'name' => $this->faker->name(),
            'structure' => json_encode([
                'type' => 'row',
                'columns' => [
                    [
                        'type' => 'column',
                        'width' => 12,
                        'elements' => [
                            [
                                'type' => 'title',
                                'text' => $this->faker->title(),
                            ],
                        ],
                    ],
                ],
            ]),
            'created_by' => \App\Models\User::factory()->create()->id,
        ];
    }
}
