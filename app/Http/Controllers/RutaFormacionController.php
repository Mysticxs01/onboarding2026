<?php

namespace App\Http\Controllers;

use App\Models\RutaFormacion;
use App\Models\Curso;
use App\Models\Cargo;
use App\Models\Area;
use App\Models\AuditoriaOnboarding;
use Illuminate\Http\Request;

class RutaFormacionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', RutaFormacion::class);

        $query = RutaFormacion::query();

        if ($request->cargo_id) {
            $query->donde('cargo_id', $request->cargo_id);
        }

        if ($request->area_id) {
            $query->where('area_id', $request->area_id);
        }

        if ($request->activa !== null) {
            $query->where('activa', $request->activa);
        }

        $rutas = $query->paginate(15);

        return view('formacion.rutas.index', [
            'rutas' => $rutas,
            'cargos' => Cargo::all(),
            'areas' => Area::all(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', RutaFormacion::class);

        return view('formacion.rutas.create', [
            'cargos' => Cargo::all(),
            'areas' => Area::all(),
            'cursos' => Curso::activos()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', RutaFormacion::class);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cargo_id' => 'required_without:area_id|nullable|exists:cargos,id',
            'area_id' => 'required_without:cargo_id|nullable|exists:areas,id',
            'version' => 'required|string|max:10',
            'fecha_vigencia' => 'nullable|date',
            'responable_rrhh_id' => 'nullable|exists:users,id',
            'cursos' => 'array',
            'cursos.*.id' => 'exists:cursos,id',
            'cursos.*.numero_secuencia' => 'integer|min:0',
            'cursos.*.es_obligatorio' => 'boolean',
        ]);

        $ruta = RutaFormacion::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'cargo_id' => $validated['cargo_id'] ?? null,
            'area_id' => $validated['area_id'] ?? null,
            'version' => $validated['version'],
            'fecha_vigencia' => $validated['fecha_vigencia'] ?? null,
            'responsable_rrhh_id' => auth()->id(),
            'activa' => true,
        ]);

        // Agregar cursos a la ruta
        if (!empty($validated['cursos'])) {
            foreach ($validated['cursos'] as $cursoData) {
                $ruta->agregarCurso(
                    $cursoData['id'],
                    $cursoData['numero_secuencia'] ?? 0,
                    $cursoData['es_obligatorio'] ?? true
                );
            }
        }

        $ruta->duracion_total_horas = $ruta->calcularDuracionTotal();
        $ruta->save();

        AuditoriaOnboarding::registrarCreacion('RutaFormacion', $ruta->id, $validated);

        return redirect()->route('rutas.show', $ruta)
                       ->with('success', 'Ruta de formación creada exitosamente.');
    }

    public function show(RutaFormacion $ruta)
    {
        $this->authorize('view', $ruta);

        return view('formacion.rutas.show', [
            'ruta' => $ruta,
            'cursos' => $ruta->obtenerCursosSecuenciados(),
        ]);
    }

    public function edit(RutaFormacion $ruta)
    {
        $this->authorize('update', $ruta);

        return view('formacion.rutas.edit', [
            'ruta' => $ruta,
            'cargos' => Cargo::all(),
            'areas' => Area::all(),
            'cursos' => Curso::activos()->get(),
            'cursosEnRuta' => $ruta->cursos()->pluck('id')->toArray(),
        ]);
    }

    public function update(Request $request, RutaFormacion $ruta)
    {
        $this->authorize('update', $ruta);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cargo_id' => 'nullable|exists:cargos,id',
            'area_id' => 'nullable|exists:areas,id',
            'version' => 'required|string|max:10',
            'fecha_vigencia' => 'nullable|date',
            'activa' => 'boolean',
        ]);

        $ruta->update($validated);

        AuditoriaOnboarding::registrarActualizacion('RutaFormacion', $ruta->id, 
                                                   $ruta->getOriginal(), $validated);

        return redirect()->route('rutas.show', $ruta)
                       ->with('success', 'Ruta de formación actualizada exitosamente.');
    }

    public function destroy(RutaFormacion $ruta)
    {
        $this->authorize('delete', $ruta);

        $ruta->delete();

        AuditoriaOnboarding::registrarEliminacion('RutaFormacion', $ruta->id, 'Eliminación por usuario');

        return redirect()->route('rutas.index')
                       ->with('success', 'Ruta de formación eliminada.');
    }

    public function agregarCurso(Request $request, RutaFormacion $ruta)
    {
        $this->authorize('update', $ruta);

        $validated = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'numero_secuencia' => 'required|integer|min:0',
            'es_obligatorio' => 'boolean',
        ]);

        if ($ruta->cursos()->where('curso_id', $validated['curso_id'])->exists()) {
            return back()->with('error', 'Este curso ya está en la ruta.');
        }

        $ruta->agregarCurso(
            $validated['curso_id'],
            $validated['numero_secuencia'],
            $validated['es_obligatorio'] ?? true
        );

        $ruta->duracion_total_horas = $ruta->calcularDuracionTotal();
        $ruta->save();

        AuditoriaOnboarding::registrar('update', 'RutaFormacion', $ruta->id, 
                                       'Se agregó curso a la ruta');

        return back()->with('success', 'Curso agregado a la ruta.');
    }

    public function removerCurso(Request $request, RutaFormacion $ruta)
    {
        $this->authorize('update', $ruta);

        $validated = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $ruta->removerCurso($validated['curso_id']);

        $ruta->duracion_total_horas = $ruta->calcularDuracionTotal();
        $ruta->save();

        AuditoriaOnboarding::registrar('update', 'RutaFormacion', $ruta->id, 
                                       'Se removió curso de la ruta');

        return back()->with('success', 'Curso removido de la ruta.');
    }
}
