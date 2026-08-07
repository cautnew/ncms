<?php

namespace App\Policies;

use App\Enums\WebsiteRole;
use App\Models\Asset;
use App\Models\User;
use App\Models\Website;
use App\Policies\Concerns\AuthorizesWebsiteAccess;

class AssetPolicy
{
    use AuthorizesWebsiteAccess;

    /**
     * Determine whether the user can view the website's assets.
     */
    public function viewAny(User $user, Website $website): bool
    {
        return $user->roleOn($website) !== null;
    }

    /**
     * Determine whether the user can view the asset.
     */
    public function view(User $user, Asset $asset): bool
    {
        return $user->roleOn($asset->website) !== null;
    }

    /**
     * Determine whether the user can upload a new asset to the website.
     */
    public function create(User $user, Website $website): bool
    {
        return $this->authorize($user, $website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user can update the asset. If it's attached to a
     * published page_version, the write transparently lands on an auto-cloned
     * draft instead (see PageVersionCloningService) — the published version's
     * own copy of the asset is never touched. Assets on an under_review/
     * approved/archived version remain fully locked.
     */
    public function update(User $user, Asset $asset): bool
    {
        if ($asset->pageVersion !== null && ! $asset->pageVersion->status->isWritable()) {
            return false;
        }

        return $this->authorize($user, $asset->website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user can delete the asset. Same clone-on-write
     * rule as update().
     */
    public function delete(User $user, Asset $asset): bool
    {
        if ($asset->pageVersion !== null && ! $asset->pageVersion->status->isWritable()) {
            return false;
        }

        return $this->authorize($user, $asset->website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user can restore the asset.
     */
    public function restore(User $user, Asset $asset): bool
    {
        return $user->roleOn($asset->website) === WebsiteRole::Owner;
    }

    /**
     * Determine whether the user can permanently delete the asset.
     */
    public function forceDelete(User $user, Asset $asset): bool
    {
        return $user->roleOn($asset->website) === WebsiteRole::Owner;
    }
}
