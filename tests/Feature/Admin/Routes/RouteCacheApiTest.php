<?php

namespace Tests\Feature\Admin\Routes;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Routes\RouteCacheService;
use App\Services\Auth\RegisterUserService;
use Mockery\MockInterface;

class RouteCacheApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = RegisterUserService::register_for_test() ?? User::factory()->create();
    }

    // public function test_unauthenticated_users_cannot_access_cache_api()
    // {
    //     $this->getJson('/api/admin/routes/cache')->assertStatus(401);
    //     $this->postJson('/api/admin/routes/cache')->assertStatus(401);
    //     $this->deleteJson('/api/admin/routes/cache')->assertStatus(401);
    // }

    public function test_can_get_route_cache_status()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/routes/cache');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'is_cached',
                'last_cached_at'
            ]);
    }

    public function test_can_generate_route_cache()
    {
        // Mock the service to avoid actual filesystem changes during testing if preferred,
        // but since we want to test the API flow:
        $this->mock(RouteCacheService::class, function (MockInterface $mock) {
            $mock->shouldReceive('generate')->once()->andReturn(true);
            $mock->shouldReceive('getLastCachedAt')->andReturn('27/03/2026 20:30:00');
        });

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/routes/cache');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Route cache generated successfully.',
                'last_cached_at' => '27/03/2026 20:30:00'
            ]);
    }

    public function test_can_clear_route_cache()
    {
        $this->mock(RouteCacheService::class, function (MockInterface $mock) {
            $mock->shouldReceive('clear')->once()->andReturn(true);
        });

        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson('/api/admin/routes/cache');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Route cache cleared successfully.'
            ]);
    }
}
