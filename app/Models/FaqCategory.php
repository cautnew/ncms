<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $order
 */
#[Fillable(['name', 'order'])]
class FaqCategory extends Model
{
    /**
     * @return HasMany<FaqItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(FaqItem::class)->orderBy('order');
    }
}
