<?php

namespace Tests\Feature\Pages;

use App\Models\Pages\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageTypesTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_page_types_index_can_be_rendered()
    {
        Type::factory()->create([
            'slug' => 'test-slug-1',
            'version' => '1.0',
        ]);

        $response = $this->actingAs($this->user)->get('/page-types');

        $response->assertOk();
    }

    public function test_page_types_create_can_be_rendered()
    {
        $response = $this->actingAs($this->user)->get('/page-types/create');

        $response->assertOk();
    }

    public function test_page_types_can_be_stored()
    {
        $data = [
            'name' => 'News Page',
            'slug' => 'news-page',
            'version' => '1.0',
            'description' => 'A news page type',
        ];

        $response = $this->actingAs($this->user)->post('/page-types', $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('page_types', ['slug' => 'news-page']);
    }

    public function test_page_types_show_can_be_rendered()
    {
        $type = Type::factory()->create([
            'slug' => 'test-slug-2',
            'version' => '1.0',
        ]);

        $response = $this->actingAs($this->user)->get('/page-types/' . $type->id);

        $response->assertOk();
    }

    public function test_page_types_edit_can_be_rendered()
    {
        $type = Type::factory()->create([
            'slug' => 'test-slug-3',
            'version' => '1.0',
        ]);

        $response = $this->actingAs($this->user)->get('/page-types/' . $type->id . '/edit');

        $response->assertOk();
    }

    public function test_page_types_can_be_updated()
    {
        $type = Type::factory()->create([
            'slug' => 'test-slug-4',
            'version' => '1.0',
        ]);

        $data = [
            'name' => 'Updated Name',
            'slug' => 'updated-slug',
            'version' => '1.1',
            'description' => 'Updated desc',
        ];

        $response = $this->actingAs($this->user)->put('/page-types/' . $type->id, $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('page_types', [
            'id' => $type->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_page_types_pages_can_be_fetched()
    {
        $type = Type::factory()->create([
            'slug' => 'test-slug-5',
            'version' => '1.0',
        ]);

        $response = $this->actingAs($this->user)->get('/page-types/' . $type->id . '/pages');

        $response->assertOk();
        $response->assertJsonStructure([]);
    }
}
