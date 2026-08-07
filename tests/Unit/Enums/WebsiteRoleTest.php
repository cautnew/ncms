<?php

use App\Enums\WebsiteRole;

it('labels every role', function (WebsiteRole $role, string $label) {
    expect($role->label())->toBe($label);
})->with([
    'owner' => [WebsiteRole::Owner, 'Owner'],
    'admin' => [WebsiteRole::Admin, 'Admin'],
    'editor' => [WebsiteRole::Editor, 'Editor'],
    'qa' => [WebsiteRole::Qa, 'QA'],
    'viewer' => [WebsiteRole::Viewer, 'Viewer'],
]);

it('lists exactly editor, qa and viewer as assignable by an admin', function () {
    expect(WebsiteRole::assignableByAdmin())->toBe([
        WebsiteRole::Editor,
        WebsiteRole::Qa,
        WebsiteRole::Viewer,
    ]);
    expect(WebsiteRole::assignableByAdmin())->not->toContain(WebsiteRole::Owner);
    expect(WebsiteRole::assignableByAdmin())->not->toContain(WebsiteRole::Admin);
});

it('is backed by the expected string values', function () {
    expect(WebsiteRole::Owner->value)->toBe('owner');
    expect(WebsiteRole::Admin->value)->toBe('admin');
    expect(WebsiteRole::Editor->value)->toBe('editor');
    expect(WebsiteRole::Qa->value)->toBe('qa');
    expect(WebsiteRole::Viewer->value)->toBe('viewer');
});
