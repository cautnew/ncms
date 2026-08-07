<?php

namespace App\Policies;

use App\Enums\WebsiteRole;
use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\User;
use App\Policies\Concerns\AuthorizesWebsiteAccess;

class PiecePolicy
{
    use AuthorizesWebsiteAccess;

    /**
     * Determine whether the user can view the version's pieces.
     */
    public function viewAny(User $user, PageVersion $pageVersion): bool
    {
        return $user->roleOn($pageVersion->page->website) !== null;
    }

    /**
     * Determine whether the user can view the piece.
     */
    public function view(User $user, Piece $piece): bool
    {
        return $user->roleOn($piece->pageVersion->page->website) !== null;
    }

    /**
     * Determine whether the user can add a piece to the version.
     * Allowed while the version is directly editable (draft/rejected), or
     * against a published version — in which case the write transparently
     * lands on an auto-cloned draft instead (see PageVersionCloningService).
     * under_review/approved/archived remain fully locked.
     */
    public function create(User $user, PageVersion $pageVersion): bool
    {
        if (! $pageVersion->status->isWritable()) {
            return false;
        }

        return $this->authorize($user, $pageVersion->page->website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user can update the piece (content, position, slot).
     */
    public function update(User $user, Piece $piece): bool
    {
        if (! $piece->pageVersion->status->isWritable()) {
            return false;
        }

        return $this->authorize($user, $piece->pageVersion->page->website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user can delete the piece.
     */
    public function delete(User $user, Piece $piece): bool
    {
        if (! $piece->pageVersion->status->isWritable()) {
            return false;
        }

        return $this->authorize($user, $piece->pageVersion->page->website, WebsiteRole::Admin, WebsiteRole::Editor);
    }
}
