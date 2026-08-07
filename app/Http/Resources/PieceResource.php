<?php

namespace App\Http\Resources;

use App\Models\Piece;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Piece
 */
class PieceResource extends JsonResource
{
    /**
     * Transform the resource into an array. Recursive: each child is
     * serialized by this same resource, walking the in-memory tree that was
     * eager-loaded (in a single query) onto the `children` relation.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'page_version_id' => $this->page_version_id,
            'parent_piece_id' => $this->parent_piece_id,
            'asset_id' => $this->asset_id,
            'type' => $this->type->value,
            'slot' => $this->slot,
            'position' => $this->position,
            'content' => $this->content,
            'settings' => $this->settings,
            'is_root' => $this->is_root,
            'children' => self::collection($this->whenLoaded('children')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
