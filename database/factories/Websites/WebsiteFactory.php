<?php

namespace Database\Factories\Websites;

use App\Models\User;
use App\Models\Websites\Website;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $currentUserId = User::where('is_admin', true)->first()->id;

        $domain = $this->faker->domainName();
        $parts = explode('.', $domain);
        $sld = $parts[0];
        $tld = $parts[1];
        $cctld = $parts[2] ?? 'com';

        return [
            'id' => (string) Str::uuid(),
            'slug' => $this->faker->slug(),
            'name' => $this->faker->name(),
            'domain' => $domain,
            'subdomain' => $this->faker->slug(),
            'sld' => $sld,
            'tld' => $tld,
            'cctld' => $cctld,
            'is_active' => $this->faker->boolean(),
            'created_by' => $currentUserId,
            'created_at' => now(),
        ];
    }
}
