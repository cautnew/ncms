<?php

namespace App\DTOs\Piece;

use App\Enums\PieceType;
use App\Http\Requests\Piece\StorePieceRequest;

final readonly class CreatePieceData
{
    /**
     * @param  array<string, mixed>|null  $content
     * @param  array<string, mixed>|null  $settings
     */
    public function __construct(
        public PieceType $type,
        public ?string $parentPieceId,
        public ?string $assetId,
        public ?string $slot,
        public ?array $content,
        public ?array $settings,
        public ?int $position,
    ) {}

    public static function fromRequest(StorePieceRequest $request): self
    {
        return new self(
            type: PieceType::from($request->string('type')->toString()),
            parentPieceId: $request->filled('parent_piece_id') ? $request->string('parent_piece_id')->toString() : null,
            assetId: $request->filled('asset_id') ? $request->string('asset_id')->toString() : null,
            slot: $request->filled('slot') ? $request->string('slot')->toString() : null,
            content: $request->input('content'),
            settings: $request->input('settings'),
            position: $request->filled('position') ? (int) $request->input('position') : null,
        );
    }
}
