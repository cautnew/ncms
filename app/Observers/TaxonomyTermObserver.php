<?php

namespace App\Observers;

use App\Models\Taxonomy\TaxonomyTerm;
use App\Models\Taxonomy\TaxonomyAuditLog;
use Illuminate\Support\Facades\Auth;

class TaxonomyTermObserver
{
    public function created(TaxonomyTerm $term): void
    {
        $this->log($term, 'created', null, $term->getAttributes());
    }

    public function updated(TaxonomyTerm $term): void
    {
        $this->log($term, 'updated', $term->getOriginal(), $term->getAttributes());
    }

    public function deleted(TaxonomyTerm $term): void
    {
        $this->log($term, 'deleted', $term->getAttributes(), null);
    }

    public function restored(TaxonomyTerm $term): void
    {
        $this->log($term, 'restored', null, $term->getAttributes());
    }

    protected function log(TaxonomyTerm $term, string $action, ?array $old, ?array $new): void
    {
        TaxonomyAuditLog::create([
            'auditable_type' => TaxonomyTerm::class,
            'auditable_id'   => $term->id,
            'action'         => $action,
            'old_values'     => $old,
            'new_values'     => $new,
            'user_id'        => Auth::check() ? Auth::id() : null,
        ]);
    }
}
