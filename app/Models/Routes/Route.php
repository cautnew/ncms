<?php

namespace App\Models\Routes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\RequestMethods;
use App\Enums\ResponseTypes;
use Illuminate\Support\Str;

/**
 * Fillable: []
 */
class Route extends Model
{
    /** @use HasFactory<\Database\Factories\Routes\RouteFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'request_method_id',
        'route',
        'page_id',
        'controller_class',
        'is_redirect',
        'redirect_to',
        'redirect_type_id',
        'is_active',
        'id_authenticated_only',
        'created_by'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'name' => 'string',
            'description' => 'string',
            'request_method_id' => RequestMethods::class,
            'route' => 'string',
            'page_id' => 'string',
            'controller_class' => 'string',
            'is_redirect' => 'boolean',
            'redirect_to' => 'string',
            'redirect_type_id' => ResponseTypes::class,
            'is_active' => 'boolean',
            'is_authenticated_only' => 'boolean',
            'created_by' => 'string'
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Route $route) {
            $route->id = (string) Str::uuid();
        });
    }
}
