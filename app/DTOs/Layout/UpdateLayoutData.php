<?php

namespace App\DTOs\Layout;

use App\Http\Requests\Layout\UpdateLayoutRequest;

final readonly class UpdateLayoutData
{
    /**
     * @param  array<string, mixed>|null  $schema
     */
    public function __construct(
        public ?string $name = null,
        public ?string $slug = null,
        public ?string $description = null,
        public ?array $schema = null,
        public ?string $status = null,
    ) {}

    public static function fromRequest(UpdateLayoutRequest $request): self
    {
        return new self(
            name: $request->filled('name') ? $request->string('name')->toString() : null,
            slug: $request->filled('slug') ? $request->string('slug')->toString() : null,
            description: $request->filled('description') ? $request->string('description')->toString() : null,
            schema: $request->has('schema') ? $request->input('schema') : null,
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'schema' => $this->schema,
            'status' => $this->status,
        ], fn (mixed $value): bool => $value !== null);
    }
}
