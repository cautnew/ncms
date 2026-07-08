<?php

namespace App\Models;

use App\Contracts\HasContentBlocks;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $page
 */
#[Fillable(['page'])]
class PageContent extends Model implements HasContentBlocks
{
    public function getRouteKeyName(): string
    {
        return 'page';
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
