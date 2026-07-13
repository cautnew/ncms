<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

abstract class ModelWithPrincipals extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    abstract protected function casts(): array;

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            $currentUserId = Auth::user()->id ?? null;
            if (empty($currentUserId)) {
                throw new \Exception('No authenticated user found for creating a Website.');
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
                throw new \Exception('No authenticated user found for updating a Website.');
            }

            $model->updated_by = $currentUserId;
            $model->updated_at = now();
        });

        static::deleting(function (self $model): void {
            $currentUserId = Auth::user()->id ?? null;
            if (empty($currentUserId)) {
                throw new \Exception('No authenticated user found for deleting a Website.');
            }

            $model->updated_by = $currentUserId;
            $model->deleted_by = $currentUserId;

            $model->updated_at = now();
            $model->deleted_at = now();
        });
    }
}
