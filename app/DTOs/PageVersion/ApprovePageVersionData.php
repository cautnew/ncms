<?php

namespace App\DTOs\PageVersion;

use App\Http\Requests\PageVersion\ApprovePageVersionRequest;

final readonly class ApprovePageVersionData
{
    public function __construct(
        public ?string $notes,
    ) {}

    public static function fromRequest(ApprovePageVersionRequest $request): self
    {
        return new self(
            notes: $request->filled('notes') ? $request->string('notes')->toString() : null,
        );
    }
}
