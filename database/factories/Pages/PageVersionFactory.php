<?php

namespace Database\Factories\Pages;

use App\Models\Pages\Page;
use App\Models\Pages\PageVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $currentUserId = User::where('is_admin', true)->first()->id;

        return [
            'id' => (string) Str::uuid(),
            'name' => $this->faker->sentence(3),
            'locale' => $this->faker->locale(),
            'page_id' => Page::all()->pluck('id')->random(),
            'is_current_editing' => $this->faker->boolean(1),
            'is_current' => $this->faker->boolean(1),
            'created_by' => $currentUserId,
            'created_at' => now(),
        ];
    }
}
