<?php

namespace App\Http\Resources;

use App\Models\PageVersion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PageVersion
 */
class PageVersionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'page_id' => $this->page_id,
            'layout_id' => $this->layout_id,
            'cloned_from_id' => $this->cloned_from_id,
            'version_number' => $this->version_number,
            'status' => $this->status->value,
            'is_published' => $this->is_published,
            'is_editable' => $this->is_editable,
            'data' => $this->data,
            'layout_snapshot' => $this->layout_snapshot,
            'seo_snapshot' => $this->seo_snapshot,
            'created_by' => $this->created_by,
            'qa_user_id' => $this->qa_user_id,
            'qa_reviewed_at' => $this->qa_reviewed_at?->toIso8601String(),
            'qa_notes' => $this->qa_notes,
            'published_at' => $this->published_at?->toIso8601String(),
            'published_by' => $this->published_by,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
