<?php

namespace App\Enums;

enum PageVersionStatus: string
{
    case Draft = 'draft';
    case UnderReview = 'under_review';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Published = 'published';
    case Archived = 'archived';

    /**
     * Human-readable label for UI display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::UnderReview => 'Under Review',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Published => 'Published',
            self::Archived => 'Archived',
        };
    }

    /**
     * The editorial workflow's state machine: which statuses this one may move to.
     * draft/rejected --submit-review--> under_review --approve--> approved --publish--> published
     *                                                --reject---> rejected
     * "archived" is never reached through the workflow endpoints — it is assigned
     * automatically to a page's previously published version when a new one is published.
     *
     * @return array<int, self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Draft => [self::UnderReview],
            self::UnderReview => [self::Approved, self::Rejected],
            self::Rejected => [self::UnderReview],
            self::Approved => [self::Published],
            self::Published, self::Archived => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }

    /**
     * Whether a version in this status can still be edited.
     * Published (and archived) versions are immutable.
     */
    public function isEditable(): bool
    {
        return in_array($this, [self::Draft, self::Rejected], true);
    }

    public function isPublished(): bool
    {
        return $this === self::Published;
    }

    /**
     * Whether content under this version can be written to at all: either
     * directly (draft/rejected) or via an automatic clone-on-write (published,
     * which is never edited in place — see PageVersionCloningService).
     * under_review/approved/archived remain fully locked either way.
     */
    public function isWritable(): bool
    {
        return $this->isEditable() || $this->isPublished();
    }
}
