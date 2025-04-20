<?php

namespace App\Models\NCMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Route extends Model
{
    /** @use HasFactory<\Database\Factories\NCMS\RouteFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'method',
        'route',
        'content_id',
        'controller_class',
        'method_get',
        'method_post',
        'method_put',
        'method_delete',
        'method_patch',
        'method_options',
        'is_redirect',
        'redirect_to',
        'redirect_type',
        'is_active',
        'is_authenticated_only',
    ];

    protected $casts = [
        'is_redirect' => 'boolean',
        'is_active' => 'boolean',
        'is_authenticated_only' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically set the user_id when creating a new record
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->user_id = Auth::id();
            }
        });
    }
}
