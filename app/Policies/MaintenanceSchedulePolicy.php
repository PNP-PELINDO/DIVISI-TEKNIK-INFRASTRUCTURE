<?php

namespace App\Policies;

use App\Models\MaintenanceSchedule;
use App\Models\User;

class MaintenanceSchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, MaintenanceSchedule $maintenanceSchedule): bool
    {
        if ($user->role === 'superadmin') return true;
        return $user->entity_id === $maintenanceSchedule->infrastructure->entity_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, MaintenanceSchedule $maintenanceSchedule): bool
    {
        if ($user->role === 'superadmin') return true;
        return $user->entity_id === $maintenanceSchedule->infrastructure->entity_id;
    }

    public function delete(User $user, MaintenanceSchedule $maintenanceSchedule): bool
    {
        if ($user->role === 'superadmin') return true;
        return $user->entity_id === $maintenanceSchedule->infrastructure->entity_id;
    }
}
