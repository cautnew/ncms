<?php

namespace App\DTOs\PageVersion;

use App\Http\Requests\PageVersion\StorePageVersionRequest;

final readonly class CreatePageVersionData
{
    /**
     * @param  array<string, mixed>|null  $data
     * @param  array<string, mixed>|null  $seo
     */
    public function __construct(
        public string $layoutId,
        public ?array $data,
        public ?array $seo,
    ) {}

    public static function fromRequest(StorePageVersionRequest $request): self
    {
        return new self(
            layoutId: $request->string('layout_id')->toString(),
            data: $request->input('data'),
            seo: $request->input('seo'),
        );
    }
}
