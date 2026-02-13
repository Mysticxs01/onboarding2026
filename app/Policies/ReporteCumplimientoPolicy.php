<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ReporteCumplimiento;

class ReporteCumplimientoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-reportes') || 
               $user->hasAnyRole(['Admin', 'Root', 'Jefe RRHH', 'Jefe']);
    }

    public function view(User $user, ReporteCumplimiento $reporte): bool
    {
        return $user->hasPermissionTo('view-reportes') || 
               $user->hasAnyRole(['Admin', 'Root', 'Jefe RRHH']);
    }

    public function exportar(User $user): bool
    {
        return $user->hasPermissionTo('export-reportes') || 
               $user->hasAnyRole(['Admin', 'Root', 'Jefe RRHH']);
    }
}
