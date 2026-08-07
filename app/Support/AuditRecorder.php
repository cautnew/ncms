<?php

namespace App\Support;

use App\Events\ModelAudited;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Dispatches ModelAudited on behalf of the Auditable trait. A plain static
 * helper (rather than an injected service) because Eloquent model events are
 * registered from a static boot method, where there is no instance to hold
 * constructor-injected dependencies.
 */
final class AuditRecorder
{
    /**
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     */
    public static function record(Model $model, string $action, ?array $oldValues, ?array $newValues): void
    {
        $user = Auth::user();
        $context = app(RequestAuditContext::class);

        event(new ModelAudited(
            modelType: $model::class,
            modelId: (string) $model->getKey(),
            websiteId: $model->auditWebsiteId(),
            action: $action,
            actorId: $user instanceof User ? $user->id : null,
            oldValues: $oldValues,
            newValues: $newValues,
            ipAddress: $context->ip(),
            userAgent: $context->userAgent(),
        ));
    }
}
