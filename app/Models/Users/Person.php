<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Person extends Model
{
    /** @use HasFactory<\Database\Factories\Users\PersonFactory> */
    use HasFactory, HasApiTokens;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'name',
        'middle_name',
        'lastname',
        'birthdate',
        //'created_by'
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
            'user_id' => 'string',
            'created_by' => 'string',
            'name' => 'string',
            'middle_name' => 'string',
            'lastname' => 'string',
            'birthdate' => 'date'
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Model $model) {
            $model->id = (string) Str::uuid();
            if (Auth::check()) {
                $model->created_by = Auth::id();
            } else {
                $model->created_by = $model->user_id;
            }
        });
    }

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically set the user_id when creating a new record
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            } else {
                $model->created_by = $model->user_id;
            }
        });
    }
}
