<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ReparationRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReparationRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReparationRequest $reparationRequest): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReparationRequest $reparationRequest): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReparationRequest $reparationRequest): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReparationRequest $reparationRequest): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReparationRequest $reparationRequest): bool
    {
        return true;
    }
}
