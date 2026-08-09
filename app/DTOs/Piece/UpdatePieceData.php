<?php

namespace App\DTOs\Piece;

use App\Http\Requests\Piece\UpdatePieceRequest;

final readonly class UpdatePieceData
{
    /**
     * @param  array<string, mixed>|null  $content
     * @param  array<string, mixed>|null  $settings
     */
    public function __construct(
        public ?string $parentPieceId = null,
        public ?string $assetId = null,
        public ?string $slot = null,
        public ?string $region = null,
        public ?array $content = null,
        public ?array $settings = null,
        public ?int $position = null,
    ) {}

    public static function fromRequest(UpdatePieceRequest $request): self
    {
        return new self(
            parentPieceId: $request->filled('parent_piece_id') ? $request->string('parent_piece_id')->toString() : null,
            assetId: $request->filled('asset_id') ? $request->string('asset_id')->toString() : null,
            slot: $request->filled('slot') ? $request->string('slot')->toString() : null,
            region: $request->filled('region') ? $request->string('region')->toString() : null,
            content: $request->has('content') ? $request->input('content') : null,
            settings: $request->has('settings') ? $request->input('settings') : null,
            position: $request->filled('position') ? (int) $request->input('position') : null,
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
            'parent_piece_id' => $this->parentPieceId,
            'asset_id' => $this->assetId,
            'slot' => $this->slot,
            'region' => $this->region,
            'content' => $this->content,
            'settings' => $this->settings,
            'position' => $this->position,
        ], fn (mixed $value): bool => $value !== null);
    }
}
