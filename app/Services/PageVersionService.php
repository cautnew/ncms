<?php

namespace App\Services;

use App\DTOs\PageVersion\CreatePageVersionData;
use App\DTOs\PageVersion\UpdatePageVersionData;
use App\Enums\PageVersionStatus;
use App\Enums\ReviewDecision;
use App\Models\Layout;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\User;
use App\Repositories\Contracts\PageVersionRepositoryInterface;
use App\Repositories\Contracts\PageVersionReviewRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class PageVersionService
{
    public function __construct(
        private readonly PageVersionRepositoryInterface $versions,
        private readonly PageVersionReviewRepositoryInterface $reviews,
        private readonly PageVersionCloningService $cloning,
    ) {}

    public function listForPage(Page $page, int $perPage = 15): LengthAwarePaginator
    {
        return $this->versions->paginateForPage($page, $perPage);
    }

    /**
     * Create a new draft version for the page. The layout is snapshotted in full
     * at this moment, so later edits to the Layout never affect an existing version.
     * Every version starts as a draft: publication (and therefore the "single
     * published version per page" invariant) is enforced by Page::published_version_id,
     * a single column that can only ever reference one version at a time.
     */
    public function create(Page $page, User $creator, CreatePageVersionData $data): PageVersion
    {
        $layout = Layout::where('id', $data->layoutId)->where('website_id', $page->website_id)->firstOrFail();

        return $this->versions->create($page, [
            'layout_id' => $layout->id,
            'layout_snapshot' => $layout->only(['name', 'slug', 'description', 'schema']),
            'seo_snapshot' => $data->seo,
            'status' => PageVersionStatus::Draft,
            'created_by' => $creator->id,
        ]);
    }

    /**
     * Update a version's seo snapshot and/or layout (which re-derives its
     * layout_snapshot). If the target is published, it is cloned into a new
     * draft first (the published version is never written to), and the
     * change is applied there instead.
     */
    public function update(PageVersion $pageVersion, User $actor, UpdatePageVersionData $data): PageVersion
    {
        if ($pageVersion->status->isPublished()) {
            $pageVersion = $this->cloning->cloneAsDraft($pageVersion, $actor)->draft;
        }

        $attributes = [];

        if ($data->layoutId !== null) {
            $layout = Layout::where('id', $data->layoutId)
                ->where('website_id', $pageVersion->page->website_id)
                ->firstOrFail();

            $attributes['layout_id'] = $layout->id;
            $attributes['layout_snapshot'] = $layout->only(['name', 'slug', 'description', 'schema']);
        }

        if ($data->seoProvided) {
            $attributes['seo_snapshot'] = $data->seo;
        }

        if ($attributes === []) {
            return $pageVersion;
        }

        return $this->versions->update($pageVersion, $attributes);
    }

    /**
     * Submit a draft (or a previously rejected version) for QA review.
     */
    public function submitForReview(PageVersion $pageVersion): PageVersion
    {
        return $this->versions->update($pageVersion, ['status' => PageVersionStatus::UnderReview]);
    }

    /**
     * QA approves a version under review, clearing it for publication. The
     * decision is appended to the version's review history, not stored on
     * the version itself — see PageVersionReview.
     */
    public function approve(PageVersion $pageVersion, User $qa, ?string $notes): PageVersion
    {
        $pageVersion = $this->versions->update($pageVersion, ['status' => PageVersionStatus::Approved]);

        $this->reviews->create($pageVersion, [
            'decision' => ReviewDecision::Approved,
            'qa_user_id' => $qa->id,
            'notes' => $notes,
        ]);

        return $pageVersion->fresh();
    }

    /**
     * QA rejects a version under review, sending it back for edits. The
     * decision is appended to the version's review history, not stored on
     * the version itself — see PageVersionReview.
     */
    public function reject(PageVersion $pageVersion, User $qa, string $notes): PageVersion
    {
        $pageVersion = $this->versions->update($pageVersion, ['status' => PageVersionStatus::Rejected]);

        $this->reviews->create($pageVersion, [
            'decision' => ReviewDecision::Rejected,
            'qa_user_id' => $qa->id,
            'notes' => $notes,
        ]);

        return $pageVersion->fresh();
    }

    /**
     * Publish an approved version. Whatever version was previously published for
     * this page (if any) is atomically archived, and Page::published_version_id is
     * repointed to the new one — the single source of truth enforcing that a page
     * only ever has one published version at a time.
     */
    public function publish(PageVersion $pageVersion, User $publisher): PageVersion
    {
        return DB::transaction(function () use ($pageVersion, $publisher): PageVersion {
            $page = $pageVersion->page()->lockForUpdate()->first();
            $previouslyPublishedId = $page->published_version_id;

            if ($previouslyPublishedId !== null && $previouslyPublishedId !== $pageVersion->id) {
                $previouslyPublished = PageVersion::whereKey($previouslyPublishedId)->lockForUpdate()->first();

                if ($previouslyPublished !== null) {
                    $this->versions->update($previouslyPublished, ['status' => PageVersionStatus::Archived]);
                }
            }

            $pageVersion = $this->versions->update($pageVersion, [
                'status' => PageVersionStatus::Published,
                'published_at' => now(),
                'published_by' => $publisher->id,
            ]);

            $page->update(['published_version_id' => $pageVersion->id]);

            return $pageVersion;
        });
    }

    public function delete(PageVersion $pageVersion): void
    {
        $this->versions->delete($pageVersion);
    }
}
