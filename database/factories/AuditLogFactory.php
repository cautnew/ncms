<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    /**
     * The vocabulary actually produced by the app's audit system — see
     * App\Models\Concerns\Auditable and the login/logout/clone listeners.
     *
     * @var array<int, string>
     */
    private const ACTIONS = [
        'login', 'logout', 'create', 'update', 'delete',
        'approve', 'reject', 'publish', 'upload', 'version_cloned',
    ];

    /**
     * Define the model's default state.
     *
     * Defaults to auditing the website itself, so auditable_id/auditable_type
     * form a valid, resolvable relationship out of the box rather than a
     * random, unrelated UUID.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'website_id' => Website::factory(),
            'user_id' => User::factory(),
            'auditable_type' => Website::class,
            'auditable_id' => fn (array $attributes) => $attributes['website_id'],
            'action' => fake()->randomElement(self::ACTIONS),
            'old_values' => null,
            'new_values' => null,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }

    /**
     * Indicate that this entry was performed by the system, not a user.
     */
    public function system(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }

    /**
     * Point this entry at a specific auditable model, scoping website_id to
     * match whenever the model exposes one (directly or via auditWebsiteId()).
     */
    public function forModel(Model $model): static
    {
        return $this->state(function (array $attributes) use ($model): array {
            $websiteId = method_exists($model, 'auditWebsiteId')
                ? $model->auditWebsiteId()
                : ($model->getAttribute('website_id') ?? $attributes['website_id']);

            return [
                'website_id' => $websiteId,
                'auditable_type' => $model::class,
                'auditable_id' => $model->getKey(),
            ];
        });
    }

    /**
     * Indicate a successful login entry (no website scope, no old/new values).
     *
     * auditable_id is set to a closure (rather than reading $attributes['user_id']
     * directly here) because this outer state() closure still sees the *raw*,
     * unresolved User::factory() definition for 'user_id' — a nested closure
     * value is resolved in a later pass, once 'user_id' has already become a
     * concrete id, the same trick definition() uses for auditable_id/website_id.
     */
    public function login(): static
    {
        return $this->state(fn (array $attributes) => [
            'website_id' => null,
            'auditable_type' => User::class,
            'auditable_id' => fn (array $attrs) => $attrs['user_id'],
            'action' => 'login',
            'old_values' => null,
            'new_values' => null,
        ]);
    }

    /**
     * Indicate a logout entry (no website scope, no old/new values).
     */
    public function logout(): static
    {
        return $this->state(fn (array $attributes) => [
            'website_id' => null,
            'auditable_type' => User::class,
            'auditable_id' => fn (array $attrs) => $attrs['user_id'],
            'action' => 'logout',
            'old_values' => null,
            'new_values' => null,
        ]);
    }

    /**
     * Attach a before/after value diff, as a genuine "update" entry would have.
     *
     * @param  array<string, mixed>  $old
     * @param  array<string, mixed>  $new
     */
    public function withChanges(array $old, array $new): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'update',
            'old_values' => $old,
            'new_values' => $new,
        ]);
    }
}
