<?php

use App\Enums\PageVersionStatus;
use App\Enums\PieceType;
use App\Models\AuditLog;
use App\Models\Asset;
use App\Models\Layout;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\User;
use App\Models\Website;
use Illuminate\Support\Facades\Hash;

// --- User ------------------------------------------------------------------------------------

it('creates a valid user with a known default password', function () {
    $user = User::factory()->create();

    expect($user->email)->not->toBeEmpty();
    expect($user->email_verified_at)->not->toBeNull();
    expect(Hash::check('password', $user->password))->toBeTrue();
});

it('creates an unverified user', function () {
    $user = User::factory()->unverified()->create();

    expect($user->email_verified_at)->toBeNull();
});

// --- Website ---------------------------------------------------------------------------------

it('creates a valid website with a unique domain', function () {
    $a = Website::factory()->create();
    $b = Website::factory()->create();

    expect($a->domain)->not->toBe($b->domain);
    expect($a->status)->toBe('active');
    expect($a->host)->toBe($a->domain);
});

it('creates a suspended and an archived website', function () {
    expect(Website::factory()->suspended()->create()->status)->toBe('suspended');
    expect(Website::factory()->archived()->create()->status)->toBe('archived');
});

it('creates a website with a subdomain reflected in its host', function () {
    $website = Website::factory()->withSubdomain('blog')->create();

    expect($website->subdomain)->toBe('blog');
    expect($website->host)->toBe("blog.{$website->domain}");
});

// --- Layout ----------------------------------------------------------------------------------

it('creates a layout with a non-null, realistic default schema, scoped to its website', function () {
    $layout = Layout::factory()->create();

    expect($layout->website_id)->not->toBeNull();
    expect(Website::query()->whereKey($layout->website_id)->exists())->toBeTrue();
    expect($layout->schema)->toBe(['regions' => ['header', 'main', 'footer']]);
    expect($layout->status)->toBe('active');
    expect($layout->is_default)->toBeFalse();
});

it('creates a default, a draft and an archived layout', function () {
    expect(Layout::factory()->default()->create()->is_default)->toBeTrue();
    expect(Layout::factory()->draft()->create()->status)->toBe('draft');
    expect(Layout::factory()->archived()->create()->status)->toBe('archived');
});

// --- Asset -----------------------------------------------------------------------------------

it('creates an asset owned by a layout on the same website by default', function () {
    $asset = Asset::factory()->create();

    expect($asset->layout_id)->not->toBeNull();
    expect($asset->page_version_id)->toBeNull();
    expect(Layout::query()->whereKey($asset->layout_id)->where('website_id', $asset->website_id)->exists())->toBeTrue();
});

it('creates an asset scoped to a specific page version, mutually exclusive with layout', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);
    $version = PageVersion::factory()->create(['page_id' => $page->id, 'layout_id' => $layout->id]);

    $asset = Asset::factory()->forPageVersion($version)->create();

    expect($asset->page_version_id)->toBe($version->id);
    expect($asset->layout_id)->toBeNull();
    expect($asset->website_id)->toBe($website->id);
});

it('creates an asset scoped to a specific existing layout', function () {
    $layout = Layout::factory()->create();

    $asset = Asset::factory()->forLayout($layout)->create();

    expect($asset->layout_id)->toBe($layout->id);
    expect($asset->page_version_id)->toBeNull();
    expect($asset->website_id)->toBe($layout->website_id);
});

it('creates svg, document and video asset variants with consistent mime types', function () {
    $svg = Asset::factory()->svg()->create();
    expect($svg->mime_type)->toBe('image/svg+xml');
    expect($svg->width)->toBeNull();

    $document = Asset::factory()->document()->create();
    expect($document->mime_type)->toBe('application/pdf');
    expect($document->is_image)->toBeFalse();

    $video = Asset::factory()->video()->create();
    expect($video->mime_type)->toBe('video/mp4');
    expect($video->width)->toBe(1920);
});

// --- Page ------------------------------------------------------------------------------------

it('creates a page whose layout belongs to the same website', function () {
    $page = Page::factory()->create();

    expect(Layout::query()->whereKey($page->layout_id)->where('website_id', $page->website_id)->exists())->toBeTrue();
    expect($page->status)->toBe('active');
    expect($page->published_version_id)->toBeNull();
});

it('creates an archived page', function () {
    expect(Page::factory()->archived()->create()->status)->toBe('archived');
});

// --- PageVersion -------------------------------------------------------------------------------

it('creates a page version whose layout_snapshot mirrors its layout at creation time', function () {
    $version = PageVersion::factory()->create();

    $layout = Layout::query()->findOrFail($version->layout_id);
    expect($version->layout_snapshot)->toBe($layout->only(['name', 'slug', 'description', 'schema']));
    expect(Page::query()->whereKey($version->page_id)->where('website_id', $layout->website_id)->exists())->toBeTrue();
    expect($version->status)->toBe(PageVersionStatus::Draft);
    expect($version->cloned_from_id)->toBeNull();
});

