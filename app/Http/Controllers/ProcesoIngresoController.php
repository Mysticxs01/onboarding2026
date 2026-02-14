<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcesoIngreso;
use App\Models\Cargo;
use App\Models\User;
use App\Models\Gerencia;
use App\Models\PlantillaSolicitud;
use App\Models\Solicitud;
use Carbon\Carbon;

class ProcesoIngresoController extends Controller
{
    public function index()
    {
        $procesos = ProcesoIngreso::with('cargo', 'area')
            ->latest()
            ->get();

        return view('procesos_ingreso.index', compact('procesos'));
    }

    public function create()
    {
        // Solo Jefe RRHH y Root pueden crear nuevos procesos de ingreso
        if (!auth()->user()->hasRole(['Root', 'Jefe RRHH'])) {
            abort(403, 'Solo el Jefe de RRHH puede crear nuevos procesos de ingreso.');
        }

        $gerencias = Gerencia::where('activo', true)
            ->with([
                'areas' => function ($query) {
                    $query->where('activo', true)
                        ->with(['cargos' => function ($cargoQuery) {
                            $cargoQuery->with('jefeInmediato');
                        }]);
                },
            ])
            ->get();

        return view('procesos_ingreso.create', compact('gerencias'));
    }

public function store(Request $request)
{
    // Solo Jefe RRHH y Root pueden crear nuevos procesos
    if (!auth()->user()->hasRole(['Root', 'Jefe RRHH'])) {
        abort(403, 'Solo el Jefe de RRHH puede crear nuevos procesos de ingreso.');
    }

    // Validación inicial
    $request->validate([
        'nombre_completo' => 'required|string|max:255',
        'tipo_documento' => 'required|string|max:50',
        'documento' => 'required|string|unique:procesos_ingresos',
        'cargo_id' => 'required|exists:cargos,id',
        'fecha_ingreso' => 'required|date|after_or_equal:today',
    ]);

    try {
        // 🔐 Autogenerar código
        $codigo = 'ING-' . now()->format('YmdHis');

        // Verificar que el cargo existe
        $cargo = Cargo::with(['area', 'jefeInmediato'])->findOrFail($request->cargo_id);
        $jefeCargoId = $cargo->jefe_inmediato_cargo_id;
        $jefeUsuario = $jefeCargoId ? User::where('cargo_id', $jefeCargoId)->first() : null;

        // Crear proceso de ingreso
        $proceso = ProcesoIngreso::create([
            'codigo' => $codigo,
            'nombre_completo' => $request->nombre_completo,
            'tipo_documento' => $request->tipo_documento,
            'documento' => $request->documento,
            'cargo_id' => $cargo->id,
            'area_id' => $cargo->area_id,
            'fecha_ingreso' => $request->fecha_ingreso,
            'jefe_id' => $jefeUsuario?->id,
            'jefe_cargo_id' => $jefeCargoId,
            'estado' => 'Pendiente',
        ]);

        // Disparar solicitudes automáticas si existen plantillas
        $plantillas = PlantillaSolicitud::where('cargo_id', $cargo->id)->get();

        foreach ($plantillas as $plantilla) {
            Solicitud::create([
                'proceso_ingreso_id' => $proceso->id,
                'area_id' => $plantilla->area_id,
                'tipo' => $plantilla->tipo_solicitud,
                'fecha_limite' => Carbon::parse($request->fecha_ingreso)
                    ->subDays($plantilla->dias_maximos),
                'estado' => 'Pendiente',
            ]);
        }

        return redirect()
            ->route('procesos-ingreso.index')
            ->with('success', 'Proceso de ingreso creado correctamente');

    } catch (\Exception $e) {
        // Capturar error y regresar al formulario con mensaje
        return back()
            ->withErrors(['error' => 'No se pudo crear el proceso: ' . $e->getMessage()])
            ->withInput();
    }
}

    /**
     * Obtener jefes por área (API AJAX)
     */
    public function getJefesByArea($area_id)
    {
        $jefes = User::where('area_id', $area_id)->select('id', 'name')->get();
        return response()->json($jefes);
    }

