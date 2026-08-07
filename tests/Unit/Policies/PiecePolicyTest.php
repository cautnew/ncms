<?php

use App\Enums\PageVersionStatus;
use App\Enums\WebsiteRole;
use App\Models\Layout;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\User;
use App\Models\Website;
use App\Policies\PiecePolicy;

beforeEach(function () {
    $this->policy = new PiecePolicy;
    $this->website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $this->website->id]);
    $page = Page::factory()->create(['website_id' => $this->website->id, 'layout_id' => $layout->id]);
    $this->version = PageVersion::factory()->create(['page_id' => $page->id, 'layout_id' => $layout->id]);
});

it('lets any member view pieces but denies an outsider', function () {
    $piece = Piece::factory()->create(['page_version_id' => $this->version->id]);
    $member = User::factory()->create();
    createWebsiteMembership($this->website, $member, WebsiteRole::Viewer);
    $outsider = User::factory()->create();

    expect($this->policy->viewAny($member, $this->version))->toBeTrue();
    expect($this->policy->view($member, $piece))->toBeTrue();
    expect($this->policy->viewAny($outsider, $this->version))->toBeFalse();
    expect($this->policy->view($outsider, $piece))->toBeFalse();
});

it('only lets owner, admin and editor create, update or delete pieces', function (WebsiteRole $role, bool $expected) {
    $user = User::factory()->create();
    createWebsiteMembership($this->website, $user, $role);
    $piece = Piece::factory()->create(['page_version_id' => $this->version->id]);

    expect($this->policy->create($user, $this->version))->toBe($expected);
    expect($this->policy->update($user, $piece))->toBe($expected);
    expect($this->policy->delete($user, $piece))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, true],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('allows piece writes while draft/rejected/published, denies while under_review/approved/archived', function (PageVersionStatus $status, bool $expected) {
    $owner = User::factory()->create();
    createWebsiteMembership($this->website, $owner, WebsiteRole::Owner);
    $this->version->update(['status' => $status]);
    $piece = Piece::factory()->create(['page_version_id' => $this->version->id]);

    expect($this->policy->create($owner, $this->version->fresh()))->toBe($expected);
    expect($this->policy->update($owner, $piece))->toBe($expected);
    expect($this->policy->delete($owner, $piece))->toBe($expected);
})->with([
    'draft' => [PageVersionStatus::Draft, true],
    'rejected' => [PageVersionStatus::Rejected, true],
    'published' => [PageVersionStatus::Published, true],
    'under_review' => [PageVersionStatus::UnderReview, false],
    'approved' => [PageVersionStatus::Approved, false],
    'archived' => [PageVersionStatus::Archived, false],
]);
