<?php

use App\Enums\PageVersionStatus;
use App\Enums\WebsiteRole;
use App\Models\Layout;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\User;
use App\Models\Website;
use App\Policies\PageVersionPolicy;

beforeEach(function () {
    $this->policy = new PageVersionPolicy;
    $this->website = Website::factory()->create();
    $this->layout = Layout::factory()->create(['website_id' => $this->website->id]);
    $this->page = Page::factory()->create(['website_id' => $this->website->id, 'layout_id' => $this->layout->id]);
});

it('lets any member view versions but denies an outsider', function () {
    $version = PageVersion::factory()->create(['page_id' => $this->page->id, 'layout_id' => $this->layout->id]);
    $member = User::factory()->create();
    createWebsiteMembership($this->website, $member, WebsiteRole::Viewer);
    $outsider = User::factory()->create();

    expect($this->policy->viewAny($member, $this->page))->toBeTrue();
    expect($this->policy->view($member, $version))->toBeTrue();
    expect($this->policy->viewAny($outsider, $this->page))->toBeFalse();
    expect($this->policy->view($outsider, $version))->toBeFalse();
});

it('only lets owner, admin and editor create a new version', function (WebsiteRole $role, bool $expected) {
    $user = User::factory()->create();
    createWebsiteMembership($this->website, $user, $role);

    expect($this->policy->create($user, $this->page))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, true],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('allows update while draft/rejected/published (write triggers clone), denies while under_review/approved/archived', function (PageVersionStatus $status, bool $expected) {
    $owner = User::factory()->create();
    createWebsiteMembership($this->website, $owner, WebsiteRole::Owner);
    $version = PageVersion::factory()->create([
        'page_id' => $this->page->id,
        'layout_id' => $this->layout->id,
        'status' => $status,
    ]);

    expect($this->policy->update($owner, $version))->toBe($expected);
})->with([
    'draft' => [PageVersionStatus::Draft, true],
    'rejected' => [PageVersionStatus::Rejected, true],
    'published' => [PageVersionStatus::Published, true],
    'under_review' => [PageVersionStatus::UnderReview, false],
    'approved' => [PageVersionStatus::Approved, false],
    'archived' => [PageVersionStatus::Archived, false],
]);

it('only lets submitForReview succeed from draft or rejected', function (PageVersionStatus $status, bool $expected) {
    $editor = User::factory()->create();
    createWebsiteMembership($this->website, $editor, WebsiteRole::Editor);
    $version = PageVersion::factory()->create([
        'page_id' => $this->page->id,
        'layout_id' => $this->layout->id,
        'status' => $status,
    ]);

    expect($this->policy->submitForReview($editor, $version))->toBe($expected);
})->with([
    'draft' => [PageVersionStatus::Draft, true],
    'rejected' => [PageVersionStatus::Rejected, true],
    'under_review' => [PageVersionStatus::UnderReview, false],
    'approved' => [PageVersionStatus::Approved, false],
    'published' => [PageVersionStatus::Published, false],
]);

it('only lets qa approve or reject a version under review', function () {
    $qa = User::factory()->create();
    createWebsiteMembership($this->website, $qa, WebsiteRole::Qa);
    $admin = User::factory()->create();
    createWebsiteMembership($this->website, $admin, WebsiteRole::Admin);

    $underReview = PageVersion::factory()->create([
        'page_id' => $this->page->id, 'layout_id' => $this->layout->id, 'status' => PageVersionStatus::UnderReview, 'version_number' => 1,
    ]);
    $draft = PageVersion::factory()->create([
        'page_id' => $this->page->id, 'layout_id' => $this->layout->id, 'status' => PageVersionStatus::Draft, 'version_number' => 2,
    ]);

    expect($this->policy->approve($qa, $underReview))->toBeTrue();
    expect($this->policy->reject($qa, $underReview))->toBeTrue();
    expect($this->policy->approve($admin, $underReview))->toBeFalse();
    expect($this->policy->reject($admin, $underReview))->toBeFalse();
    expect($this->policy->approve($qa, $draft))->toBeFalse();
    expect($this->policy->reject($qa, $draft))->toBeFalse();
});

it('only lets admin publish an approved version, never editor', function () {
    $admin = User::factory()->create();
    createWebsiteMembership($this->website, $admin, WebsiteRole::Admin);
    $editor = User::factory()->create();
    createWebsiteMembership($this->website, $editor, WebsiteRole::Editor);

    $approved = PageVersion::factory()->create([
        'page_id' => $this->page->id, 'layout_id' => $this->layout->id, 'status' => PageVersionStatus::Approved, 'version_number' => 1,
    ]);
    $underReview = PageVersion::factory()->create([
        'page_id' => $this->page->id, 'layout_id' => $this->layout->id, 'status' => PageVersionStatus::UnderReview, 'version_number' => 2,
    ]);

    expect($this->policy->publish($admin, $approved))->toBeTrue();
    expect($this->policy->publish($editor, $approved))->toBeFalse();
    expect($this->policy->publish($admin, $underReview))->toBeFalse();
});

it('never allows deleting a published version, even for the owner', function () {
    $owner = User::factory()->create();
    createWebsiteMembership($this->website, $owner, WebsiteRole::Owner);

    $published = PageVersion::factory()->published()->create(['page_id' => $this->page->id, 'layout_id' => $this->layout->id, 'version_number' => 1]);
    $draft = PageVersion::factory()->create(['page_id' => $this->page->id, 'layout_id' => $this->layout->id, 'version_number' => 2]);

    expect($this->policy->delete($owner, $published))->toBeFalse();
    expect($this->policy->delete($owner, $draft))->toBeTrue();
});

it('only lets admin delete a non-published version', function (WebsiteRole $role, bool $expected) {
    $user = User::factory()->create();
    createWebsiteMembership($this->website, $user, $role);
    $draft = PageVersion::factory()->create(['page_id' => $this->page->id, 'layout_id' => $this->layout->id]);

    expect($this->policy->delete($user, $draft))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
]);

it('only lets the owner restore or force-delete a version', function (WebsiteRole $role, bool $expected) {
    $user = User::factory()->create();
    createWebsiteMembership($this->website, $user, $role);
    $version = PageVersion::factory()->create(['page_id' => $this->page->id, 'layout_id' => $this->layout->id]);

    expect($this->policy->restore($user, $version))->toBe($expected);
    expect($this->policy->forceDelete($user, $version))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, false],
]);
