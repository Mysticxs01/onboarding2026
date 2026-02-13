<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Curso;

class CursoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-cursos') || $user->hasAnyRole(['Admin', 'Root']);
    }

    public function view(User $user, Curso $curso): bool
    {
        return $user->hasPermissionTo('view-cursos') || $user->hasAnyRole(['Admin', 'Root']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-cursos') || $user->hasAnyRole(['Admin', 'Root']);
    }

    public function update(User $user, Curso $curso): bool
    {
        return $user->hasPermissionTo('update-cursos') || $user->hasAnyRole(['Admin', 'Root']);
    }

    public function delete(User $user, Curso $curso): bool
    {
        return $user->hasPermissionTo('delete-cursos') || $user->hasAnyRole(['Admin', 'Root']);
    }

    public function restore(User $user, Curso $curso): bool
    {
        return $user->hasAnyRole(['Admin', 'Root']);
    }

    public function forceDelete(User $user, Curso $curso): bool
    {
        return $user->hasAnyRole(['Root']);
    }
}
