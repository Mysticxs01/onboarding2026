<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaOnboarding;
use App\Models\ProcesoIngreso;
use App\Models\Area;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', AuditoriaOnboarding::class);

        $query = AuditoriaOnboarding::with('usuario');

        if ($request->accion) {
            $query->where('accion', $request->accion);
        }

        if ($request->entidad) {
            $query->where('entidad', $request->entidad);
        }

        if ($request->usuario_id) {
            $query->where('usuario_id', $request->usuario_id);
        }

        if ($request->fecha_desde) {
            $query->where('created_at', '>=', $request->fecha_desde);
        }

        if ($request->fecha_hasta) {
            $query->where('created_at', '<=', $request->fecha_hasta . ' 23:59:59');
        }

        $registros = $query->orderBy('created_at', 'desc')->paginate(25);

        return view('auditoria.index', [
            'registros' => $registros,
            'acciones' => [
                'create' => 'Creación',
                'update' => 'Actualización',
                'delete' => 'Eliminación',
                'view' => 'Visualización',
                'export' => 'Exportación',
                'anular' => 'Anulación',
            ],
        ]);
    }

    public function show(AuditoriaOnboarding $auditoria)
    {
        $this->authorize('view', $auditoria);

        return view('auditoria.show', [
            'registro' => $auditoria,
            'cambios' => $auditoria->obtenerCambios(),
        ]);
    }

    public function porProceso(ProcesoIngreso $proceso)
    {
        $this->authorize('view', AuditoriaOnboarding::class);

        $registros = AuditoriaOnboarding::where('entidad', 'ProcesoIngreso')
                                       ->where('entidad_id', $proceso->id)
                                       ->orWhere(function ($query) use ($proceso) {
                                           $query->where('entidad', 'Solicitud')
                                                 ->whereIn('entidad_id', $proceso->solicitudes()->pluck('id'));
                                       })
                                       ->orderBy('created_at', 'desc')
                                       ->paginate(20);

        return view('auditoria.por-proceso', [
            'proceso' => $proceso,
            'registros' => $registros,
        ]);
    }

    public function exportar(Request $request)
    {
        $this->authorize('view', AuditoriaOnboarding::class);

        $query = AuditoriaOnboarding::query();

        if ($request->fecha_desde) {
            $query->where('created_at', '>=', $request->fecha_desde);
        }

        if ($request->fecha_hasta) {
            $query->where('created_at', '<=', $request->fecha_hasta . ' 23:59:59');
        }

        $registros = $query->get();

        AuditoriaOnboarding::registrarExportacion('AuditoriaOnboarding', $registros->count());

        return response()->json($registros);
    }

    public function reportePorArea(Request $request)
    {
        $this->authorize('view', AuditoriaOnboarding::class);

        $areas = Area::all();
        $reportesPorArea = [];

        foreach ($areas as $area) {
            $registros = AuditoriaOnboarding::where('entidad', 'ProcesoIngreso')
                                           ->whereHas('usuario', function ($query) use ($area) {
                                               $query->where('area_id', $area->id);
                                           })
                                           ->count();

            $reportesPorArea[] = [
                'area' => $area->nombre,
                'cantidad_cambios' => $registros,
            ];
        }

        return view('auditoria.reporte-por-area', [
            'reportes' => $reportesPorArea,
        ]);
    }
}
