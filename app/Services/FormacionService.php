<?php

namespace App\Services;

use App\Models\ProcesoIngreso;
use App\Models\Curso;
use App\Models\AsignacionCurso;
use App\Models\RutaFormacion;
use App\Models\AuditoriaOnboarding;
use Illuminate\Notifications\Notifiable;

class FormacionService
{
    /**
     * Obtener cursos sugeridos para un proceso de ingreso basado en cargo
     */
    public function obtenerCursosSugeridos(ProcesoIngreso $proceso): \Illuminate\Database\Eloquent\Collection
    {
        return Curso::query()
            ->where('activo', true)
            ->whereHas('cargos', function ($query) use ($proceso) {
                $query->where('cargo_id', $proceso->cargo_id)
                      ->where('es_obligatorio', true);
            })
            ->get();
    }

    /**
     * Asignar cursos automáticamente al crear un proceso
     */
    public function asignarCursosAutomaticos(ProcesoIngreso $proceso, $diasLimite = 90): int
    {
        $cursosSugeridos = $this->obtenerCursosSugeridos($proceso);
        $asignacionesCreadas = 0;

        foreach ($cursosSugeridos as $curso) {
            $asignacion = AsignacionCurso::create([
                'proceso_ingreso_id' => $proceso->id,
                'curso_id' => $curso->id,
                'fecha_asignacion' => now(),
                'fecha_limite' => now()->addDays($diasLimite),
                'estado' => 'Asignado',
                'asignado_por_id' => auth()->id() ?? null,
            ]);

            AuditoriaOnboarding::registrar('create', 'AsignacionCurso', $asignacion->id, 
                                          'Asignación automática por cargo');

            $asignacionesCreadas++;
        }

        return $asignacionesCreadas;
    }

    /**
     * Obtener progreso de formación de un proceso
     */
    public function obtenerProgresoFormacion(ProcesoIngreso $proceso): array
    {
        $asignaciones = $proceso->asignacionesCursos()->get();
        $total = $asignaciones->count();

        if ($total === 0) {
            return [
                'total' => 0,
                'completadas' => 0,
                'en_progreso' => 0,
                'pendientes' => 0,
                'vencidas' => 0,
                'porcentaje' => 0,
            ];
        }

        $completadas = $asignaciones->where('estado', 'Completado')->count();
        $en_progreso = $asignaciones->where('estado', 'En Progreso')->count();
        $pendientes = $asignaciones->where('estado', 'Asignado')->count();
        $vencidas = $asignaciones->filter(function ($a) {
            return $a->estaAtrasada();
        })->count();

        return [
            'total' => $total,
            'completadas' => $completadas,
            'en_progreso' => $en_progreso,
            'pendientes' => $pendientes,
            'vencidas' => $vencidas,
            'porcentaje' => round(($completadas / $total) * 100, 2),
        ];
    }

    /**
     * Notificar asignación de cursos al empleado
     */
    public function notificarAsignacionCursos(ProcesoIngreso $proceso, array $cursoIds = []): bool
    {
        try {
            $asignaciones = AsignacionCurso::where('proceso_ingreso_id', $proceso->id)
                                           ->when(!empty($cursoIds), function ($q) use ($cursoIds) {
                                               $q->whereIn('curso_id', $cursoIds);
                                           })
                                           ->get();

            if ($asignaciones->isEmpty()) {
                return false;
            }

            $mensaje = "Se te han asignado " . $asignaciones->count() . " cursos de formación.";
            
            // Aquí se integraría con el servicio de notificaciones existente
            // NotificationService::notificarCambioEstado($proceso->email, $mensaje);

            AuditoriaOnboarding::registrar('update', 'ProcesoIngreso', $proceso->id, 
                                          'Notificación de cursos asignados');

            return true;
        } catch (\Exception $e) {
            \Log::error('Error notificando cursos: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Marcar curso como completado
     */
    public function marcarCursoCompletado(AsignacionCurso $asignacion, $calificacion = null, $certificadoUrl = null): bool
    {
        try {
            $asignacion->marcarCompletado($calificacion, $certificadoUrl);

            AuditoriaOnboarding::registrarActualizacion('AsignacionCurso', $asignacion->id,
                                                       ['estado' => 'Asignado'],
                                                       ['estado' => 'Completado']);

            return true;
        } catch (\Exception $e) {
            \Log::error('Error completando curso: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener ruta de formación aplicable al proceso
     */
    public function obtenerRutaAplicable(ProcesoIngreso $proceso): ?RutaFormacion
    {
        return RutaFormacion::where('cargo_id', $proceso->cargo_id)
                           ->orWhere('area_id', $proceso->area_id)
                           ->where('activa', true)
                           ->first();
    }

    /**
     * Asignar ruta completa de formación
     */
    public function asignarRuta(ProcesoIngreso $proceso, RutaFormacion $ruta): int
    {
        $cursos = $ruta->obtenerCursosSecuenciados();
        $asignacionesCreadas = 0;

        foreach ($cursos as $curso) {
            if (AsignacionCurso::where('proceso_ingreso_id', $proceso->id)
                               ->where('curso_id', $curso->id)
                               ->exists()) {
                continue;
            }

            AsignacionCurso::create([
                'proceso_ingreso_id' => $proceso->id,
                'curso_id' => $curso->id,
                'fecha_asignacion' => now(),
                'fecha_limite' => now()->addMonths(3),
                'estado' => 'Asignado',
                'asignado_por_id' => auth()->id() ?? null,
            ]);

            $asignacionesCreadas++;
        }

        return $asignacionesCreadas;
    }

    /**
     * Verificar cursos vencidos y actualizar estado
     */
    public function procesarVencimientos(): int
    {
        $asignacionesVencidas = AsignacionCurso::where('estado', '!=', 'Completado')
                                              ->where('estado', '!=', 'Vencido')
                                              ->where('fecha_limite', '<', now())
                                              ->get();

        $procesados = 0;
        foreach ($asignacionesVencidas as $asignacion) {
            $asignacion->marcarVencido();
            $procesados++;
        }

        return $procesados;
    }

    /**
     * Obtener estadísticas de formación por área
     */
    public function obtenerEstadisticasPorArea($areaId): array
    {
        $procesos = ProcesoIngreso::where('area_id', $areaId)->get();
        $totalAsignaciones = 0;
        $completadas = 0;
        $en_progreso = 0;
        $vencidas = 0;

        foreach ($procesos as $proceso) {
            $asignaciones = $proceso->asignacionesCursos()->get();
            $totalAsignaciones += $asignaciones->count();
            $completadas += $asignaciones->where('estado', 'Completado')->count();
            $en_progreso += $asignaciones->where('estado', 'En Progreso')->count();
            $vencidas += $asignaciones->filter(function ($a) {
                return $a->estaAtrasada();
            })->count();
        }

        return [
            'total_asignaciones' => $totalAsignaciones,
            'completadas' => $completadas,
            'en_progreso' => $en_progreso,
            'vencidas' => $vencidas,
            'porcentaje_completacion' => $totalAsignaciones > 0 
                ? round(($completadas / $totalAsignaciones) * 100, 2)
                : 0,
        ];
    }
}
