<?php

namespace Tests\Unit\Taxonomy;

use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyTerm;
use App\Models\Taxonomy\TaxonomyTranslation;
use App\Services\Taxonomy\TaxonomyService;
use App\Services\Taxonomy\TaxonomyTermService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxonomyTermServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TaxonomyTermService $service;
    protected string $taxSlug = 'categorias';

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(TaxonomyTermService::class);

        // Create base taxonomy
        $taxService = app(TaxonomyService::class);
        $taxService->create(['slug' => $this->taxSlug], ['pt' => ['name' => 'Categorias']]);
    }

    public function test_can_create_term_with_translations(): void
    {
        $term = $this->service->create($this->taxSlug, [], [
            'pt' => ['name' => 'Tecnologia', 'description' => 'Posts de tech'],
            'en' => ['name' => 'Technology'],
        ]);

        $this->assertEquals('tecnologia', $term->slug);
        $this->assertEquals('Tecnologia', $term->name('pt'));
        $this->assertEquals('Technology', $term->name('en'));
    }

    public function test_slug_auto_generated_from_pt_name(): void
    {
        $term = $this->service->create($this->taxSlug, [], ['pt' => ['name' => 'Machine Learning']]);
        $this->assertEquals('machine-learning', $term->slug);
    }

    public function test_find_by_taxonomy_and_term_slug(): void
    {
        $this->service->create($this->taxSlug, ['slug' => 'tech'], ['pt' => ['name' => 'Tech']]);

        $found = $this->service->find($this->taxSlug, 'tech');

        $this->assertNotNull($found);
        $this->assertEquals('tech', $found->slug);
    }

    public function test_find_returns_null_for_unknown_term(): void
    {
        $result = $this->service->find($this->taxSlug, 'nao-existe');
        $this->assertNull($result);
    }

    public function test_create_hierarchical_term_with_parent_slug(): void
    {
        $parent = $this->service->create($this->taxSlug, ['slug' => 'brasil'], ['pt' => ['name' => 'Brasil']]);

        $child = $this->service->create($this->taxSlug, ['parent_slug' => 'brasil'], ['pt' => ['name' => 'São Paulo']]);

        $this->assertEquals($parent->id, $child->parent_id);
    }

    public function test_list_by_taxonomy_returns_only_root_terms(): void
    {
        $root  = $this->service->create($this->taxSlug, ['slug' => 'root'], ['pt' => ['name' => 'Root']]);
        $child = $this->service->create($this->taxSlug, ['slug' => 'child', 'parent_slug' => 'root'], ['pt' => ['name' => 'Child']]);

        $list = $this->service->listByTaxonomy($this->taxSlug);

        $slugs = $list->pluck('slug');
        $this->assertTrue($slugs->contains('root'));
        $this->assertFalse($slugs->contains('child')); // children loaded via relation, not top-level
    }

    public function test_update_changes_translations_and_rank(): void
    {
        $this->service->create($this->taxSlug, ['slug' => 'tech', 'rank' => 0], ['pt' => ['name' => 'Tech']]);

        $updated = $this->service->update($this->taxSlug, 'tech', ['rank' => 10], [
            'pt' => ['name' => 'Tecnologia'],
        ]);

        $this->assertEquals(10, $updated->rank);
        $this->assertEquals('Tecnologia', $updated->name('pt'));
    }

    public function test_soft_delete_keeps_history(): void
    {
        $this->service->create($this->taxSlug, ['slug' => 'rascunho'], ['pt' => ['name' => 'Rascunho']]);

        $this->service->delete($this->taxSlug, 'rascunho');

        $this->assertSoftDeleted('taxonomy_terms', ['slug' => 'rascunho']);
        $this->assertDatabaseHas('taxonomy_audit_logs', ['action' => 'deleted']);
    }

    public function test_name_fallback_to_pt_when_locale_missing(): void
    {
        $term = $this->service->create($this->taxSlug, [], ['pt' => ['name' => 'Esportes']]);

        $this->assertEquals('Esportes', $term->name('en')); // falls back to PT
    }
}
