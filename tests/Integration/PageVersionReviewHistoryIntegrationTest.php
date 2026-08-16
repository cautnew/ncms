<?php

use App\Enums\PageVersionStatus;
use App\Enums\ReviewDecision;
use App\Models\Layout;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\User;
use App\Models\Website;
use App\Services\PageVersionService;

/**
 * Exercises PageVersionService::approve()/reject() directly — Service +
 * Repository + database, no HTTP — proving QA decisions are appended to
 * page_version_reviews rather than overwriting a single set of columns, so
 * a version rejected and later approved keeps both decisions in its history.
 */
it('preserves every QA decision across a reject-then-approve cycle, instead of overwriting the last one', function () {
    $website = Website::factory()->create();
    $page = Page::factory()->create(['website_id' => $website->id]);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $version = PageVersion::factory()->underReview()->create(['page_id' => $page->id, 'layout_id' => $layout->id]);
    $qa = User::factory()->create();
    $this->actingAs($qa);

    /** @var PageVersionService $service */
    $service = app(PageVersionService::class);

    $version = $service->reject($version, $qa, 'Fix the broken links.');
    expect($version->status)->toBe(PageVersionStatus::Rejected);

    $version = $service->submitForReview($version);
    $version = $service->approve($version, $qa, 'Looks good now.');
    expect($version->status)->toBe(PageVersionStatus::Approved);

    $reviews = $version->reviews()->orderBy('created_at')->get();

    expect($reviews)->toHaveCount(2);
    expect($reviews[0]->decision)->toBe(ReviewDecision::Rejected);
    expect($reviews[0]->notes)->toBe('Fix the broken links.');
    expect($reviews[1]->decision)->toBe(ReviewDecision::Approved);
    expect($reviews[1]->notes)->toBe('Looks good now.');
    expect($reviews[0]->qa_user_id)->toBe($qa->id);
    expect($reviews[1]->qa_user_id)->toBe($qa->id);
});
