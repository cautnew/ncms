<?php

namespace App\Listeners;

use App\Events\PageVersionCloned;
use App\Models\AuditLog;
use App\Models\PageVersion;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordPageVersionCloneAuditLog implements ShouldQueue
{
    public function handle(PageVersionCloned $event): void
    {
        AuditLog::create([
            'website_id' => $event->draft->page->website_id,
            'user_id' => $event->actor->id,
            'auditable_type' => PageVersion::class,
            'auditable_id' => $event->draft->id,
            'action' => 'version_cloned',
            'old_values' => [
                'source_version_id' => $event->source->id,
                'source_version_number' => $event->source->version_number,
            ],
            'new_values' => [
                'draft_version_id' => $event->draft->id,
                'draft_version_number' => $event->draft->version_number,
            ],
            'ip_address' => $event->ipAddress,
            'user_agent' => $event->userAgent,
        ]);
    }
}
