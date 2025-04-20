<?php

namespace App\Models\NCMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GlobalVars extends Model
{
    /** @use HasFactory<\Database\Factories\GlobalVarsFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'description',
        'is_read_only',
        'is_protected',
    ];

    protected $casts = [
        'is_read_only' => 'boolean',
        'is_protected' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically set the user_id when creating a new record
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->user_id = Auth::id();
            }
        });
    }

    public static function find(int $id): ?self
    {
        return self::where('id', '=', $id)->first();
    }

    /**
     * Find the global variable by its name.
     */
    public static function findByName(string $name): ?self
    {
        return self::where('name', '=', $name)->first();
    }
}
