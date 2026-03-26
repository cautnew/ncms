<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    /** @use HasFactory<\Database\Factories\Pages\PageFactory> */
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'active',
        'type_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Page $page) {
            $page->id = (string) Str::uuid();
            $page->created_by = auth()->user()->id ?? null;
            $page->updated_by = null;
        });

        static::updating(function (Page $page) {
            $page->updated_by = auth()->user()->id ?? null;
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
