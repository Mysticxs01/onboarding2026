<?php

namespace Database\Seeders;

use App\Models\PuestoTrabajo;
use Illuminate\Database\Seeder;

class PuestoTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PISO 1 - PLANTA BAJA
        // Sector Operaciones
        for ($i = 1; $i <= 6; $i++) {
            PuestoTrabajo::create([
                'numero_puesto' => "1-OPS-{$i}",
                'piso' => 1,
                'seccion' => 'Operaciones',
                'ubicacion_x' => 100 + ($i * 60),
                'ubicacion_y' => 100,
                'equipamiento' => json_encode(['Computadora', 'Monitor', 'Teléfono']),
                'estado' => 'Disponible',
            ]);
        }

        // Sector Administrativo
        for ($i = 1; $i <= 4; $i++) {
            PuestoTrabajo::create([
                'numero_puesto' => "1-ADM-{$i}",
                'piso' => 1,
                'seccion' => 'Administrativo',
                'ubicacion_x' => 100 + ($i * 60),
                'ubicacion_y' => 200,
                'equipamiento' => json_encode(['Computadora', 'Monitor', 'Teléfono', 'Impresora']),
                'estado' => 'Disponible',
            ]);
        }

        // Sector Atención al Cliente
        for ($i = 1; $i <= 3; $i++) {
            PuestoTrabajo::create([
                'numero_puesto' => "1-AAC-{$i}",
                'piso' => 1,
                'seccion' => 'Atención al Cliente',
                'ubicacion_x' => 100 + ($i * 60),
                'ubicacion_y' => 300,
                'equipamiento' => json_encode(['Computadora', 'Monitor', 'Teléfono', 'Headset']),
                'estado' => 'Disponible',
            ]);
        }

        // PISO 2 - OFICINAS GERENCIALES
        // Gerencias
        for ($i = 1; $i <= 3; $i++) {
            PuestoTrabajo::create([
                'numero_puesto' => "2-GER-{$i}",
                'piso' => 2,
                'seccion' => 'Gerencia',
                'ubicacion_x' => 100 + ($i * 100),
                'ubicacion_y' => 100,
                'equipamiento' => json_encode(['Computadora', 'Monitor Dual', 'Teléfono IP', 'Impresora Láser']),
                'estado' => 'Disponible',
            ]);
        }

        // Coordinadores
        for ($i = 1; $i <= 5; $i++) {
            PuestoTrabajo::create([
                'numero_puesto' => "2-COORD-{$i}",
                'piso' => 2,
                'seccion' => 'Coordinación',
                'ubicacion_x' => 100 + ($i * 70),
                'ubicacion_y' => 250,
                'equipamiento' => json_encode(['Computadora', 'Monitor', 'Teléfono']),
                'estado' => 'Disponible',
            ]);
        }

        // PISO 3 - DESARROLLO TI
        // Developers
        for ($i = 1; $i <= 8; $i++) {
            PuestoTrabajo::create([
                'numero_puesto' => "3-DEV-{$i}",
                'piso' => 3,
                'seccion' => 'Desarrollo',
                'ubicacion_x' => 100 + ($i * 50),
                'ubicacion_y' => 100,
                'equipamiento' => json_encode(['Computadora High-end', '2x Monitor Ultrawide', 'Teclado Mecánico', 'Mouse Gamer']),
                'estado' => 'Disponible',
            ]);
        }

        // QA
        for ($i = 1; $i <= 3; $i++) {
            PuestoTrabajo::create([
                'numero_puesto' => "3-QA-{$i}",
                'piso' => 3,
                'seccion' => 'QA',
                'ubicacion_x' => 100 + ($i * 60),
                'ubicacion_y' => 250,
                'equipamiento' => json_encode(['Computadora', 'Monitor Dual', 'Teléfono']),
                'estado' => 'Disponible',
            ]);
        }

        // PISO 4 - REUNIONES Y COLABORACIÓN
        // Salas de Reunión (Puesto de trabajo temporal)
        for ($i = 1; $i <= 6; $i++) {
            PuestoTrabajo::create([
                'numero_puesto' => "4-SALA-{$i}",
                'piso' => 4,
                'seccion' => 'Salas',
                'ubicacion_x' => 100 + ($i * 80),
                'ubicacion_y' => 100,
                'equipamiento' => json_encode(['Proyector', 'Pantalla', 'Teléfono Conferencia']),
                'estado' => 'Disponible',
            ]);
        }

        // Puestos Colaborativos
        for ($i = 1; $i <= 10; $i++) {
            PuestoTrabajo::create([
                'numero_puesto' => "4-COLABO-{$i}",
                'piso' => 4,
                'seccion' => 'Colaboración',
                'ubicacion_x' => 100 + ($i * 40),
                'ubicacion_y' => 250,
                'equipamiento' => json_encode(['Computadora Portátil', 'Monitor Portátil', 'Conexión WiFi']),
                'estado' => 'Disponible',
            ]);
        }

        \Log::info('Se crearon ' . PuestoTrabajo::count() . ' puestos de trabajo');
    }
}
