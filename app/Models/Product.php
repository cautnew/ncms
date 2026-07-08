<?php

namespace App\Models;

use App\Contracts\HasContentBlocks;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $category
 * @property string $price
 * @property string|null $old_price
 * @property string $rating
 * @property int $reviews
 * @property string $image
 * @property string $description
 * @property array<string, string>|null $specs
 */
#[Fillable(['slug', 'name', 'category', 'price', 'old_price', 'rating', 'reviews', 'image', 'description', 'specs'])]
class Product extends Model implements HasContentBlocks
{
    protected function casts(): array
    {
        return [
            'specs' => 'array',
            'reviews' => 'integer',
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
}
