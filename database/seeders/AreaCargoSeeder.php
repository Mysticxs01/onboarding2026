<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Cargo;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AreaCargoSeeder extends Seeder
{
    public function run()
    {
        // ==================== GERENCIA ADMINISTRACIÓN ====================
        $gerenciaAdmin = Area::create(['nombre' => 'Gerencia Administración']);
        
        $servicGen = Area::create(['nombre' => 'Servicios Generales']);
        $mantenimiento = Area::create(['nombre' => 'Mantenimiento']);

        Cargo::create(['nombre' => 'Gerente Administrativo', 'area_id' => $gerenciaAdmin->id]);
        Cargo::create(['nombre' => 'Coordinador de Servicios Corporativos', 'area_id' => $servicGen->id]);
        Cargo::create(['nombre' => 'Auxiliar de Servicios Generales y Cafetería', 'area_id' => $servicGen->id]);
        Cargo::create(['nombre' => 'Asistente de Compras e Inventario', 'area_id' => $servicGen->id]);
        Cargo::create(['nombre' => 'Jefe de Infraestructura y Mantenimiento', 'area_id' => $mantenimiento->id]);
        Cargo::create(['nombre' => 'Técnico de Mantenimiento Locativo', 'area_id' => $mantenimiento->id]);
        Cargo::create(['nombre' => 'Técnico en Climatización y Electricidad', 'area_id' => $mantenimiento->id]);

        // ==================== GERENCIA COMERCIAL ====================
        $gerenciaComercial = Area::create(['nombre' => 'Gerencia Comercial']);
        
        $ventasCapt = Area::create(['nombre' => 'Ventas y Captación']);
        $canales = Area::create(['nombre' => 'Gestión de Canales']);
        $marketing = Area::create(['nombre' => 'Marketing y Producto']);
        $servCliente = Area::create(['nombre' => 'Servicio al Cliente']);

        Cargo::create(['nombre' => 'Gerente Comercial', 'area_id' => $gerenciaComercial->id]);
        Cargo::create(['nombre' => 'Coordinador de Ventas y Captación', 'area_id' => $ventasCapt->id]);
        Cargo::create(['nombre' => 'Ejecutivo de Captación y Colocación', 'area_id' => $ventasCapt->id]);
        Cargo::create(['nombre' => 'Asesor de Crédito Externo', 'area_id' => $ventasCapt->id]);
        Cargo::create(['nombre' => 'Jefe de Canales', 'area_id' => $canales->id]);
        Cargo::create(['nombre' => 'Coordinador de Sucursales', 'area_id' => $canales->id]);
        Cargo::create(['nombre' => 'Administrador de Canales Digitales', 'area_id' => $canales->id]);
        Cargo::create(['nombre' => 'Coordinador de Marketing y Producto', 'area_id' => $marketing->id]);
        Cargo::create(['nombre' => 'Analista de Producto', 'area_id' => $marketing->id]);
        Cargo::create(['nombre' => 'Especialista en Comunicación y Marca', 'area_id' => $marketing->id]);
        Cargo::create(['nombre' => 'Coordinador de Servicio al Cliente', 'area_id' => $servCliente->id]);
        Cargo::create(['nombre' => 'Oficial de Experiencia al Asociado', 'area_id' => $servCliente->id]);
        Cargo::create(['nombre' => 'Analista de Fidelización y Retención', 'area_id' => $servCliente->id]);

        // ==================== GERENCIA RIESGO Y CRÉDITO ====================
        $gerenciaRiesgo = Area::create(['nombre' => 'Gerencia Riesgo y Crédito']);
        
        $analisisCredito = Area::create(['nombre' => 'Análisis de Crédito']);
        $riesgoOper = Area::create(['nombre' => 'Riesgo Operativo']);

        Cargo::create(['nombre' => 'Gerente de Riesgos', 'area_id' => $gerenciaRiesgo->id]);
        Cargo::create(['nombre' => 'Coordinador de Análisis y Crédito', 'area_id' => $analisisCredito->id]);
        Cargo::create(['nombre' => 'Analista de Crédito Senior', 'area_id' => $analisisCredito->id]);
        Cargo::create(['nombre' => 'Asistente de Verificación y Garantías', 'area_id' => $analisisCredito->id]);
        Cargo::create(['nombre' => 'Analista de Microcrédito y Terreno', 'area_id' => $analisisCredito->id]);
        Cargo::create(['nombre' => 'Coordinador de Riesgo Operativo', 'area_id' => $riesgoOper->id]);
        Cargo::create(['nombre' => 'Oficial de Cumplimiento (SARLAFT)', 'area_id' => $riesgoOper->id]);
        Cargo::create(['nombre' => 'Analista de Riesgo Operacional', 'area_id' => $riesgoOper->id]);
        Cargo::create(['nombre' => 'Auditor de Procesos Crediticios', 'area_id' => $riesgoOper->id]);

        // ==================== GERENCIA FINANCIERA ====================
        $gerenciaFinanciera = Area::create(['nombre' => 'Gerencia Financiera']);
        
        $tesoreria = Area::create(['nombre' => 'Tesorería']);
        $contabilidad = Area::create(['nombre' => 'Contabilidad']);
        $planeacion = Area::create(['nombre' => 'Planeación']);

        Cargo::create(['nombre' => 'Gerente Financiero', 'area_id' => $gerenciaFinanciera->id]);
        Cargo::create(['nombre' => 'Coordinador de Tesorería', 'area_id' => $tesoreria->id]);
        Cargo::create(['nombre' => 'Analista de Tesorería y Pagos', 'area_id' => $tesoreria->id]);
        Cargo::create(['nombre' => 'Coordinador de Contabilidad', 'area_id' => $contabilidad->id]);
        Cargo::create(['nombre' => 'Analista de Impuestos y Costos', 'area_id' => $contabilidad->id]);
        Cargo::create(['nombre' => 'Asistente Contable', 'area_id' => $contabilidad->id]);
        Cargo::create(['nombre' => 'Coordinador de Planeación', 'area_id' => $planeacion->id]);
        Cargo::create(['nombre' => 'Analista de Estudios Económicos', 'area_id' => $planeacion->id]);

        // ==================== GERENCIA TI ====================
        $gerenciaTI = Area::create(['nombre' => 'Gerencia TI']);
        
        $infraestructura = Area::create(['nombre' => 'Infraestructura y Redes']);
        $desarrollo = Area::create(['nombre' => 'Desarrollo de Software']);
        $soporte = Area::create(['nombre' => 'Soporte Técnico']);

        Cargo::create(['nombre' => 'Gerente de TI', 'area_id' => $gerenciaTI->id]);
        Cargo::create(['nombre' => 'Coordinador de Infraestructura y Redes', 'area_id' => $infraestructura->id]);
        Cargo::create(['nombre' => 'Administrador de Servidores y Nube', 'area_id' => $infraestructura->id]);
        Cargo::create(['nombre' => 'Coordinador de Desarrollo de Software', 'area_id' => $desarrollo->id]);
        Cargo::create(['nombre' => 'Desarrollador Full Stack', 'area_id' => $desarrollo->id]);
        Cargo::create(['nombre' => 'Analista de QA (Aseguramiento de Calidad)', 'area_id' => $desarrollo->id]);
        Cargo::create(['nombre' => 'Coordinador de Soporte Técnico', 'area_id' => $soporte->id]);
        Cargo::create(['nombre' => 'Técnico de Soporte Nivel 1', 'area_id' => $soporte->id]);

        // ==================== GERENCIA TALENTO HUMANO ====================
        $gerenciaTH = Area::create(['nombre' => 'Gerencia de Talento Humano']);
        
        $seleccion = Area::create(['nombre' => 'Selección y Reclutamiento']);
        $formacion = Area::create(['nombre' => 'Formación y Capacitación']);
        $nomina = Area::create(['nombre' => 'Nómina']);
        $clima = Area::create(['nombre' => 'Clima Organizacional']);

        Cargo::create(['nombre' => 'Gerente Talento Humano', 'area_id' => $gerenciaTH->id]);
        Cargo::create(['nombre' => 'Coordinador de Selección y Reclutamiento', 'area_id' => $seleccion->id]);
        Cargo::create(['nombre' => 'Analista de Atracción de Talento', 'area_id' => $seleccion->id]);
        Cargo::create(['nombre' => 'Coordinador de Formación y Capacitación', 'area_id' => $formacion->id]);
        Cargo::create(['nombre' => 'Facilitador de Aprendizaje Interno', 'area_id' => $formacion->id]);
        Cargo::create(['nombre' => 'Coordinador de Nómina y Compensación', 'area_id' => $nomina->id]);
        Cargo::create(['nombre' => 'Analista de Prestaciones y Seguridad Social', 'area_id' => $nomina->id]);
        Cargo::create(['nombre' => 'Coordinador de Clima Organizacional', 'area_id' => $clima->id]);
        Cargo::create(['nombre' => 'Especialista en Bienestar y Cultura', 'area_id' => $clima->id]);

        // ==================== USUARIOS DE PRUEBA ====================
        User::firstOrCreate(
            ['email' => 'admin@sinergia.com'],
            ['name' => 'Administrador', 'password' => Hash::make('password'), 'area_id' => $gerenciaTH->id]
        );

        User::firstOrCreate(
            ['email' => 'jefe.rrhh@sinergia.com'],
            ['name' => 'Jefe de RRHH', 'password' => Hash::make('password'), 'area_id' => $gerenciaTH->id]
        );

        User::firstOrCreate(
            ['email' => 'coordinador.formacion@sinergia.com'],
            ['name' => 'Coordinador de Formación', 'password' => Hash::make('password'), 'area_id' => $formacion->id]
        );
    }
}