    /**
     * Ver detalles del proceso de ingreso
     */
    public function show($id)
    {
        $proceso = ProcesoIngreso::with(['cargo', 'area', 'jefe', 'solicitudes'])->findOrFail($id);
        $progreso = $proceso->obtenerProgreso();

        return view('procesos_ingreso.show', compact('proceso', 'progreso'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $proceso = ProcesoIngreso::findOrFail($id);

        if (!$proceso->puedeEditar()) {
            return redirect()->route('procesos-ingreso.show', $id)
                ->withErrors(['error' => 'No se puede editar un proceso con solicitudes finalizadas.']);
        }

        $gerencias = Gerencia::where('activo', true)
            ->with([
                'areas' => function ($query) {
                    $query->where('activo', true)
                        ->with(['cargos' => function ($cargoQuery) {
                            $cargoQuery->where('activo', true)
                                ->with('jefeInmediato');
                        }]);
                },
            ])
            ->get();

        return view('procesos_ingreso.edit', compact('proceso', 'gerencias'));
    }

    /**
     * Actualizar el proceso de ingreso
     */
    public function update(Request $request, $id)
    {
        $proceso = ProcesoIngreso::findOrFail($id);

        if (!$proceso->puedeEditar()) {
            return redirect()->route('procesos-ingreso.show', $id)
                ->withErrors(['error' => 'No se puede editar un proceso con solicitudes finalizadas.']);
        }

        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'tipo_documento' => 'required|string|max:50',
            'cargo_id' => 'required|exists:cargos,id',
        ]);

        try {
            $cargo = Cargo::with(['area', 'jefeInmediato'])->findOrFail($request->cargo_id);
            $jefeCargoId = $cargo->jefe_inmediato_cargo_id;
            $jefeUsuario = $jefeCargoId ? User::where('cargo_id', $jefeCargoId)->first() : null;

            $proceso->update([
                'nombre_completo' => $request->nombre_completo,
                'tipo_documento' => $request->tipo_documento,
                'cargo_id' => $request->cargo_id,
                'area_id' => $cargo->area_id,
                'jefe_id' => $jefeUsuario?->id,
                'jefe_cargo_id' => $jefeCargoId,
            ]);

            return redirect()->route('procesos-ingreso.show', $id)
                ->with('success', 'Proceso actualizado correctamente.');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Cambiar la fecha de ingreso
     */
    public function cambiarFecha($id)
    {
        $proceso = ProcesoIngreso::findOrFail($id);

        if (!$proceso->puedeEditar()) {
            return redirect()->route('procesos-ingreso.show', $id)
                ->withErrors(['error' => 'No se puede cambiar la fecha de un proceso con solicitudes finalizadas.']);
        }

        return view('procesos_ingreso.cambiar_fecha', compact('proceso'));
    }

    /**
     * Procesar cambio de fecha
     */
    public function actualizarFecha(Request $request, $id)
    {
        $proceso = ProcesoIngreso::findOrFail($id);

        $request->validate([
            'nueva_fecha' => 'required|date|after_or_equal:today'
        ]);

        try {
            $proceso->cambiarFechaIngreso($request->nueva_fecha);

            return redirect()->route('procesos-ingreso.show', $id)
                ->with('success', 'Fecha de ingreso actualizada y solicitudes ajustadas.');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Mostrar confirmación de cancelación
     */
    public function mostrarCancelacion($id)
    {
        $proceso = ProcesoIngreso::findOrFail($id);

        if (!$proceso->puedeCancelar()) {
            return redirect()->route('procesos-ingreso.show', $id)
                ->withErrors(['error' => 'No se puede cancelar un proceso con solicitudes finalizadas.']);
        }

        return view('procesos_ingreso.cancelar', compact('proceso'));
    }

    /**
     * Cancelar proceso
     */
    public function cancelar(Request $request, $id)
    {
        $proceso = ProcesoIngreso::findOrFail($id);

        try {
            $proceso->cancelar($request->motivo ?? null);

            return redirect()->route('procesos-ingreso.index')
                ->with('success', 'Proceso cancelado correctamente.');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Mostrar histórico de ingresos exitosos y cancelados
     */
    public function historico()
    {
        $ingresosFinal = ProcesoIngreso::with(['cargo', 'area', 'solicitudes.puestoTrabajo'])
            ->where('estado', 'Finalizado')
            ->latest('fecha_finalizacion')
            ->get();

        $procesoCancelados = ProcesoIngreso::with(['cargo', 'area'])
            ->where('estado', 'Cancelado')
            ->latest('fecha_cancelacion')
            ->get();

        return view('procesos_ingreso.historico', compact('ingresosFinal', 'procesoCancelados'));
    }
}

