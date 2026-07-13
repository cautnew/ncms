<?php

namespace App\Models\Pages;

use Database\Factories\Pages\PageVersionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * PageVersion Model
 * @property string $id
 * @property string $locale
 * @property string $name
 * @property string $page_id
 * @property boolean $is_current_editing
 * @property boolean $is_current
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class PageVersion extends Model
{
    /** @use HasFactory<PageVersionFactory> */
    use HasFactory;

    protected $table = "page_versions";

    protected $fillable = [
        "locale",
        "name",
        "page_id",
        "is_current_editing",
        "is_current",
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
            'page_id' => 'string',
            'is_current' => 'boolean',
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
                throw new \Exception('No authenticated user found for creating page version.');
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
                throw new \Exception('No authenticated user found for updating page version.');
            }

            $model->updated_by = $currentUserId;
            $model->updated_at = now();
        });

        static::deleting(function (self $model): void {
            $currentUserId = Auth::user()->id ?? null;
            if (empty($currentUserId)) {
                throw new \Exception('No authenticated user found for deleting page version.');
            }

            $model->updated_by = $currentUserId;
            $model->deleted_by = $currentUserId;

            $model->updated_at = now();
            $model->deleted_at = now();
        });
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function pageData()
    {
        return $this->hasMany(PageData::class);
    }

    public function findByPageId(string $page_id)
    {
        $this->where('page_id', $page_id)->first();
    }
}
