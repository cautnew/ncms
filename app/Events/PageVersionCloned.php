<?php

namespace App\Events;

use App\Models\PageVersion;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PageVersionCloned
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly PageVersion $source,
        public readonly PageVersion $draft,
        public readonly User $actor,
        public readonly ?string $ipAddress = null,
        public readonly ?string $userAgent = null,
    ) {}
}
