<?php

namespace Database\Factories;

use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Website>
 */
class WebsiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'domain' => fake()->unique()->domainName(),
            'subdomain' => '',
            'locale' => 'pt-BR',
            'timezone' => 'America/Sao_Paulo',
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the website is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }

    /**
     * Indicate that the website is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
        ]);
    }

    /**
     * Indicate that the website is served from a subdomain of its domain
     * (e.g. "blog" + "example.com" -> host "blog.example.com"), rather than
     * the default root-domain setup.
     */
    public function withSubdomain(?string $subdomain = null): static
    {
        return $this->state(fn (array $attributes) => [
            'subdomain' => $subdomain ?? fake()->unique()->domainWord(),
        ]);
    }
}
