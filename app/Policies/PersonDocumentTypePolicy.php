<?php

namespace App\Policies;

use App\Models\User;
use App\Models\User\PersonDocumentType;
use Illuminate\Auth\Access\Response;

class PersonDocumentTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonDocumentType $personDocumentType): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonDocumentType $personDocumentType): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonDocumentType $personDocumentType): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonDocumentType $personDocumentType): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonDocumentType $personDocumentType): bool
    {
        //
    }
}
