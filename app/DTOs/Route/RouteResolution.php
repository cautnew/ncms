<?php

namespace App\DTOs\Route;

use App\Enums\RouteDestinationType;
use App\Enums\RouteResolutionOutcome;
use App\Models\Route;
use LogicException;

final readonly class RouteResolution
{
    private function __construct(
        public RouteResolutionOutcome $outcome,
        public ?Route $matchedRoute,
        public ?Route $finalRoute,
        public int $httpStatus,
    ) {}

    public static function notFound(): self
    {
        return new self(RouteResolutionOutcome::NotFound, null, null, 404);
    }

    /**
     * The matched route serves its destination directly — no redirect involved.
     */
    public static function destination(Route $matched): self
    {
        $outcome = match ($matched->destination_type) {
            RouteDestinationType::Page => RouteResolutionOutcome::Page,
            RouteDestinationType::Asset => RouteResolutionOutcome::Asset,
            RouteDestinationType::Redirect => throw new LogicException('destination() is not for redirect routes.'),
        };

        return new self($outcome, $matched, $matched, $matched->http_status);
    }

    /**
     * The matched route is a redirect whose chain resolves to $final.
     */
    public static function redirect(Route $matched, Route $final): self
    {
        return new self(RouteResolutionOutcome::Redirect, $matched, $final, $matched->http_status);
    }

    public static function loopDetected(Route $matched): self
    {
        return new self(RouteResolutionOutcome::LoopDetected, $matched, null, 508);
    }

    public static function tooManyRedirects(Route $matched): self
    {
        return new self(RouteResolutionOutcome::TooManyRedirects, $matched, null, 508);
    }

    /**
     * A redirect chain pointing at a route that no longer exists resolves
     * the same as a plain 404 — from the client's perspective there's
     * nothing left to distinguish it by.
     */
    public static function broken(Route $matched): self
    {
        return new self(RouteResolutionOutcome::NotFound, $matched, null, 404);
    }
}
