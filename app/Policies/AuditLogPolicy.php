<?php

namespace App\Policies;

use App\Enums\WebsiteRole;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Website;
use App\Policies\Concerns\AuthorizesWebsiteAccess;

class AuditLogPolicy
{
    use AuthorizesWebsiteAccess;

    /**
     * Determine whether the user can view the website's audit trail.
     */
    public function viewAny(User $user, Website $website): bool
    {
        return $this->authorize($user, $website, WebsiteRole::Admin);
    }

    /**
     * Determine whether the user can view a single audit log entry.
     */
    public function view(User $user, AuditLog $auditLog): bool
    {
        if ($auditLog->website === null) {
            return false;
        }

        return $this->authorize($user, $auditLog->website, WebsiteRole::Admin);
    }

    /**
     * Audit log entries are written exclusively by the application (never by users),
     * and are never updated, deleted, or restored — the trail is append-only and immutable.
     */
    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, AuditLog $auditLog): bool
    {
        return false;
    }

    public function delete(User $user, AuditLog $auditLog): bool
    {
        return false;
    }
}
