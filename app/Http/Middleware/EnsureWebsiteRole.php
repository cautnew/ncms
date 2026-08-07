<?php

namespace App\Http\Middleware;

use App\Enums\WebsiteRole;
use App\Models\Website;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWebsiteRole
{
    /**
     * Handle an incoming request.
     *
     * Resolves the {website} route parameter and ensures the authenticated user
     * holds at least one of the given roles on it. Owners always pass. Usage:
     *
     *   Route::middleware('website.role:admin,editor')->group(...);
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $website = $request->route('website');

        if (! $website instanceof Website) {
            abort(404);
        }

        $user = $request->user();

        if ($user === null) {
            abort(401);
        }

        $role = $user->roleOn($website);

        if ($role === null) {
            abort(403, 'You do not have access to this website.');
        }

        if ($role === WebsiteRole::Owner || $roles === []) {
            return $next($request);
        }

        if (! in_array($role->value, $roles, true)) {
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
