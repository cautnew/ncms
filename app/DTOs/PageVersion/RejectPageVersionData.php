<?php

namespace App\DTOs\PageVersion;

use App\Http\Requests\PageVersion\RejectPageVersionRequest;

final readonly class RejectPageVersionData
{
    public function __construct(
        public string $notes,
    ) {}

    public static function fromRequest(RejectPageVersionRequest $request): self
    {
        return new self(
            notes: $request->string('notes')->toString(),
        );
    }
}
