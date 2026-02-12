<?php

namespace Database\Seeders;

use App\Models\Puesto;
use Illuminate\Database\Seeder;

class PuestosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un plano de 6 columnas x 5 filas = 30 puestos
        $filas = 5;
        $columnas = 6;
        $numero = 1;

        for ($fila = 1; $fila <= $filas; $fila++) {
            for ($columna = 1; $columna <= $columnas; $columna++) {
                Puesto::create([
                    'numero' => chr(64 + $fila) . $columna, // A1, A2, A3... B1, B2...
                    'fila' => $fila,
                    'columna' => $columna,
                    'estado' => 'Disponible',
                ]);
            }
        }
    }
}
