<?php

namespace App\Policies;

use App\Enums\PageVersionStatus;
use App\Enums\WebsiteRole;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\User;
use App\Policies\Concerns\AuthorizesWebsiteAccess;

class PageVersionPolicy
{
    use AuthorizesWebsiteAccess;

    /**
     * Determine whether the user can view the page's versions.
     */
    public function viewAny(User $user, Page $page): bool
    {
        return $user->roleOn($page->website) !== null;
    }

    /**
     * Determine whether the user can view the version.
     */
    public function view(User $user, PageVersion $pageVersion): bool
    {
        return $user->roleOn($pageVersion->page->website) !== null;
    }

    /**
     * Determine whether the user can create a new draft version for the page.
     */
    public function create(User $user, Page $page): bool
    {
        return $this->authorize($user, $page->website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user can edit the version's seo/layout. Allowed
     * directly while draft/rejected, or against a published version — in
     * which case the write transparently lands on an auto-cloned draft
     * instead (see PageVersionCloningService). under_review/approved/archived
     * remain fully locked.
     */
    public function update(User $user, PageVersion $pageVersion): bool
    {
        if (! $pageVersion->status->isWritable()) {
            return false;
        }

        return $this->authorize($user, $pageVersion->page->website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user can submit the draft (or a rejected version) for QA review.
     */
    public function submitForReview(User $user, PageVersion $pageVersion): bool
    {
        if (! $pageVersion->status->canTransitionTo(PageVersionStatus::UnderReview)) {
            return false;
        }

        return $this->authorize($user, $pageVersion->page->website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user (as QA) can approve the version under review.
     */
    public function approve(User $user, PageVersion $pageVersion): bool
    {
        if (! $pageVersion->status->canTransitionTo(PageVersionStatus::Approved)) {
            return false;
        }

        return $this->authorize($user, $pageVersion->page->website, WebsiteRole::Qa);
    }

    /**
     * Determine whether the user (as QA) can reject the version under review.
     */
    public function reject(User $user, PageVersion $pageVersion): bool
    {
        if (! $pageVersion->status->canTransitionTo(PageVersionStatus::Rejected)) {
            return false;
        }

        return $this->authorize($user, $pageVersion->page->website, WebsiteRole::Qa);
    }

    /**
     * Determine whether the user can publish an approved version.
     * Editors never publish, even though they can create/submit content; only
     * admin (and, via owner-supremacy, the owner) may do so.
     */
    public function publish(User $user, PageVersion $pageVersion): bool
    {
        if (! $pageVersion->status->canTransitionTo(PageVersionStatus::Published)) {
            return false;
        }

        return $this->authorize($user, $pageVersion->page->website, WebsiteRole::Admin);
    }

    /**
     * Determine whether the user can delete the version.
     * Published versions can never be deleted.
     */
    public function delete(User $user, PageVersion $pageVersion): bool
    {
        if ($pageVersion->status->isPublished()) {
            return false;
        }

        return $this->authorize($user, $pageVersion->page->website, WebsiteRole::Admin);
    }

    /**
     * Determine whether the user can restore the version.
     */
    public function restore(User $user, PageVersion $pageVersion): bool
    {
        return $user->roleOn($pageVersion->page->website) === WebsiteRole::Owner;
    }

    /**
     * Determine whether the user can permanently delete the version.
     */
    public function forceDelete(User $user, PageVersion $pageVersion): bool
    {
        return $user->roleOn($pageVersion->page->website) === WebsiteRole::Owner;
    }
}
