<?php

namespace App\Models\Pages;

use App\Models\Websites\Website;
use App\Models\Pages\PageData;
use Database\Factories\Pages\PageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Page Model
 * @property string $id
 * @property string $slug
 * @property string $name
 * @property boolean $is_public
 * @property boolean $is_draft
 * @property boolean $is_active
 * @property boolean $is_editing
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    public const string SLUG_COLUMN = 'slug';

    public const string NAME_COLUMN = 'name';

    public const string EMAIL_COLUMN = 'email';

    protected $fillable = [
        'slug',
        'name',
        'website_id',
        'is_public',
        'is_draft',
        'is_active',
        'is_editing',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'website_id' => 'string',
            'is_public'=> 'boolean',
            'is_draft'=> 'boolean',
            'is_active'=> 'boolean',
            'is_editing'=> 'boolean',
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
                throw new \Exception('No authenticated user found for creating a Page.');
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
                throw new \Exception('No authenticated user found for updating a Page.');
            }

            $model->updated_by = $currentUserId;
            $model->updated_at = now();
        });

        static::deleting(function (self $model): void {
            $currentUserId = Auth::user()->id ?? null;
            if (empty($currentUserId)) {
                throw new \Exception('No authenticated user found for deleting a Page.');
            }

            $model->updated_by = $currentUserId;
            $model->deleted_by = $currentUserId;

            $model->updated_at = now();
            $model->deleted_at = now();
        });
    }

    public static function findById(string $id): ?self
    {
        return self::where(self::getKey(), $id)->first();
    }

    public static function findByEmail(string $email): ?self
    {
        return self::where(self::EMAIL_COLUMN, $email)->first();
    }

    public static function findBySlug(string $slug): ?self
    {
        return self::where(self::SLUG_COLUMN, $slug)->first();
    }

    public static function slugExists(string $slug): bool
    {
        return self::where(self::SLUG_COLUMN, $slug)->exists();
    }

    public static function joinAll()
    {
        return self::join((new Website())->getTable(), 'pages.website_id', '=', 'websites.id')
          ->join((new PageLayout())->getTable(), 'pages.page_layout_id', '=', 'page_layouts.id')
          ->select([
            'pages.*',
            'websites.slug as website_slug',
            'websites.name as website_name',
            'page_layouts.slug as page_layout_slug',
            'page_layouts.name as page_layout_name',
            'page_layouts.template_class_name as page_layout_template_class_name',
          ])->get();
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function pageData()
    {
        return $this->hasMany(PageData::class);
    }

    public function versions()
    {
        return $this->hasMany(PageVersion::class);
    }

    public function pageLayout()
    {
        return $this->belongsTo(PageLayout::class);
    }
}
