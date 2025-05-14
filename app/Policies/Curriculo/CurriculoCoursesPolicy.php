<?php

namespace App\Policies\Curriculo;

use App\Models\Curriculo\CurriculoCourses;
use App\Models\NCMS\User;
use Illuminate\Auth\Access\Response;

class CurriculoCoursesPolicy
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
    public function view(User $user, CurriculoCourses $curriculoCourses): bool
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
    public function update(User $user, CurriculoCourses $curriculoCourses): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CurriculoCourses $curriculoCourses): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CurriculoCourses $curriculoCourses): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CurriculoCourses $curriculoCourses): bool
    {
        return false;
    }
}
