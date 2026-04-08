<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Taxonomy\TaxonomyAuditLog;
use App\Models\Taxonomy\TaxonomyTerm;
use App\Services\Settings\SettingsService;
use App\Services\Taxonomy\TaxonomyTermService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxonomyTermController extends Controller
{
    public function __construct(
        protected TaxonomyTermService $service,
        protected SettingsService $settings,
    ) {}

    public function index(string $slug): JsonResponse
    {
        $terms = $this->service->listByTaxonomy($slug);
        $locale = app()->getLocale();

        return response()->json($terms->map(fn($t) => $this->format($t, $locale)));
    }

    public function show(string $slug, string $termSlug): JsonResponse
    {
        $term = $this->service->find($slug, $termSlug);

        if (! $term) {
            return response()->json(['message' => 'Term not found.'], 404);
        }

        return response()->json($this->format($term, app()->getLocale()));
    }

    public function store(Request $request, string $slug): JsonResponse
    {
        $allowedLocaleCodes = collect($this->settings->availableLocales())->pluck('code')->all();
        $defaultLocale = $this->settings->defaultLocale();

        $data = $request->validate([
            'slug'                        => 'nullable|string',
            'rank'                        => 'nullable|integer',
            'parent_slug'                 => 'nullable|string',
            'primary_locale'              => ['nullable', 'string', Rule::in($allowedLocaleCodes)],
            'translations'                => 'required|array',
            'translations.*.name'         => 'nullable|string|max:255',
            'translations.*.description'  => 'nullable|string',
        ]);

        $primaryLocale = $data['primary_locale'] ?? $defaultLocale;
        if (empty($data['translations'][$primaryLocale]['name'] ?? null)) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => [
                    "translations.$primaryLocale.name" => 'Name is required in the initial language.',
                ],
            ], 422);
        }

        $term = $this->service->create($slug, $data, $data['translations'], $primaryLocale);

        return response()->json($this->format($term, app()->getLocale()), 201);
    }

    public function update(Request $request, string $slug, string $termSlug): JsonResponse
    {
        $term = $this->service->find($slug, $termSlug);

        if (! $term) {
            return response()->json(['message' => 'Term not found.'], 404);
        }

        $data = $request->validate([
            'slug'                        => 'nullable|string',
            'rank'                        => 'nullable|integer',
            'parent_slug'                 => 'nullable|string',
            'translations'                => 'nullable|array',
            'translations.*.name'         => 'nullable|string|max:255',
            'translations.*.description'  => 'nullable|string',
        ]);

        $term = $this->service->update($slug, $term, $data, $data['translations'] ?? []);

        return response()->json($this->format($term, app()->getLocale()));
    }

    public function destroy(string $slug, string $termSlug): JsonResponse
    {
        $term = $this->service->find($slug, $termSlug);

        if (! $term) {
            return response()->json(['message' => 'Term not found.'], 404);
        }

        $this->service->delete($slug, $term);

        return response()->json(['message' => 'Term deleted.']);
    }

    public function history(string $slug, string $termSlug): JsonResponse
    {
        $term = $this->service->find($slug, $termSlug)
            ?? TaxonomyTerm::withTrashed()
                ->whereHas('taxonomy', fn($q) => $q->withTrashed()->where('slug', $slug))
                ->where('slug', $termSlug)
                ->firstOrFail();

        $logs = TaxonomyAuditLog::where('auditable_type', TaxonomyTerm::class)
            ->where('auditable_id', $term->id)
            ->with('user:id,name,email')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($logs);
    }

    // ──────────────────────────────────────────────────────────────

    protected function format(TaxonomyTerm $t, string $locale): array
    {
        return [
            'id'           => $t->id,
            'slug'         => $t->slug,
            'name'         => $t->name($locale),
            'description'  => $t->description($locale),
            'rank'         => $t->rank,
            'parent_id'    => $t->parent_id,
            'translations' => $t->translationsArray(),
            'children'     => $t->relationLoaded('children')
                ? $t->children->map(fn($c) => $this->format($c, $locale))
                : [],
            'created_at'   => $t->created_at,
            'updated_at'   => $t->updated_at,
            'deleted_at'   => $t->deleted_at,
        ];
    }
}
