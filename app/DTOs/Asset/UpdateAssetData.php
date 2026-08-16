<?php

namespace App\DTOs\Asset;

use App\Http\Requests\Asset\UpdateAssetRequest;

final readonly class UpdateAssetData
{
    /**
     * @param  array<string, mixed>|null  $metadata
     */
    public function __construct(
        public ?string $altText = null,
        public ?array $metadata = null,
    ) {}

    public static function fromRequest(UpdateAssetRequest $request): self
    {
        return new self(
            altText: $request->filled('alt_text') ? $request->string('alt_text')->toString() : null,
            metadata: $request->has('metadata') ? $request->input('metadata') : null,
        );
    }

    /**
     * The metadata keys that were actually provided, to be merged into the
     * asset's existing metadata (alt_text/width/height/etc. all live inside
     * that JSON column — there's no dedicated attributes array to return here).
     *
     * @return array<string, mixed>
     */
    public function metadataPatch(): array
    {
        return array_filter([
            'alt_text' => $this->altText,
            ...($this->metadata ?? []),
        ], fn (mixed $value): bool => $value !== null);
    }
}
