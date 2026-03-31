<?php

namespace Database\Factories\Routes;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\RequestMethods;
use App\Enums\ResponseTypes;
use App\Models\Pages\Page;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Routes\Route>
 */
class RouteFactory extends Factory
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
            'request_method_id' => $this->faker->randomElement(RequestMethods::cases()),
            'route' => '/' . $this->faker->slug,
            'page_id' => Page::factory()->create()->id,
            'controller_class' => $this->faker->word,
            'is_redirect' => $this->faker->boolean,
            'redirect_to' => $this->faker->url,
            'redirect_type_id' => $this->faker->randomElement(ResponseTypes::cases()),
            'is_active' => $this->faker->boolean,
            'is_authenticated_only' => $this->faker->boolean,
        ];
    }
}
