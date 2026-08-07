<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordLoginAuditLog implements ShouldQueue
{
    public function handle(UserLoggedIn $event): void
    {
        AuditLog::create([
            'website_id' => null,
            'user_id' => $event->userId,
            'auditable_type' => User::class,
            'auditable_id' => $event->userId,
            'action' => 'login',
            'old_values' => null,
            'new_values' => null,
            'ip_address' => $event->ipAddress,
            'user_agent' => $event->userAgent,
        ]);
    }
}
