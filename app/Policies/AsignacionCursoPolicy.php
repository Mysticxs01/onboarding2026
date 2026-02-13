<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AsignacionCurso;

class AsignacionCursoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-asignaciones') || 
               $user->hasAnyRole(['Admin', 'Root', 'Jefe RRHH']);
    }

    public function view(User $user, AsignacionCurso $asignacion): bool
    {
        return $user->hasPermissionTo('view-asignaciones') || 
               $user->hasAnyRole(['Admin', 'Root', 'Jefe RRHH']) ||
               $user->id === $asignacion->procesoIngreso->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-asignaciones') || 
               $user->hasAnyRole(['Admin', 'Root', 'Jefe RRHH']);
    }

    public function createAssignment(User $user): bool
    {
        return $user->hasPermissionTo('create-asignaciones') || 
               $user->hasAnyRole(['Admin', 'Root', 'Jefe RRHH']);
    }

    public function update(User $user, AsignacionCurso $asignacion): bool
    {
        return $user->hasPermissionTo('update-asignaciones') || 
               $user->hasAnyRole(['Admin', 'Root', 'Jefe RRHH']) ||
               $user->hasAnyRole(['Jefe']) && $user->area_id === $asignacion->procesoIngreso->area_id;
    }

    public function delete(User $user, AsignacionCurso $asignacion): bool
    {
        return $user->hasPermissionTo('delete-asignaciones') || 
               $user->hasAnyRole(['Admin', 'Root']);
    }
}
