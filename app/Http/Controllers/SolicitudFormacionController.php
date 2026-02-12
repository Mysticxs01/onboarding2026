<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\PlanCapacitacion;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class SolicitudFormacionController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Mostrar formulario de creación de plan de capacitación
     */
    public function mostrarFormulario(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        return view('areas.formacion', compact('solicitud'));
    }

    /**
     * Guardar plan de capacitación
     */
    public function guardar(Request $request, Solicitud $solicitud)
    {
        $this->authorize('update', $solicitud);

        $validated = $request->validate([
            'modulos.*.nombre' => 'required|string',
            'modulos.*.descripcion' => 'nullable|string',
            'modulos.*.duracion_horas' => 'required|numeric|min:1',
            'modulos.*.responsable' => 'required|string',
            'duracion_horas' => 'required|numeric|min:1',
            'responsable_capacitacion' => 'required|string',
            'enviar_notificacion' => 'nullable|boolean',
        ]);

        // Preparar módulos
        $modulos = [];
        if (isset($validated['modulos'])) {
            foreach ($validated['modulos'] as $modulo) {
                $modulos[] = [
                    'nombre' => $modulo['nombre'],
                    'descripcion' => $modulo['descripcion'] ?? null,
                    'duracion_horas' => $modulo['duracion_horas'],
                    'responsable' => $modulo['responsable'],
                    'estado' => 'No iniciado',
                ];
            }
        }

        // Crear o actualizar plan
        $plan = PlanCapacitacion::updateOrCreate(
            ['solicitud_id' => $solicitud->id],
            [
                'cargo_id' => $solicitud->proceso->cargo_id,
                'modulos' => $modulos,
                'duracion_horas' => $validated['duracion_horas'],
                'responsable_capacitacion' => $validated['responsable_capacitacion'],
                'estado' => 'Diseño',
            ]
        );

        // Enviar notificación por email si se solicita
        if ($request->enviar_notificacion) {
            $this->enviarNotificacion($solicitud, $plan);
        }

        return back()->with('success', '✓ Plan de capacitación creado exitosamente. ' . 
            ($request->enviar_notificacion ? 'Notificación enviada al empleado.' : ''));
    }

    /**
     * Enviar notificación del plan por email
     */
    private function enviarNotificacion(Solicitud $solicitud, PlanCapacitacion $plan)
    {
        $empleado = $solicitud->proceso;
        
        $mensaje = "Plan de Capacitación para {$empleado->nombre_completo}\n";
        $mensaje .= "Cargo: {$empleado->cargo->nombre}\n";
        $mensaje .= "Duración Total: {$plan->duracion_horas} horas\n";
        $mensaje .= "Responsable: {$plan->responsable_capacitacion}\n\n";
        $mensaje .= "Módulos:\n";
        
        foreach ($plan->modulos as $modulo) {
            $mensaje .= "- {$modulo['nombre']} ({$modulo['duracion_horas']}h) - {$modulo['responsable']}\n";
        }

        try {
            $this->notificationService->enviar(
                destinatario: $empleado->email,
                asunto: "Tu Plan de Capacitación ha sido creado",
                mensaje: $mensaje,
                tipo: 'capacitacion'
            );

            // Marcar como enviado
            $plan->update([
                'email_enviado' => true,
                'fecha_email_enviado' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error("Error al enviar notificación de plan: " . $e->getMessage());
        }
    }

    /**
     * Cargar plan estándar para un cargo
     */
    public function cargarPlanEstandar(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $plan = PlanCapacitacion::obtenerPlanPorCargo($solicitud->proceso->cargo_id);

        return response()->json([
            'success' => true,
            'plan' => $plan,
            'mensaje' => "Plan estándar cargado para {$solicitud->proceso->cargo->nombre}",
        ]);
    }

    /**
     * Actualizar estado del plan
     */
    public function actualizarEstado(Request $request, PlanCapacitacion $plan)
    {
        $this->authorize('update', $plan->solicitud);

        $validated = $request->validate([
            'estado' => 'required|in:Diseño,Programado,En Ejecución,Completado,Cancelado',
        ]);

        $plan->update($validated);

        $estados = [
            'Diseño' => '🎨',
            'Programado' => '📅',
            'En Ejecución' => '▶️',
            'Completado' => '✅',
            'Cancelado' => '❌',
        ];

        return back()->with('success', "{$estados[$validated['estado']]} Estado actualizado a {$validated['estado']}");
    }

    /**
     * Ver resumen del plan
     */
    public function verResumen(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $plan = $solicitud->planCapacitacion;

        if (!$plan) {
            return back()->withErrors(['plan' => 'No hay plan de capacitación para esta solicitud.']);
        }

        return view('areas.formacion-resumen', compact('solicitud', 'plan'));
    }

    /**
     * Marcar módulo como completado
     */
    public function completarModulo(Request $request, PlanCapacitacion $plan)
    {
        $this->authorize('update', $plan->solicitud);

        $validated = $request->validate([
            'indice_modulo' => 'required|numeric',
        ]);

        $modulos = $plan->modulos;
        $indice = (int) $validated['indice_modulo'];

        if (isset($modulos[$indice])) {
            $modulos[$indice]['estado'] = 'Completado';
            $modulos[$indice]['fecha_completacion'] = now()->toDateTimeString();
            
            $plan->update(['modulos' => $modulos]);

            return back()->with('success', "✓ Módulo '{$modulos[$indice]['nombre']}' marcado como completado.");
        }

        return back()->withErrors(['modulo' => 'Módulo no encontrado.']);
    }
}
