<?php

namespace App\Http\Resources;

use App\Models\PageVersionReview;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PageVersionReview
 */
class PageVersionReviewResource extends JsonResource
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
            'page_version_id' => $this->page_version_id,
            'decision' => $this->decision->value,
            'qa_user_id' => $this->qa_user_id,
            'notes' => $this->notes,
            'reviewed_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
