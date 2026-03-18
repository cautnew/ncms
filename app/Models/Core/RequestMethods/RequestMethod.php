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

    public function findByCode(string $code): ?self
    {
        return $this->where('code', '=', strtoupper($code))->first();
    }
}
