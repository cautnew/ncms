<?php

namespace App\Models;

use App\Enums\WebsiteRole;
use App\Models\Concerns\Auditable;
use Database\Factories\WebsiteUserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['website_id', 'user_id', 'role', 'invited_by', 'invited_at', 'accepted_at'])]
class WebsiteUser extends Model
{
    /** @use HasFactory<WebsiteUserFactory> */
    use Auditable, HasFactory, HasUuids;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role' => WebsiteRole::class,
            'invited_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    /**
     * The website this membership belongs to.
     *
     * @return BelongsTo<Website, $this>
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * The member (user) this membership belongs to.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The user who sent the invitation.
     *
     * @return BelongsTo<User, $this>
     */
    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Whether the member has accepted the invitation.
     *
     * @return Attribute<bool, never>
     */
    protected function isAccepted(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => $this->accepted_at !== null,
        );
    }

    /**
     * Scope a query to memberships with a given role.
     *
     * @param  Builder<WebsiteUser>  $query
     * @return Builder<WebsiteUser>
     */
    public function scopeRole(Builder $query, WebsiteRole $role): Builder
    {
        return $query->where('role', $role);
    }

    /**
     * Scope a query to memberships that already accepted their invitation.
     *
     * @param  Builder<WebsiteUser>  $query
     * @return Builder<WebsiteUser>
     */
    public function scopeAccepted(Builder $query): Builder
    {
        return $query->whereNotNull('accepted_at');
    }
}
