<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar roles y permisos anteriores
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        \DB::table('role_has_permissions')->delete();
        \DB::table('roles')->delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Crear roles nuevos
        $roles = [
            'Root' => 'Usuario maestro del sistema',
            'Jefe RRHH' => 'Jefe de Recursos Humanos - Gestiona todos los procesos',
            'Jefe Tecnología' => 'Jefe de Tecnología - Gestiona solicitudes de Tecnología',
            'Jefe Dotación' => 'Jefe de Dotación - Gestiona solicitudes de Dotación',
            'Jefe Servicios Generales' => 'Jefe de Servicios Generales - Gestiona solicitudes de Servicios Generales',
            'Jefe Bienes y Servicios' => 'Jefe de Bienes y Servicios - Gestiona solicitudes de Bienes',
        ];

        foreach ($roles as $roleName => $description) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Crear permisos
        $permissions = [
            // Procesos de Ingreso
            'ver-procesos',
            'crear-procesos',
            'editar-procesos',
            'cancelar-procesos',
            'ver-historico-procesos',

            // Solicitudes
            'ver-solicitudes',
            'editar-solicitudes',
            'completar-solicitudes',
            'ver-solicitudes-area',

            // Detalles Técnicos
            'especificar-requerimientos-ti',
            'especificar-tallas',
            'validar-solicitudes',

            // Check-in
            'generar-pdf-checkin',
            'confirmar-entrada-activos',
            'ver-checkin',

            // Usuarios
            'gestionar-usuarios',
            'asignar-roles',
            'ver-usuarios',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar permisos a roles
        $rootRole = Role::findByName('Root');
        $rootRole->givePermissionTo(Permission::all());

        // Jefe RRHH - Acceso a todo
        $jefeRRHHRole = Role::findByName('Jefe RRHH');
        $jefeRRHHRole->givePermissionTo([
            'ver-procesos',
            'crear-procesos',
            'editar-procesos',
            'cancelar-procesos',
            'ver-historico-procesos',
            'ver-solicitudes',
            'editar-solicitudes',
            'completar-solicitudes',
            'ver-solicitudes-area',
            'especificar-requerimientos-ti',
            'especificar-tallas',
            'validar-solicitudes',
            'ver-checkin',
            'generar-pdf-checkin',
        ]);

        // Jefe Tecnología - Solo su área
        $jefeTecnologiaRole = Role::findByName('Jefe Tecnología');
        $jefeTecnologiaRole->givePermissionTo([
            'ver-solicitudes',
            'ver-solicitudes-area',
            'editar-solicitudes',
            'completar-solicitudes',
            'ver-checkin',
        ]);

        // Jefe Dotación - Solo su área
        $jefeDotacionRole = Role::findByName('Jefe Dotación');
        $jefeDotacionRole->givePermissionTo([
            'ver-solicitudes',
            'ver-solicitudes-area',
            'editar-solicitudes',
            'completar-solicitudes',
            'ver-checkin',
        ]);

        // Jefe Servicios Generales - Solo su área
        $jefeServiciosRole = Role::findByName('Jefe Servicios Generales');
        $jefeServiciosRole->givePermissionTo([
            'ver-solicitudes',
            'ver-solicitudes-area',
            'editar-solicitudes',
            'completar-solicitudes',
            'ver-checkin',
        ]);

        // Jefe Bienes y Servicios - Solo su área
        $jefeBienesRole = Role::findByName('Jefe Bienes y Servicios');
        $jefeBienesRole->givePermissionTo([
            'ver-solicitudes',
            'ver-solicitudes-area',
            'editar-solicitudes',
            'completar-solicitudes',
            'ver-checkin',
        ]);
    }
}
