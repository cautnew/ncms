<?php

namespace Tests\Feature\Taxonomy;

use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyAuditLog;
use App\Models\Taxonomy\TaxonomyTranslation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxonomyApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // ──────────────────────────────────────────────────────────────
    // Index
    // ──────────────────────────────────────────────────────────────

    public function test_guest_cannot_list_taxonomies(): void
    {
        $this->getJson('/api/admin/taxonomies')->assertUnauthorized();
    }

    public function test_authenticated_user_can_list_taxonomies(): void
    {
        $taxonomy = Taxonomy::create(['slug' => 'categorias']);
        TaxonomyTranslation::create(['taxonomy_id' => $taxonomy->id, 'locale' => 'pt', 'name' => 'Categorias']);

        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/taxonomies')
            ->assertOk()
            ->assertJsonFragment(['slug' => 'categorias']);
    }

    // ──────────────────────────────────────────────────────────────
    // Create
    // ──────────────────────────────────────────────────────────────

    public function test_can_create_taxonomy_with_translations(): void
    {
        $payload = [
            'translations' => [
                'pt' => ['name' => 'Categorias', 'description' => 'Categorias do site'],
                'en' => ['name' => 'Categories', 'description' => 'Site categories'],
            ],
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/taxonomies', $payload)
            ->assertCreated()
            ->assertJsonFragment(['name' => 'Categorias']);

        $this->assertDatabaseHas('taxonomies', ['slug' => 'categorias']);
        $this->assertDatabaseHas('taxonomy_translations', ['locale' => 'pt', 'name' => 'Categorias']);
        $this->assertDatabaseHas('taxonomy_translations', ['locale' => 'en', 'name' => 'Categories']);
    }

    public function test_create_auto_generates_slug_from_pt_name(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/taxonomies', [
                'translations' => ['pt' => ['name' => 'Tags de Artigo']],
            ])
            ->assertCreated();

        $this->assertDatabaseHas('taxonomies', ['slug' => 'tags-de-artigo']);
    }

    public function test_create_requires_at_least_one_translation(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/taxonomies', ['slug' => 'test'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['translations']);
    }

    public function test_create_logs_audit_entry(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/taxonomies', [
                'translations' => ['pt' => ['name' => 'Test']],
            ])
            ->assertCreated();

        $this->assertDatabaseHas('taxonomy_audit_logs', [
            'auditable_type' => Taxonomy::class,
            'action'         => 'created',
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // Show
    // ──────────────────────────────────────────────────────────────

    public function test_can_show_taxonomy_by_slug(): void
    {
        $taxonomy = Taxonomy::create(['slug' => 'tags']);
        TaxonomyTranslation::create(['taxonomy_id' => $taxonomy->id, 'locale' => 'pt', 'name' => 'Tags']);

        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/taxonomies/tags')
            ->assertOk()
            ->assertJsonFragment(['slug' => 'tags', 'name' => 'Tags']);
    }

    public function test_show_returns_404_for_unknown_slug(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/taxonomies/does-not-exist')
            ->assertNotFound();
    }

    // ──────────────────────────────────────────────────────────────
    // Update
    // ──────────────────────────────────────────────────────────────

    public function test_can_update_taxonomy_translations(): void
    {
        $taxonomy = Taxonomy::create(['slug' => 'tags']);
        TaxonomyTranslation::create(['taxonomy_id' => $taxonomy->id, 'locale' => 'pt', 'name' => 'Tags']);

        $this->actingAs($this->user, 'sanctum')
            ->putJson('/api/admin/taxonomies/tags', [
                'translations' => ['pt' => ['name' => 'Tags Atualizadas']],
            ])
            ->assertOk()
            ->assertJsonFragment(['name' => 'Tags Atualizadas']);

        $this->assertDatabaseHas('taxonomy_translations', ['name' => 'Tags Atualizadas', 'locale' => 'pt']);
        $this->assertDatabaseHas('taxonomy_audit_logs', ['action' => 'updated']);
    }

    // ──────────────────────────────────────────────────────────────
    // Delete
    // ──────────────────────────────────────────────────────────────

    public function test_can_soft_delete_taxonomy(): void
    {
        $taxonomy = Taxonomy::create(['slug' => 'temporaria']);
        TaxonomyTranslation::create(['taxonomy_id' => $taxonomy->id, 'locale' => 'pt', 'name' => 'Temporária']);

        $this->actingAs($this->user, 'sanctum')
            ->deleteJson('/api/admin/taxonomies/temporaria')
            ->assertOk();

        $this->assertSoftDeleted('taxonomies', ['slug' => 'temporaria']);
        $this->assertDatabaseHas('taxonomy_audit_logs', ['action' => 'deleted']);
        // translations still exist (not physically deleted)
        $this->assertDatabaseHas('taxonomy_translations', ['name' => 'Temporária']);
    }

    // ──────────────────────────────────────────────────────────────
    // History
    // ──────────────────────────────────────────────────────────────

    public function test_can_retrieve_audit_history(): void
    {
        $taxonomy = Taxonomy::create(['slug' => 'auditavel']);
        TaxonomyTranslation::create(['taxonomy_id' => $taxonomy->id, 'locale' => 'pt', 'name' => 'Auditavel']);
        $taxonomy->update(['slug' => 'auditavel-v2']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/taxonomies/auditavel-v2/history')
            ->assertOk();

        $actions = collect($response->json())->pluck('action');
        $this->assertTrue($actions->contains('created'));
        $this->assertTrue($actions->contains('updated'));
    }
}
