<?php

namespace App\DTOs\PageVersion;

use App\Http\Requests\PageVersion\UpdatePageVersionRequest;

final readonly class UpdatePageVersionData
{
    /**
     * @param  array<string, mixed>|null  $seo
     */
    public function __construct(
        public ?string $layoutId = null,
        public ?array $seo = null,
        public bool $seoProvided = false,
    ) {}

    public static function fromRequest(UpdatePageVersionRequest $request): self
    {
        return new self(
            layoutId: $request->filled('layout_id') ? $request->string('layout_id')->toString() : null,
            seo: $request->has('seo') ? $request->input('seo') : null,
            seoProvided: $request->has('seo'),
        );
    }
}
