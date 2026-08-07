<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use Database\Factories\AssetFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'website_id', 'layout_id', 'page_version_id', 'disk', 'path', 'filename',
    'mime_type', 'size', 'width', 'height', 'alt_text', 'metadata', 'uploaded_by',
    'deleted_by',
])]
class Asset extends Model
{
    /** @use HasFactory<AssetFactory> */
    use Auditable, HasFactory, HasUserstamps, HasUuids, SoftDeletes;

    /**
     * Assets already track their creator via the domain-specific
     * "uploaded_by" column — reuse it instead of adding a redundant
     * generic created_by that would always hold the same value.
     */
    public function getCreatedByColumn(): string
    {
        return 'uploaded_by';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'metadata' => 'array',
        ];
    }

    /**
     * The website this asset is scoped to.
     *
     * @return BelongsTo<Website, $this>
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * The layout that owns this asset, when applicable.
     *
     * @return BelongsTo<Layout, $this>
     */
    public function layout(): BelongsTo
    {
        return $this->belongsTo(Layout::class);
    }

    /**
     * The page version that owns this asset, when applicable.
     *
     * @return BelongsTo<PageVersion, $this>
     */
    public function pageVersion(): BelongsTo
    {
        return $this->belongsTo(PageVersion::class);
    }

    /**
     * The user who uploaded this asset.
     *
     * @return BelongsTo<User, $this>
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * The user who last updated this asset.
     *
     * @return BelongsTo<User, $this>
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * The user who deleted this asset, if it has been deleted.
     *
     * @return BelongsTo<User, $this>
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Pieces referencing this asset (e.g. image pieces).
     *
     * @return HasMany<Piece, $this>
     */
    public function pieces(): HasMany
    {
        return $this->hasMany(Piece::class);
    }

    /**
     * The publicly accessible URL for this asset, resolved from its disk.
     *
     * @return Attribute<string, never>
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn (): string => Storage::disk($this->disk)->url($this->path),
        );
    }

    /**
     * Whether this asset is an image, based on its MIME type.
     *
     * @return Attribute<bool, never>
     */
    protected function isImage(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => Str::startsWith($this->mime_type, 'image/'),
        );
    }

    /**
     * Scope a query to only include image assets.
     *
     * @param  Builder<Asset>  $query
     * @return Builder<Asset>
     */
    public function scopeImages(Builder $query): Builder
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    /**
     * Scope a query to assets belonging to a given website.
     *
     * @param  Builder<Asset>  $query
     * @return Builder<Asset>
     */
    public function scopeForWebsite(Builder $query, string $websiteId): Builder
    {
        return $query->where('website_id', $websiteId);
    }

    /**
     * An asset's creation is domain-specifically an "upload", not a bare "create".
     */
    public function auditActionForCreate(): string
    {
        return 'upload';
    }
}
