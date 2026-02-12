<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PlantillaSolicitud;
use App\Models\Cargo;
use App\Models\Area;

class PlantillaSolicitudSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Obtener todos los cargos
        $cargos = Cargo::all();
        
        // Obtener todas las áreas
        $areas = Area::all();
        
        // Mapeo de tipos de solicitud con sus días máximos antes de ingreso
        $solicitudTypes = [
            ['tipo' => 'Tecnología', 'dias' => 5],      // Urgente - 5 días antes
            ['tipo' => 'Dotación', 'dias' => 10],       // 10 días antes
            ['tipo' => 'Servicios Generales', 'dias' => 7],  // 7 días antes
            ['tipo' => 'Formación', 'dias' => 3],        // 3 días antes
            ['tipo' => 'Bienes y Servicios', 'dias' => 10],  // 10 días antes
        ];

        // Crear plantillas para cada cargo
        foreach ($cargos as $cargo) {
            foreach ($solicitudTypes as $solicitud) {
                // Determinar el área responsable según el tipo de solicitud
                $areaResponsable = $this->obtenerAreaResponsable($solicitud['tipo']);
                
                PlantillaSolicitud::create([
                    'cargo_id' => $cargo->id,
                    'area_id' => $areaResponsable->id,
                    'tipo_solicitud' => $solicitud['tipo'],
                    'dias_maximos' => $solicitud['dias'],
                ]);
            }
        }
    }

    /**
     * Obtener el área responsable según el tipo de solicitud
     */
    private function obtenerAreaResponsable($tipo)
    {
        $mapeo = [
            'Tecnología' => 'Tecnología',
            'Dotación' => 'Recursos Humanos',
            'Servicios Generales' => 'Servicios Generales',
            'Formación' => 'Formación y Capacitación',
            'Bienes y Servicios' => 'Bienes y Servicios',
        ];

        $nombreArea = $mapeo[$tipo] ?? 'Recursos Humanos';
        
        return Area::where('nombre', $nombreArea)->firstOrFail();
    }
}
