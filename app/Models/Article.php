<?php

namespace App\Models;

use App\Contracts\HasContentBlocks;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $excerpt
 * @property string $category
 * @property string $author
 * @property Carbon $published_at
 * @property string $views
 * @property string $image
 */
#[Fillable(['slug', 'title', 'excerpt', 'category', 'author', 'published_at', 'views', 'image'])]
class Article extends Model implements HasContentBlocks
{
    protected function casts(): array
    {
        return [
            'published_at' => 'date:Y-m-d',
        ];
    }

    /**
     * @return MorphMany<ContentBlock, $this>
     *
     * @phpstan-ignore method.childReturnType
     */
    public function blocks(): MorphMany
    {
        return $this->morphMany(ContentBlock::class, 'blockable')->orderBy('order');
    }

    public function displayDate(): string
    {
        static $months = [
            1 => 'jan', 2 => 'fev', 3 => 'mar', 4 => 'abr', 5 => 'mai', 6 => 'jun',
            7 => 'jul', 8 => 'ago', 9 => 'set', 10 => 'out', 11 => 'nov', 12 => 'dez',
        ];

        return sprintf('%02d %s %d', $this->published_at->day, $months[$this->published_at->month], $this->published_at->year);
    }
}
