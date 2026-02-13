<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AuditoriaOnboarding;

class AuditoriaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-auditoria') || 
               $user->hasAnyRole(['Admin', 'Root']);
    }

    public function view(User $user, AuditoriaOnboarding $auditoria): bool
    {
        return $user->hasPermissionTo('view-auditoria') || 
               $user->hasAnyRole(['Admin', 'Root']);
    }

    public function exportar(User $user): bool
    {
        return $user->hasPermissionTo('export-auditoria') || 
               $user->hasAnyRole(['Admin', 'Root']);
    }
}
