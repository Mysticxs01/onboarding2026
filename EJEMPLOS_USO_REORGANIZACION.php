<?php

namespace App\Http\Controllers\Examples;

use App\Models\User;
use App\Models\Cargo;
use App\Models\MaestroCargo;
use App\Models\ProcesoIngreso;

/**
 * EJEMPLOS DE USO - Reorganización de Usuarios y Cargos
 * 
 * Este archivo muestra cómo usar la nueva estructura de datos
 * en tus controladores y servicios.
 * 
 * NO EJECUTAR ESTE ARCHIVO - Es solo referencia
 */
class EjemplosUsoReorganizacion
{
    // =====================================================================
    // EJEMPLO 1: Obtener Aprobadores de Solicitudes
    // =====================================================================

    public function obtenerAprobadoresSolicitud()
    {
        // Todos los usuarios que pueden aprobar
        $aprobadores = User::aprobadores()->get();
        
        // Result:
        // Id | Name | Cargo | Rol
        // 2  | Jefe RRHH | Gerente Talento Humano | jefe_area
        // 3  | Coordinador Formación | Coordinador de Formación | coordinador
        // etc.

        // Aprobadores de un área específica
        $areaId = 20; // Talento Humano
        $aprobadoresArea = User::aprobadores()
            ->where('area_id', $areaId)
            ->get();

        // Aprobadores solo jefes de área
        $jefes = User::jefesArea()->get();

        // Aprobadores solo coordinadores
        $coordinadores = User::coordinadores()->get();

        return response()->json($aprobadores);
    }


    // =====================================================================
    // EJEMPLO 2: Validar si Usuario Puede Aprobar
    // =====================================================================

    public function validarCapacidadAprobacion(User $usuario)
    {
        // Método directo
        if ($usuario->puedeAprobarSolicitudes()) {
            // Mostrar botón de aprobación
            return "El usuario puede aprobar solicitudes";
        }

        // O verificar por rol
        if ($usuario->rol_onboarding === 'jefe_area') {
            // Es jefe de área, puede aprobar todo de su área
            return "Es jefe de área";
        }

        if ($usuario->rol_onboarding === 'coordinador') {
            // Es coordinador, puede aprobar en su especialidad
            return "Es coordinador";
        }

        return "Usuario operativo, no puede aprobar";
    }


    // =====================================================================
    // EJEMPLO 3: Obtener Cargos Disponibles para Nuevo Ingreso
    // =====================================================================

    public function cargosDisponibles()
    {
        // Obtener cargos CON VACANTES
        $cargosVacantes = Cargo::conVacantes()->with('area')->get();

        // Resultado:
        // Id | Nombre | Area | Vacantes
        // 5  | Técnico de Soporte | Soporte Técnico | 2
        // 8  | Analista de Crédito | Riesgo y Crédito | 1
        // etc.

        // O verificar cargo específico
        $cargo = Cargo::find(5);
        if ($cargo->tieneVacantes()) {
            // Desplegar como opción en formulario
            echo "disponible";
        }

        // Obtener cantidad de empleados actuales con este cargo
        $cantidad = $cargo->obtenerCantidadEmpleados();
        $disponibles = $cargo->vacantes_disponibles;
        $porcentajeOcupacion = ($cantidad / ($cantidad + $disponibles)) * 100;

        return [
            'cargos' => $cargosVacantes,
            'estadisticas' => compact('cantidad', 'disponibles', 'porcentajeOcupacion')
        ];
    }


    // =====================================================================
    // EJEMPLO 4: Maestro de Cargos - Referencia Completa
    // =====================================================================

    public function cargosCompleto()
    {
        // Obtener TODOS los cargos (54) de la empresa
        $todosCargos = MaestroCargo::activos()->get();

        // Cargos por nivel jerárquico
        $cargosEntrada = MaestroCargo::puestosEntrada()->get();  // Nivel 1-2
        $cargosTecnicos = MaestroCargo::porNivel(2)->get();  // Especialistas
        $coordin = MaestroCargo::porNivel(3)->get();    // Coordinadores
        $jefes = MaestroCargo::porNivel(4)->get();      // Jefes
        $gerentes = MaestroCargo::porNivel(5)->get();   // Gerencia

        // Cargos por área
        $cargosTI = MaestroCargo::porArea(16)->get();   // Área de TI

        // Ver si un cargo del maestro está activo como puesto de entrada
        $maestroCargo = MaestroCargo::where('nombre', 'Gerente de TI')->first();
        $cargoActivo = $maestroCargo->cargoActivo();  // Busca en tabla cargos

        return compact('todosCargos', 'cargosTecnicos', 'coordinadores', 'jefes', 'gerentes');
    }


    // =====================================================================
    // EJEMPLO 5: Cadena de Mando - Estructura Jerárquica
    // =====================================================================

    public function cadenaMando(User $usuario)
    {
        // Obtener JEFE DIRECTO
        $jefe = $usuario->jefe;
        echo $jefe->name;  // "Jefe Talento Humano"

        // Obtener SUBORDINADOS DIRECTOS
        $subordinados = $usuario->subordinados()->get();

        // Obtener TODOS LOS SUPERVISADOS (directo + indirecto)
        $supervision = $usuario->obtenerSupervisionados();

        // Obtener CADENA COMPLETA (usuario -> jefe -> jefe del jefe)
        $cadena = [];
        $actual = $usuario;
        while ($actual->jefe) {
            $actual = $actual->jefe;
            $cadena[] = $actual->name;
        }

        return [
            'usuario' => $usuario->name,
            'jefe_directo' => $jefe?->name ?? 'Sin jefe',
            'subordinados_directos' => $subordinados,
            'todos_supervisados' => $supervision,
            'cadena_hacia_arriba' => $cadena,
        ];
    }


