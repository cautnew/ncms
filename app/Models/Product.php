<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

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
 * @property string|null $usage_text
 * @property string|null $ingredients_text
 */
#[Fillable(['slug', 'name', 'category', 'price', 'old_price', 'rating', 'reviews', 'image', 'description', 'specs', 'usage_text', 'ingredients_text'])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'specs' => 'array',
            'reviews' => 'integer',
        ];
    }
}
