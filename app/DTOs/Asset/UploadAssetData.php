<?php

namespace App\DTOs\Asset;

use App\Http\Requests\Asset\StoreAssetRequest;
use Illuminate\Http\UploadedFile;

final readonly class UploadAssetData
{
    /**
     * @param  array<string, mixed>|null  $metadata
     */
    public function __construct(
        public UploadedFile $file,
        public ?string $layoutId,
        public ?string $pageVersionId,
        public ?string $altText,
        public ?array $metadata,
    ) {}

    public static function fromRequest(StoreAssetRequest $request): self
    {
        /** @var UploadedFile $file */
        $file = $request->file('file');

        return new self(
            file: $file,
            layoutId: $request->input('layout_id'),
            pageVersionId: $request->input('page_version_id'),
            altText: $request->filled('alt_text') ? $request->string('alt_text')->toString() : null,
            metadata: $request->input('metadata'),
        );
    }
}
