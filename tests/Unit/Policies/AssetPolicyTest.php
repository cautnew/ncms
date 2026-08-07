<?php

use App\Enums\PageVersionStatus;
use App\Enums\WebsiteRole;
use App\Models\Asset;
use App\Models\Layout;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\User;
use App\Models\Website;
use App\Policies\AssetPolicy;

beforeEach(function () {
    $this->policy = new AssetPolicy;
    $this->website = Website::factory()->create();
    $this->layout = Layout::factory()->create(['website_id' => $this->website->id]);
});

it('lets any member view assets but denies an outsider', function () {
    $asset = Asset::factory()->forLayout($this->layout)->create();
    $member = User::factory()->create();
    createWebsiteMembership($this->website, $member, WebsiteRole::Viewer);
    $outsider = User::factory()->create();

    expect($this->policy->viewAny($member, $this->website))->toBeTrue();
    expect($this->policy->view($member, $asset))->toBeTrue();
    expect($this->policy->viewAny($outsider, $this->website))->toBeFalse();
    expect($this->policy->view($outsider, $asset))->toBeFalse();
});

it('only lets owner, admin and editor upload assets', function (WebsiteRole $role, bool $expected) {
    $user = User::factory()->create();
    createWebsiteMembership($this->website, $user, $role);

    expect($this->policy->create($user, $this->website))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, true],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('lets owner, admin and editor update/delete a layout-owned asset (no page_version to lock it)', function (WebsiteRole $role, bool $expected) {
    $user = User::factory()->create();
    createWebsiteMembership($this->website, $user, $role);
    $asset = Asset::factory()->forLayout($this->layout)->create();

    expect($this->policy->update($user, $asset))->toBe($expected);
    expect($this->policy->delete($user, $asset))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, true],
    'qa' => [WebsiteRole::Qa, false],
]);

it('allows updating/deleting an asset while its version is draft/rejected/published, denies otherwise', function (PageVersionStatus $status, bool $expected) {
    $owner = User::factory()->create();
    createWebsiteMembership($this->website, $owner, WebsiteRole::Owner);
    $page = Page::factory()->create(['website_id' => $this->website->id, 'layout_id' => $this->layout->id]);
    $version = PageVersion::factory()->create(['page_id' => $page->id, 'layout_id' => $this->layout->id, 'status' => $status]);
    $asset = Asset::factory()->forPageVersion($version)->create();

    expect($this->policy->update($owner, $asset))->toBe($expected);
    expect($this->policy->delete($owner, $asset))->toBe($expected);
})->with([
    'draft' => [PageVersionStatus::Draft, true],
    'rejected' => [PageVersionStatus::Rejected, true],
    'published' => [PageVersionStatus::Published, true],
    'under_review' => [PageVersionStatus::UnderReview, false],
    'approved' => [PageVersionStatus::Approved, false],
    'archived' => [PageVersionStatus::Archived, false],
]);

it('only lets the owner restore or force-delete an asset', function (WebsiteRole $role, bool $expected) {
    $user = User::factory()->create();
    createWebsiteMembership($this->website, $user, $role);
    $asset = Asset::factory()->forLayout($this->layout)->create();

    expect($this->policy->restore($user, $asset))->toBe($expected);
    expect($this->policy->forceDelete($user, $asset))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, false],
]);
