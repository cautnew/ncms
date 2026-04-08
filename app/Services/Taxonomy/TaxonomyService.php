<?php

namespace App\Services\Taxonomy;

use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyAuditLog;
use App\Models\Taxonomy\TaxonomyTranslation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TaxonomyService
{
    /**
     * List all active taxonomies (with their translations loaded).
     */
    public function all(): Collection
    {
        return Taxonomy::with('translations')->latest()->get();
    }

    /**
     * Find  a taxonomy by slug. Returns null if not found.
     */
    public function find(string $slug): ?Taxonomy
    {
        return Taxonomy::with(['translations', 'terms.translations'])->where('slug', $slug)->first();
    }

    /**
     * Find or fail — throws ModelNotFoundException.
     */
    public function findOrFail(string $slug): Taxonomy
    {
        return Taxonomy::with(['translations', 'terms.translations'])->where('slug', $slug)->firstOrFail();
    }

    /**
     * Create a new taxonomy.
     *
     * @param  array  $data         ['slug' => '...'] (auto-generated from first translation if omitted)
     * @param  array  $translations ['pt' => ['name' => '...', 'description' => '...'], 'en' => [...]]
     * @param  string $primaryLocale Locale preferido para slug (fallback: primeiro nome disponível)
     */
    public function create(array $data, array $translations, string $primaryLocale = 'pt'): Taxonomy
    {
        // Auto-generate slug from primary locale name (fallback: first non-empty name)
        if (empty($data['slug'])) {
            $name = $translations[$primaryLocale]['name'] ?? null;
            if (empty($name)) {
                foreach ($translations as $values) {
                    if (!empty($values['name'])) {
                        $name = $values['name'];
                        break;
                    }
                }
            }

            if (!empty($name)) {
                $data['slug'] = Str::slug($name);
            }
        }

        $taxonomy = Taxonomy::create(['slug' => $data['slug']]);

        $this->syncTranslations($taxonomy, $translations, isCreation: true);

        return $taxonomy->load('translations');
    }

    /**
     * Update an existing taxonomy (found by slug).
     */
    public function update(string|Taxonomy $taxonomy, array $data, array $translations): Taxonomy
    {
        if (is_string($taxonomy)) {
            $taxonomy = $this->findOrFail($taxonomy);
        }

        if (!empty($data['slug'])) {
            $taxonomy->update(['slug' => $data['slug']]);
        }

        $this->syncTranslations($taxonomy, $translations);

        return $taxonomy->fresh('translations');
    }

    /**
     * Soft-delete a taxonomy (terms cascade via DB).
     */
    public function delete(string|Taxonomy $taxonomy): bool
    {
        if (is_string($taxonomy)) {
            $taxonomy = $this->findOrFail($taxonomy);
        }

        return (bool) $taxonomy->delete();
    }

    /**
     * Restore a soft-deleted taxonomy.
     */
    public function restore(string $slug): Taxonomy
    {
        $taxonomy = Taxonomy::withTrashed()->where('slug', $slug)->firstOrFail();
        $taxonomy->restore();

        return $taxonomy->load('translations');
    }

    // ──────────────────────────────────────────────────────────────
    // Internal helpers
    // ──────────────────────────────────────────────────────────────

    protected function syncTranslations(Taxonomy $taxonomy, array $translations, bool $isCreation = false): void
    {
        $changed = false;

        foreach ($translations as $locale => $values) {
            if (empty($values['name'])) {
                continue;
            }

            TaxonomyTranslation::updateOrCreate(
                ['taxonomy_id' => $taxonomy->id, 'locale' => $locale],
                ['name' => $values['name'], 'description' => $values['description'] ?? null]
            );

            $changed = true;
        }

        // On update-only (not creation), record an 'updated' audit log entry
        // directly since the translations live in a separate table and don't
        // trigger the Taxonomy model's own Eloquent events.
        if ($changed && ! $isCreation) {
            TaxonomyAuditLog::create([
                'auditable_type' => Taxonomy::class,
                'auditable_id'   => $taxonomy->id,
                'action'         => 'updated',
                'old_values'     => null,
                'new_values'     => ['translations' => $translations],
                'user_id'        => Auth::check() ? Auth::id() : null,
            ]);
        }
    }
}
