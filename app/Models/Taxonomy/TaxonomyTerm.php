<?php

namespace App\Models\Taxonomy;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TaxonomyTerm extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'taxonomy_terms';

    protected $fillable = ['taxonomy_id', 'parent_id', 'slug', 'rank'];

    // ──────────────────────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────────────────────

    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(TaxonomyTerm::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(TaxonomyTerm::class, 'parent_id')->orderBy('rank');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TaxonomyTermTranslation::class);
    }

    public function auditLogs(): MorphMany
    {
        return $this->morphMany(TaxonomyAuditLog::class, 'auditable');
    }

    // ──────────────────────────────────────────────────────────────
    // Translation helpers
    // ──────────────────────────────────────────────────────────────

    public function name(string $locale = 'pt'): string
    {
        $translation = $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'pt')
            ?? $this->translations->first();

        return $translation?->name ?? $this->slug;
    }

    public function description(string $locale = 'pt'): ?string
    {
        $translation = $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'pt')
            ?? $this->translations->first();

        return $translation?->description;
    }

    public function translationsArray(): array
    {
        return $this->translations->keyBy('locale')->map(function ($t) {
            return ['name' => $t->name, 'description' => $t->description];
        })->toArray();
    }
}
