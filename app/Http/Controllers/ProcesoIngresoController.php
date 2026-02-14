<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcesoIngreso;
use App\Models\Cargo;
use App\Models\User;
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

        $cargos = Cargo::with('area')->get();
        $jefes = User::all();

        return view('procesos_ingreso.create', compact('cargos', 'jefes'));
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
        'jefe_id' => 'required|exists:users,id',
    ]);

    try {
        // 🔐 Autogenerar código
        $codigo = 'ING-' . now()->format('YmdHis');

        // Verificar que el cargo existe
        $cargo = Cargo::with('area')->findOrFail($request->cargo_id);

        // Verificar que el jefe existe
        $jefe = User::findOrFail($request->jefe_id);

        // ⚠ Validación: jefe pertenece al área del cargo
        if ($jefe->area_id !== $cargo->area_id) {
            return back()
                ->withErrors(['jefe_id' => 'El jefe seleccionado no pertenece al área del cargo.'])
                ->withInput();
        }

        // Crear proceso de ingreso
        $proceso = ProcesoIngreso::create([
            'codigo' => $codigo,
            'nombre_completo' => $request->nombre_completo,
            'tipo_documento' => $request->tipo_documento,
            'documento' => $request->documento,
            'cargo_id' => $cargo->id,
            'area_id' => $cargo->area_id,
            'fecha_ingreso' => $request->fecha_ingreso,
            'jefe_id' => $request->jefe_id,
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

        $cargos = Cargo::with('area')->get();
        $jefes = User::where('area_id', $proceso->area_id)->get();

        return view('procesos_ingreso.edit', compact('proceso', 'cargos', 'jefes'));
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
            'jefe_id' => 'required|exists:users,id',
        ]);

        try {
            $cargo = Cargo::findOrFail($request->cargo_id);
            $jefe = User::findOrFail($request->jefe_id);

            if ($jefe->area_id !== $cargo->area_id) {
                return back()
                    ->withErrors(['jefe_id' => 'El jefe no pertenece al área del cargo.'])
                    ->withInput();
            }

            $proceso->update([
                'nombre_completo' => $request->nombre_completo,
                'tipo_documento' => $request->tipo_documento,
                'cargo_id' => $request->cargo_id,
                'area_id' => $cargo->area_id,
                'jefe_id' => $request->jefe_id,
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
     * Mostrar plano de puestos para asignar
     */
    public function mostrarPlano($id)
    {
        $proceso = ProcesoIngreso::findOrFail($id);        $user = auth()->user();

        // Validar permisos: solo Jefe del proceso o Admin
        if (!$user->hasRole('Admin') && $proceso->jefe_id !== $user->id) {
            abort(403, 'No tiene permiso para asignar puestos a este proceso');
        }
        $puestos = \App\Models\Puesto::orderBy('fila')->orderBy('columna')->get();

        return view('procesos_ingreso.plano_puestos', compact('proceso', 'puestos'));
    }

    /**
     * Asignar puesto de trabajo
     */
    public function asignarPuesto(Request $request, $id)
    {
        $proceso = ProcesoIngreso::findOrFail($id);
        $user = auth()->user();

        // Validar permisos: solo Jefe del proceso o Admin
        if (!$user->hasRole('Admin') && $proceso->jefe_id !== $user->id) {
            return response()->json(['error' => 'No tiene permiso para asignar puestos a este proceso'], 403);
        }

        // Validar que el proceso no esté finalizado o cancelado
        if (in_array($proceso->estado, ['Finalizado', 'Cancelado'])) {
            return response()->json(['error' => 'No se puede asignar puestos a un proceso finalizado o cancelado'], 400);
        }

        $request->validate([
            'puesto_id' => 'required|exists:puestos,id'
        ]);

        try {
            $puesto = \App\Models\Puesto::findOrFail($request->puesto_id);

            if ($puesto->estado !== 'Disponible') {
                return response()->json(['error' => 'Puesto no disponible'], 400);
            }

            // Liberar puesto anterior si existe
            if ($proceso->puesto) {
                $proceso->puesto()->update(['estado' => 'Disponible', 'proceso_ingreso_id' => null]);
            }

            // Asignar nuevo puesto
            $puesto->update([
                'estado' => 'Ocupado',
                'proceso_ingreso_id' => $proceso->id
            ]);

            return response()->json(['success' => 'Puesto asignado correctamente.']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Obtener puestos en formato JSON
     */
    public function obtenerPuestos()
    {
        $puestos = \App\Models\Puesto::select('id', 'numero', 'fila', 'columna', 'estado', 'proceso_ingreso_id')->get();
        return response()->json($puestos);
    }

    /**
     * Mostrar histórico de ingresos exitosos y cancelados
     */
    public function historico()
    {
        $ingresosFinal = ProcesoIngreso::with(['cargo', 'area', 'puesto'])
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

