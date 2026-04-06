<?php

namespace App\Policies;

use App\Models\User;

class CheckInAccesoPolicy
{
    /**
     * Ver el panel de administración (Solo Admin/Root)
     */
    public function viewCheckInAdmin(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Root']);
    }

    /**
     * Exportar datos de check-in (Solo Admin/Root)
     */
    public function exportCheckIn(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Root']);
    }

    /**
     * Ver estadísticas de área (Solo Admin/Root)
     */
    public function viewAreaStatistics(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Root']);
    }
}
