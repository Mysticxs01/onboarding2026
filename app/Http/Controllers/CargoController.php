<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cargo;

class CargoController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasRole('Root')) {
            abort(403, 'Solo el usuario Root puede gestionar cargos.');
        }

        $cargos = Cargo::with(['area.gerencia', 'jefeInmediato'])
            ->orderBy('nombre')
            ->get();

        return view('cargos.index', compact('cargos'));
    }

    public function actualizarEstado(Request $request, Cargo $cargo)
    {
        if (!auth()->user()->hasRole('Root')) {
            abort(403, 'Solo el usuario Root puede gestionar cargos.');
        }

        $request->validate([
            'activo' => 'required|boolean',
        ]);

        $cargo->update([
            'activo' => (bool) $request->boolean('activo'),
        ]);

        return back()->with('success', 'Estado del cargo actualizado correctamente.');
    }
}
