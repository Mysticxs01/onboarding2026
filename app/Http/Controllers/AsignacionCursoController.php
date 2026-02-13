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

        $query = AsignacionCurso::with(['procesoIngreso.cargo', 'procesoIngreso.area', 'curso']);

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->proceso_id) {
            $query->where('proceso_ingreso_id', $request->proceso_id);
        }

        // Filtro por búsqueda en nombre de curso
        if ($request->busqueda) {
            $query->whereHas('curso', function($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->busqueda}%");
            });
        }

        $asignaciones = $query->orderBy('fecha_asignacion', 'DESC')->paginate(15);

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
        $cursosDisponibles = Curso::activos()->orderBy('nombre')->get();
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
            'calificacion' => 'nullable|numeric|min:0|max:100',
            'certificado_url' => 'nullable|url',
        ]);

        $asignacion->update([
            'estado' => 'Completado',
            'fecha_completado' => now(),
            'calificacion' => $validated['calificacion'] ?? null,
            'certificado_url' => $validated['certificado_url'] ?? null,
        ]);

        AuditoriaOnboarding::registrar(
            'update', 
            'AsignacionCurso', 
            $asignacion->proceso_ingreso_id,
            "Curso '{$asignacion->curso->nombre}' marcado como completado (Calificación: {$validated['calificacion']})"
        );

        return redirect()->route('asignaciones.show', $asignacion)
                       ->with('success', 'Curso marcado como completado exitosamente.');
    }

    public function marcarEnProgreso(AsignacionCurso $asignacion)
    {
        $this->authorize('update', $asignacion);

        if ($asignacion->estado !== 'Asignado') {
            return redirect()->back()
                           ->with('error', 'Solo se pueden marcar en progreso los cursos asignados.');
        }

        $asignacion->update([
            'estado' => 'En Progreso',
            'fecha_inicio' => now(),
        ]);

        AuditoriaOnboarding::registrar(
            'update', 
            'AsignacionCurso', 
            $asignacion->proceso_ingreso_id,
            "Curso '{$asignacion->curso->nombre}' marcado como en progreso"
        );

        return redirect()->back()
                       ->with('success', 'Curso marcado como en progreso.');
    }

    public function cancelar(Request $request, AsignacionCurso $asignacion)
    {
        $this->authorize('delete', $asignacion);

        if (!in_array($asignacion->estado, ['Asignado', 'En Progreso'])) {
            return redirect()->back()
                           ->with('error', 'No se puede cancelar un curso completado o ya cancelado.');
        }

        $validated = $request->validate([
            'motivo' => 'required|string|max:500',
        ]);

        $asignacion->update([
            'estado' => 'Cancelado',
            'motivo_cancelacion' => $validated['motivo'],
            'fecha_cancelacion' => now(),
        ]);

        AuditoriaOnboarding::registrar(
            'delete', 
            'AsignacionCurso', 
            $asignacion->proceso_ingreso_id,
            "Asignación de curso '{$asignacion->curso->nombre}' cancelada. Motivo: {$validated['motivo']}"
        );

        return redirect()->back()
                       ->with('success', 'Asignación cancelada exitosamente.');
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
