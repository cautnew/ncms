<?php

namespace App\DTOs\Website;

use App\Http\Requests\Website\UpdateWebsiteRequest;

final readonly class UpdateWebsiteData
{
    public function __construct(
        public ?string $name = null,
        public ?string $domain = null,
        public ?string $subdomain = null,
        public ?string $locale = null,
        public ?string $timezone = null,
        public ?string $status = null,
    ) {}

    public static function fromRequest(UpdateWebsiteRequest $request): self
    {
        return new self(
            name: $request->filled('name') ? $request->string('name')->toString() : null,
            domain: $request->filled('domain') ? $request->string('domain')->toString() : null,
            subdomain: $request->has('subdomain') ? $request->string('subdomain')->toString() : null,
            locale: $request->filled('locale') ? $request->string('locale')->toString() : null,
            timezone: $request->has('timezone') ? $request->string('timezone')->toString() : null,
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
            'domain' => $this->domain,
            'subdomain' => $this->subdomain,
            'locale' => $this->locale,
            'timezone' => $this->timezone,
            'status' => $this->status,
        ], fn (mixed $value): bool => $value !== null);
    }
}
