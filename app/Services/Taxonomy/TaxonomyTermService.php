<?php

namespace App\Services\Taxonomy;

use App\Models\Taxonomy\TaxonomyAuditLog;
use App\Models\Taxonomy\TaxonomyTerm;
use App\Models\Taxonomy\TaxonomyTermTranslation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TaxonomyTermService
{
    public function __construct(protected TaxonomyService $taxonomyService) {}

    /**
     * List all (active) terms for a given taxonomy slug.
     */
    public function listByTaxonomy(string $taxonomySlug): Collection
    {
        $taxonomy = $this->taxonomyService->findOrFail($taxonomySlug);

        return TaxonomyTerm::with('translations', 'children.translations')
            ->where('taxonomy_id', $taxonomy->id)
            ->whereNull('parent_id')
            ->orderBy('rank')
            ->get();
    }

    /**
     * Find a single term by taxonomy slug + term slug.
     */
    public function find(string $taxonomySlug, string $termSlug): ?TaxonomyTerm
    {
        $taxonomy = $this->taxonomyService->findOrFail($taxonomySlug);

        return TaxonomyTerm::with('translations', 'children.translations')
            ->where('taxonomy_id', $taxonomy->id)
            ->where('slug', $termSlug)
            ->first();
    }

    public function findOrFail(string $taxonomySlug, string $termSlug): TaxonomyTerm
    {
        $term = $this->find($taxonomySlug, $termSlug);

        if (! $term) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException(
                "Term [{$termSlug}] not found in taxonomy [{$taxonomySlug}]."
            );
        }

        return $term;
    }

    /**
     * Create a new term in a taxonomy.
     *
     * @param  array  $data         ['slug', 'rank', 'parent_slug'] — slug auto-generated from PT name
     * @param  array  $translations ['pt' => ['name' => ..., 'description' => ...], ...]
     * @param  string $primaryLocale Locale preferido para slug (fallback: primeiro nome disponível)
     */
    public function create(string $taxonomySlug, array $data, array $translations, string $primaryLocale = 'pt'): TaxonomyTerm
    {
        $taxonomy = $this->taxonomyService->findOrFail($taxonomySlug);

        // Auto-generate slug
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

        // Resolve parent
        $parentId = null;
        if (!empty($data['parent_slug'])) {
            $parent = $this->find($taxonomySlug, $data['parent_slug']);
            $parentId = $parent?->id;
        }

        $term = TaxonomyTerm::create([
            'taxonomy_id' => $taxonomy->id,
            'slug'        => $data['slug'],
            'rank'        => $data['rank'] ?? 0,
            'parent_id'   => $parentId,
        ]);

        $this->syncTranslations($term, $translations, isCreation: true);

        return $term->load('translations');
    }

    /**
     * Update an existing term (found by taxonomy slug + term slug).
     */
    public function update(string $taxonomySlug, string|TaxonomyTerm $term, array $data, array $translations): TaxonomyTerm
    {
        if (is_string($term)) {
            $term = $this->findOrFail($taxonomySlug, $term);
        }

        $updateData = [];
        if (isset($data['slug']))  $updateData['slug'] = $data['slug'];
        if (isset($data['rank']))  $updateData['rank'] = $data['rank'];

        if (!empty($data['parent_slug'])) {
            $parent = $this->find($taxonomySlug, $data['parent_slug']);
            $updateData['parent_id'] = $parent?->id;
        }

        if (!empty($updateData)) {
            $term->update($updateData);
        }

        $this->syncTranslations($term, $translations);

        return $term->fresh('translations');
    }

    /**
     * Soft-delete a term.
     */
    public function delete(string $taxonomySlug, string|TaxonomyTerm $term): bool
    {
        if (is_string($term)) {
            $term = $this->findOrFail($taxonomySlug, $term);
        }

        return (bool) $term->delete();
    }

    /**
     * Restore a soft-deleted term.
     */
    public function restore(string $taxonomySlug, string $termSlug): TaxonomyTerm
    {
        $taxonomy = $this->taxonomyService->findOrFail($taxonomySlug);

        $term = TaxonomyTerm::withTrashed()
            ->where('taxonomy_id', $taxonomy->id)
            ->where('slug', $termSlug)
            ->firstOrFail();

        $term->restore();

        return $term->load('translations');
    }

    // ──────────────────────────────────────────────────────────────

    protected function syncTranslations(TaxonomyTerm $term, array $translations, bool $isCreation = false): void
    {
        $changed = false;

        foreach ($translations as $locale => $values) {
            if (empty($values['name'])) {
                continue;
            }

            TaxonomyTermTranslation::updateOrCreate(
                ['taxonomy_term_id' => $term->id, 'locale' => $locale],
                ['name' => $values['name'], 'description' => $values['description'] ?? null]
            );

            $changed = true;
        }

        if ($changed && ! $isCreation) {
            TaxonomyAuditLog::create([
                'auditable_type' => TaxonomyTerm::class,
                'auditable_id'   => $term->id,
                'action'         => 'updated',
                'old_values'     => null,
                'new_values'     => ['translations' => $translations],
                'user_id'        => Auth::check() ? Auth::id() : null,
            ]);
        }
    }
}
