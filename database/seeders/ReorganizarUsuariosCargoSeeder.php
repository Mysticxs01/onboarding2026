<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Cargo;
use App\Models\User;
use App\Models\Area;

class ReorganizarUsuariosCargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * IMPORTANTE: Este seeder sincroniza los datos actuales con la nueva estructura.
     * Ejecutar con: php artisan db:seed --class=ReorganizarUsuariosCargoSeeder
     */
    public function run(): void
    {
        \Log::info('Iniciando reorganización de usuarios y cargos...');

        // 1. Poblamos maestro_cargos con todos los cargos actuales de la tabla cargos
        $cargosTodos = Cargo::all();
        
        foreach ($cargosTodos as $cargo) {
            DB::table('maestro_cargos')->updateOrInsert(
                ['nombre' => $cargo->nombre],
                [
                    'nombre' => $cargo->nombre,
                    'area_id' => $cargo->area_id,
                    'es_puesto_entrada' => true,  // Por defecto, asumir que todos aceptan entrada
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        \Log::info('✅ maestro_cargos poblada con ' . DB::table('maestro_cargos')->count() . ' registros');

        // 2. Mapeo de usuarios actuales a cargos y roles
        $userMap = [
            1 => [
                'name' => 'Administrador Sistema',
                'cargo_nombre' => 'Gerente Talento Humano',
                'rol_onboarding' => 'admin',
                'puede_aprobar' => true,
            ],
            2 => [
                'name' => 'Jefe Talento Humano',
                'cargo_nombre' => 'Gerente Talento Humano',
                'rol_onboarding' => 'jefe_area',
                'puede_aprobar' => true,
            ],
            3 => [
                'name' => 'Coordinador Formación',
                'cargo_nombre' => 'Coordinador de Formación y Capacitación',
                'rol_onboarding' => 'coordinador',
                'puede_aprobar' => true,
            ],
            4 => [
                'name' => 'Root Administrator',
                'cargo_nombre' => 'Gerente Talento Humano',
                'rol_onboarding' => 'admin',
                'puede_aprobar' => true,
            ],
            5 => [
                'name' => 'Admin Onboarding',
                'cargo_nombre' => 'Gerente Talento Humano',
                'rol_onboarding' => 'admin',
                'puede_aprobar' => true,
            ],
            6 => [
                'name' => 'Jefe Tecnología',
                'cargo_nombre' => 'Gerente de TI',
                'rol_onboarding' => 'jefe_area',
                'puede_aprobar' => true,
            ],
            7 => [
                'name' => 'Operador Soporte TI',
                'cargo_nombre' => 'Técnico de Soporte Nivel 1',
                'rol_onboarding' => 'operador',
                'puede_aprobar' => false,
            ],
            8 => [
                'name' => 'Operador Dotación',
                'cargo_nombre' => 'Asistente Administrativo',  // Crear o usar existente
                'rol_onboarding' => 'operador',
                'puede_aprobar' => false,
            ],
            9 => [
                'name' => 'Operador Servicios Generales',
                'cargo_nombre' => 'Supervisor de Servicios Generales',  // Crear o usar existente
                'rol_onboarding' => 'operador',
                'puede_aprobar' => false,
            ],
            10 => [
                'name' => 'Operador Formación',
                'cargo_nombre' => 'Coordinador de Formación y Capacitación',
                'rol_onboarding' => 'operador',
                'puede_aprobar' => false,
            ],
            11 => [
                'name' => 'Operador Bienes',
                'cargo_nombre' => 'Asistente de Inventario',  // Crear o usar existente
                'rol_onboarding' => 'operador',
                'puede_aprobar' => false,
            ],
        ];

        // 3. Crear cargos faltantes si no existen
        $cargosFaltantes = [
            ['nombre' => 'Asistente Administrativo', 'area_id' => 1],
            ['nombre' => 'Supervisor de Servicios Generales', 'area_id' => 2],
            ['nombre' => 'Asistente de Inventario', 'area_id' => 1],
        ];

        foreach ($cargosFaltantes as $cargoData) {
            Cargo::firstOrCreate(
                ['nombre' => $cargoData['nombre']],
                ['area_id' => $cargoData['area_id']]
            );
        }

        \Log::info('✅ Cargos faltantes creados o verificados');

        // 4. Actualizar cada usuario con cargo_id y rol_onboarding
        foreach ($userMap as $userId => $userData) {
            $user = User::find($userId);
            
            if (!$user) {
                \Log::warning("Usuario $userId no encontrado");
                continue;
            }

            // Buscar el cargo por nombre
            $cargo = Cargo::where('nombre', $userData['cargo_nombre'])->first();
            
            if (!$cargo) {
                \Log::warning("Cargo '{$userData['cargo_nombre']}' no encontrado para usuario {$userId}");
                continue;
            }

            // Actualizar usuario
            $user->update([
                'name' => $userData['name'],
                'cargo_id' => $cargo->id,
                'rol_onboarding' => $userData['rol_onboarding'],
                'puede_aprobar_solicitudes' => $userData['puede_aprobar'],
            ]);

            \Log::info("✅ Usuario {$userId} actualizado: {$user->name} -> Cargo: {$cargo->nombre}, Rol: {$userData['rol_onboarding']}");
        }

        // 5. Configurar cargos "de entrada" en la tabla cargos
        // Definir cuáles son puestos donde entran nuevos empleados
        $puestosEntrada = [
            'Asistente Administrativo',
            'Técnico de Soporte Nivel 1',
            'Analista de Atracción de Talento',
            'Facilitador de Aprendizaje Interno',
            'Analista de Crédito',
            'Coordinador de Ventas',
            'Asesor de Servicios',
            // Agregar más según corresponda
        ];

        Cargo::whereIn('nombre', $puestosEntrada)->update(['activo' => true]);
        Cargo::whereNotIn('nombre', $puestosEntrada)->update(['activo' => false]);

        \Log::info('✅ Cargos de entrada configurados');
        \Log::info('✅ ¡Reorganización completada exitosamente!');
    }
}