it('creates versions in every workflow status with consistent qa/publish fields', function () {
    expect(PageVersion::factory()->underReview()->create()->status)->toBe(PageVersionStatus::UnderReview);

    $approved = PageVersion::factory()->approved()->create();
    expect($approved->status)->toBe(PageVersionStatus::Approved);
    expect($approved->qa_user_id)->not->toBeNull();
    expect($approved->qa_reviewed_at)->not->toBeNull();

    $rejected = PageVersion::factory()->rejected()->create();
    expect($rejected->status)->toBe(PageVersionStatus::Rejected);
    expect($rejected->qa_notes)->not->toBeNull();

    $published = PageVersion::factory()->published()->create();
    expect($published->status)->toBe(PageVersionStatus::Published);
    expect($published->published_at)->not->toBeNull();
    expect($published->published_by)->not->toBeNull();
});

it('creates a clone that mirrors its published source', function () {
    $source = PageVersion::factory()->published()->create(['version_number' => 1]);

    $clone = PageVersion::factory()->clonedFrom($source)->create(['version_number' => 2]);

    expect($clone->cloned_from_id)->toBe($source->id);
    expect($clone->page_id)->toBe($source->page_id);
    expect($clone->layout_id)->toBe($source->layout_id);
    expect($clone->layout_snapshot)->toBe($source->layout_snapshot);
    expect($clone->seo_snapshot)->toBe($source->seo_snapshot);
    expect($clone->status)->toBe(PageVersionStatus::Draft);
});

// --- Piece -----------------------------------------------------------------------------------

it('creates a paragraph piece by default, scoped to a real page version', function () {
    $piece = Piece::factory()->create();

    expect($piece->type)->toBe(PieceType::Paragraph);
    expect(PageVersion::query()->whereKey($piece->page_version_id)->exists())->toBeTrue();
    expect($piece->parent_piece_id)->toBeNull();
    expect($piece->is_root)->toBeTrue();
});

it('creates every piece type with type-appropriate content', function () {
    $heading = Piece::factory()->heading(3)->create();
    expect($heading->type)->toBe(PieceType::Heading);
    expect($heading->content['level'])->toBe(3);

    expect(Piece::factory()->list()->create()->content['items'])->toBeArray();
    expect(Piece::factory()->twoColumnsWrapper()->create()->type)->toBe(PieceType::TwoColumnsWrapper);
    expect(Piece::factory()->textImageWrapper()->create()->type)->toBe(PieceType::TextImageWrapper);
});

it('creates an image piece referencing a given asset', function () {
    $asset = Asset::factory()->create();

    $piece = Piece::factory()->image($asset)->create(['page_version_id' => PageVersion::factory()->create()->id]);

    expect($piece->type)->toBe(PieceType::Image);
    expect($piece->asset_id)->toBe($asset->id);
});

it('nests a piece under a parent, inheriting its page_version_id', function () {
    $version = PageVersion::factory()->create();
    $parent = Piece::factory()->twoColumnsWrapper()->create(['page_version_id' => $version->id]);

    $child = Piece::factory()->paragraph()->childOf($parent, 'left')->create();

    expect($child->page_version_id)->toBe($version->id);
    expect($child->parent_piece_id)->toBe($parent->id);
    expect($child->slot)->toBe('left');
    expect($child->is_root)->toBeFalse();
});

// --- AuditLog --------------------------------------------------------------------------------

it('creates an audit log entry whose auditable relationship actually resolves', function () {
    $log = AuditLog::factory()->create();

    expect($log->auditable_type)->toBe(Website::class);
    expect(Website::query()->whereKey($log->auditable_id)->exists())->toBeTrue();
    expect($log->auditable_id)->toBe($log->website_id);
});

it('scopes an audit log entry to an arbitrary given model via forModel()', function () {
    $page = Page::factory()->create();

    $log = AuditLog::factory()->forModel($page)->create();

    expect($log->auditable_type)->toBe(Page::class);
    expect($log->auditable_id)->toBe($page->id);
    expect($log->website_id)->toBe($page->website_id);
});

it('creates login and logout entries with no website scope', function () {
    $login = AuditLog::factory()->login()->create();
    expect($login->action)->toBe('login');
    expect($login->website_id)->toBeNull();
    expect($login->auditable_type)->toBe(User::class);
    expect($login->auditable_id)->toBe($login->user_id);

    $logout = AuditLog::factory()->logout()->create();
    expect($logout->action)->toBe('logout');
    expect($logout->website_id)->toBeNull();
});

it('creates an update entry carrying a before/after diff', function () {
    $log = AuditLog::factory()->withChanges(['name' => 'Before'], ['name' => 'After'])->create();

    expect($log->action)->toBe('update');
    expect($log->old_values)->toBe(['name' => 'Before']);
    expect($log->new_values)->toBe(['name' => 'After']);
});

it('creates a system-performed entry with no user', function () {
    $log = AuditLog::factory()->system()->create();

    expect($log->user_id)->toBeNull();
});
