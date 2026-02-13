<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Area;
use App\Models\AuditoriaOnboarding;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', Curso::class);

        $query = Curso::query();

        if ($request->search) {
            $query->buscar($request->search);
        }

        if ($request->categoria) {
            $query->porCategoria($request->categoria);
        }

        if ($request->modalidad) {
            $query->porModalidad($request->modalidad);
        }

        if ($request->activo) {
            $query->where('activo', $request->activo == 'true');
        }

        $cursos = $query->paginate(15);

        return view('formacion.cursos.index', [
            'cursos' => $cursos,
            'categorias' => [
                'Obligatorio' => 'Obligatorio',
                'Opcional' => 'Opcional',
                'Cumplimiento Normativo' => 'Cumplimiento Normativo',
                'Desarrollo' => 'Desarrollo',
                'Liderazgo' => 'Liderazgo',
            ],
            'modalidades' => [
                'Presencial' => 'Presencial',
                'Virtual' => 'Virtual',
                'Híbrida' => 'Híbrida',
            ],
        ]);
    }

    public function create()
    {
        $this->authorize('create', Curso::class);

        return view('formacion.cursos.create', [
            'areas' => Area::all(),
            'categorias' => [
                'Obligatorio' => 'Obligatorio',
                'Opcional' => 'Opcional',
                'Cumplimiento Normativo' => 'Cumplimiento Normativo',
                'Desarrollo' => 'Desarrollo',
                'Liderazgo' => 'Liderazgo',
            ],
            'modalidades' => [
                'Presencial' => 'Presencial',
                'Virtual' => 'Virtual',
                'Híbrida' => 'Híbrida',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Curso::class);

        $validated = $request->validate([
            'codigo' => 'required|string|unique:cursos,codigo|max:50',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'required|in:Obligatorio,Opcional,Cumplimiento Normativo,Desarrollo,Liderazgo',
            'modalidad' => 'required|in:Presencial,Virtual,Híbrida',
            'duracion_horas' => 'required|integer|min:1',
            'objetivo' => 'nullable|string',
            'contenido' => 'nullable|string',
            'area_responsable_id' => 'nullable|exists:areas,id',
            'costo' => 'nullable|numeric|min:0',
            'requiere_certificado' => 'boolean',
            'vigencia_meses' => 'nullable|integer|min:1',
            'activo' => 'boolean',
        ]);

        $curso = Curso::create($validated);

        AuditoriaOnboarding::registrarCreacion('Curso', $curso->id, $validated);

        return redirect()->route('cursos.show', $curso)
                       ->with('success', 'Curso creado exitosamente.');
    }

    public function show(Curso $curso)
    {
        $this->authorize('view', $curso);

        return view('formacion.cursos.show', [
            'curso' => $curso,
            'asignaciones' => $curso->asignaciones()->paginate(10),
        ]);
    }

    public function edit(Curso $curso)
    {
        $this->authorize('update', $curso);

        return view('formacion.cursos.edit', [
            'curso' => $curso,
            'areas' => Area::all(),
            'categorias' => [
                'Obligatorio' => 'Obligatorio',
                'Opcional' => 'Opcional',
                'Cumplimiento Normativo' => 'Cumplimiento Normativo',
                'Desarrollo' => 'Desarrollo',
                'Liderazgo' => 'Liderazgo',
            ],
            'modalidades' => [
                'Presencial' => 'Presencial',
                'Virtual' => 'Virtual',
                'Híbrida' => 'Híbrida',
            ],
        ]);
    }

    public function update(Request $request, Curso $curso)
    {
        $this->authorize('update', $curso);

        $validated = $request->validate([
            'codigo' => 'required|string|unique:cursos,codigo,' . $curso->id . '|max:50',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'required|in:Obligatorio,Opcional,Cumplimiento Normativo,Desarrollo,Liderazgo',
            'modalidad' => 'required|in:Presencial,Virtual,Híbrida',
            'duracion_horas' => 'required|integer|min:1',
            'objetivo' => 'nullable|string',
            'contenido' => 'nullable|string',
            'area_responsable_id' => 'nullable|exists:areas,id',
            'costo' => 'nullable|numeric|min:0',
            'requiere_certificado' => 'boolean',
            'vigencia_meses' => 'nullable|integer|min:1',
            'activo' => 'boolean',
        ]);

        $valoresAnteriores = $curso->toArray();
        $curso->update($validated);

        AuditoriaOnboarding::registrarActualizacion('Curso', $curso->id, $valoresAnteriores, $validated);

        return redirect()->route('cursos.show', $curso)
                       ->with('success', 'Curso actualizado exitosamente.');
    }

    public function destroy(Curso $curso)
    {
        $this->authorize('delete', $curso);

        $curso->delete();

        AuditoriaOnboarding::registrarEliminacion('Curso', $curso->id, 'Eliminación por usuario');

        return redirect()->route('cursos.index')
                       ->with('success', 'Curso eliminado exitosamente.');
    }

    public function asignarACargo(Curso $curso, Request $request)
    {
        $this->authorize('update', $curso);

        $validated = $request->validate([
            'cargo_id' => 'required|exists:cargos,id',
            'es_obligatorio' => 'boolean',
            'orden_secuencia' => 'required|integer|min:0',
        ]);

        $curso->cargos()->attach($request->cargo_id, [
            'es_obligatorio' => $validated['es_obligatorio'] ?? false,
            'orden_secuencia' => $validated['orden_secuencia'],
        ]);

        AuditoriaOnboarding::registrar('update', 'Curso', $curso->id, 'Asignado a cargo ' . $request->cargo_id);

        return back()->with('success', 'Curso asignado al cargo exitosamente.');
    }

    public function exportar(Request $request)
    {
        $this->authorize('view', Curso::class);

        $query = Curso::query();

        if ($request->categoria) {
            $query->porCategoria($request->categoria);
        }

        $cursos = $query->get();

        AuditoriaOnboarding::registrarExportacion('Curso', $cursos->count());

        return response()->json($cursos);
    }
}
