<?php

namespace App\Policies;

use App\Models\User;
use App\Models\RutaFormacion;

class RutaFormacionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-rutas') || 
               $user->hasAnyRole(['Admin', 'Root', 'Jefe RRHH']);
    }

    public function view(User $user, RutaFormacion $ruta): bool
    {
        return $user->hasPermissionTo('view-rutas') || 
               $user->hasAnyRole(['Admin', 'Root', 'Jefe RRHH']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-rutas') || 
               $user->hasAnyRole(['Admin', 'Root', 'Jefe RRHH']);
    }

    public function update(User $user, RutaFormacion $ruta): bool
    {
        return $user->hasPermissionTo('update-rutas') || 
               $user->hasAnyRole(['Admin', 'Root', 'Jefe RRHH']);
    }

    public function delete(User $user, RutaFormacion $ruta): bool
    {
        return $user->hasPermissionTo('delete-rutas') || 
               $user->hasAnyRole(['Admin', 'Root']);
    }
}
