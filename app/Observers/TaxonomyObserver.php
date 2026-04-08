<?php

namespace App\Observers;

use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyAuditLog;
use Illuminate\Support\Facades\Auth;

class TaxonomyObserver
{
    public function created(Taxonomy $taxonomy): void
    {
        $this->log($taxonomy, 'created', null, $taxonomy->getAttributes());
    }

    public function updated(Taxonomy $taxonomy): void
    {
        $this->log($taxonomy, 'updated', $taxonomy->getOriginal(), $taxonomy->getAttributes());
    }

    public function deleted(Taxonomy $taxonomy): void
    {
        $this->log($taxonomy, 'deleted', $taxonomy->getAttributes(), null);
    }

    public function restored(Taxonomy $taxonomy): void
    {
        $this->log($taxonomy, 'restored', null, $taxonomy->getAttributes());
    }

    protected function log(Taxonomy $taxonomy, string $action, ?array $old, ?array $new): void
    {
        TaxonomyAuditLog::create([
            'auditable_type' => Taxonomy::class,
            'auditable_id'   => $taxonomy->id,
            'action'         => $action,
            'old_values'     => $old,
            'new_values'     => $new,
            'user_id'        => Auth::check() ? Auth::id() : null,
        ]);
    }
}
