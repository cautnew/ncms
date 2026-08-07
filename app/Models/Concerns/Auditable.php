<?php

namespace App\Models\Concerns;

use App\Support\AuditRecorder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Makes every create/update/delete of the model automatically produce an
 * audit_logs entry — no call site anywhere in the app has to remember to
 * fire one by hand. Attach with `use Auditable;` on any model that should be
 * tracked; never attach it to AuditLog itself (infinite recursion).
 *
 * Models with a more specific vocabulary than plain create/update (e.g. an
 * Asset being "uploaded" rather than merely "created", or a PageVersion being
 * "approved"/"rejected"/"published" rather than merely "updated") should
 * override auditActionForCreate()/auditActionForUpdate() below.
 */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function (Model $model): void {
            AuditRecorder::record($model, $model->auditActionForCreate(), null, $model->getAttributes());
        });

        static::updated(function (Model $model): void {
            $changes = $model->getChanges();
            unset($changes['updated_at']);

            if ($changes === []) {
                return;
            }

            $oldValues = Arr::only($model->getOriginal(), array_keys($changes));

            AuditRecorder::record($model, $model->auditActionForUpdate($changes), $oldValues, $changes);
        });

        static::deleted(function (Model $model): void {
            AuditRecorder::record($model, 'delete', $model->getAttributes(), null);
        });
    }

    public function auditActionForCreate(): string
    {
        return 'create';
    }

    /**
     * @param  array<string, mixed>  $changes
     */
    public function auditActionForUpdate(array $changes): string
    {
        return 'update';
    }

    /**
     * The website an audit_logs entry for this model should be scoped to.
     * Default assumes a direct website_id column; override on models that
     * reach their owning website indirectly (or, for Website itself, are it).
     */
    public function auditWebsiteId(): ?string
    {
        return $this->getAttribute('website_id');
    }
}
