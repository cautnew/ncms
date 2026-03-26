<?php

namespace Tests\Feature\Pages;

use App\Models\Pages\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->user = User::factory()->create();
    }

    public function test_pages_index_can_be_rendered()
    {
        $response = $this->actingAs($this->user)->get('/pages');

        $response->assertOk();
    }

    public function test_pages_create_can_be_rendered()
    {
        $response = $this->actingAs($this->user)->get('/pages/create');

        $response->assertOk();
    }

    public function test_pages_can_be_stored()
    {
        Storage::fake('public');

        $type = Type::factory()->create([
            'slug' => 'test-slug-6',
            'version' => '1.0',
        ]);

        $file = UploadedFile::fake()->image('feature.png');

        $data = [
            'title' => 'Test Page',
            'type_id' => $type->id,
            'description' => 'A test page description',
            'featured_image_file' => $file,
            'featured_image_desc' => 'An image desc',
        ];

        $response = $this->actingAs($this->user)->post('/pages', $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        
        $this->assertFileExists(app_path('Http/Controllers/Pages/pages.json'));
    }

    public function test_pages_show_can_be_rendered()
    {
        $response = $this->actingAs($this->user)->get('/pages/some-id');

        $response->assertOk();
    }

    public function test_pages_edit_can_be_rendered()
    {
        $response = $this->actingAs($this->user)->get('/pages/some-id/edit');

        $response->assertOk();
    }

    public function test_pages_can_be_updated()
    {
        $response = $this->actingAs($this->user)->put('/pages/some-id');

        $response->assertOk();
    }

    public function test_pages_can_be_destroyed()
    {
        $response = $this->actingAs($this->user)->delete('/pages/some-id');

        $response->assertOk();
    }
}
