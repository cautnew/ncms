<?php

namespace App\Events;

use App\Models\Page;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PageCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Page $page,
    ) {}
}
