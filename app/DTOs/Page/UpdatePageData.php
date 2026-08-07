<?php

namespace App\DTOs\Page;

use App\Http\Requests\Page\UpdatePageRequest;

final readonly class UpdatePageData
{
    public function __construct(
        public ?string $slug = null,
        public ?string $layoutId = null,
        public ?string $status = null,
    ) {}

    public static function fromRequest(UpdatePageRequest $request): self
    {
        return new self(
            slug: $request->filled('slug') ? $request->string('slug')->toString() : null,
            layoutId: $request->filled('layout_id') ? $request->string('layout_id')->toString() : null,
            status: $request->filled('status') ? $request->string('status')->toString() : null,
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
            'slug' => $this->slug,
            'layout_id' => $this->layoutId,
            'status' => $this->status,
        ], fn (mixed $value): bool => $value !== null);
    }
}
