<?php

namespace App\Models\Concerns;

use App\Exceptions\MissingActingUserException;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Stamps every create/update/(soft)delete with the acting user's id —
 * resolved from Auth::id() by default, or from an explicit actor set via
 * asActor() (for code paths that already have the actor in hand but run
 * outside an authenticated HTTP request, e.g. a queued job). If neither is
 * available, the write is refused: there is no silent "system"/null
 * fallback for who performed a write, only for when it happened (see
 * App\Support\Database\UserstampSchema for the DB-side date triggers).
 *
 * created_by, uniquely, also accepts a plain explicit attribute (mass-
 * assigned or set directly) — matching the pre-existing pattern of
 * PageVersion.created_by/Asset.uploaded_by — since "was it null before
 * insert" is unambiguous. updated_by/deleted_by are not: an update that
 * explicitly re-sets them to the value they already hold is indistinguishable
 * from an untouched attribute via Eloquent's dirty-tracking, so asActor() is
 * the only explicit override for those two.
 *
 * Requires the host model to also use Illuminate\Database\Eloquent\SoftDeletes
 * — every current usage does. Attach with `use HasUserstamps;`.
 *
 * SoftDeletes::runSoftDelete() persists only deleted_at/updated_at via a
 * hand-built column list, so it can't pick up deleted_by as an ordinary
 * dirty attribute. deleted_by is therefore written in a second, silent
 * follow-up query fired from the 'trashed' event, using the actor resolved
 * (and validated) up front in 'deleting' — before any row is touched.
 * Restoring is simpler: it nulls deleted_at and goes through a normal
 * save(), so nulling deleted_by in the 'restoring' listener rides along in
 * that same UPDATE.
 */
trait HasUserstamps
{
    private ?string $userstampActorId = null;

    public static function bootHasUserstamps(): void
    {
        static::creating(function (Model $model): void {
            $createdByColumn = $model->getCreatedByColumn();
            $updatedByColumn = $model->getUpdatedByColumn();

            if ($model->{$createdByColumn} === null) {
                $model->{$createdByColumn} = $model->resolveActingUserId('create');
            }

            if ($model->{$updatedByColumn} === null) {
                $model->{$updatedByColumn} = $model->{$createdByColumn};
            }
        });

        static::updating(function (Model $model): void {
            $model->{$model->getUpdatedByColumn()} = $model->resolveActingUserId('update');
        });

        static::deleting(function (Model $model): void {
            $column = $model->getDeletedByColumn();

            if ($model->{$column} === null) {
                $model->{$column} = $model->resolveActingUserId('delete');
            }
        });

        static::softDeleted(function (Model $model): void {
            $column = $model->getDeletedByColumn();

            static::withoutEvents(function () use ($model, $column): void {
                $model->newQueryWithoutScopes()
                    ->whereKey($model->getKey())
                    ->update([$column => $model->{$column}]);
            });
        });

        static::restoring(function (Model $model): void {
            $model->{$model->getDeletedByColumn()} = null;
        });
    }

    /**
     * Explicitly names the acting user for the next create/update/delete on
     * this instance, taking priority over Auth::id(). For code that already
     * knows its actor (a Service given a User parameter) but may run outside
     * an authenticated request — tests, console commands, queued jobs.
     */
    public function asActor(User|string $actor): static
    {
        $this->userstampActorId = $actor instanceof User ? $actor->getKey() : $actor;

        return $this;
    }

    public function getCreatedByColumn(): string
    {
        return 'created_by';
    }

    public function getUpdatedByColumn(): string
    {
        return 'updated_by';
    }

    public function getDeletedByColumn(): string
    {
        return 'deleted_by';
    }

    private function resolveActingUserId(string $operation): string
    {
        return $this->userstampActorId
            ?? Auth::id()
            ?? throw new MissingActingUserException(static::class, $operation);
    }
}
