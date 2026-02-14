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
            AreaCargoSeeder::class,                // Crea áreas, cargos y jefes (Sinergia Financiera)
            RoleSeeder::class,                     // Crea roles y permisos
            CreateDefaultUsersSeeder::class,       // Crea usuarios con roles específicos (NUEVO)
            PlantillaSolicitudSeeder::class,       // Crea plantillas de solicitud
            PuestoTrabajoSeeder::class,            // Crea grid de puestos de trabajo
            ProcesoIngresoSeeder::class,           // Crea procesos de ingreso y solicitudes
            CursoSeeder::class,                    // Crea 31 cursos de Sinergia Financiera
            RutaFormacionSeeder::class,            // Crea rutas de formación
            PermisosFormacionSeeder::class,        // Asigna permisos de formación
        ]);
    }

}


