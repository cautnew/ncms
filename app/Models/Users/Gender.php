<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    /** @use HasFactory<\Database\Factories\Users\GenderFactory> */
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'symbol'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'int',
            'name' => 'string',
            'symbol' => 'string'
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Gender $gender) {
            $gender->created_by = auth()->user()->id ?? null;
            $gender->updated_by = null;
        });

        static::updating(function (Gender $gender) {
            $gender->updated_by = auth()->user()->id ?? null;
        });
    }

    public function findBySymbol(string $symbol): ?self
    {
        return self::where('symbol', '=', $symbol)->first();
    }
}
