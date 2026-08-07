<?php

namespace App\Http\Resources;

use App\DTOs\Content\PublishedContentData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The CMS's main delivery payload: everything a frontend needs to render a
 * single published page in one response.
 *
 * @mixin PublishedContentData
 */
class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $version = $this->version;

        return [
            'website' => new WebsiteResource($this->website),
            // The layout as it existed at publish time (layout_snapshot), not
            // whatever the live Layout looks like now — a published version is
            // immutable, and this is the endpoint that guarantee is for.
            'layout' => [
                'id' => $version->layout_id,
                'name' => $version->layout_snapshot['name'] ?? null,
                'slug' => $version->layout_snapshot['slug'] ?? null,
                'description' => $version->layout_snapshot['description'] ?? null,
                'schema' => $version->layout_snapshot['schema'] ?? null,
            ],
            'layout_assets' => AssetResource::collection($version->layout->assets),
            'page' => new PageResource($this->page),
            'version' => [
                'id' => $version->id,
                'version_number' => $version->version_number,
                'published_at' => $version->published_at?->toIso8601String(),
                'data' => $version->data,
            ],
            'version_assets' => AssetResource::collection($version->assets),
            'pieces' => PieceResource::collection($this->pieces),
            'seo' => $version->seo_snapshot,
        ];
    }
}
