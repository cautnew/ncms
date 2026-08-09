<?php

namespace App\DTOs\PageVersion;

use App\Http\Requests\PageVersion\StorePageVersionRequest;

final readonly class CreatePageVersionData
{
    /**
     * @param  array<string, mixed>|null  $seo
     */
    public function __construct(
        public string $layoutId,
        public ?array $seo,
    ) {}

    public static function fromRequest(StorePageVersionRequest $request): self
    {
        return new self(
            layoutId: $request->string('layout_id')->toString(),
            seo: $request->input('seo'),
        );
    }
}
