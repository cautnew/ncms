<?php

namespace App\DTOs\Website;

use App\Http\Requests\Website\StoreWebsiteRequest;

final readonly class CreateWebsiteData
{
    public function __construct(
        public string $name,
        public string $domain,
        public string $subdomain,
        public string $locale,
        public ?string $timezone,
    ) {}

    public static function fromRequest(StoreWebsiteRequest $request): self
    {
        return new self(
            name: $request->string('name')->toString(),
            domain: $request->string('domain')->toString(),
            subdomain: $request->string('subdomain')->toString(),
            locale: $request->filled('locale') ? $request->string('locale')->toString() : 'pt-BR',
            timezone: $request->filled('timezone') ? $request->string('timezone')->toString() : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'domain' => $this->domain,
            'subdomain' => $this->subdomain,
            'locale' => $this->locale,
            'timezone' => $this->timezone,
        ];
    }
}
