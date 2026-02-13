<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class PermisosFormacionSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos para Cursos
        $permisoCursos = [
            'view-cursos',
            'create-cursos',
            'update-cursos',
            'delete-cursos',
        ];

        foreach ($permisoCursos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Crear permisos para Asignaciones
        $permisoAsignaciones = [
            'view-asignaciones',
            'create-asignaciones',
            'update-asignaciones',
            'delete-asignaciones',
        ];

        foreach ($permisoAsignaciones as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Crear permisos para Rutas de Formación
        $permisoRutas = [
            'view-rutas',
            'create-rutas',
            'update-rutas',
            'delete-rutas',
        ];

        foreach ($permisoRutas as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Crear permisos para Reportes
        $permisoReportes = [
            'view-reportes',
            'export-reportes',
        ];

        foreach ($permisoReportes as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Crear permisos para Auditoría
        $permisoAuditoria = [
            'view-auditoria',
            'export-auditoria',
        ];

        foreach ($permisoAuditoria as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Obtener o crear roles existentes
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleRoot = Role::firstOrCreate(['name' => 'Root']);
        $roleJefeRRHH = Role::firstOrCreate(['name' => 'Jefe RRHH']);
        $roleJefe = Role::firstOrCreate(['name' => 'Jefe']);
        $roleOperador = Role::firstOrCreate(['name' => 'Operador']);

        // Asignar permisos a roles
        // Admin: Acceso total a formación y lectura de auditoría
        $roleAdmin->givePermissionTo(
            array_merge($permisoCursos, $permisoAsignaciones, $permisoRutas, $permisoReportes, $permisoAuditoria)
        );

        // Root: Acceso total
        $roleRoot->givePermissionTo(
            array_merge($permisoCursos, $permisoAsignaciones, $permisoRutas, $permisoReportes, $permisoAuditoria)
        );

        // Jefe RRHH: Gestión de cursos, asignaciones, rutas y reportes
        $roleJefeRRHH->givePermissionTo([
            'view-cursos',
            'create-cursos',
            'update-cursos',
            'view-asignaciones',
            'create-asignaciones',
            'update-asignaciones',
            'view-rutas',
            'create-rutas',
            'update-rutas',
            'view-reportes',
            'export-reportes',
            'view-auditoria',
        ]);

        // Jefe: Ver cursos, asignaciones, rutas y reportes
        $roleJefe->givePermissionTo([
            'view-cursos',
            'view-asignaciones',
            'view-rutas',
            'view-reportes',
            'view-auditoria',
        ]);

        // Operador: Solo lectura de cursos y sus asignaciones
        $roleOperador->givePermissionTo([
            'view-cursos',
            'view-asignaciones',
        ]);
    }
}
