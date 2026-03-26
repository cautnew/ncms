<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetadataValue extends Model
{
    /** @use HasFactory<\Database\Factories\Pages\MetadataFactory> */
    use HasFactory;

    protected $table = 'page_metadata_values';

    protected $fillable = [
        'metadata_id',
        'metadata_key_id',
        'value',
        'is_null',
        'is_active',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(MetadataValue::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Metadata $metadata) {
            $metadata->id = (string) Str::uuid();
            $metadata->created_by = auth()->user()->id;
            $metadata->updated_by = null;
        });

        static::updating(function (Metadata $metadata) {
            $metadata->updated_by = auth()->user()->id;
        });
    }
}
