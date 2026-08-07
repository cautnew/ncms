<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Database\Factories\LayoutFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['website_id', 'name', 'slug', 'description', 'schema', 'status', 'is_default'])]
class Layout extends Model
{
    /** @use HasFactory<LayoutFactory> */
    use Auditable, HasFactory, HasUuids, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'schema' => 'array',
            'is_default' => 'boolean',
        ];
    }

    /**
     * The website this layout belongs to.
     *
     * @return BelongsTo<Website, $this>
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Page versions using this layout.
     *
     * @return HasMany<PageVersion, $this>
     */
    public function pageVersions(): HasMany
    {
        return $this->hasMany(PageVersion::class);
    }

    /**
     * Assets owned directly by this layout.
     *
     * @return HasMany<Asset, $this>
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Scope a query to only include the default layout of a website.
     *
     * @param  Builder<Layout>  $query
     * @return Builder<Layout>
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope a query to layouts belonging to a given website.
     *
     * @param  Builder<Layout>  $query
     * @return Builder<Layout>
     */
    public function scopeForWebsite(Builder $query, string $websiteId): Builder
    {
        return $query->where('website_id', $websiteId);
    }

    /**
     * Scope a query to layouts with a given status.
     *
     * @param  Builder<Layout>  $query
     * @return Builder<Layout>
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include active layouts.
     *
     * @param  Builder<Layout>  $query
     * @return Builder<Layout>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}
