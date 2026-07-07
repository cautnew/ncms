<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
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
 * @property string|null $body
 */
#[Fillable(['slug', 'title', 'excerpt', 'category', 'author', 'published_at', 'views', 'image', 'body'])]
class Article extends Model
{
    protected function casts(): array
    {
        return [
            'published_at' => 'date:Y-m-d',
        ];
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
