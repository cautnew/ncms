<?php

namespace App\Models;

use App\Enums\PageVersionStatus;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use Database\Factories\PageVersionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'page_id', 'layout_id', 'cloned_from_id', 'version_number', 'status', 'layout_snapshot', 'seo_snapshot',
    'created_by', 'published_at', 'published_by',
    'deleted_by',
])]
class PageVersion extends Model
{
    /** @use HasFactory<PageVersionFactory> */
    use Auditable, HasFactory, HasUserstamps, HasUuids, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'version_number' => 'integer',
            'status' => PageVersionStatus::class,
            'layout_snapshot' => 'array',
            'seo_snapshot' => 'array',
            'published_at' => 'datetime',
        ];
    }

    /**
     * The page this version belongs to.
     *
     * @return BelongsTo<Page, $this>
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * The layout used by this version.
     *
     * @return BelongsTo<Layout, $this>
     */
    public function layout(): BelongsTo
    {
        return $this->belongsTo(Layout::class);
    }

    /**
     * The user who created this version.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The published version this draft was automatically cloned from, if any.
     * See PageVersionCloningService — published versions are never edited in
     * place, so any write against one produces a new draft linked back here.
     *
     * @return BelongsTo<PageVersion, $this>
     */
    public function clonedFrom(): BelongsTo
    {
        return $this->belongsTo(PageVersion::class, 'cloned_from_id');
    }

    /**
     * The full QA review history for this version (one row per
     * approve/reject decision), oldest first.
     *
     * @return HasMany<PageVersionReview, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(PageVersionReview::class)->oldest();
    }

    /**
     * The user who published this version.
     *
     * @return BelongsTo<User, $this>
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * The user who last updated this version.
     *
     * @return BelongsTo<User, $this>
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * The user who deleted this version, if it has been deleted.
     *
     * @return BelongsTo<User, $this>
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * All pieces belonging to this version (full tree, unordered by level).
     *
     * @return HasMany<Piece, $this>
     */
    public function pieces(): HasMany
    {
        return $this->hasMany(Piece::class);
    }

    /**
     * Root-level pieces of this version, ordered for rendering.
     *
     * @return HasMany<Piece, $this>
     */
    public function rootPieces(): HasMany
    {
        return $this->hasMany(Piece::class)
            ->whereNull('parent_piece_id')
            ->orderBy('position');
    }

    /**
     * Assets owned directly by this version.
     *
     * @return HasMany<Asset, $this>
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Whether this version is the currently published one.
     *
     * @return Attribute<bool, never>
     */
    protected function isPublished(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => $this->status->isPublished(),
        );
    }

    /**
     * Whether this version can still be edited (i.e. is not immutable).
     *
     * @return Attribute<bool, never>
     */
    protected function isEditable(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => $this->status->isEditable(),
        );
    }

    /**
     * Scope a query to only include draft versions.
     *
     * @param  Builder<PageVersion>  $query
     * @return Builder<PageVersion>
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', PageVersionStatus::Draft);
    }

    /**
     * Scope a query to only include published versions.
     *
     * @param  Builder<PageVersion>  $query
     * @return Builder<PageVersion>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PageVersionStatus::Published);
    }

    /**
     * Scope a query to versions with a given status.
     *
     * @param  Builder<PageVersion>  $query
     * @return Builder<PageVersion>
     */
    public function scopeStatus(Builder $query, PageVersionStatus $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * A page version has no website_id of its own — reached via its page.
     */
    public function auditWebsiteId(): ?string
    {
        return $this->page?->website_id;
    }

    /**
     * A status transition into approved/rejected/published has a more
     * specific name than plain "update" — everything else (submitted for
     * review, seo/layout edits, ...) stays generic.
     *
     * Note: Model::getChanges() reports the raw (pre-cast) attribute value,
     * not the PageVersionStatus enum instance — compare against the enum's
     * backing string values, not the cases themselves.
     *
     * @param  array<string, mixed>  $changes
     */
    public function auditActionForUpdate(array $changes): string
    {
        if (! array_key_exists('status', $changes)) {
            return 'update';
        }

        return match ($changes['status']) {
            PageVersionStatus::Approved->value => 'approve',
            PageVersionStatus::Rejected->value => 'reject',
            PageVersionStatus::Published->value => 'publish',
            default => 'update',
        };
    }
}
