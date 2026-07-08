<?php

namespace App\Contracts;

use App\Models\ContentBlock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasContentBlocks
{
    /**
     * @return MorphMany<ContentBlock, Model>
     */
    public function blocks(): MorphMany;
}
