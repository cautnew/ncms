<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired automatically by the Auditable trait for every create/update/delete
 * of an auditable model.
 *
 * Deliberately carries scalar identifiers (modelType/modelId/websiteId/
 * actorId) rather than the actual Eloquent instances: RecordModelAuditLog
 * implements ShouldQueue, and Laravel always routes ShouldQueue listeners
 * through job serialization — even under the "sync" queue driver. A raw
 * Model property would be replaced by a ModelIdentifier and re-fetched by
 * primary key when the listener runs; for the "delete" action that re-fetch
 * happens *after* the row is gone and throws ModelNotFoundException. ip/
 * userAgent are likewise captured at dispatch time (from RequestAuditContext)
 * rather than re-read in the listener, since a queue worker runs in a
 * different process/request where that per-request context would be empty.
 */
class ModelAudited
{
    use Dispatchable;

    /**
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     */
    public function __construct(
        public readonly string $modelType,
        public readonly string $modelId,
        public readonly ?string $websiteId,
        public readonly string $action,
        public readonly ?string $actorId,
        public readonly ?array $oldValues,
        public readonly ?array $newValues,
        public readonly ?string $ipAddress,
        public readonly ?string $userAgent,
    ) {}
}
