<?php

namespace App\DTOs\Page;

use App\Http\Requests\Page\StorePageRequest;

final readonly class CreatePageData
{
    public function __construct(
        public string $name,
        public string $slug,
        public string $layoutId,
        public string $status,
    ) {}

    public static function fromRequest(StorePageRequest $request): self
    {
        return new self(
            name: $request->string('name')->toString(),
            slug: $request->string('slug')->toString(),
            layoutId: $request->string('layout_id')->toString(),
            status: $request->filled('status') ? $request->string('status')->toString() : 'active',
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'layout_id' => $this->layoutId,
            'status' => $this->status,
        ];
    }
}
