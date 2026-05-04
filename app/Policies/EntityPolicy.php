<?php

namespace App\Policies;

use App\Models\Entity;
use App\Models\User;

class EntityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'superadmin';
    }

    public function view(User $user, Entity $entity): bool
    {
        return $user->role === 'superadmin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'superadmin';
    }

    public function update(User $user, Entity $entity): bool
    {
        return $user->role === 'superadmin';
    }

    public function delete(User $user, Entity $entity): bool
    {
        return $user->role === 'superadmin';
    }
}
