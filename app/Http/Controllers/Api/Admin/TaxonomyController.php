<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyAuditLog;
use App\Services\Settings\SettingsService;
use App\Services\Taxonomy\TaxonomyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxonomyController extends Controller
{
    public function __construct(
        protected TaxonomyService $service,
        protected SettingsService $settings,
    ) {}

    public function index(): JsonResponse
    {
        $taxonomies = $this->service->all()->map(fn($t) => $this->format($t));

        return response()->json($taxonomies);
    }

    public function show(string $slug): JsonResponse
    {
        $taxonomy = $this->service->find($slug);

        if (! $taxonomy) {
            return response()->json(['message' => 'Taxonomy not found.'], 404);
        }

        return response()->json($this->format($taxonomy, withTerms: true));
    }

    public function store(Request $request): JsonResponse
    {
        $allowedLocaleCodes = collect($this->settings->availableLocales())->pluck('code')->all();
        $defaultLocale = $this->settings->defaultLocale();

        $data = $request->validate([
            'slug'                         => 'nullable|string|unique:taxonomies,slug',
            'primary_locale'               => ['nullable', 'string', Rule::in($allowedLocaleCodes)],
            'translations'                 => 'required|array',
            'translations.*.name'          => 'nullable|string|max:255',
            'translations.*.description'   => 'nullable|string',
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

        $taxonomy = $this->service->create(
            ['slug' => $data['slug'] ?? null],
            $data['translations'],
            $primaryLocale
        );

        return response()->json($this->format($taxonomy), 201);
    }

    public function update(Request $request, string $slug): JsonResponse
    {
        $taxonomy = $this->service->find($slug);

        if (! $taxonomy) {
            return response()->json(['message' => 'Taxonomy not found.'], 404);
        }

        $data = $request->validate([
            'slug'                        => ['nullable', 'string', Rule::unique('taxonomies', 'slug')->ignore($taxonomy->id)],
            'translations'                => 'nullable|array',
            'translations.*.name'         => 'nullable|string|max:255',
            'translations.*.description'  => 'nullable|string',
        ]);

        $taxonomy = $this->service->update($taxonomy, $data, $data['translations'] ?? []);

        return response()->json($this->format($taxonomy));
    }

    public function destroy(string $slug): JsonResponse
    {
        $taxonomy = $this->service->find($slug);

        if (! $taxonomy) {
            return response()->json(['message' => 'Taxonomy not found.'], 404);
        }

        $this->service->delete($taxonomy);

        return response()->json(['message' => 'Taxonomy deleted.']);
    }

    public function history(string $slug): JsonResponse
    {
        $taxonomy = Taxonomy::withTrashed()->where('slug', $slug)->firstOrFail();

        $logs = TaxonomyAuditLog::where('auditable_type', Taxonomy::class)
            ->where('auditable_id', $taxonomy->id)
            ->with('user:id,name,email')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($logs);
    }

    // ──────────────────────────────────────────────────────────────

    protected function format(Taxonomy $t, bool $withTerms = false): array
    {
        $locale = app()->getLocale();

        $out = [
            'id'           => $t->id,
            'slug'         => $t->slug,
            'name'         => $t->name($locale),
            'description'  => $t->description($locale),
            'translations' => $t->translationsArray(),
            'created_at'   => $t->created_at,
            'updated_at'   => $t->updated_at,
            'deleted_at'   => $t->deleted_at,
        ];

        if ($withTerms) {
            $out['terms'] = $t->terms->map(fn($term) => [
                'id'           => $term->id,
                'slug'         => $term->slug,
                'name'         => $term->name($locale),
                'rank'         => $term->rank,
                'parent_id'    => $term->parent_id,
                'translations' => $term->translationsArray(),
            ]);
        }

        return $out;
    }
}
