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
     * Only the attributes that were actually provided.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'alt_text' => $this->altText,
            'metadata' => $this->metadata,
        ], fn (mixed $value): bool => $value !== null);
    }
}
