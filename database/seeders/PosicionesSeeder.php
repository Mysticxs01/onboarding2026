<?php

namespace Database\Seeders;

use App\Models\Posicion;
use App\Models\Cargo;
use App\Models\Area;
use App\Models\User;
use Illuminate\Database\Seeder;

class PosicionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear posiciones para cada usuario actual
        $usuarios = User::where('activo', true)->get();
        
        foreach ($usuarios as $usuario) {
            if ($usuario->cargo_id && $usuario->area_id) {
                Posicion::create([
                    'cargo_id' => $usuario->cargo_id,
                    'area_id' => $usuario->area_id,
                    'usuario_id' => $usuario->id,
                    'sucursal' => 'Sede Principal', // Ajustar según necesidad
                    'estado' => 'Ocupada',
                    'fecha_disponible_desde' => $usuario->created_at,
                    'observaciones' => "Posición actual del usuario {$usuario->name}",
                ]);
            }
        }

        // 2. Crear posiciones libres adicionales
        // Configurar cuántas posiciones adicionales por cargo
        $posicionesAdicionales = [
            // Gerencia Administración
            1 => 1, // Gerente Administrativo
            2 => 2, // Coordinador de Servicios Corporativos
            3 => 2, // Auxiliar de Servicios Generales
            4 => 1, // Asistente de Compras
            5 => 1, // Jefe de Infraestructura
            6 => 2, // Técnico de Mantenimiento Locativo
            7 => 1, // Técnico en Climatización
            
            // Gerencia Comercial
            8 => 1,  // Gerente Comercial
            9 => 2,  // Coordinador de Ventas
            10 => 3, // Ejecutivo de Captación
            11 => 3, // Asesor de Crédito Externo
            12 => 1, // Jefe de Canales
            13 => 2, // Coordinador de Sucursales
            14 => 1, // Administrador de Canales Digitales
            15 => 1, // Coordinador de Marketing
            16 => 2, // Analista de Producto
            17 => 1, // Especialista en Comunicación
            18 => 1, // Coordinador de Servicio al Cliente
            19 => 2, // Oficial de Experiencia al Asociado
            20 => 2, // Analista de Fidelización
            
            // Gerencia Riesgo y Crédito
            21 => 1, // Gerente de Riesgos
            22 => 1, // Coordinador de Análisis y Crédito
            23 => 2, // Analista de Crédito Senior
            24 => 1, // Asistente de Verificación
            25 => 2, // Analista de Microcrédito
            26 => 1, // Coordinador de Riesgo Operativo
            27 => 1, // Oficial de Cumplimiento
            28 => 1, // Analista de Riesgo Operacional
            29 => 1, // Auditor de Procesos
            
            // Gerencia Financiera
            30 => 1, // Gerente Financiero
            31 => 1, // Coordinador de Tesorería
            32 => 2, // Analista de Tesorería
            33 => 1, // Coordinador de Contabilidad
            34 => 1, // Analista de Impuestos
            35 => 1, // Asistente Contable
            36 => 1, // Coordinador de Planeación
            37 => 1, // Analista de Estudios Económicos
            
            // Gerencia TI
            38 => 1, // Gerente de TI
            39 => 1, // Coordinador de Infraestructura
            40 => 1, // Administrador de Servidores
            41 => 1, // Coordinador de Desarrollo
            42 => 2, // Desarrollador Full Stack
            43 => 1, // Analista de QA
            44 => 1, // Coordinador de Soporte Técnico
            45 => 3, // Técnico de Soporte Nivel 1
            
            // Gerencia Talento Humano
            46 => 1, // Gerente Talento Humano
            47 => 1, // Coordinador de Selección
            48 => 1, // Analista de Atracción
            49 => 1, // Coordinador de Formación
            50 => 1, // Facilitador de Aprendizaje
            51 => 1, // Coordinador de Nómina
            52 => 1, // Analista de Prestaciones
            53 => 1, // Coordinador de Clima
            54 => 1, // Especialista en Bienestar
        ];

        // Crear posiciones libres adicionales
        foreach ($posicionesAdicionales as $cargoId => $cantidad) {
            $cargo = Cargo::find($cargoId);
            
            if ($cargo) {
                for ($i = 0; $i < $cantidad; $i++) {
                    Posicion::create([
                        'cargo_id' => $cargoId,
                        'area_id' => $cargo->area_id,
                        'usuario_id' => null,
                        'sucursal' => 'Sede Principal',
                        'estado' => 'Libre',
                        'fecha_disponible_desde' => now(),
                        'observaciones' => "Posición disponible para {$cargo->nombre}",
                    ]);
                }
            }
        }

        $this->command->info('Posiciones creadas exitosamente');
    }
}
