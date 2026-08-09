<?php

namespace App\Models;

use App\Enums\ReviewDecision;
use Database\Factories\PageVersionReviewFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['page_version_id', 'decision', 'qa_user_id', 'notes'])]
class PageVersionReview extends Model
{
    /** @use HasFactory<PageVersionReviewFactory> */
    use HasFactory, HasUuids;

    /**
     * Append-only log: there is no updated_at column to maintain.
     */
    const UPDATED_AT = null;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'decision' => ReviewDecision::class,
        ];
    }

    /**
     * The page version this review was made against.
     *
     * @return BelongsTo<PageVersion, $this>
     */
    public function pageVersion(): BelongsTo
    {
        return $this->belongsTo(PageVersion::class);
    }

    /**
     * The QA user who made this review decision.
     *
     * @return BelongsTo<User, $this>
     */
    public function qaUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qa_user_id');
    }
}
