<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders en orden correcto
        $this->call([
            AreaCargoSeeder::class,           // Crea áreas, cargos y jefes (Sinergia Financiera)
            PlantillaSolicitudSeeder::class,  // Crea plantillas de solicitud
            RoleSeeder::class,                 // Crea roles y permisos
            PuestoTrabajoSeeder::class,        // Crea grid de puestos de trabajo
            ProcesoIngresoSeeder::class,      // Crea procesos de ingreso y solicitudes
            CursoSeeder::class,               // Crea 31 cursos de Sinergia Financiera
            RutaFormacionSeeder::class,       // Crea rutas de formación
            PermisosFormacionSeeder::class,   // Asigna permisos de formación
        ]);

        // Crear usuarios de prueba con diferentes roles
        $this->crearUsuariosPrueba();
    }

    private function crearUsuariosPrueba(): void
    {
        // Root/Admin - Tiene acceso a todo
        User::create([
            'name' => 'Root Admin',
            'email' => 'root@test.com',
            'password' => bcrypt('12345678'),
            'area_id' => 1, // Recursos Humanos
        ])->assignRole('Root');

        // Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@test.com',
            'password' => bcrypt('12345678'),
            'area_id' => 1,
        ])->assignRole('Admin');

        // Jefe de Tecnología (ya existe en AreaCargoSeeder)
        $jefeInfo = User::where('email', 'jefe.tecnologia@example.com')->first();
        if ($jefeInfo) {
            $jefeInfo->assignRole('Jefe');
        } else {
            User::create([
                'name' => 'Jefe de Tecnología',
                'email' => 'jefe.tecnologia@test.com',
                'password' => bcrypt('12345678'),
                'area_id' => 2,
            ])->assignRole('Jefe');
        }

        // Operador de Tecnología
        User::create([
            'name' => 'Operador Tecnología',
            'email' => 'operador.ti@test.com',
            'password' => bcrypt('12345678'),
            'area_id' => 2, // Tecnología
        ])->assignRole('Operador Tecnología');

        // Operador de Dotación
        User::create([
            'name' => 'Operador Dotación',
            'email' => 'operador.dotacion@test.com',
            'password' => bcrypt('12345678'),
            'area_id' => 1, // Recursos Humanos
        ])->assignRole('Operador Dotación');

        // Operador de Servicios Generales
        User::create([
            'name' => 'Operador Servicios',
            'email' => 'operador.servicios@test.com',
            'password' => bcrypt('12345678'),
            'area_id' => 3, // Servicios Generales
        ])->assignRole('Operador Servicios Generales');

        // Operador de Formación
        User::create([
            'name' => 'Operador Formación',
            'email' => 'operador.formacion@test.com',
            'password' => bcrypt('12345678'),
            'area_id' => 4, // Formación y Capacitación
        ])->assignRole('Operador Formación');

        // Operador de Bienes y Servicios
        User::create([
            'name' => 'Operador Bienes',
            'email' => 'operador.bienes@test.com',
            'password' => bcrypt('12345678'),
            'area_id' => 5, // Bienes y Servicios
        ])->assignRole('Operador Bienes y Servicios');
    }
}

