<?php

namespace App\Models\Taxonomy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxonomyTermTranslation extends Model
{
    protected $table = 'taxonomy_term_translations';

    protected $fillable = ['taxonomy_term_id', 'locale', 'name', 'description'];

    public function term(): BelongsTo
    {
        return $this->belongsTo(TaxonomyTerm::class, 'taxonomy_term_id');
    }
}
