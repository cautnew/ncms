<?php

namespace Database\Factories;

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WebsiteUser>
 */
class WebsiteUserFactory extends Factory
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
            'user_id' => User::factory(),
            'role' => fake()->randomElement(WebsiteRole::cases()),
            'invited_by' => null,
            'invited_at' => now(),
            'accepted_at' => now(),
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the invitation has not been accepted yet.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'accepted_at' => null,
        ]);
    }
}
