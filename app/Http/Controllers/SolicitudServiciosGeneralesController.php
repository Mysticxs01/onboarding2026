<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\PuestoTrabajo;
use App\Models\SolicitudServiciosGenerales;
use Illuminate\Http\Request;

class SolicitudServiciosGeneralesController extends Controller
{
    /**
     * Mostrar formulario de plano interactivo
     */
    public function mostrarPlano(Solicitud $solicitud)
    {
        // Verificar permisos
        $this->authorize('view', $solicitud);

        $piso = request('piso', 1);
        $puestos = PuestoTrabajo::where('piso', $piso)->get();

        return view('areas.servicios-generales', compact('solicitud', 'puestos', 'piso'));
    }

    /**
     * Asignar puesto de trabajo a solicitud
     */
    public function asignarPuesto(Request $request, Solicitud $solicitud)
    {
        $this->authorize('update', $solicitud);

        $validated = $request->validate([
            'puesto_trabajo_id' => 'required|exists:puestos_trabajo,id',
        ]);

        $puesto = PuestoTrabajo::findOrFail($validated['puesto_trabajo_id']);

        // Verificar disponibilidad
        if (!$puesto->estaDisponible()) {
            return back()->withErrors(['puesto' => 'El puesto seleccionado ya está asignado.']);
        }

        // Crear o actualizar asignación
        $asignacion = SolicitudServiciosGenerales::updateOrCreate(
            ['solicitud_id' => $solicitud->id],
            [
                'puesto_trabajo_id' => $puesto->id,
                'carnet_generado' => false,
            ]
        );

        // Actualizar estado del puesto
        $puesto->update(['estado' => 'Asignado']);

        return back()->with('success', "✓ Puesto {$puesto->numero_puesto} asignado correctamente a {$solicitud->proceso->nombre_completo}");
    }

    /**
     * Generar carnet de identificación
     */
    public function generarCarnet(Solicitud $solicitud)
    {
        $this->authorize('update', $solicitud);

        $asignacion = $solicitud->solicitudServiciosGenerales;

        if (!$asignacion || !$asignacion->puesto_trabajo_id) {
            return back()->withErrors(['carnet' => 'Primero debes asignar un puesto de trabajo.']);
        }

        // Generar número de carnet
        $numeroCarnet = 'CARNET-' . $solicitud->id . '-' . now()->format('Ymd');

        $asignacion->update([
            'carnet_generado' => true,
            'numero_carnet' => $numeroCarnet,
            'fecha_carnetizacion' => now(),
        ]);

        return back()->with('success', "✓ Carnet generado: $numeroCarnet");
    }

    /**
     * Ver detalles de la asignación
     */
    public function verDetalles(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $asignacion = $solicitud->solicitudServiciosGenerales;

        if (!$asignacion) {
            return back()->withErrors(['asignacion' => 'No hay asignación de puesto para esta solicitud.']);
        }

        return view('areas.servicios-generales-detalles', compact('solicitud', 'asignacion'));
    }

    /**
     * Liberar puesto asignado
     */
    public function liberarPuesto(Solicitud $solicitud)
    {
        $this->authorize('update', $solicitud);

        $asignacion = $solicitud->solicitudServiciosGenerales;

        if (!$asignacion || !$asignacion->puesto_trabajo_id) {
            return back()->withErrors(['puesto' => 'No hay puesto asignado.']);
        }

        // Liberar el puesto
        $puesto = $asignacion->puestoTrabajo;
        $puesto->update(['estado' => 'Disponible']);

        // Eliminar asignación
        $asignacion->delete();

        return back()->with('success', "✓ Puesto {$puesto->numero_puesto} liberado exitosamente.");
    }
}
