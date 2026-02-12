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
        // Crear roles
        $roles = [
            'Root' => 'Usuario maestro del sistema',
            'Admin' => 'Administrador de Recursos Humanos',
            'Jefe' => 'Jefe Inmediato',
            'Operador' => 'Operador de Área',
        ];

        foreach ($roles as $roleName => $description) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Crear roles por área (operadores específicos)
        $areas = [
            'Operador Dotación',
            'Operador Tecnología',
            'Operador Servicios Generales',
            'Operador Formación',
            'Operador Bienes y Servicios'
        ];

        foreach ($areas as $roleName) {
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

            // área
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar permisos a roles
        $rootRole = Role::findByName('Root');
        $rootRole->givePermissionTo(Permission::all());

        $adminRole = Role::findByName('Admin');
        $adminRole->givePermissionTo([
            'ver-procesos',
            'crear-procesos',
            'editar-procesos',
            'cancelar-procesos',
            'ver-historico-procesos',
            'ver-solicitudes',
            'ver-usuarios'
        ]);

        $jefeRole = Role::findByName('Jefe');
        $jefeRole->givePermissionTo([
            'ver-procesos',
            'especificar-requerimientos-ti',
            'especificar-tallas',
            'validar-solicitudes',
            'ver-solicitudes'
        ]);

        $operadorRole = Role::findByName('Operador');
        $operadorRole->givePermissionTo([
            'ver-solicitudes-area',
            'editar-solicitudes',
            'completar-solicitudes'
        ]);
    }
}
