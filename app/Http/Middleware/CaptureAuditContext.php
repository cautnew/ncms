<?php

namespace App\Http\Middleware;

use App\Support\RequestAuditContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Captures the request's IP address and User-Agent once, up front, into
 * RequestAuditContext — the single source these two fields are read from
 * everywhere an audit_logs row is written (see App\Traits\Auditable and the
 * login/logout listeners). Registered globally so it runs on every request,
 * since auditable actions happen across every route group (public and
 * authenticated alike).
 */
class CaptureAuditContext
{
    public function __construct(
        private readonly RequestAuditContext $context,
    ) {}

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->context->fill($request->ip(), $request->userAgent());

        return $next($request);
    }
}
