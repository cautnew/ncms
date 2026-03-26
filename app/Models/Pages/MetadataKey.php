<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetadataKey extends Model
{
    /** @use HasFactory<\Database\Factories\Pages\MetadataFactory> */
    use HasFactory;

    protected $table = 'page_metadata_keys';

    protected $fillable = [
        'key',
        'is_nullable',
        'is_required',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(MetadataValue::class);
    }

    protected static function booted(): void
    {
        static::creating(function (MetadataKey $metadata) {
            $metadata->id = (string) Str::uuid();
            $metadata->created_by = auth()->user()->id;
            $metadata->updated_by = null;
        });

        static::updating(function (MetadataKey $metadata) {
            $metadata->updated_by = auth()->user()->id;
        });
    }
}
