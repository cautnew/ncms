<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds the platform's bootstrap admin account — the credential used to log
 * into a fresh install and start creating websites. It has no website
 * membership of its own; access to a specific website is granted separately
 * (see WebsiteSeeder, which makes this user the Owner of the demo website).
 *
 * Idempotent: safe to run standalone or as a dependency of other seeders.
 */
class AdminSeeder extends Seeder
{
    public const EMAIL = 'admin@kautch.test';

    public const PASSWORD = 'password';

    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => self::EMAIL],
            [
                'name' => 'System Admin',
                'email_verified_at' => now(),
                'password' => Hash::make(self::PASSWORD),
            ],
        );

        $this->command?->info(sprintf('Admin user ready: %s / %s',
            self::EMAIL,
            self::PASSWORD,
        ));
    }
}
