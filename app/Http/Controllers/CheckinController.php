<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\ProcesoIngreso;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CheckinController extends Controller
{
    /**
     * Listar check-ins (admin)
     */
    public function index()
    {
        $checkins = Checkin::with('procesoIngreso')
            ->latest()
            ->paginate(15);

        return view('checkins.index', compact('checkins'));
    }

    /**
     * Generar check-in para un proceso
     */
    public function generar($procesoId)
    {
        $proceso = ProcesoIngreso::with(['solicitudes', 'puesto'])->findOrFail($procesoId);

        // Verificar que todas las solicitudes estén finalizadas
        if ($proceso->solicitudes()->where('estado', '!=', 'Finalizada')->exists()) {
            return back()->withErrors(['error' => 'No todas las solicitudes están finalizadas']);
        }

        // Construir listado de activos
        $activos = [];

        foreach ($proceso->solicitudes as $solicitud) {
            switch ($solicitud->tipo) {
                case 'Tecnología':
                    if ($detalles = $solicitud->detalleTecnologia) {
                        $activos[] = [
                            'item' => "Computador {$detalles->tipo_computador} ({$detalles->marca_computador})",
                            'especificaciones' => $detalles->especificaciones,
                            'entregado' => false,
                        ];
                        if ($detalles->monitor_adicional) {
                            $activos[] = [
                                'item' => 'Monitor Adicional',
                                'especificaciones' => null,
                                'entregado' => false,
                            ];
                        }
                        if ($detalles->mouse_teclado) {
                            $activos[] = [
                                'item' => 'Mouse y Teclado',
                                'especificaciones' => null,
                                'entregado' => false,
                            ];
                        }
                    }
                    break;

                case 'Dotación':
                    if ($detalles = $solicitud->detalleUniforme) {
                        $activos[] = [
                            'item' => "Uniforme {$detalles->cantidad_uniformes} piezas",
                            'especificaciones' => "Tallas: Camisa {$detalles->talla_camisa}, Pantalón {$detalles->talla_pantalon}, Zapatos {$detalles->talla_zapatos}",
                            'entregado' => false,
                        ];
                    }
                    break;

                case 'Servicios Generales':
                    if ($proceso->puesto) {
                        $activos[] = [
                            'item' => "Puesto de Trabajo ({$proceso->puesto->numero})",
                            'especificaciones' => null,
                            'entregado' => false,
                        ];
                    }
                    break;

                default:
                    $activos[] = [
                        'item' => ucfirst($solicitud->tipo),
                        'especificaciones' => $solicitud->observaciones,
                        'entregado' => false,
                    ];
            }
        }

        // Crear check-in
        $checkin = Checkin::create([
            'proceso_ingreso_id' => $proceso->id,
            'codigo_verificacion' => Checkin::generarCodigoVerificacion(),
            'activos_entregados' => $activos,
            'estado_checkin' => 'Pendiente',
            'fecha_generacion' => now(),
            'email_empleado' => $proceso->jefe->email, // Usar email del jefe como contacto
        ]);

        return redirect()->route('checkins.show', $checkin->id)
            ->with('success', 'Check-in generado correctamente. Se enviará email de confirmación.');
    }

    /**
     * Ver detalles del check-in
     */
    public function show($id)
    {
        $checkin = Checkin::with('procesoIngreso')->findOrFail($id);

        return view('checkins.show', compact('checkin'));
    }

    /**
     * Generar PDF del acta de entrega
     */
    public function generarPDF($id)
    {
        $checkin = Checkin::with('procesoIngreso.cargo', 'procesoIngreso.jefe', 'procesoIngreso.area')->findOrFail($id);
        $proceso = $checkin->procesoIngreso;

        // Generar PDF
        $pdf = Pdf::loadView('checkins.pdf', compact('checkin', 'proceso'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("acta_entrega_{$proceso->codigo}.pdf");
    }

    /**
     * Mostrar formulario de confirmación (para el empleado)
     */
    public function confirmar($codigo)
    {
        // El código se envía al empleado por email
        $checkin = Checkin::where('codigo_verificacion', $codigo)
            ->where('estado_checkin', 'Pendiente')
            ->with('procesoIngreso')
            ->firstOrFail();

        return view('checkins.confirmar', compact('checkin'));
    }

    /**
     * Procesar confirmación de activos entregados
     */
    public function procesarConfirmacion(Request $request, $codigo)
    {
        $checkin = Checkin::where('codigo_verificacion', $codigo)
            ->where('estado_checkin', 'Pendiente')
            ->firstOrFail();

        $request->validate([
            'nombre_persona' => 'required|string|max:255',
            'cedula_persona' => 'required|string|max:20',
            'firma_digital' => 'required|string',
            'aceptar_terminos' => 'required|boolean',
        ]);

        // Procesar activos confirmados
        $activos = $checkin->activos_entregados;
        $activos_confirmados = [];
        
        if ($request->has('activos_confirmados')) {
            $activos_confirmados = json_decode($request->input('activos_confirmados'), true) ?? [];
        }

        // Marcar activos como entregados según lo confirmado
        foreach ($activos_confirmados as $confirmados) {
            $index = $confirmados['index'];
            if (isset($activos[$index])) {
                $activos[$index]['entregado'] = $confirmados['entregado'];
            }
        }

        // Limpiar firma digital para que no sea demasiado grande en base64 si es necesario
        $firma = $request->firma_digital;
        if (strlen($firma) > 1000000) { // Si es más de 1MB
            $firma = substr($firma, 0, 1000000); // Truncar
        }

        // Confirmar check-in
        $checkin->update([
            'activos_entregados' => $activos,
            'estado_checkin' => 'Completado',
            'fecha_confirmacion' => now(),
            'firma_digital' => $firma,
            'dispositivo_confirmacion' => $request->userAgent() ?? 'Desconocido',
            'ip_confirmacion' => $request->ip(),
        ]);

        // Marcar proceso como completo
        $checkin->procesoIngreso->update(['estado' => 'Finalizado']);

        return response()->json([
            'success' => true,
            'message' => 'Activos confirmados correctamente. ¡Bienvenido!',
            'checkin_id' => $checkin->id,
        ]);
    }

    /**
     * Página de confirmación completada
     */
    public function confirmado($codigo)
    {
        $checkin = Checkin::where('codigo_verificacion', $codigo)
            ->with('procesoIngreso')
            ->firstOrFail();

        if ($checkin->estado_checkin !== 'Completado') {
            return back()->withErrors(['error' => 'Este check-in aún no ha sido confirmado']);
        }

        return view('checkins.confirmado', compact('checkin'));
    }

    /**
     * Ver estado del check-in (público - sin autenticación)
     */
    public function verificarEstado($codigo)
    {
        $checkin = Checkin::where('codigo_verificacion', $codigo)->firstOrFail();
        $porcentaje = $checkin->obtenerPorcentajeCompletado();

        return response()->json([
            'codigo_verificacion' => $checkin->codigo_verificacion,
            'estado' => $checkin->estado_checkin,
            'empleado' => $checkin->procesoIngreso->nombre_completo,
            'cargo' => $checkin->procesoIngreso->cargo->nombre,
            'porcentaje_completado' => $porcentaje,
            'activos' => $checkin->activos_entregados,
        ]);
    }
}
