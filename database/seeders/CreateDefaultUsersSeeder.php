<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Area;

class CreateDefaultUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar procesos_ingresos primero (tiene FK a users)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        \DB::table('procesos_ingresos')->delete();
        \DB::table('users')->delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Obtener áreas
        $areas = Area::all()->keyBy('nombre');

        // 1. Usuario ROOT - Acceso total
        $root = User::create([
            'name' => 'Administrador Root',
            'email' => 'root@sinergia.com',
            'password' => Hash::make('root123456'),
            'area_id' => $areas['Administración']->id ?? null,
            'email_verified_at' => now(),
        ]);
        $root->assignRole('Root');

        // 2. Jefe RRHH - Gestiona todo el onboarding
        $jefeRRHH = User::create([
            'name' => 'María González - Jefe RRHH',
            'email' => 'jefe.rrhh@sinergia.com',
            'password' => Hash::make('rrhh123456'),
            'area_id' => $areas['Recursos Humanos']->id ?? null,
            'email_verified_at' => now(),
        ]);
        $jefeRRHH->assignRole('Jefe RRHH');

        // 3. Jefe Tecnología - Solo solicitudes de Tecnología
        $jefeTecnologia = User::create([
            'name' => 'Carlos Rodríguez - Jefe Tecnología',
            'email' => 'jefe.tecnologia@sinergia.com',
            'password' => Hash::make('tech123456'),
            'area_id' => $areas['Tecnología']->id ?? null,
            'email_verified_at' => now(),
        ]);
        $jefeTecnologia->assignRole('Jefe Tecnología');

        // 4. Jefe Dotación - Solo solicitudes de Dotación
        $jefeDotacion = User::create([
            'name' => 'Ana Martínez - Jefe Dotación',
            'email' => 'jefe.dotacion@sinergia.com',
            'password' => Hash::make('dotacion123456'),
            'area_id' => $areas['Dotación']->id ?? null,
            'email_verified_at' => now(),
        ]);
        $jefeDotacion->assignRole('Jefe Dotación');

        // 5. Jefe Servicios Generales - Solo solicitudes de Servicios Generales
        $jefeServiciosGenerales = User::create([
            'name' => 'Juan Pérez - Jefe Servicios Generales',
            'email' => 'jefe.servicios@sinergia.com',
            'password' => Hash::make('servicios123456'),
            'area_id' => $areas['Servicios Generales']->id ?? null,
            'email_verified_at' => now(),
        ]);
        $jefeServiciosGenerales->assignRole('Jefe Servicios Generales');

        // 6. Jefe Bienes y Servicios - Solo solicitudes de Bienes
        $jefeBienes = User::create([
            'name' => 'Patricia López - Jefe Bienes y Servicios',
            'email' => 'jefe.bienes@sinergia.com',
            'password' => Hash::make('bienes123456'),
            'area_id' => $areas['Administración']->id ?? null,
            'email_verified_at' => now(),
        ]);
        $jefeBienes->assignRole('Jefe Bienes y Servicios');

        // Datos de prueba - Empleados de ejemplo para crear procesos
        $jefeVentas = User::create([
            'name' => 'Roberto Sánchez - Jefe Ventas',
            'email' => 'jefe.ventas@sinergia.com',
            'password' => Hash::make('ventas123456'),
            'area_id' => $areas['Ventas']->id ?? null,
            'email_verified_at' => now(),
        ]);

        $jefeCapacitacion = User::create([
            'name' => 'Laura Torres - Jefe Capacitación',
            'email' => 'jefe.capacitacion@sinergia.com',
            'password' => Hash::make('capacitacion123456'),
            'area_id' => $areas['Capacitación']->id ?? null,
            'email_verified_at' => now(),
        ]);

        // Salida en consola
        $this->command->info('✅ Usuarios creados exitosamente:');
        $this->command->info('');
        $this->command->info('🔐 USUARIOS CON PERMISOS ESPECIALES:');
        $this->command->info('-----');
        $this->command->info('Root (Acceso Total):');
        $this->command->info('  Email: root@sinergia.com');
        $this->command->info('  Password: root123456');
        $this->command->info('  Permisos: TODO');
        $this->command->info('');
        $this->command->info('Jefe RRHH (Gestiona Onboarding):');
        $this->command->info('  Email: jefe.rrhh@sinergia.com');
        $this->command->info('  Password: rrhh123456');
        $this->command->info('  Permisos: Ver todas las solicitudes, Crear procesos, Asignar cursos');
        $this->command->info('');
        $this->command->info('Jefe Tecnología (Solo solicitudes de Tecnología):');
        $this->command->info('  Email: jefe.tecnologia@sinergia.com');
        $this->command->info('  Password: tech123456');
        $this->command->info('  Permisos: Ver/Editar SOLO tipo \'Tecnología\'');
        $this->command->info('');
        $this->command->info('Jefe Dotación (Solo solicitudes de Dotación):');
        $this->command->info('  Email: jefe.dotacion@sinergia.com');
        $this->command->info('  Password: dotacion123456');
        $this->command->info('  Permisos: Ver/Editar SOLO tipo \'Dotación\'');
        $this->command->info('');
        $this->command->info('Jefe Servicios Generales (Solo solicitudes de Servicios Generales):');
        $this->command->info('  Email: jefe.servicios@sinergia.com');
        $this->command->info('  Password: servicios123456');
        $this->command->info('  Permisos: Ver/Editar SOLO tipo \'Servicios Generales\'');
        $this->command->info('');
        $this->command->info('Jefe Bienes y Servicios (Solo solicitudes de Bienes):');
        $this->command->info('  Email: jefe.bienes@sinergia.com');
        $this->command->info('  Password: bienes123456');
        $this->command->info('  Permisos: Ver/Editar SOLO tipo \'Bienes\'');
        $this->command->info('');
        $this->command->info('-----');
        $this->command->info('');
        $this->command->line('Los usuarios están listos para usar.');
    }
}

