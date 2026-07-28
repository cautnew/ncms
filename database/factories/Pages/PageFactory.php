<?php

namespace Database\Factories\Pages;

use App\Models\Pages\Page;
use App\Models\Pages\PageLayout;
use App\Models\User;
use App\Models\Websites\Website;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $currentUserId = User::where('is_admin', true)->first()->id;

        return [
            'id' => (string) Str::uuid(),
            'name' => $this->faker->sentence(3),
            'slug' => $this->faker->unique()->slug(),
            'website_id' => Website::all()->pluck('id')->random(),
            'page_layout_id' => PageLayout::all()->pluck('id')->random(),
            'created_by' => $currentUserId,
            'created_at' => now(),
        ];
    }
}
