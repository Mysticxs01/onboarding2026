<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Cargo;
use App\Models\Area;
use App\Models\Gerencia;

class CargoHierarchySeeder extends Seeder
{
    public function run(): void
    {
        $gerenciaGeneral = Gerencia::firstOrCreate(
            ['nombre' => 'Gerencia General'],
            ['codigo' => 'GG', 'descripcion' => 'Direccion General', 'activo' => true]
        );

        $areaGeneral = Area::firstOrCreate(
            ['nombre' => 'Gerencia General'],
            ['gerencia_id' => $gerenciaGeneral->id, 'descripcion' => 'Direccion General', 'activo' => true]
        );

        Cargo::firstOrCreate(
            ['nombre' => 'Gerente General'],
            ['area_id' => $areaGeneral->id, 'activo' => true]
        );

        $map = [
            // Gerencia General
            'Gerente Administrativo' => 'Gerente General',
            'Gerente Comercial' => 'Gerente General',
            'Gerente de Riesgos' => 'Gerente General',
            'Gerente Financiero' => 'Gerente General',
            'Gerente de TI' => 'Gerente General',
            'Gerente Talento Humano' => 'Gerente General',

            // Gerencia Administrativa
            'Coordinador de Servicios Corporativos' => 'Gerente Administrativo',
            'Auxiliar de Servicios Generales y Cafetería' => 'Coordinador de Servicios Corporativos',
            'Asistente de Compras e Inventario' => 'Coordinador de Servicios Corporativos',
            'Jefe de Infraestructura y Mantenimiento' => 'Gerente Administrativo',
            'Técnico de Mantenimiento Locativo' => 'Jefe de Infraestructura y Mantenimiento',
            'Técnico en Climatización y Electricidad' => 'Jefe de Infraestructura y Mantenimiento',

            // Gerencia Comercial - Ventas y Captación
            'Coordinador de Ventas y Captación' => 'Gerente Comercial',
            'Ejecutivo de Captación y Colocación' => 'Coordinador de Ventas y Captación',
            'Asesor de Crédito Externo' => 'Coordinador de Ventas y Captación',

            // Gerencia Comercial - Gestión de Canales
            'Jefe de Canales' => 'Gerente Comercial',
            'Coordinador de Sucursales' => 'Jefe de Canales',
            'Administrador de Canales Digitales' => 'Jefe de Canales',

            // Gerencia Comercial - Marketing y Producto
            'Coordinador de Marketing y Producto' => 'Gerente Comercial',
            'Analista de Producto' => 'Coordinador de Marketing y Producto',
            'Especialista en Comunicación y Marca' => 'Coordinador de Marketing y Producto',

            // Gerencia Comercial - Servicio al Cliente
            'Coordinador de Servicio al Cliente' => 'Gerente Comercial',
            'Oficial de Experiencia al Asociado' => 'Coordinador de Servicio al Cliente',
            'Analista de Fidelización y Retención' => 'Coordinador de Servicio al Cliente',

            // Gerencia de Riesgo y Crédito - Análisis de Crédito
            'Coordinador de Análisis y Crédito' => 'Gerente de Riesgos',
            'Analista de Crédito Senior' => 'Coordinador de Análisis y Crédito',
            'Asistente de Verificación y Garantías' => 'Coordinador de Análisis y Crédito',
            'Analista de Microcrédito y Terreno' => 'Coordinador de Análisis y Crédito',

            // Gerencia de Riesgo y Crédito - Riesgo Operativo
            'Coordinador de Riesgo Operativo' => 'Gerente de Riesgos',
            'Oficial de Cumplimiento (SARLAFT)' => 'Coordinador de Riesgo Operativo',
            'Analista de Riesgo Operacional' => 'Coordinador de Riesgo Operativo',
            'Auditor de Procesos Crediticios' => 'Coordinador de Riesgo Operativo',

            // Gerencia Financiera
            'Coordinador de Tesorería' => 'Gerente Financiero',
            'Analista de Tesorería y Pagos' => 'Coordinador de Tesorería',
            'Coordinador de Contabilidad' => 'Gerente Financiero',
            'Analista de Impuestos y Costos' => 'Coordinador de Contabilidad',
            'Asistente Contable' => 'Coordinador de Contabilidad',
            'Coordinador de Planeación' => 'Gerente Financiero',
            'Analista de Estudios Económicos' => 'Coordinador de Planeación',

            // Gerencia de TI
            'Coordinador de Infraestructura y Redes' => 'Gerente de TI',
            'Administrador de Servidores y Nube' => 'Coordinador de Infraestructura y Redes',
            'Coordinador de Desarrollo de Software' => 'Gerente de TI',
            'Desarrollador Full Stack' => 'Coordinador de Desarrollo de Software',
            'Analista de QA (Aseguramiento de Calidad)' => 'Coordinador de Desarrollo de Software',
            'Coordinador de Soporte Técnico' => 'Gerente de TI',
            'Técnico de Soporte Nivel 1' => 'Coordinador de Soporte Técnico',

            // Gerencia de Talento Humano
            'Coordinador de Selección y Reclutamiento' => 'Gerente Talento Humano',
            'Analista de Atracción de Talento' => 'Coordinador de Selección y Reclutamiento',
            'Coordinador de Formación y Capacitación' => 'Gerente Talento Humano',
            'Facilitador de Aprendizaje Interno' => 'Coordinador de Formación y Capacitación',
            'Coordinador de Nómina y Compensación' => 'Gerente Talento Humano',
            'Analista de Prestaciones y Seguridad Social' => 'Coordinador de Nómina y Compensación',
            'Coordinador de Clima Organizacional' => 'Gerente Talento Humano',
            'Especialista en Bienestar y Cultura' => 'Coordinador de Clima Organizacional',
        ];

        $allNames = array_unique(array_merge(array_keys($map), array_values($map), ['Gerente General']));
        $cargos = Cargo::whereIn('nombre', $allNames)->get()->keyBy('nombre');

        foreach ($map as $cargoName => $jefeName) {
            $cargo = $cargos->get($cargoName);
            $jefe = $cargos->get($jefeName);

            if (! $cargo || ! $jefe) {
                continue;
            }

            $cargo->update([
                'jefe_inmediato_cargo_id' => $jefe->id,
            ]);
        }

        $cargosNoPermitidos = Cargo::whereNotIn('nombre', $allNames)->get();

        foreach ($cargosNoPermitidos as $cargo) {
            $tieneUsuarios = $cargo->users()->exists();
            $tieneProcesos = DB::table('procesos_ingresos')->where('cargo_id', $cargo->id)->exists();
            $esJefe = DB::table('procesos_ingresos')->where('jefe_cargo_id', $cargo->id)->exists();

            if ($tieneUsuarios || $tieneProcesos || $esJefe) {
                continue;
            }

            $cargo->delete();
        }

        DB::table('procesos_ingresos')
            ->whereNull('jefe_cargo_id')
            ->update([
                'jefe_cargo_id' => DB::raw('(SELECT jefe_inmediato_cargo_id FROM cargos WHERE cargos.id = procesos_ingresos.cargo_id)')
            ]);
    }
}
