<?php

use App\Enums\ReviewDecision;
use App\Models\PageVersion;
use App\Models\PageVersionReview;
use App\Models\User;

it('resolves the page version it belongs to and the qa user who made the decision', function () {
    $version = PageVersion::factory()->create();
    $qa = User::factory()->create();
    $review = PageVersionReview::factory()->approved()->create([
        'page_version_id' => $version->id,
        'qa_user_id' => $qa->id,
    ]);

    expect($review->pageVersion->is($version))->toBeTrue();
    expect($review->qaUser->is($qa))->toBeTrue();
    expect($review->decision)->toBe(ReviewDecision::Approved);
});

it('is append-only: has no updated_at column', function () {
    $review = PageVersionReview::factory()->create();

    expect($review->getAttributes())->not->toHaveKey('updated_at');
});
