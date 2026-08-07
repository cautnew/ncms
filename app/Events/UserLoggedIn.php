<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UserLoggedIn
{
    use Dispatchable;

    public function __construct(
        public readonly string $userId,
        public readonly ?string $ipAddress,
        public readonly ?string $userAgent,
    ) {}
}
