<?php

namespace App\Http\Controllers;

use App\Models\ProcesoIngreso;
use App\Models\Solicitud;
use App\Models\Area;
use App\Models\ReporteCumplimiento;
use App\Models\Curso;
use App\Models\AsignacionCurso;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function dashboard()
    {
        $this->authorize('view', ReporteCumplimiento::class);

        $totalProcesos = ProcesoIngreso::count();
        $procesosCompletados = ProcesoIngreso::where('estado', 'Finalizado')->count();
        $procesosEnProgreso = ProcesoIngreso::where('estado', 'En Proceso')->count();
        $procesosPendientes = ProcesoIngreso::where('estado', 'Pendiente')->count();

        $porcentajeCompletacion = $totalProcesos > 0 
            ? round(($procesosCompletados / $totalProcesos) * 100, 2)
            : 0;

        // Procesos retrasados
        $procesosRetrasados = ProcesoIngreso::where('estado', '!=', 'Finalizado')
            ->filter(function ($p) {
                return isset($p->fecha_esperada_finalizacion) && 
                       now()->isAfter($p->fecha_esperada_finalizacion);
            })->count();

        // Procesos por área
        $procesos_por_area = ProcesoIngreso::selectRaw('area_id, COUNT(*) as total, 
                                                       SUM(CASE WHEN estado = "Finalizado" THEN 1 ELSE 0 END) as completados')
            ->groupBy('area_id')
            ->with('area')
            ->get();

        return view('reportes.dashboard', [
            'totalProcesos' => $totalProcesos,
            'procesosCompletados' => $procesosCompletados,
            'procesosEnProgreso' => $procesosEnProgreso,
            'procesosPendientes' => $procesosPendientes,
            'porcentajeCompletacion' => $porcentajeCompletacion,
            'procesosRetrasados' => $procesosRetrasados,
            'procesos_por_area' => $procesos_por_area,
        ]);
    }

    public function cumplimientoPorArea(Request $request)
    {
        $this->authorize('view', ReporteCumplimiento::class);

        $areas = Area::all();
        $reportes = [];

        foreach ($areas as $area) {
            ReporteCumplimiento::generarPorArea($area->id);
            
            $reporte = ReporteCumplimiento::where('area_id', $area->id)
                                         ->latest('fecha_reporte')
                                         ->first();

            if ($reporte) {
                $reportes[] = $reporte;
            }
        }

        return view('reportes.cumplimiento-por-area', [
            'reportes' => $reportes,
        ]);
    }

    public function formacionPorCurso()
    {
        $this->authorize('view', ReporteCumplimiento::class);

        $cursos = Curso::withCount(['asignaciones' => function ($q) {
                                        $q->where('estado', 'Completado');
                                    }])
            ->get()
            ->map(function ($curso) {
                return [
                    'nombre' => $curso->nombre,
                    'categoria' => $curso->categoria,
                    'asignadas' => $curso->asignaciones()->count(),
                    'completadas' => $curso->asignaciones()->where('estado', 'Completado')->count(),
                    'tasa_completacion' => $curso->obtenerTasaCompletacion(),
                ];
            });

        return view('reportes.formacion-por-curso', [
            'cursos' => $cursos,
        ]);
    }

    public function asignacionesPendientes()
    {
        $this->authorize('view', ReporteCumplimiento::class);

        $asignaciones = AsignacionCurso::where('estado', '!=', 'Completado')
            ->where('estado', '!=', 'Cancelado')
            ->with('procesoIngreso', 'curso')
            ->orderBy('fecha_limite')
            ->paginate(50);

        return view('reportes.asignaciones-pendientes', [
            'asignaciones' => $asignaciones,
        ]);
    }

    public function retrasosFormacion()
    {
        $this->authorize('view', ReporteCumplimiento::class);

        $retrasos = AsignacionCurso::where('estado', '!=', 'Completado')
            ->where('estado', '!=', 'Cancelado')
            ->where('fecha_limite', '<', now())
            ->with('procesoIngreso', 'curso')
            ->orderBy('fecha_limite')
            ->paginate(50);

        return view('reportes.retrasos-formacion', [
            'retrasos' => $retrasos,
        ]);
    }

    public function costosFormacion()
    {
        $this->authorize('view', ReporteCumplimiento::class);

        $cursos = Curso::with(['asignaciones' => function ($q) {
                                $q->where('estado', 'Completado');
                            }])
            ->get()
            ->map(function ($curso) {
                return [
                    'nombre' => $curso->nombre,
                    'costo_unitario' => $curso->costo,
                    'asignaciones_completadas' => $curso->asignaciones()->where('estado', 'Completado')->count(),
                    'costo_total' => $curso->costo * $curso->asignaciones()->where('estado', 'Completado')->count(),
                ];
            });

        $costoTotal = collect($cursos)->sum('costo_total');

        return view('reportes.costos-formacion', [
            'cursos' => $cursos,
            'costoTotal' => $costoTotal,
        ]);
    }

    public function exportarDatos(Request $request)
    {
        $this->authorize('view', ReporteCumplimiento::class);

        $tipo = $request->tipo ?? 'procesos';

        $datos = match($tipo) {
            'procesos' => ProcesoIngreso::with('area', 'cargo')->get(),
            'asignaciones' => AsignacionCurso::with('curso', 'procesoIngreso')->get(),
            'cursos' => Curso::all(),
            default => [],
        };

        return response()->json($datos);
    }
}
