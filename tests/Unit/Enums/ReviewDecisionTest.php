<?php

use App\Enums\ReviewDecision;

it('labels every decision', function (ReviewDecision $decision, string $label) {
    expect($decision->label())->toBe($label);
})->with([
    'approved' => [ReviewDecision::Approved, 'Approved'],
    'rejected' => [ReviewDecision::Rejected, 'Rejected'],
]);
