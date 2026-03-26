<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Type extends Model
{
    /** @use HasFactory<\Database\Factories\Pages\ModelFactory> */
    use HasFactory;

    public $incrementing = false;

    protected $table = 'page_types';

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'version',
        'active',
    ];

    protected static function booted(): void
    {
        static::creating(function (Type $type) {
            $type->id = (string) Str::uuid();
            $type->created_by = auth()->user()->id ?? null;
            $type->updated_by = null;
        });

        static::updating(function (Type $type) {
            $type->updated_by = auth()->user()->id;
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string'
        ];
    }
}
