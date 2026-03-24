<?php

namespace App\Policies;

use App\Models\Pacient;
use App\Models\User;

class PacientPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('pacients.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pacient $pacient): bool
    {
        return $user->can('pacients.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('pacients.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pacient $pacient): bool
    {
        return $user->can('pacients.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pacient $pacient): bool
    {
        return $user->can('pacients.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pacient $pacient): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pacient $pacient): bool
    {
        return false;
    }
}
