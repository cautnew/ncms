<?php

namespace App\DTOs\Content;

use App\Models\Page;
use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\Website;
use Illuminate\Support\Collection;

final readonly class PublishedContentData
{
    /**
     * @param  Collection<int, Piece>  $pieces  root pieces, with descendants recursively eager-loaded
     */
    public function __construct(
        public Website $website,
        public Page $page,
        public PageVersion $version,
        public Collection $pieces,
    ) {}
}
