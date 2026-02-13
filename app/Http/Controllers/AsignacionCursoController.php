<?php

namespace App\Http\Controllers;

use App\Models\ProcesoIngreso;
use App\Models\Curso;
use App\Models\AsignacionCurso;
use App\Models\AuditoriaOnboarding;
use Illuminate\Http\Request;

class AsignacionCursoController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', AsignacionCurso::class);

        $query = AsignacionCurso::query();

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->proceso_id) {
            $query->where('proceso_ingreso_id', $request->proceso_id);
        }

        $asignaciones = $query->paginate(15);

        return view('formacion.asignaciones.index', [
            'asignaciones' => $asignaciones,
        ]);
    }

    public function panel()
    {
        $this->authorize('createAssignment', AsignacionCurso::class);

        // Procesos sin asignaciones completas
        $procesosSinAsignaciones = ProcesoIngreso::where('estado', '!=', 'Cancelado')
            ->with('area', 'cargo')
            ->paginate(10);

        return view('formacion.asignaciones.panel', [
            'procesos' => $procesosSinAsignaciones,
            'cursos' => Curso::activos()->get(),
        ]);
    }

    public function asignar(ProcesoIngreso $procesoIngreso)
    {
        $this->authorize('createAssignment', AsignacionCurso::class);

        $cursosAsignados = $procesoIngreso->asignacionesCursos()->pluck('curso_id')->toArray();
        $cursosDisponibles = Curso::activos()->get();
        $cursosSugeridos = $this->obtenerCursosSugeridos($procesoIngreso);

        return view('formacion.asignaciones.asignar', [
            'proceso' => $procesoIngreso,
            'cursosDisponibles' => $cursosDisponibles,
            'cursosSugeridos' => $cursosSugeridos,
            'cursosAsignados' => $cursosAsignados,
        ]);
    }

    public function guardar(Request $request, ProcesoIngreso $procesoIngreso)
    {
        $this->authorize('createAssignment', AsignacionCurso::class);

        $validated = $request->validate([
            'curso_ids' => 'array',
            'curso_ids.*' => 'exists:cursos,id',
            'fecha_limite' => 'nullable|date',
        ]);

        $asignacionesCreadas = 0;
        $fecha_limite = $validated['fecha_limite'] ?? now()->addMonths(3);

        foreach ($validated['curso_ids'] ?? [] as $cursoId) {
            // Evitar duplicados
            if (AsignacionCurso::where('proceso_ingreso_id', $procesoIngreso->id)
                               ->where('curso_id', $cursoId)
                               ->exists()) {
                continue;
            }

            AsignacionCurso::create([
                'proceso_ingreso_id' => $procesoIngreso->id,
                'curso_id' => $cursoId,
                'fecha_asignacion' => now(),
                'fecha_limite' => $fecha_limite,
                'estado' => 'Asignado',
                'asignado_por_id' => auth()->id(),
            ]);

            $asignacionesCreadas++;
        }

        AuditoriaOnboarding::registrar('create', 'AsignacionCurso', $procesoIngreso->id, 
                                       "Se asignaron {$asignacionesCreadas} cursos");

        return redirect()->route('asignaciones.index')
                       ->with('success', "{$asignacionesCreadas} cursos asignados exitosamente.");
    }

    public function show(AsignacionCurso $asignacion)
    {
        $this->authorize('view', $asignacion);

        return view('formacion.asignaciones.show', [
            'asignacion' => $asignacion,
        ]);
    }

    public function validar(AsignacionCurso $asignacion)
    {
        $this->authorize('update', $asignacion);

        return view('formacion.asignaciones.validar', [
            'asignacion' => $asignacion,
        ]);
    }

    public function marcarCompletada(Request $request, AsignacionCurso $asignacion)
    {
        $this->authorize('update', $asignacion);

        $validated = $request->validate([
            'calificacion' => 'nullable|integer|min:0|max:100',
            'certificado_url' => 'nullable|string',
        ]);

        $asignacion->marcarCompletado(
            $validated['calificacion'] ?? null,
            $validated['certificado_url'] ?? null
        );

        return redirect()->back()
                       ->with('success', 'Curso marcado como completado.');
    }

    public function marcarEnProgreso(AsignacionCurso $asignacion)
    {
        $this->authorize('update', $asignacion);

        if (!$asignacion->puedeProceder()) {
            return redirect()->back()
                           ->with('error', 'No se puede cambiar el estado de esta asignación.');
        }

        $asignacion->marcarEnProgreso();

        return redirect()->back()
                       ->with('success', 'Curso marcado como en progreso.');
    }

    public function cancelar(Request $request, AsignacionCurso $asignacion)
    {
        $this->authorize('delete', $asignacion);

        $validated = $request->validate([
            'motivo' => 'required|string|max:500',
        ]);

        $asignacion->cancelar($validated['motivo']);

        return redirect()->back()
                       ->with('success', 'Asignación cancelada.');
    }

    // Método para obtener cursos sugeridos basado en cargo
    private function obtenerCursosSugeridos(ProcesoIngreso $proceso)
    {
        // Obtener cursos obligatorios del cargo
        $cursosSugeridos = $proceso->cargo->cursos()
            ->wherePivot('es_obligatorio', true)
            ->get();

        return $cursosSugeridos;
    }
}
