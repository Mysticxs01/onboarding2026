<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\ElementoProteccion;
use App\Models\DetalleUniforme;
use Illuminate\Http\Request;

class SolicitudDotacionController extends Controller
{
    /**
     * Mostrar formulario de asignación de EPP
     */
    public function mostrarFormulario(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $elementosProteccion = $solicitud->elementosProteccion;

        return view('areas.dotacion', compact('solicitud', 'elementosProteccion'));
    }

    /**
     * Guardar asignación de EPP y uniformes
     */
    public function guardar(Request $request, Solicitud $solicitud)
    {
        $this->authorize('update', $solicitud);

        $validated = $request->validate([
            'elementos.*.tipo' => 'required|string',
            'elementos.*.cantidad' => 'required|numeric|min:1',
            'elementos.*.talla' => 'nullable|string',
            'elementos.*.color' => 'nullable|string',
            'elementos.*.entregado' => 'nullable|boolean',
            'uniformes' => 'nullable|array',
        ]);

        // Guardar elementos de protección
        if (isset($validated['elementos'])) {
            foreach ($validated['elementos'] as $elemento) {
                ElementoProteccion::create([
                    'solicitud_id' => $solicitud->id,
                    'tipo' => $elemento['tipo'],
                    'cantidad' => $elemento['cantidad'],
                    'talla' => $elemento['talla'] ?? null,
                    'color' => $elemento['color'] ?? null,
                    'entregado' => $elemento['entregado'] ?? false,
                    'fecha_entrega' => isset($elemento['entregado']) && $elemento['entregado'] ? now() : null,
                ]);
            }
        }

        // Guardar uniformes
        if (isset($request->uniformes)) {
            foreach ($request->uniformes as $uniforme) {
                DetalleUniforme::create([
                    'solicitud_id' => $solicitud->id,
                    'tipo_uniforme' => $uniforme,
                    'estado' => 'Pendiente',
                ]);
            }
        }

        return back()->with('success', '✓ Asignación de dotación (EPP y uniformes) guardada correctamente.');
    }

    /**
     * Cargar kit estándar para un cargo
     */
    public function cargarKitEstandar(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $cargo = $solicitud->proceso->cargo->nombre;
        
        // Obtener kit estándar del modelo
        $kit = ElementoProteccion::obtenerKitEstandarPorCargo($solicitud->proceso->cargo_id);

        return response()->json([
            'success' => true,
            'kit' => $kit,
            'mensaje' => "Kit estándar para {$cargo} cargado",
        ]);
    }

    /**
     * Marcar elemento como entregado
     */
    public function marcarEntregado(Request $request, ElementoProteccion $elemento)
    {
        $this->authorize('update', $elemento->solicitud);

        $elemento->update([
            'entregado' => true,
            'fecha_entrega' => now(),
        ]);

        return back()->with('success', "✓ {$elemento->tipo} marcado como entregado.");
    }

    /**
     * Ver resumen de entregas
     */
    public function verResumen(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $elementosEntregados = $solicitud->elementosProteccion()->where('entregado', true)->get();
        $uniformesEntregados = $solicitud->detallesUniforme()->where('estado', 'Entregado')->get();

        return view('areas.dotacion-resumen', compact(
            'solicitud',
            'elementosEntregados',
            'uniformesEntregados'
        ));
    }
}