    // =====================================================================
    // EJEMPLO 6: Asignar Solicitud a Aprobador Correcto
    // =====================================================================

    public function asignarSolicitudAprobador(ProcesoIngreso $solicitud)
    {
        // El nuevo empleado va a trabajar en área específica
        $areaDestino = $solicitud->area_id;

        // Obtener jefe de esa área
        $jefesArea = User::jefesArea()
            ->where('area_id', $areaDestino)
            ->get();

        // Si no hay jefe, obtener coordinador
        if ($jefesArea->isEmpty()) {
            $aprobador = User::coordinadores()
                ->where('area_id', $areaDestino)
                ->first();
        } else {
            $aprobador = $jefesArea->first();
        }

        // Asignar solicitud a aprobador
        $solicitud->aprobado_por_id = $aprobador->id;
        $solicitud->save();

        return $aprobador;
    }


    // =====================================================================
    // EJEMPLO 7: Crear Nuevo Usuario con Cargo y Rol
    // =====================================================================

    public function crearNuevoUsuario()
    {
        // Buscar el cargo
        $cargo = Cargo::where('nombre', 'Técnico de Soporte Nivel 1')->first();

        // Crear usuario asignado a ese cargo
        $usuario = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@sinergia.com',
            'password' => bcrypt('password'),
            'area_id' => $cargo->area_id,
            'cargo_id' => $cargo->id,
            'rol_onboarding' => 'operador',
            'puede_aprobar_solicitudes' => false,
            'jefe_directo_id' => 6, // Jefe de Tecnología
        ]);

        return $usuario;
    }


    // =====================================================================
    // EJEMPLO 8: Actualizar Vacantes Disponibles
    // =====================================================================

    public function procesarNuevoIngreso(ProcesoIngreso $solicitud)
    {
        // El empleado entra a un cargo específico
        $cargo = Cargo::find($solicitud->cargo_id);

        // Reducir vacante disponible
        if ($cargo->vacantes_disponibles > 0) {
            $cargo->decrement('vacantes_disponibles');
        }

        // Si no hay más vacantes, desactivar el cargo
        if ($cargo->vacantes_disponibles === 0) {
            $cargo->update(['activo' => false]);
        }

        return $cargo;
    }


    // =====================================================================
    // EJEMPLO 9: Reporte de Cargos y Empleados
    // =====================================================================

    public function generarReporteCargos()
    {
        // Obtener todos los cargos con información
        $reporteCargos = Cargo::conVacantes()
            ->with('area')
            ->get()
            ->map(function ($cargo) {
                return [
                    'cargo_id' => $cargo->id,
                    'cargo_nombre' => $cargo->nombre,
                    'area' => $cargo->area->nombre,
                    'empleados_actuales' => $cargo->obtenerCantidadEmpleados(),
                    'vacantes_disponibles' => $cargo->vacantes_disponibles,
                    'activo' => $cargo->activo,
                ];
            });

        return $reporteCargos;
    }


    // =====================================================================
    // EJEMPLO 10: Búsqueda Inteligente de Aprobadores
    // =====================================================================

    public function buscarAprobadorOptimo(ProcesoIngreso $solicitud)
    {
        $areaDestino = $solicitud->area_id;
        $cargoDestino = $solicitud->cargo_id;

        // Prioridad 1: Jefe del área
        $aprobador = User::jefesArea()
            ->where('area_id', $areaDestino)
            ->aprobadores()
            ->first();

        // Prioridad 2: Coordinador del área
        if (!$aprobador) {
            $aprobador = User::coordinadores()
                ->where('area_id', $areaDestino)
                ->first();
        }

        // Prioridad 3: Administrador general
        if (!$aprobador) {
            $aprobador = User::administradores()
                ->where('area_id', 20)  // Talento Humano
                ->first();
        }

        return $aprobador;
    }


    // =====================================================================
    // NOTAS IMPORTANTES
    // =====================================================================
    /*
     * 1. RELACIONES DISPONIBLES EN USER
     *    - $usuario->cargo()      // Relación con tabla cargos
     *    - $usuario->area()       // Relación con tabla areas
     *    - $usuario->jefe()       // Usuario que es jefe
     *    - $usuario->subordinados()  // Usuarios que reportan a este
     *
     * 2. SCOPES DISPONIBLES EN USER
     *    - User::aprobadores()    // Pueden aprobar solicitudes
     *    - User::jefesArea()      // Son jefes de área
     *    - User::coordinadores()  // Son coordinadores
     *    - User::administradores()// Son administradores
     *
     * 3. CAMPOS NUEVOS EN USER
     *    - rol_onboarding         // admin|jefe_area|coordinador|revisor|operador
     *    - puede_aprobar_solicitudes // true/false
     *    - jefe_directo_id        // FK a otro usuario
     *
     * 4. CAMPOS NUEVOS EN CARGOS
     *    - vacantes_disponibles   // Int
     *    - activo                 // Boolean (true = aceptan nuevos)
     *    - descripcion            // Text
     *
     * 5. NUEVA TABLA MAESTRO_CARGOS
     *    - Referencia completa de todos los 54 cargos
     *    - No es FK en users, es solo referencia histórica
     *    - Útil para reportería y análisis
     */
}
