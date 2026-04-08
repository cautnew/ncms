<?php

namespace App\Http\Controllers\Taxonomy;

use App\Http\Controllers\Controller;
use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyAuditLog;
use App\Models\Taxonomy\TaxonomyTerm;
use App\Services\Taxonomy\TaxonomyService;
use App\Services\Taxonomy\TaxonomyTermService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaxonomyWebController extends Controller
{
    public function __construct(
        protected TaxonomyService     $taxonomyService,
        protected TaxonomyTermService $termService,
    ) {}

    // ──────────────────────────────────────────────────────────────
    // Taxonomy CRUD
    // ──────────────────────────────────────────────────────────────

    public function index(): Response
    {
        $taxonomies = $this->taxonomyService->all()->map(fn($t) => [
            'id'          => $t->id,
            'slug'        => $t->slug,
            'name'        => $t->name('pt'),
            'description' => $t->description('pt'),
            'terms_count' => $t->terms()->count(),
            'translations'=> $t->translationsArray(),
        ]);

        return Inertia::render('taxonomy/all', ['taxonomies' => $taxonomies]);
    }

    public function create(): Response
    {
        return Inertia::render('taxonomy/create');
    }

    public function show(string $slug): Response
    {
        $taxonomy = $this->taxonomyService->findOrFail($slug);
        $locale   = 'pt';

        return Inertia::render('taxonomy/show', [
            'taxonomy' => [
                'id'           => $taxonomy->id,
                'slug'         => $taxonomy->slug,
                'name'         => $taxonomy->name($locale),
                'description'  => $taxonomy->description($locale),
                'translations' => $taxonomy->translationsArray(),
                'terms'        => $taxonomy->rootTerms->map(fn($t) => $this->formatTerm($t, $locale)),
            ]
        ]);
    }

    public function edit(string $slug): Response
    {
        $taxonomy = $this->taxonomyService->findOrFail($slug);

        return Inertia::render('taxonomy/edit', [
            'taxonomy' => [
                'id'           => $taxonomy->id,
                'slug'         => $taxonomy->slug,
                'translations' => $taxonomy->translationsArray(),
            ]
        ]);
    }

    public function confirmDelete(string $slug): Response
    {
        $taxonomy = $this->taxonomyService->findOrFail($slug);

        return Inertia::render('taxonomy/delete', [
            'taxonomy' => [
                'id'   => $taxonomy->id,
                'slug' => $taxonomy->slug,
                'name' => $taxonomy->name('pt'),
                'terms_count' => $taxonomy->terms()->count(),
            ]
        ]);
    }

    public function taxonomyHistory(string $slug): Response
    {
        $taxonomy = Taxonomy::withTrashed()->where('slug', $slug)->firstOrFail();

        $logs = TaxonomyAuditLog::where('auditable_type', Taxonomy::class)
            ->where('auditable_id', $taxonomy->id)
            ->with('user:id,name')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($l) => [
                'id'            => $l->id,
                'action'        => $l->action,
                'old_values'    => $l->old_values,
                'new_values'    => $l->new_values,
                'user'          => $l->user ? ['name' => $l->user->name] : null,
                'created_at'    => $l->created_at?->toISOString(),
            ]);

        return Inertia::render('taxonomy/history', [
            'taxonomy' => ['slug' => $taxonomy->slug, 'name' => $taxonomy->name('pt')],
            'logs'     => $logs,
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // Term CRUD
    // ──────────────────────────────────────────────────────────────

    public function createTerm(string $slug): Response
    {
        $taxonomy = $this->taxonomyService->findOrFail($slug);

        return Inertia::render('taxonomy/terms/create', [
            'taxonomy' => ['slug' => $taxonomy->slug, 'name' => $taxonomy->name('pt')],
            'terms'    => $taxonomy->terms->map(fn($t) => ['slug' => $t->slug, 'name' => $t->name('pt')]),
        ]);
    }

    public function editTerm(string $slug, string $termSlug): Response
    {
        $term = $this->termService->findOrFail($slug, $termSlug);

        return Inertia::render('taxonomy/terms/edit', [
            'taxonomy' => ['slug' => $slug, 'name' => $this->taxonomyService->findOrFail($slug)->name('pt')],
            'term'     => [
                'id'           => $term->id,
                'slug'         => $term->slug,
                'rank'         => $term->rank,
                'parent_id'    => $term->parent_id,
                'translations' => $term->translationsArray(),
            ],
        ]);
    }

    public function confirmDeleteTerm(string $slug, string $termSlug): Response
    {
        $term = $this->termService->findOrFail($slug, $termSlug);

        return Inertia::render('taxonomy/terms/delete', [
            'taxonomy' => ['slug' => $slug, 'name' => $this->taxonomyService->findOrFail($slug)->name('pt')],
            'term'     => ['slug' => $term->slug, 'name' => $term->name('pt'), 'children_count' => $term->children()->count()],
        ]);
    }

    public function termHistory(string $slug, string $termSlug): Response
    {
        $term = TaxonomyTerm::withTrashed()
            ->whereHas('taxonomy', fn($q) => $q->withTrashed()->where('slug', $slug))
            ->where('slug', $termSlug)
            ->firstOrFail();

        $logs = TaxonomyAuditLog::where('auditable_type', TaxonomyTerm::class)
            ->where('auditable_id', $term->id)
            ->with('user:id,name')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($l) => [
                'id'         => $l->id,
                'action'     => $l->action,
                'old_values' => $l->old_values,
                'new_values' => $l->new_values,
                'user'       => $l->user ? ['name' => $l->user->name] : null,
                'created_at' => $l->created_at?->toISOString(),
            ]);

        return Inertia::render('taxonomy/terms/history', [
            'taxonomy' => ['slug' => $slug, 'name' => $this->taxonomyService->findOrFail($slug)->name('pt')],
            'term'     => ['slug' => $term->slug, 'name' => $term->name('pt')],
            'logs'     => $logs,
        ]);
    }

    // ──────────────────────────────────────────────────────────────

    protected function formatTerm($term, string $locale): array
    {
        return [
            'id'           => $term->id,
            'slug'         => $term->slug,
            'name'         => $term->name($locale),
            'description'  => $term->description($locale),
            'rank'         => $term->rank,
            'parent_id'    => $term->parent_id,
            'translations' => $term->translationsArray(),
            'children'     => $term->relationLoaded('children')
                ? $term->children->map(fn($c) => $this->formatTerm($c, $locale))->values()->toArray()
                : [],
        ];
    }
}
