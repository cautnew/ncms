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

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->id = (string) Str::uuid();
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
