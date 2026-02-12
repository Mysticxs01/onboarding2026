<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\DetalleTecnologia;
use Illuminate\Http\Request;

class SolicitudTecnologiaController extends Controller
{
    /**
     * Mostrar formulario de asignación de tecnología
     */
    public function mostrarFormulario(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $detalleTecnologia = $solicitud->detalleTecnologia;

        return view('areas.tecnologia', compact('solicitud', 'detalleTecnologia'));
    }

    /**
     * Guardar asignación de tecnología
     */
    public function guardar(Request $request, Solicitud $solicitud)
    {
        $this->authorize('update', $solicitud);

        $validated = $request->validate([
            'usuario_ad' => 'required|string|unique:detalles_tecnologia,usuario_ad',
            'correo_corporativo' => 'required|email',
            'password' => 'required|string|min:8',
            'hardware' => 'nullable|array',
            'accesos' => 'nullable|array',
            'software' => 'nullable|array',
        ]);

        // Crear o actualizar detalle de tecnología
        $detalle = DetalleTecnologia::updateOrCreate(
            ['solicitud_id' => $solicitud->id],
            [
                'usuario_ad' => $validated['usuario_ad'],
                'correo_corporativo' => $validated['correo_corporativo'],
                'password' => bcrypt($validated['password']),
                'hardware_asignado' => $request->hardware ?? [],
                'accesos_asignados' => $request->accesos ?? [],
                'software_asignado' => $request->software ?? [],
                'estado' => 'Pendiente',
            ]
        );

        return back()->with('success', '✓ Asignación de tecnología guardada correctamente. Usuario AD: ' . $validated['usuario_ad']);
    }

    /**
     * Cargar kit estándar para un cargo
     */
    public function cargarKitEstandar(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $kit = DetalleTecnologia::obtenerKitEstandar($solicitud->proceso->cargo_id);

        return response()->json([
            'success' => true,
            'kit' => $kit,
            'mensaje' => "Kit estándar cargado para {$solicitud->proceso->cargo->nombre}",
        ]);
    }

    /**
     * Marcar hardware como entregado
     */
    public function marcarHardwareEntregado(Request $request, DetalleTecnologia $detalle)
    {
        $this->authorize('update', $detalle->solicitud);

        $validated = $request->validate([
            'hardware' => 'required|string',
            'serial' => 'nullable|string',
            'entregado_por' => 'required|string',
        ]);

        $entregasHardware = $detalle->entregas_hardware ?? [];
        
        $entregasHardware[] = [
            'hardware' => $validated['hardware'],
            'serial' => $validated['serial'] ?? null,
            'entregado_por' => $validated['entregado_por'],
            'fecha_entrega' => now()->toDateTimeString(),
        ];

        $detalle->update(['entregas_hardware' => $entregasHardware]);

        return back()->with('success', "✓ {$validated['hardware']} entregado por {$validated['entregado_por']}");
    }

    /**
     * Actualizar estado de la asignación
     */
    public function actualizarEstado(Request $request, DetalleTecnologia $detalle)
    {
        $this->authorize('update', $detalle->solicitud);

        $validated = $request->validate([
            'estado' => 'required|in:Pendiente,En Proceso,Completado,Rechazado',
        ]);

        $detalle->update($validated);

        $estados = [
            'Pendiente' => '⏳',
            'En Proceso' => '⚙️',
            'Completado' => '✅',
            'Rechazado' => '❌',
        ];

        return back()->with('success', "{$estados[$validated['estado']]} Estado actualizado a {$validated['estado']}");
    }

    /**
     * Ver detalles de la asignación
     */
    public function verDetalles(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $detalle = $solicitud->detalleTecnologia;

        if (!$detalle) {
            return back()->withErrors(['detalle' => 'No hay asignación de tecnología.']);
        }

        return view('areas.tecnologia-detalles', compact('solicitud', 'detalle'));
    }

    /**
     * Generar checklist de implementación
     */
    public function generarChecklist(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $detalle = $solicitud->detalleTecnologia;

        if (!$detalle) {
            return response()->json(['error' => 'No hay detalle de tecnología'], 404);
        }

        $checklist = [
            'usuario_ad' => [
                'nombre' => 'Usuario AD creado',
                'completado' => !empty($detalle->usuario_ad),
            ],
            'correo' => [
                'nombre' => 'Correo corporativo activo',
                'completado' => !empty($detalle->correo_corporativo),
            ],
            'hardware' => [
                'nombre' => 'Hardware entregado',
                'completado' => count($detalle->entregas_hardware ?? []) > 0,
                'items' => $detalle->hardware_asignado ?? [],
            ],
            'accesos' => [
                'nombre' => 'Accesos configurados',
                'completado' => count($detalle->accesos_asignados ?? []) > 0,
                'items' => $detalle->accesos_asignados ?? [],
            ],
            'software' => [
                'nombre' => 'Software instalado',
                'completado' => count($detalle->entregas_software ?? []) >= count($detalle->software_asignado ?? []),
                'items' => $detalle->software_asignado ?? [],
            ],
        ];

        return response()->json($checklist);
    }

    /**
     * Validar credenciales (para testing)
     */
    public function validarCredenciales(Request $request, DetalleTecnologia $detalle)
    {
        $this->authorize('view', $detalle->solicitud);

        // En producción, esto verificaría contra AD
        $esValido = true;

        return response()->json([
            'valido' => $esValido,
            'usuario' => $detalle->usuario_ad,
            'mensaje' => $esValido ? 'Credenciales válidas' : 'Credenciales inválidas',
        ]);
    }
}
