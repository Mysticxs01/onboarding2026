<?php

namespace App\Services;

use App\Models\ProcesoIngreso;
use App\Models\Area;
use App\Models\ReporteCumplimiento;
use App\Models\AsignacionCurso;
use App\Models\Curso;
use Illuminate\Support\Collection;

class ReporteService
{
    /**
     * Generar dashboard ejecutivo
     */
    public function generarDashboardEjecutivo(): array
    {
        $totalProcesos = ProcesoIngreso::count();
        $completados = ProcesoIngreso::where('estado', 'Finalizado')->count();
        $en_progreso = ProcesoIngreso::where('estado', 'En Proceso')->count();
        $pendientes = ProcesoIngreso::where('estado', 'Pendiente')->count();

        $porcentaje_completacion = $totalProcesos > 0 
            ? round(($completados / $totalProcesos) * 100, 2)
            : 0;

        $retrasados = ProcesoIngreso::where('estado', '!=', 'Finalizado')
            ->filter(function ($p) {
                return isset($p->fecha_esperada_finalizacion) && 
                       now()->isAfter($p->fecha_esperada_finalizacion);
            })->count();

        return [
            'total_procesos' => $totalProcesos,
            'procesos_completados' => $completados,
            'procesos_en_progreso' => $en_progreso,
            'procesos_pendientes' => $pendientes,
            'procesos_retrasados' => $retrasados,
            'porcentaje_completacion' => $porcentaje_completacion,
            'dias_promedio' => $this->calcularDiasPromecio(),
        ];
    }

    /**
     * Generar cumplimiento por área
     */
    public function generarCumplimientoPorArea(): Collection
    {
        $areas = Area::with(['procesosIngresos' => function ($q) {
                                $q->select('id', 'area_id', 'estado', 'fecha_ingreso', 'fecha_finalizacion');
                            }])->get();

        return $areas->map(function ($area) {
            ReporteCumplimiento::generarPorArea($area->id);
            
            return [
                'area' => $area->nombre,
                'total_procesos' => $area->procesosIngresos->count(),
                'completados' => $area->procesosIngresos()->where('estado', 'Finalizado')->count(),
                'porcentaje' => $area->procesosIngresos->count() > 0 
                    ? round(($area->procesosIngresos()->where('estado', 'Finalizado')->count() / $area->procesosIngresos->count()) * 100, 2)
                    : 0,
            ];
        });
    }

    /**
     * Generar reporte de formación completada
     */
    public function generarReporteFormacion(): array
    {
        $totalAsignaciones = AsignacionCurso::count();
        $completadas = AsignacionCurso::where('estado', 'Completado')->count();
        $en_progreso = AsignacionCurso::where('estado', 'En Progreso')->count();
        $vencidas = AsignacionCurso::where('estado', 'Vencido')->count();

        $cursosMasAsignados = Curso::withCount(['asignaciones'])
            ->orderBy('asignaciones_count', 'desc')
            ->limit(5)
            ->get();

        return [
            'total_asignaciones' => $totalAsignaciones,
            'completadas' => $completadas,
            'en_progreso' => $en_progreso,
            'vencidas' => $vencidas,
            'porcentaje_completacion' => $totalAsignaciones > 0 
                ? round(($completadas / $totalAsignaciones) * 100, 2)
                : 0,
            'cursos_mas_asignados' => $cursosMasAsignados,
        ];
    }

    /**
     * Generar reporte de costos
     */
    public function generarReporteCostos(): array
    {
        $costoPorArea = Area::with(['procesosIngresos.asignacionesCursos.curso'])
            ->get()
            ->map(function ($area) {
                $costo_total = 0;
                
                foreach ($area->procesosIngresos as $proceso) {
                    foreach ($proceso->asignacionesCursos as $asignacion) {
                        if ($asignacion->estado === 'Completado') {
                            $costo_total += $asignacion->curso->costo;
                        }
                    }
                }

                return [
                    'area' => $area->nombre,
                    'costo_total' => $costo_total,
                    'cantidad_asignaciones' => $area->procesosIngresos()
                        ->selectRaw('COUNT(*)')
                        ->first()
                        ->count,
                ];
            });

        $costo_total_general = $costoPorArea->sum('costo_total');

        return [
            'costo_por_area' => $costoPorArea,
            'costo_total' => $costo_total_general,
            'costo_promedio_por_empleado' => $costo_total_general > 0 
                ? round($costo_total_general / ProcesoIngreso::count(), 2)
                : 0,
        ];
    }

    /**
     * Generar reporte de retención
     */
    public function generarReporteRetencion(): array
    {
        $procesos = ProcesoIngreso::with('cargo')
            ->whereBetween('fecha_ingreso', [
                now()->subMonths(6),
                now()
            ])
            ->get();

        $total = $procesos->count();
        $completados = $procesos->where('estado', 'Finalizado')->count();

        $porCargoId = $procesos->groupBy('cargo_id')->map(function ($grupo) {
            $totalGrupo = $grupo->count();
            $completadosGrupo = $grupo->where('estado', 'Finalizado')->count();

            return [
                'cargo' => $grupo->first()->cargo->nombre,
                'total' => $totalGrupo,
                'completados' => $completadosGrupo,
                'porcentaje' => round(($completadosGrupo / $totalGrupo) * 100, 2),
                'riesgo' => $completadosGrupo < ($totalGrupo * 0.7) ? 'Alto' : 'Bajo',
            ];
        });

        return [
            'total_procesos' => $total,
            'tasa_completacion_6meses' => $total > 0 
                ? round(($completados / $total) * 100, 2)
                : 0,
            'por_cargo' => $porCargoId,
        ];
    }

    /**
     * Generar reporte de auditoría
     */
    public function generarReporteAuditoria($fechaDesde = null, $fechaHasta = null): array
    {
        $query = \App\Models\AuditoriaOnboarding::query();

        if ($fechaDesde) {
            $query->where('created_at', '>=', $fechaDesde);
        }

        if ($fechaHasta) {
            $query->where('created_at', '<=', $fechaHasta . ' 23:59:59');
        }

        $registros = $query->get();

        $porAccion = $registros->groupBy('accion')->map->count();
        $porEntidad = $registros->groupBy('entidad')->map->count();
        $porUsuario = $registros->groupBy('usuario_id')->map->count();

        return [
            'total_operaciones' => $registros->count(),
            'por_accion' => $porAccion,
            'por_entidad' => $porEntidad,
            'por_usuario' => $porUsuario,
            'periodo' => [
                'desde' => $fechaDesde,
                'hasta' => $fechaHasta,
            ],
        ];
    }

    /**
     * Método auxiliar para calcular días promedio
     */
    private function calcularDiasPromecio(): float
    {
        $procesos = ProcesoIngreso::whereNotNull('fecha_finalizacion')->get();
        
        if ($procesos->isEmpty()) {
            return 0;
        }

        $promedio = $procesos->map(function ($p) {
            return $p->fecha_ingreso->diffInDays($p->fecha_finalizacion);
        })->average();

        return round($promedio, 2);
    }
}
