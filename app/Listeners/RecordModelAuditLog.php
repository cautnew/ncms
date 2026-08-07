<?php

namespace App\Listeners;

use App\Events\ModelAudited;
use App\Models\AuditLog;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordModelAuditLog implements ShouldQueue
{
    public function handle(ModelAudited $event): void
    {
        AuditLog::create([
            'website_id' => $event->websiteId,
            'user_id' => $event->actorId,
            'auditable_type' => $event->modelType,
            'auditable_id' => $event->modelId,
            'action' => $event->action,
            'old_values' => $event->oldValues,
            'new_values' => $event->newValues,
            'ip_address' => $event->ipAddress,
            'user_agent' => $event->userAgent,
        ]);
    }
}
