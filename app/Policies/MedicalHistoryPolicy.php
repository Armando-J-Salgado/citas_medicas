<?php

namespace App\Policies;

use App\Models\MedicalHistory;
use App\Models\User;

class MedicalHistoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('medical_histories.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MedicalHistory $medicalHistory): bool
    {
        return $user->can('medical_histories.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('medical_histories.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MedicalHistory $medicalHistory): bool
    {
        return $user->can('medical_histories.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MedicalHistory $medicalHistory): bool
    {
        return $user->can('medical_histories.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MedicalHistory $medicalHistory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MedicalHistory $medicalHistory): bool
    {
        return false;
    }
}
