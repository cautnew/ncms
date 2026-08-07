<?php

namespace App\Events;

use App\Models\User;
use App\Models\WebsiteUser;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebsiteUserInvited
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly WebsiteUser $membership,
        public readonly User $inviter,
    ) {}
}
