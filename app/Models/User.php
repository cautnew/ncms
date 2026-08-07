<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\WebsiteRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasUuids, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Website memberships held by this user.
     *
     * @return HasMany<WebsiteUser, $this>
     */
    public function websiteUsers(): HasMany
    {
        return $this->hasMany(WebsiteUser::class);
    }

    /**
     * Websites this user has access to.
     *
     * @return BelongsToMany<Website, $this>
     */
    public function websites(): BelongsToMany
    {
        return $this->belongsToMany(Website::class, 'website_users')
            ->withPivot(['role', 'invited_by', 'invited_at', 'accepted_at'])
            ->withTimestamps();
    }

    /**
     * Assets uploaded by this user.
     *
     * @return HasMany<Asset, $this>
     */
    public function uploadedAssets(): HasMany
    {
        return $this->hasMany(Asset::class, 'uploaded_by');
    }

    /**
     * Page versions created by this user.
     *
     * @return HasMany<PageVersion, $this>
     */
    public function createdPageVersions(): HasMany
    {
        return $this->hasMany(PageVersion::class, 'created_by');
    }

    /**
     * Page versions reviewed (approved/rejected) by this user as QA.
     *
     * @return HasMany<PageVersion, $this>
     */
    public function qaReviewedPageVersions(): HasMany
    {
        return $this->hasMany(PageVersion::class, 'qa_user_id');
    }

    /**
     * Page versions published by this user.
     *
     * @return HasMany<PageVersion, $this>
     */
    public function publishedPageVersions(): HasMany
    {
        return $this->hasMany(PageVersion::class, 'published_by');
    }

    /**
     * Audit log entries authored by this user.
     *
     * @return HasMany<AuditLog, $this>
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Scope a query to only include users with a verified email address.
     *
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * The role this user holds on a given website, or null if they are not a member.
     */
    public function roleOn(Website $website): ?WebsiteRole
    {
        return $this->websiteUsers()
            ->where('website_id', $website->id)
            ->first()
            ?->role;
    }
}
