<?php

namespace App\Models\Core\RequestMethods;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RequestMethod extends Model
{
    protected $table = 'request_methods';

    protected $fillable = [
        'name',
        'description',
        'code',
    ];

    protected static function booted(): void
    {
        static::creating(function (RequestMethod $requestMethod) {
            $requestMethod->created_by = auth()->user()->id ?? null;
            $requestMethod->updated_by = auth()->user()->id ?? null;
        });

        static::updating(function (RequestMethod $requestMethod) {
            $requestMethod->updated_by = auth()->user()->id ?? null;
        });
    }

    public function findByCode(string $code): ?self
    {
        return $this->where('code', '=', strtoupper($code))->first();
    }
}
