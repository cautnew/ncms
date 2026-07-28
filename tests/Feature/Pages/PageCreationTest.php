<?php

use App\Models\Pages\Page;
use App\Models\User;
use App\Models\Websites\Website;
use Illuminate\Support\Facades\Auth;

test('Creates a page successfully', function () {
    $user = User::factory()->admin()->create();
    Auth::login($user);
    $website = Website::factory()->create();
    $slug = 'test-page';

    $response = $this->actingAs($user)->post('/api/page/create', [
        'name' => 'Test Page',
        'slug' => $slug,
        'website_id' => $website->id,
    ]);

    $page = Page::findBySlug($slug);

    $response->assertJsonStructure([
        'new-page' => [
            'page-id', 'slug', 'website-slug', 'message'
        ],
    ]);
    $response->assertJson(['new-page' => [
        'page-id' => $page->id,
        'slug' => $page->slug,
        'website-slug' => $page->website->slug,
        'message' => 'Page created successfully.',
    ]]);
    $response->assertStatus(200);
});

test("It's available for admin users", function () {
    $user = User::factory()->admin()->create();
    Auth::login($user);
    $website = Website::factory()->create();

    $response = $this->actingAs($user)->post('/api/page/create', [
        'name' => fake()->name(),
        'website_id' => $website->id,
    ]);

    $response->assertStatus(200);
});

test("It's NOT available for non admin users", function () {
    $user = User::factory()->nonAdmin()->create();
    $response = $this->actingAs($user)->post('/api/page/create');

    $response->assertStatus(403);
});

test('Invalid data fails', function () {
    $user = User::factory()->admin()->create();
    $response = $this->actingAs($user)->post('/api/page/create', [
        'slug' => 'test-page',
    ]);

    $response->assertStatus(422);
});

test('Slug already exists', function () {
    $user = User::factory()->admin()->create();
    Auth::login($user);
    $website = Website::factory()->create();
    $response = $this->actingAs($user)->post('/api/page/create', [
        'name' => 'Test Page',
        'slug' => 'test-page',
        'website_id' => $website->id,
    ]);

    $response = $this->actingAs($user)->post('/api/page/create', [
        'name' => 'Test Page',
        'slug' => 'test-page',  
        'website_id' => $website->id,
    ]);

    $response->assertStatus(400);
    $response->assertJson([
        'error' => 'Failed to create page.',
        'message' => 'Slug already exists.',
    ]);
});
