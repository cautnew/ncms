<?php

namespace App\Repositories\Eloquent;

use App\Models\PageVersion;
use App\Models\PageVersionReview;
use App\Repositories\Contracts\PageVersionReviewRepositoryInterface;

final class PageVersionReviewRepository implements PageVersionReviewRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(PageVersion $pageVersion, array $attributes): PageVersionReview
    {
        return $pageVersion->reviews()->create($attributes);
    }
}
