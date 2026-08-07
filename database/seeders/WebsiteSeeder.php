<?php

namespace Database\Seeders;

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;
use Illuminate\Database\Seeder;

/**
 * Seeds the single base website every other seeder builds on top of (see
 * DemoContentSeeder), owned by the AdminSeeder bootstrap account.
 *
 * Idempotent: safe to run standalone or as a dependency of other seeders —
 * re-running it never produces a second website or a duplicate membership.
 */
class WebsiteSeeder extends Seeder
{
    public const DOMAIN = 'kautch-demo.test';

    public function run(): void
    {
        $this->call(AdminSeeder::class);

        $owner = User::query()->where('email', AdminSeeder::EMAIL)->firstOrFail();

        $website = Website::query()->firstOrCreate(
            ['domain' => self::DOMAIN],
            [
                'name' => 'Kautch Demo',
                'subdomain' => '',
                'locale' => 'pt-BR',
                'timezone' => 'America/Sao_Paulo',
                'status' => 'active',
            ],
        );

        WebsiteUser::query()->firstOrCreate(
            ['website_id' => $website->id, 'user_id' => $owner->id],
            [
                'role' => WebsiteRole::Owner,
                'invited_by' => null,
                'invited_at' => now(),
                'accepted_at' => now(),
            ],
        );

        $this->command?->info("Website ready: {$website->name} ({$website->domain}), owned by {$owner->email}");
    }
}
