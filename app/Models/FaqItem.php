<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $faq_category_id
 * @property string $question
 * @property string $answer
 * @property int $order
 */
#[Fillable(['faq_category_id', 'question', 'answer', 'order'])]
class FaqItem extends Model
{
    /**
     * @return BelongsTo<FaqCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }
}
