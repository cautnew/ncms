<?php

namespace App\Models\Taxonomy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxonomyTranslation extends Model
{
    protected $table = 'taxonomy_translations';

    protected $fillable = ['taxonomy_id', 'locale', 'name', 'description'];

    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }
}
