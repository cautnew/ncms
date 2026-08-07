<?php

use App\Enums\PageVersionStatus;

it('labels every status', function (PageVersionStatus $status, string $label) {
    expect($status->label())->toBe($label);
})->with([
    'draft' => [PageVersionStatus::Draft, 'Draft'],
    'under_review' => [PageVersionStatus::UnderReview, 'Under Review'],
    'approved' => [PageVersionStatus::Approved, 'Approved'],
    'rejected' => [PageVersionStatus::Rejected, 'Rejected'],
    'published' => [PageVersionStatus::Published, 'Published'],
    'archived' => [PageVersionStatus::Archived, 'Archived'],
]);

it('defines the exact allowed transitions for every status', function () {
    expect(PageVersionStatus::Draft->allowedTransitions())->toBe([PageVersionStatus::UnderReview]);
    expect(PageVersionStatus::UnderReview->allowedTransitions())->toBe([PageVersionStatus::Approved, PageVersionStatus::Rejected]);
    expect(PageVersionStatus::Rejected->allowedTransitions())->toBe([PageVersionStatus::UnderReview]);
    expect(PageVersionStatus::Approved->allowedTransitions())->toBe([PageVersionStatus::Published]);
    expect(PageVersionStatus::Published->allowedTransitions())->toBe([]);
    expect(PageVersionStatus::Archived->allowedTransitions())->toBe([]);
});

it('reports canTransitionTo() consistently with allowedTransitions()', function (PageVersionStatus $status, PageVersionStatus $target, bool $expected) {
    expect($status->canTransitionTo($target))->toBe($expected);
})->with([
    'draft -> under_review' => [PageVersionStatus::Draft, PageVersionStatus::UnderReview, true],
    'draft -> published' => [PageVersionStatus::Draft, PageVersionStatus::Published, false],
    'under_review -> approved' => [PageVersionStatus::UnderReview, PageVersionStatus::Approved, true],
    'under_review -> rejected' => [PageVersionStatus::UnderReview, PageVersionStatus::Rejected, true],
    'under_review -> published' => [PageVersionStatus::UnderReview, PageVersionStatus::Published, false],
    'rejected -> under_review' => [PageVersionStatus::Rejected, PageVersionStatus::UnderReview, true],
    'approved -> published' => [PageVersionStatus::Approved, PageVersionStatus::Published, true],
    'approved -> approved' => [PageVersionStatus::Approved, PageVersionStatus::Approved, false],
    'published -> anything' => [PageVersionStatus::Published, PageVersionStatus::Draft, false],
    'archived -> anything' => [PageVersionStatus::Archived, PageVersionStatus::Draft, false],
]);

it('is editable only in draft or rejected', function (PageVersionStatus $status, bool $expected) {
    expect($status->isEditable())->toBe($expected);
})->with([
    'draft' => [PageVersionStatus::Draft, true],
    'rejected' => [PageVersionStatus::Rejected, true],
    'under_review' => [PageVersionStatus::UnderReview, false],
    'approved' => [PageVersionStatus::Approved, false],
    'published' => [PageVersionStatus::Published, false],
    'archived' => [PageVersionStatus::Archived, false],
]);

it('is published only for the Published case', function (PageVersionStatus $status, bool $expected) {
    expect($status->isPublished())->toBe($expected);
})->with([
    'published' => [PageVersionStatus::Published, true],
    'draft' => [PageVersionStatus::Draft, false],
    'archived' => [PageVersionStatus::Archived, false],
]);

it('is writable for draft, rejected and published, and only those', function (PageVersionStatus $status, bool $expected) {
    expect($status->isWritable())->toBe($expected);
})->with([
    'draft' => [PageVersionStatus::Draft, true],
    'rejected' => [PageVersionStatus::Rejected, true],
    'published' => [PageVersionStatus::Published, true],
    'under_review' => [PageVersionStatus::UnderReview, false],
    'approved' => [PageVersionStatus::Approved, false],
    'archived' => [PageVersionStatus::Archived, false],
]);

it('is backed by the expected string values', function () {
    expect(PageVersionStatus::Draft->value)->toBe('draft');
    expect(PageVersionStatus::UnderReview->value)->toBe('under_review');
    expect(PageVersionStatus::Approved->value)->toBe('approved');
    expect(PageVersionStatus::Rejected->value)->toBe('rejected');
    expect(PageVersionStatus::Published->value)->toBe('published');
    expect(PageVersionStatus::Archived->value)->toBe('archived');
});
