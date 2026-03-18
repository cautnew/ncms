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

    public function findByCode(string $code): ?self
    {
        return $this->where('code', '=', strtoupper($code))->first();
    }
}
