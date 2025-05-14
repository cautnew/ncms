<?php

namespace App\Policies\Curriculo;

use App\Models\Curriculo\CurriculoImportantLinks;
use App\Models\NCMS\User;
use Illuminate\Auth\Access\Response;

class CurriculoImportantLinksPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CurriculoImportantLinks $curriculoImportantLinks): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CurriculoImportantLinks $curriculoImportantLinks): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CurriculoImportantLinks $curriculoImportantLinks): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CurriculoImportantLinks $curriculoImportantLinks): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CurriculoImportantLinks $curriculoImportantLinks): bool
    {
        return false;
    }
}
