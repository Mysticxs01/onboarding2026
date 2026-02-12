<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\DetalleTecnologia;
use App\Models\DetalleUniforme;
use App\Models\ProcesoIngreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SolicitudController extends Controller
{
    /**
     * Listar solicitudes del usuario
     */
    public function index()
    {
        $user = auth()->user();
        
        // Si es operador de área (Operador Dotación, Operador TI, etc.), ver solo solicitudes de su área
        if ($user->getRoleNames()->contains(fn($role) => str_contains($role, 'Operador'))) {
            $area = $user->area;
            if (!$area) {
                return back()->withErrors(['error' => 'Tu usuario no tiene un área asignada']);
            }
            $solicitudes = Solicitud::where('area_id', $area->id)
                ->with(['proceso', 'area'])
                ->latest()
                ->paginate(15);
        }
        // Si es jefe, ver solicitudes de sus empleados
        elseif ($user->hasRole('Jefe')) {
            $solicitudes = Solicitud::where(function($q) use ($user) {
                $q->whereHas('proceso', function ($query) use ($user) {
                    $query->where('jefe_id', $user->id);
                })
                ->orWhere('area_id', $user->area_id);
            })->with(['proceso', 'area'])
              ->latest()
              ->paginate(15);
        }
        // Admin ve todas
        elseif ($user->hasRole('Admin')) {
            $solicitudes = Solicitud::whereNotNull('proceso_ingreso_id')
                ->with(['proceso', 'area'])
                ->latest()
                ->paginate(15);
        } else {
            $solicitudes = Solicitud::paginate(15);
        }

        return view('solicitudes.index', compact('solicitudes'));
    }

    /**
     * Ver detalles de la solicitud
     */
    public function show(Solicitud $solicitude)
    {
        $solicitude->load(['proceso' => function($q) {
            $q->with(['cargo', 'area', 'jefe']);
        }, 'area', 'detalleTecnologia', 'detalleUniforme']);
        
        $this->verificarPermiso($solicitude);

        return view('solicitudes.show', compact('solicitude'));
    }

    /**
     * Mostrar formulario para especificar detalles de TI
     */
    public function especificarTI($id)
    {
        $solicitud = Solicitud::findOrFail($id);

        // Verificar que sea una solicitud de Tecnología
        if ($solicitud->tipo !== 'Tecnología') {
            return back()->withErrors(['error' => 'Esta solicitud no es de Tecnología']);
        }

        // Verificar que exista proceso y que el usuario sea jefe del proceso
        if (! $solicitud->proceso || $solicitud->proceso->jefe_id !== auth()->id()) {
            return back()->withErrors(['error' => 'No tiene permiso para editar esta solicitud']);
        }

        $detalle = $solicitud->detalleTecnologia ?? new DetalleTecnologia();
        
        // Obtener kit estándar sugerido basado en el cargo
        $kitEstandar = DetalleTecnologia::obtenerKitEstandar($solicitud->proceso->cargo_id);
        $estadisticas = DetalleTecnologia::obtenerEstadisticasCargo($solicitud->proceso->cargo_id);

        return view('solicitudes.especificar_ti', compact('solicitud', 'detalle', 'kitEstandar', 'estadisticas'));
    }

    /**
     * Guardar detalles de TI
     */
    public function guardarTI(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $request->validate([
            'tipo_computador' => 'required|in:Portátil,Escritorio',
            'marca_computador' => 'required|string',
            'especificaciones' => 'required|string',
            'software_requerido' => 'required|string',
            'monitor_adicional' => 'boolean',
            'mouse_teclado' => 'boolean',
        ]);

        DetalleTecnologia::updateOrCreate(
            ['solicitud_id' => $solicitud->id],
            [
                'proceso_ingreso_id' => $solicitud->proceso_ingreso_id,
                'tipo_computador' => $request->tipo_computador,
                'marca_computador' => $request->marca_computador,
                'especificaciones' => $request->especificaciones,
                'software_requerido' => $request->software_requerido,
                'monitor_adicional' => $request->has('monitor_adicional'),
                'mouse_teclado' => $request->has('mouse_teclado'),
            ]
        );

        return redirect()->route('solicitudes.show', $solicitud->id)
            ->with('success', 'Detalles de TI guardados correctamente');
    }

    /**
     * Mostrar formulario para especificar tallas de uniformes
     */
    public function especificarTallas($id)
    {
        $solicitud = Solicitud::findOrFail($id);

        // Verificar que sea una solicitud de Dotación
        if ($solicitud->tipo !== 'Dotación') {
            return back()->withErrors(['error' => 'Esta solicitud no es de Dotación']);
        }

        // Verificar que exista proceso y que el usuario sea jefe del proceso
        if (! $solicitud->proceso || $solicitud->proceso->jefe_id !== auth()->id()) {
            return back()->withErrors(['error' => 'No tiene permiso para editar esta solicitud']);
        }

        $detalle = $solicitud->detalleUniforme ?? new DetalleUniforme();
        
        // Obtener kit estándar y estadísticas
        $kitEstandar = DetalleUniforme::obtenerKitEstandar($solicitud->proceso->cargo_id);
        $estadisticas = DetalleUniforme::obtenerEstadisticasCargo($solicitud->proceso->cargo_id);

        return view('solicitudes.especificar_tallas', compact('solicitud', 'detalle', 'kitEstandar', 'estadisticas'));
    }

    /**
     * Guardar tallas de uniformes
     */
    public function guardarTallas(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $request->validate([
            'talla_camisa' => 'required|string',
            'talla_pantalon' => 'required|string',
            'talla_zapatos' => 'required|string',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'cantidad_uniformes' => 'required|integer|min:1',
            'observaciones' => 'nullable|string',
        ]);

        DetalleUniforme::updateOrCreate(
            ['solicitud_id' => $solicitud->id],
            [
                'proceso_ingreso_id' => $solicitud->proceso_ingreso_id,
                'talla_camisa' => $request->talla_camisa,
                'talla_pantalon' => $request->talla_pantalon,
                'talla_zapatos' => $request->talla_zapatos,
                'genero' => $request->genero,
                'cantidad_uniformes' => $request->cantidad_uniformes,
                'observaciones' => $request->observaciones,
            ]
        );

        return redirect()->route('solicitudes.show', $solicitud->id)
            ->with('success', 'Tallas guardadas correctamente');
    }

    /**
     * Cambiar estado de la solicitud (Operadores)
     */
    public function cambiarEstado(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $user = auth()->user();

        // Verificar que el usuario sea operador del área o Admin
        $isOperador = $user->getRoleNames()->contains(fn($role) => str_contains($role, 'Operador'));
        $isAdmin = $user->hasRole('Admin');

        // Admin puede siempre
        if ($isAdmin) {
            // allow
        } elseif ($isOperador) {
            // Operador: permitir si la solicitud pertenece a su área OR si su rol coincide con el tipo de solicitud
            $areaMatches = $solicitud->area_id && $user->area_id && $solicitud->area_id === $user->area_id;
            $roleMatchesTipo = $user->getRoleNames()->contains(fn($role) => $solicitud->tipo ? str_contains($role, $solicitud->tipo) : false);

            if (! $areaMatches && ! $roleMatchesTipo) {
                return back()->withErrors(['error' => 'No tiene permiso para editar esta solicitud (operador)']);
            }
        } else {
            return back()->withErrors(['error' => 'No tiene permiso para editar solicitudes']);
        }

        $request->validate([
            'estado' => 'required|in:Pendiente,En Proceso,Entregado,Completado,Finalizada',
            'observaciones' => 'nullable|string',
        ]);

        $estadoAnterior = $solicitud->estado;
        $estadoNuevo = $request->estado;

        $solicitud->update([
            'estado' => $estadoNuevo,
            'observaciones' => $request->observaciones,
        ]);

        // Log del cambio de estado
        \Illuminate\Support\Facades\Log::info("cambio_estado_solicitud", [
            'solicitud_id' => $solicitud->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
            'usuario' => auth()->user()->email,
            'timestamp' => now(),
        ]);

        // Enviar notificación si está disponible
        try {
            $this->enviarNotificacionEstado($solicitud, $estadoAnterior, $estadoNuevo);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("notificacion_state_change_failed: {$e->getMessage()}");
        }

        // Si existe proceso y todas las solicitudes están entregadas o completadas, marcar el proceso como exitoso
        $proceso = $solicitud->proceso;
        if ($proceso && $proceso->solicitudes()->whereNotIn('estado', ['Entregado', 'Completado', 'Finalizada'])->doesntExist()) {
            $proceso->marcarExitoso();
        }

        return back()->with('success', "Solicitud actualizada a '{$estadoNuevo}' exitosamente");
    }

    /**
     * Enviar notificación de cambio de estado
     */
    private function enviarNotificacionEstado(Solicitud $solicitud, string $estadoAnterior, string $estadoNuevo)
    {
        $notificationService = new \App\Services\NotificationService();
        $notificationService->notificarCambioEstado($solicitud, $estadoAnterior, $estadoNuevo);
    }

    /**
     * Actualizar solicitud (generalmente solo estado)
     */
    public function update(Request $request, Solicitud $solicitude)
    {
        $this->verificarPermiso($solicitude);

        $request->validate([
            'estado' => 'in:Pendiente,En Proceso,Finalizada',
            'observaciones' => 'nullable|string',
        ]);

        if ($request->has('estado')) {
            $solicitude->estado = $request->estado;
        }

        if ($request->has('observaciones')) {
            $solicitude->observaciones = $request->observaciones;
        }

        $solicitude->save();

        return redirect()->route('solicitudes.show', $solicitude->id)
            ->with('success', 'Solicitud actualizada correctamente');
    }

    /**
     * Verificar permisos del usuario
     */
    private function verificarPermiso($solicitud)
    {
        $user = auth()->user();

        // Admin ve todo
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Root ve todo
        if ($user->hasRole('Root')) {
            return true;
        }

        // Operador (Operador Dotación, Operador Formación, etc.) puede ver solicitudes de su área
        // o aquellas cuyo tipo coincide con su rol (e.g., 'Operador Dotación' -> 'Dotación')
        $isOperador = $user->getRoleNames()->contains(fn($role) => str_contains($role, 'Operador'));
        if ($isOperador) {
            $areaMatches = $solicitud->area_id && $user->area_id && $solicitud->area_id === $user->area_id;
            $roleMatchesTipo = $solicitud->tipo ? $user->getRoleNames()->contains(fn($role) => str_contains($role, $solicitud->tipo)) : false;
            if ($areaMatches || $roleMatchesTipo) {
                return true;
            }
        }

        // Jefe puede ver solicitudes relacionadas con procesos donde es jefe
        // o solicitudes del área que dirige
        if ($user->hasRole('Jefe')) {
            if ($solicitud->proceso && $solicitud->proceso->jefe_id === $user->id) {
                return true;
            }
            if ($solicitud->area_id && $user->area_id && $solicitud->area_id === $user->area_id) {
                return true;
            }
        }

        // Log debug info to help diagnose permission issues
        Log::warning('Solicitud permiso denegado', [
            'user_id' => $user->id ?? null,
            'user_area_id' => $user->area_id ?? null,
            'user_roles' => $user->getRoleNames()->toArray(),
            'solicitud_id' => $solicitud->id ?? null,
            'solicitud_area_id' => $solicitud->area_id ?? null,
            'solicitud_tipo' => $solicitud->tipo ?? null,
            'solicitud_proceso_jefe' => $solicitud->proceso->jefe_id ?? null,
        ]);

        abort(403, 'No tiene permiso para ver esta solicitud (user_id: '.($user->id ?? 'null').', user_area: '.($user->area_id ?? 'null').', solicitud_id: '.($solicitud->id ?? 'null').', solicitud_area: '.($solicitud->area_id ?? 'null').', tipo: '.($solicitud->tipo ?? 'null').')');
    }
}

