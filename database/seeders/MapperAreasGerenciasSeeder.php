<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapperAreasGerenciasSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener IDs de gerencias
        $gerencias = DB::table('gerencias')->pluck('id', 'codigo');

        // Mapeo de áreas a gerencias según el organigrama
        $mappings = [
            // Gerencia Administración
            'Servicios Generales' => 'GA',
            'Mantenimiento' => 'GA',
            
            // Gerencia Comercial
            'Ventas y Captación' => 'GC',
            'Gestión de Canales' => 'GC',
            'Marketing y Producto' => 'GC',
            'Servicio al Cliente' => 'GC',
            
            // Gerencia Riesgo y Crédito
            'Análisis de Crédito' => 'GRC',
            'Riesgo Operativo' => 'GRC',
            
            // Gerencia Financiera
            'Tesorería' => 'GF',
            'Contabilidad' => 'GF',
            'Planeación' => 'GF',
            
            // Gerencia TI
            'Infraestructura y Redes' => 'GTI',
            'Desarrollo de Software' => 'GTI',
            'Soporte Técnico' => 'GTI',
            
            // Gerencia Talento Humano
            'Selección y Reclutamiento' => 'GTH',
            'Formación y Capacitación' => 'GTH',
            'Nómina' => 'GTH',
            'Clima Organizacional' => 'GTH',
        ];

        foreach ($mappings as $areaName => $gerenciaCode) {
            DB::table('areas')
                ->where('nombre', $areaName)
                ->update(['gerencia_id' => $gerencias[$gerenciaCode]]);
        }

        $this->command->info('✅ Áreas mapeadas a gerencias correctamente');
    }
}
