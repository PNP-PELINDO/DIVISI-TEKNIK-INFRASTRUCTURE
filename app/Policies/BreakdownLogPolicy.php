<?php

namespace App\Policies;

use App\Models\BreakdownLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BreakdownLogPolicy
{
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
    public function view(User $user, BreakdownLog $breakdownLog): bool
    {
        if ($user->role === 'superadmin') return true;
        return $user->entity_id === optional($breakdownLog->infrastructure)->entity_id;
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
    public function update(User $user, BreakdownLog $breakdownLog): bool
    {
        if ($user->role === 'superadmin') return true;
        return $user->entity_id === optional($breakdownLog->infrastructure)->entity_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BreakdownLog $breakdownLog): bool
    {
        if ($user->role === 'superadmin') return true;
        return $user->entity_id === optional($breakdownLog->infrastructure)->entity_id;
    }
}
