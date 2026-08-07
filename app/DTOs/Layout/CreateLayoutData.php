<?php

namespace App\DTOs\Layout;

use App\Http\Requests\Layout\StoreLayoutRequest;

final readonly class CreateLayoutData
{
    /**
     * @param  array<string, mixed>|null  $schema
     */
    public function __construct(
        public string $name,
        public string $slug,
        public ?string $description,
        public ?array $schema,
        public string $status,
    ) {}

    public static function fromRequest(StoreLayoutRequest $request): self
    {
        return new self(
            name: $request->string('name')->toString(),
            slug: $request->string('slug')->toString(),
            description: $request->filled('description') ? $request->string('description')->toString() : null,
            schema: $request->input('schema'),
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
            'description' => $this->description,
            'schema' => $this->schema,
            'status' => $this->status,
        ];
    }
}
