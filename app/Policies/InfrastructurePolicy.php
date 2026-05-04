<?php

namespace App\Policies;

use App\Models\Infrastructure;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InfrastructurePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Filtered in controller
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Infrastructure $infrastructure): bool
    {
        if ($user->role === 'superadmin') return true;
        return $user->entity_id === $infrastructure->entity_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Both superadmin and operator can create
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Infrastructure $infrastructure): bool
    {
        if ($user->role === 'superadmin') return true;
        return $user->entity_id === $infrastructure->entity_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Infrastructure $infrastructure): bool
    {
        if ($user->role === 'superadmin') return true;
        return $user->entity_id === $infrastructure->entity_id;
    }
}
