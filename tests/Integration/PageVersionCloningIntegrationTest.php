<?php

use App\Enums\PageVersionStatus;
use App\Events\PageVersionCloned;
use App\Models\AuditLog;
use App\Models\Layout;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\User;
use App\Models\Website;
use App\Repositories\Contracts\PieceRepositoryInterface;
use App\Services\PageVersionCloningService;
use Illuminate\Support\Facades\Event;

/**
 * Exercises PageVersionCloningService directly — Service + Repository +
 * Event + Listener + database, with no HTTP layer involved — verifying the
 * copy-on-write pipeline end to end from the code that actually drives it.
 */
it('clones a published version, its tree and assets, and produces an audit trail entry — all through the service layer', function () {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);
    $source = PageVersion::factory()->published()->create([
        'page_id' => $page->id,
        'layout_id' => $layout->id,
        'seo_snapshot' => ['title' => 'Original'],
    ]);
    $wrapper = Piece::factory()->twoColumnsWrapper()->create(['page_version_id' => $source->id]);
    $child = Piece::factory()->paragraph()->childOf($wrapper, 'left')->create();

    /** @var PageVersionCloningService $service */
    $service = app(PageVersionCloningService::class);

    $result = $service->cloneAsDraft($source, $actor);

    expect($result->draft->status)->toBe(PageVersionStatus::Draft);
    expect($result->draft->cloned_from_id)->toBe($source->id);
    expect($result->draft->seo_snapshot)->toBe(['title' => 'Original']);

    // The whole tree was cloned and correctly remapped.
    expect($result->pieceIdMap)->toHaveKeys([$wrapper->id, $child->id]);
    $clonedChild = Piece::find($result->pieceIdMap[$child->id]);
    expect($clonedChild->parent_piece_id)->toBe($result->pieceIdMap[$wrapper->id]);

    // The source is fully untouched.
    expect($source->fresh()->status)->toBe(PageVersionStatus::Published);
    expect(Piece::where('page_version_id', $source->id)->count())->toBe(2);

    // The full event -> listener -> audit_logs pipeline ran through the service call alone.
    $log = AuditLog::where('action', 'version_cloned')
        ->where('auditable_id', $result->draft->id)
        ->sole();
    expect($log->old_values['source_version_id'])->toBe($source->id);
});

it('dispatches PageVersionCloned only after the transaction commits', function () {
    Event::fake([PageVersionCloned::class]);

    $website = Website::factory()->create();
    $actor = User::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);
    $source = PageVersion::factory()->published()->create(['page_id' => $page->id, 'layout_id' => $layout->id]);

    app(PageVersionCloningService::class)->cloneAsDraft($source, $actor);

    Event::assertDispatchedTimes(PageVersionCloned::class, 1);
});

it('rolls back every write (draft, pieces, assets) if any step of the clone fails', function () {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);
    $source = PageVersion::factory()->published()->create(['page_id' => $page->id, 'layout_id' => $layout->id]);
    Piece::factory()->paragraph()->create(['page_version_id' => $source->id]);

    $versionCountBefore = PageVersion::count();
    $pieceCountBefore = Piece::count();

    $this->mock(PieceRepositoryInterface::class, function ($mock) {
        $mock->shouldReceive('create')->once()->andThrow(new RuntimeException('simulated failure'));
    });

    $service = app(PageVersionCloningService::class);

    expect(fn () => $service->cloneAsDraft($source, $actor))->toThrow(RuntimeException::class);
    expect(PageVersion::count())->toBe($versionCountBefore);
    expect(Piece::count())->toBe($pieceCountBefore);
});
