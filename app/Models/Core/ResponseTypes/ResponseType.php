<?php

namespace App\Models\Core\ResponseTypes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ResponseType extends Model
{
    protected $table = 'response_types';

    protected $fillable = [
        'id',
        'name',
        'description',
        'code',
    ];

    protected static function booted(): void
    {
        static::creating(function (ResponseType $responseType) {
            $responseType->created_by = auth()->user()->id ?? null;
            $responseType->updated_by = null;
        });

        static::updating(function (ResponseType $responseType) {
            $responseType->updated_by = auth()->user()->id ?? null;
        });
    }

    public function findByCode(string $code): ?self
    {
        return $this->where('code', '=', strtoupper($code))->first();
    }
}
