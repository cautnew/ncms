<?php

namespace Database\Factories\Pages;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pages\Type;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pages\Page>
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
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'active' => $this->faker->boolean,
            'type_id' => Type::factory()->create()->id,
        ];
    }
}
