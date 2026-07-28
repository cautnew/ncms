<?php

namespace Database\Factories\Elements;

use App\Models\Elements\ElementType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ElementType>
 */
class ElementTypeFactory extends Factory
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
            'name' => $this->faker->word(),
            'element_class_name' => $this->faker->word(),
            'created_by' => $currentUserId,
            'created_at' => now(),
        ];
    }
}
