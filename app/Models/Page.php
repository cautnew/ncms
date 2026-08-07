<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['website_id', 'layout_id', 'published_version_id', 'slug', 'status', 'created_by', 'deleted_by'])]
class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use Auditable, HasFactory, HasUserstamps, HasUuids, SoftDeletes;

    /**
     * The website this page belongs to.
     *
     * @return BelongsTo<Website, $this>
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * The layout this page uses.
     *
     * @return BelongsTo<Layout, $this>
     */
    public function layout(): BelongsTo
    {
        return $this->belongsTo(Layout::class);
    }

    /**
     * All versions (draft, published, archived, ...) of this page.
     *
     * @return HasMany<PageVersion, $this>
     */
    public function versions(): HasMany
    {
        return $this->hasMany(PageVersion::class);
    }

    /**
     * The currently live, immutable published version.
     *
     * @return BelongsTo<PageVersion, $this>
     */
    public function publishedVersion(): BelongsTo
    {
        return $this->belongsTo(PageVersion::class, 'published_version_id');
    }

    /**
     * The most recent draft version being worked on, if any.
     *
     * @return HasOne<PageVersion, $this>
     */
    public function draftVersion(): HasOne
    {
        return $this->hasOne(PageVersion::class)
            ->ofMany(['version_number' => 'max'], function (Builder $query) {
                $query->where('status', 'draft');
            });
    }

    /**
     * The most recent version of this page, regardless of status.
     *
     * @return HasOne<PageVersion, $this>
     */
    public function latestVersion(): HasOne
    {
        return $this->hasOne(PageVersion::class)->ofMany('version_number', 'max');
    }

    /**
     * Whether this page currently has a published version.
     *
     * @return Attribute<bool, never>
     */
    protected function isPublished(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => $this->published_version_id !== null,
        );
    }

    /**
     * Scope a query to only include active pages.
     *
     * @param  Builder<Page>  $query
     * @return Builder<Page>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include pages that have a published version.
     *
     * @param  Builder<Page>  $query
     * @return Builder<Page>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_version_id');
    }

    /**
     * The user who created this page.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The user who last updated this page.
     *
     * @return BelongsTo<User, $this>
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * The user who deleted this page, if it has been deleted.
     *
     * @return BelongsTo<User, $this>
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
