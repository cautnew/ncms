<?php

namespace App\Repositories\Contracts;

use App\Models\PageVersion;
use App\Models\PageVersionReview;

interface PageVersionReviewRepositoryInterface
{
    /**
     * Record a QA review decision against the version. Append-only — reviews
     * are never updated or deleted, only ever added to the history.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(PageVersion $pageVersion, array $attributes): PageVersionReview;
}
