<?php

namespace App\Models\Pages;

use Database\Factories\Pages\PageDataFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string $page_version_id
 * @property string $key
 * @property string $data
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class PageData extends Model
{
    /** @use HasFactory<PageDataFactory> */
    use HasFactory;

    protected $table = "page_data";

    protected $fillable = [
        'page_version_id',
        'key',
        'data',
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
            'key' => 'string',
            'data' => 'string',
            'page_version_id' => 'string',
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
                throw new \Exception('No authenticated user found for creating page data.');
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
                throw new \Exception('No authenticated user found for updating page data.');
            }

            $model->updated_by = $currentUserId;
            $model->updated_at = now();
        });

        static::deleting(function (self $model): void {
            $currentUserId = Auth::user()->id ?? null;
            if (empty($currentUserId)) {
                throw new \Exception('No authenticated user found for deleting page data.');
            }

            $model->updated_by = $currentUserId;
            $model->deleted_by = $currentUserId;

            $model->updated_at = now();
            $model->deleted_at = now();
        });
    }

    public function page_version()
    {
        return $this->belongsTo(PageVersion::class);
    }

    public function findByKey(string $key, ?string $language = null)
    {
        $query = $this->where('key', $key);
        if (!empty($language)) {
            $query->where('language', $language);
        }
        return $query->first();
    }
}
