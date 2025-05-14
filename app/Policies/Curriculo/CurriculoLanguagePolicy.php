<?php

namespace App\Policies\Curriculo;

use App\Models\Curriculo\CurriculoLanguage;
use App\Models\NCMS\User;
use Illuminate\Auth\Access\Response;

class CurriculoLanguagePolicy
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
    public function view(User $user, CurriculoLanguage $curriculoLanguage): bool
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
    public function update(User $user, CurriculoLanguage $curriculoLanguage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CurriculoLanguage $curriculoLanguage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CurriculoLanguage $curriculoLanguage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CurriculoLanguage $curriculoLanguage): bool
    {
        return false;
    }
}
