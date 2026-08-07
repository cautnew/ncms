<?php

use App\Enums\WebsiteRole;
use App\Http\Middleware\EnsureWebsiteRole;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

beforeEach(function () {
    $this->middleware = new EnsureWebsiteRole;
    $this->next = fn (Request $request) => new \Illuminate\Http\Response('ok');
});

function requestWithRouteWebsite(?Website $website, ?User $user): Request
{
    $request = Request::create('/test');
    $route = new \Illuminate\Routing\Route('GET', '/test', []);
    $route->bind($request);
    if ($website !== null) {
        $route->setParameter('website', $website);
    }
    $request->setRouteResolver(fn () => $route);

    if ($user !== null) {
        $request->setUserResolver(fn () => $user);
    }

    return $request;
}

it('aborts with 404 when the route has no bound website', function () {
    $request = requestWithRouteWebsite(null, User::factory()->create());

    $this->middleware->handle($request, $this->next);
})->throws(NotFoundHttpException::class);

it('aborts with 401 when there is no authenticated user', function () {
    $website = Website::factory()->create();
    $request = requestWithRouteWebsite($website, null);

    try {
        $this->middleware->handle($request, $this->next);
        $this->fail('Expected an HttpException to be thrown.');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(401);
    }
});

it('aborts with 403 when the user has no membership on the website', function () {
    $website = Website::factory()->create();
    $user = User::factory()->create();
    $request = requestWithRouteWebsite($website, $user);

    try {
        $this->middleware->handle($request, $this->next);
        $this->fail('Expected an HttpException to be thrown.');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(403);
    }
});

it('lets an owner through regardless of the roles listed', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $request = requestWithRouteWebsite($website, $owner);

    $response = $this->middleware->handle($request, $this->next, 'admin');

    expect($response->getContent())->toBe('ok');
});

it('lets a member through when no specific roles are required', function () {
    $website = Website::factory()->create();
    $viewer = User::factory()->create();
    createWebsiteMembership($website, $viewer, WebsiteRole::Viewer);
    $request = requestWithRouteWebsite($website, $viewer);

    $response = $this->middleware->handle($request, $this->next);

    expect($response->getContent())->toBe('ok');
});

it('lets a member through when their role is in the required list', function () {
    $website = Website::factory()->create();
    $editor = User::factory()->create();
    createWebsiteMembership($website, $editor, WebsiteRole::Editor);
    $request = requestWithRouteWebsite($website, $editor);

    $response = $this->middleware->handle($request, $this->next, 'admin', 'editor');

    expect($response->getContent())->toBe('ok');
});

it('aborts with 403 when the member role is not in the required list', function () {
    $website = Website::factory()->create();
    $viewer = User::factory()->create();
    createWebsiteMembership($website, $viewer, WebsiteRole::Viewer);
    $request = requestWithRouteWebsite($website, $viewer);

    try {
        $this->middleware->handle($request, $this->next, 'admin', 'editor');
        $this->fail('Expected an HttpException to be thrown.');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(403);
    }
});
