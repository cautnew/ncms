<?php

namespace Tests\Unit\Taxonomy;

use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyTranslation;
use App\Services\Taxonomy\TaxonomyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxonomyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TaxonomyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(TaxonomyService::class);
    }

    public function test_create_generates_slug_from_pt_name(): void
    {
        $taxonomy = $this->service->create([], ['pt' => ['name' => 'Categorias de Produto']]);

        $this->assertEquals('categorias-de-produto', $taxonomy->slug);
    }

    public function test_create_uses_provided_slug(): void
    {
        $taxonomy = $this->service->create(['slug' => 'my-custom-slug'], ['pt' => ['name' => 'Test']]);

        $this->assertEquals('my-custom-slug', $taxonomy->slug);
    }

    public function test_create_persists_multiple_translations(): void
    {
        $taxonomy = $this->service->create([], [
            'pt' => ['name' => 'Categorias', 'description' => 'Desc PT'],
            'en' => ['name' => 'Categories', 'description' => 'Desc EN'],
        ]);

        $this->assertDatabaseHas('taxonomy_translations', ['locale' => 'pt', 'name' => 'Categorias']);
        $this->assertDatabaseHas('taxonomy_translations', ['locale' => 'en', 'name' => 'Categories']);
    }

    public function test_find_by_slug_returns_correct_taxonomy(): void
    {
        $this->service->create(['slug' => 'tags'], ['pt' => ['name' => 'Tags']]);

        $found = $this->service->find('tags');

        $this->assertNotNull($found);
        $this->assertEquals('tags', $found->slug);
    }

    public function test_find_returns_null_for_unknown_slug(): void
    {
        $found = $this->service->find('slug-inexistente');
        $this->assertNull($found);
    }

    public function test_name_helper_returns_locale_or_fallback(): void
    {
        $taxonomy = $this->service->create([], [
            'pt' => ['name' => 'Categorias'],
            'en' => ['name' => 'Categories'],
        ]);

        $this->assertEquals('Categorias', $taxonomy->name('pt'));
        $this->assertEquals('Categories', $taxonomy->name('en'));
        // Fallback to PT when locale not found
        $this->assertEquals('Categorias', $taxonomy->name('es'));
    }

    public function test_update_changes_slug_and_translations(): void
    {
        $taxonomy = $this->service->create(['slug' => 'old-slug'], ['pt' => ['name' => 'Antigo']]);

        $updated = $this->service->update('old-slug', ['slug' => 'new-slug'], ['pt' => ['name' => 'Novo']]);

        $this->assertEquals('new-slug', $updated->slug);
        $this->assertEquals('Novo', $updated->name('pt'));
    }

    public function test_update_upserts_translations(): void
    {
        $taxonomy = $this->service->create([], ['pt' => ['name' => 'Tags']]);
        $this->service->update($taxonomy->slug, [], ['en' => ['name' => 'Tags EN']]);

        $this->assertDatabaseHas('taxonomy_translations', ['locale' => 'en', 'name' => 'Tags EN']);
    }

    public function test_soft_delete_keeps_record_in_database(): void
    {
        $taxonomy = $this->service->create([], ['pt' => ['name' => 'Temporária']]);

        $this->service->delete($taxonomy->slug);

        $this->assertSoftDeleted('taxonomies', ['slug' => $taxonomy->slug]);
    }

    public function test_all_returns_only_active_taxonomies(): void
    {
        $active  = $this->service->create([], ['pt' => ['name' => 'Ativa']]);
        $deleted = $this->service->create([], ['pt' => ['name' => 'Deletada']]);
        $this->service->delete($deleted->slug);

        $all = $this->service->all();

        $slugs = $all->pluck('slug');
        $this->assertTrue($slugs->contains($active->slug));
        $this->assertFalse($slugs->contains($deleted->slug));
    }
}
