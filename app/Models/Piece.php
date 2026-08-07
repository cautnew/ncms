<?php

namespace App\Models;

use App\Enums\PieceType;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use Database\Factories\PieceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['page_version_id', 'parent_piece_id', 'asset_id', 'type', 'slot', 'position', 'content', 'settings', 'created_by', 'deleted_by'])]
class Piece extends Model
{
    /** @use HasFactory<PieceFactory> */
    use Auditable, HasFactory, HasUserstamps, HasUuids, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'type' => PieceType::class,
            'content' => 'array',
            'settings' => 'array',
        ];
    }

    /**
     * The page version this piece belongs to.
     *
     * @return BelongsTo<PageVersion, $this>
     */
    public function pageVersion(): BelongsTo
    {
        return $this->belongsTo(PageVersion::class);
    }

    /**
     * The parent piece, when this piece is nested inside a wrapper.
     *
     * @return BelongsTo<Piece, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Piece::class, 'parent_piece_id');
    }

    /**
     * Direct child pieces, ordered for rendering.
     *
     * @return HasMany<Piece, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Piece::class, 'parent_piece_id')->orderBy('position');
    }

    /**
     * The asset referenced by this piece (e.g. an image piece).
     *
     * @return BelongsTo<Asset, $this>
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Whether this piece sits at the root of the version's tree.
     *
     * @return Attribute<bool, never>
     */
    protected function isRoot(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => $this->parent_piece_id === null,
        );
    }

    /**
     * Scope a query to only include root-level pieces (no parent).
     *
     * @param  Builder<Piece>  $query
     * @return Builder<Piece>
     */
    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_piece_id');
    }

    /**
     * Scope a query to pieces of a given type.
     *
     * @param  Builder<Piece>  $query
     * @return Builder<Piece>
     */
    public function scopeOfType(Builder $query, PieceType $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query ordered by sibling position.
     *
     * @param  Builder<Piece>  $query
     * @return Builder<Piece>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position');
    }

    /**
     * A piece has no website_id of its own — reached via its page_version.
     */
    public function auditWebsiteId(): ?string
    {
        return $this->pageVersion?->page?->website_id;
    }

    /**
     * The user who created this piece.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The user who last updated this piece.
     *
     * @return BelongsTo<User, $this>
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * The user who deleted this piece, if it has been deleted.
     *
     * @return BelongsTo<User, $this>
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
