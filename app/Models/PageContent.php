<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $page
 * @property array<string, mixed> $data
 */
#[Fillable(['page', 'data'])]
class PageContent extends Model
{
    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    /**
     * @param  array<string, mixed>  $default
     * @return array<string, mixed>
     */
    public static function section(string $page, string $key, array $default = []): array
    {
        $record = static::query()->where('page', $page)->first();
        $data = $record->data ?? [];

        return array_merge($default, $data[$key] ?? []);
    }
}
