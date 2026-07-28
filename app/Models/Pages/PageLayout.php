<?php

namespace App\Models\Pages;

use Database\Factories\Pages\PageLayoutFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string $slug
 * @property string $name
 * @property string|null $template_class_name
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class PageLayout extends Model
{
    /** @use HasFactory<PageLayoutFactory> */
    use HasFactory;

    protected $fillable = [
        "slug",
        "name",
        "template_class_name",
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
            'slug' => 'string',
            'name' => 'string',
            'template_class_name' => 'string',
            'created_by' => 'string',
            'updated_by' => 'string',
            'deleted_by' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            $currentUserId = Auth::user()->id ?? null;
            if (empty($currentUserId)) {
                throw new \Exception('No authenticated user found for creating page layout.');
            }

            $model->id = (string) Str::uuid();
            $model->created_by = $currentUserId;
            $model->updated_by = null;
            $model->deleted_by = null;

            $model->created_at = now();
            $model->updated_at = null;
            $model->deleted_at = null;
        });

        static::updating(function (self $model): void {
            $currentUserId = Auth::user()->id ?? null;
            if (empty($currentUserId)) {
                throw new \Exception('No authenticated user found for updating page layout.');
            }

            $model->updated_by = $currentUserId;
            $model->updated_at = now();
        });

        static::deleting(function (self $model): void {
            $currentUserId = Auth::user()->id ?? null;
            if (empty($currentUserId)) {
                throw new \Exception('No authenticated user found for deleting page layout.');
            }

            $model->updated_by = $currentUserId;
            $model->deleted_by = $currentUserId;

            $model->updated_at = now();
            $model->deleted_at = now();
        });
    }
}
