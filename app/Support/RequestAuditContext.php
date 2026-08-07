<?php

namespace App\Support;

/**
 * Holds the current request's IP address and User-Agent, captured once by
 * CaptureAuditContext middleware and read from wherever an audit entry is
 * produced (the Auditable trait, auth events, ...).
 *
 * Bound as a singleton, so it lives for exactly one request. Events that may
 * be handled by a queued listener (i.e. after this request has ended) must
 * copy these values into their own constructor properties at dispatch time
 * rather than re-reading this context from inside handle() — a queue worker
 * runs in a different process, where this singleton would simply be empty.
 */
final class RequestAuditContext
{
    private ?string $ip = null;

    private ?string $userAgent = null;

    public function fill(?string $ip, ?string $userAgent): void
    {
        $this->ip = $ip;
        $this->userAgent = $userAgent;
    }

    public function ip(): ?string
    {
        return $this->ip;
    }

    public function userAgent(): ?string
    {
        return $this->userAgent;
    }
}
