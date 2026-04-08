<?php

namespace App\Models\Taxonomy;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Taxonomy extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'taxonomies';

    protected $fillable = ['slug'];

    // ──────────────────────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────────────────────

    public function translations(): HasMany
    {
        return $this->hasMany(TaxonomyTranslation::class);
    }

    public function terms(): HasMany
    {
        return $this->hasMany(TaxonomyTerm::class)->orderBy('rank');
    }

    public function rootTerms(): HasMany
    {
        return $this->hasMany(TaxonomyTerm::class)->whereNull('parent_id')->orderBy('rank');
    }

    public function auditLogs(): MorphMany
    {
        return $this->morphMany(TaxonomyAuditLog::class, 'auditable');
    }

    // ──────────────────────────────────────────────────────────────
    // Translation helpers
    // ──────────────────────────────────────────────────────────────

    /**
     * Get translated name for a given locale (fallback to current language).
     */
    public function name(?string $locale = null): string
    {
        $translation = $this->translations->firstWhere('locale', $locale ?? SettingsService::currentLanguage());

        return $translation?->name ?? $this->slug;
    }

    /**
     * Get translated description for a given locale.
     */
    public function description(?string $locale = null): ?string
    {
        $translation = $this->translations->firstWhere('locale', $locale ?? SettingsService::currentLanguage());

        return $translation?->description;
    }

    /**
     * Return translations as a keyed array: ['pt' => [...], 'en' => [...]]
     */
    public function translationsArray(): array
    {
        return $this->translations->keyBy('locale')->map(function ($t) {
            return ['name' => $t->name, 'description' => $t->description];
        })->toArray();
    }
}
