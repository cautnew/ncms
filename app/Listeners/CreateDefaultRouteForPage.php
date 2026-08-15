<?php

namespace App\Listeners;

use App\Enums\HttpMethod;
use App\Enums\RouteDestinationType;
use App\Events\PageCreated;
use App\Models\Route;
use App\Repositories\Contracts\RouteRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Rule: every Page created automatically gets a Route based on its slug.
 * created_by is passed explicitly (not left to HasUserstamps' Auth::id()
 * fallback) because this listener is queued — under a real queue connection
 * it runs outside the original request, with no authenticated user at all.
 */
class CreateDefaultRouteForPage implements ShouldQueue
{
    public function __construct(
        private readonly RouteRepositoryInterface $routes,
    ) {}

    public function handle(PageCreated $event): void
    {
        $page = $event->page;
        $path = Route::normalizePath('/'.$page->slug);

        // A route may already occupy this exact path (e.g. a redirect created
        // ahead of time) — the page still exists either way, it just won't
        // get an automatic route of its own in that rare case.
        if ($this->routes->findMatching($page->website, $path, HttpMethod::Get) !== null) {
            return;
        }

        $this->routes->create($page->website, [
            'path' => $path,
            'http_method' => HttpMethod::Get,
            'destination_type' => RouteDestinationType::Page,
            'page_id' => $page->id,
            'http_status' => 200,
            'created_by' => $page->created_by,
        ]);
    }
}
