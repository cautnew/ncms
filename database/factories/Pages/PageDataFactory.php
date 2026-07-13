<?php

namespace Database\Factories\Pages;

use App\Models\Pages\PageData;
use App\Models\Pages\PageVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PageData>
 */
class PageDataFactory extends Factory
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
            'key' => strtolower(implode('.', $this->faker->words(3))),
            'data' => $this->faker->realText(100),
            'page_version_id' => PageVersion::all()->pluck('id')->random(),
            'created_by' => $currentUserId,
            'created_at' => now(),
        ];
    }
}
