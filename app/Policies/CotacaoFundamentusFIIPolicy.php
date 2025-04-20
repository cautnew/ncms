<?php

namespace App\Policies;

use App\Models\Fin\Cotacoes\CotacaoFundamentusFII;
use App\Models\NCMS\User;
use Illuminate\Auth\Access\Response;

class CotacaoFundamentusFIIPolicy
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
    public function view(User $user, CotacaoFundamentusFII $cotacaoFundamentusFII): bool
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
    public function update(User $user, CotacaoFundamentusFII $cotacaoFundamentusFII): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CotacaoFundamentusFII $cotacaoFundamentusFII): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CotacaoFundamentusFII $cotacaoFundamentusFII): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CotacaoFundamentusFII $cotacaoFundamentusFII): bool
    {
        return false;
    }
}
