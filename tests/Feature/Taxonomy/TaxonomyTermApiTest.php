<?php

namespace Tests\Feature\Taxonomy;

use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyAuditLog;
use App\Models\Taxonomy\TaxonomyTerm;
use App\Models\Taxonomy\TaxonomyTranslation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxonomyTermApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Taxonomy $taxonomy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->taxonomy = Taxonomy::create(['slug' => 'categorias']);
        TaxonomyTranslation::create([
            'taxonomy_id' => $this->taxonomy->id,
            'locale'      => 'pt',
            'name'        => 'Categorias',
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // Create Term
    // ──────────────────────────────────────────────────────────────

    public function test_can_create_term_with_translations(): void
    {
        $payload = [
            'rank'         => 0,
            'translations' => [
                'pt' => ['name' => 'Tecnologia',  'description' => 'Artigos de tech'],
                'en' => ['name' => 'Technology',  'description' => 'Tech articles'],
            ],
        ];

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/taxonomies/categorias/terms', $payload)
            ->assertCreated()
            ->assertJsonFragment(['name' => 'Tecnologia']);

        $this->assertDatabaseHas('taxonomy_terms', ['slug' => 'tecnologia', 'taxonomy_id' => $this->taxonomy->id]);
        $this->assertDatabaseHas('taxonomy_term_translations', ['locale' => 'pt', 'name' => 'Tecnologia']);
        $this->assertDatabaseHas('taxonomy_term_translations', ['locale' => 'en', 'name' => 'Technology']);
        $this->assertDatabaseHas('taxonomy_audit_logs', ['auditable_type' => TaxonomyTerm::class, 'action' => 'created']);
    }

    public function test_can_create_child_term_with_parent_slug(): void
    {
        // Create parent
        $parent = TaxonomyTerm::create(['taxonomy_id' => $this->taxonomy->id, 'slug' => 'tecnologia', 'rank' => 0]);

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/taxonomies/categorias/terms', [
                'parent_slug'  => 'tecnologia',
                'translations' => ['pt' => ['name' => 'Mobile']],
            ])
            ->assertCreated();

        $this->assertDatabaseHas('taxonomy_terms', ['slug' => 'mobile', 'parent_id' => $parent->id]);
    }

    public function test_create_term_requires_translation(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/taxonomies/categorias/terms', ['slug' => 'test'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['translations']);
    }

    // ──────────────────────────────────────────────────────────────
    // List Terms
    // ──────────────────────────────────────────────────────────────

    public function test_can_list_terms_for_taxonomy(): void
    {
        $term = TaxonomyTerm::create(['taxonomy_id' => $this->taxonomy->id, 'slug' => 'tech', 'rank' => 0]);
        $term->translations()->create(['locale' => 'pt', 'name' => 'Tecnologia']);

        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/taxonomies/categorias/terms')
            ->assertOk()
            ->assertJsonFragment(['slug' => 'tech']);
    }

    // ──────────────────────────────────────────────────────────────
    // Update Term
    // ──────────────────────────────────────────────────────────────

    public function test_can_update_term(): void
    {
        $term = TaxonomyTerm::create(['taxonomy_id' => $this->taxonomy->id, 'slug' => 'tech', 'rank' => 0]);
        $term->translations()->create(['locale' => 'pt', 'name' => 'Tech']);

        $this->actingAs($this->user, 'sanctum')
            ->putJson('/api/admin/taxonomies/categorias/terms/tech', [
                'translations' => ['pt' => ['name' => 'Tecnologia Avançada']],
            ])
            ->assertOk()
            ->assertJsonFragment(['name' => 'Tecnologia Avançada']);

        $this->assertDatabaseHas('taxonomy_audit_logs', ['action' => 'updated', 'auditable_type' => TaxonomyTerm::class]);
    }

    // ──────────────────────────────────────────────────────────────
    // Delete Term
    // ──────────────────────────────────────────────────────────────

    public function test_can_soft_delete_term(): void
    {
        $term = TaxonomyTerm::create(['taxonomy_id' => $this->taxonomy->id, 'slug' => 'deletar', 'rank' => 0]);
        $term->translations()->create(['locale' => 'pt', 'name' => 'Deletar']);

        $this->actingAs($this->user, 'sanctum')
            ->deleteJson('/api/admin/taxonomies/categorias/terms/deletar')
            ->assertOk();

        $this->assertSoftDeleted('taxonomy_terms', ['slug' => 'deletar']);
        $this->assertDatabaseHas('taxonomy_audit_logs', ['action' => 'deleted', 'auditable_type' => TaxonomyTerm::class]);
    }

    // ──────────────────────────────────────────────────────────────
    // History
    // ──────────────────────────────────────────────────────────────

    public function test_can_view_term_history(): void
    {
        $term = TaxonomyTerm::create(['taxonomy_id' => $this->taxonomy->id, 'slug' => 'historico', 'rank' => 0]);
        $term->translations()->create(['locale' => 'pt', 'name' => 'Histórico']);
        $term->update(['rank' => 5]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/taxonomies/categorias/terms/historico/history')
            ->assertOk();

        $actions = collect($response->json())->pluck('action');
        $this->assertTrue($actions->contains('created'));
        $this->assertTrue($actions->contains('updated'));
    }

    public function test_history_persists_after_deletion(): void
    {
        $term = TaxonomyTerm::create(['taxonomy_id' => $this->taxonomy->id, 'slug' => 'efemero', 'rank' => 0]);
        $term->translations()->create(['locale' => 'pt', 'name' => 'Efêmero']);
        $term->delete();

        // Even after deletion, history should be visible
        $this->assertDatabaseHas('taxonomy_audit_logs', [
            'auditable_type' => TaxonomyTerm::class,
            'auditable_id'   => $term->id,
            'action'         => 'deleted',
        ]);
    }
}
