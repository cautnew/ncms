<?php

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Policies\Concerns\AuthorizesWebsiteAccess;

/**
 * Exercises the shared authorize() helper directly (rather than through one
 * specific policy) since every Policy in the app delegates to it.
 */
function authorizesWebsiteAccessChecker(): object
{
    return new class
    {
        use AuthorizesWebsiteAccess;

        public function check(User $user, Website $website, WebsiteRole ...$roles): bool
        {
            return $this->authorize($user, $website, ...$roles);
        }
    };
}

it('denies a user with no membership on the website, regardless of roles listed', function () {
    $website = Website::factory()->create();
    $outsider = User::factory()->create();

    expect(authorizesWebsiteAccessChecker()->check($outsider, $website, WebsiteRole::Admin, WebsiteRole::Editor))->toBeFalse();
    expect(authorizesWebsiteAccessChecker()->check($outsider, $website))->toBeFalse();
});

it('always allows an owner, even when no roles are listed', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);

    expect(authorizesWebsiteAccessChecker()->check($owner, $website))->toBeTrue();
    expect(authorizesWebsiteAccessChecker()->check($owner, $website, WebsiteRole::Viewer))->toBeTrue();
});

it('allows a member whose role is in the given list', function () {
    $website = Website::factory()->create();
    $editor = User::factory()->create();
    createWebsiteMembership($website, $editor, WebsiteRole::Editor);

    expect(authorizesWebsiteAccessChecker()->check($editor, $website, WebsiteRole::Admin, WebsiteRole::Editor))->toBeTrue();
});

it('denies a member whose role is not in the given list', function () {
    $website = Website::factory()->create();
    $viewer = User::factory()->create();
    createWebsiteMembership($website, $viewer, WebsiteRole::Viewer);

    expect(authorizesWebsiteAccessChecker()->check($viewer, $website, WebsiteRole::Admin, WebsiteRole::Editor))->toBeFalse();
});
