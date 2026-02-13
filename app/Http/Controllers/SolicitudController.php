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
     * Listar solicitudes según el rol del usuario
     */
    public function index()
    {
        $user = auth()->user();
        
        // Root ve todas las solicitudes
        if ($user->hasRole('Root')) {
            $solicitudes = Solicitud::with(['proceso', 'area', 'detalleTecnologia', 'detalleUniforme', 'detalleBienes', 'puestoTrabajo', 'cursos'])
                ->latest()
                ->paginate(15);
        }
        // Jefe RRHH ve todas las solicitudes (gestiona todo el onboarding)
        elseif ($user->hasRole('Jefe RRHH')) {
            $solicitudes = Solicitud::with(['proceso', 'area', 'detalleTecnologia', 'detalleUniforme', 'detalleBienes', 'puestoTrabajo', 'cursos'])
                ->latest()
                ->paginate(15);
        }
        // Jefe Tecnología ve SOLO solicitudes de Tecnología
        elseif ($user->hasRole('Jefe Tecnología')) {
            $solicitudes = Solicitud::where('tipo', 'Tecnología')
                ->with(['proceso', 'area', 'detalleTecnologia'])
                ->latest()
                ->paginate(15);
        }
        // Jefe Dotación ve SOLO solicitudes de Dotación
        elseif ($user->hasRole('Jefe Dotación')) {
            $solicitudes = Solicitud::where('tipo', 'Dotación')
                ->with(['proceso', 'area', 'detalleUniforme'])
                ->latest()
                ->paginate(15);
        }
        // Jefe Servicios Generales ve SOLO solicitudes de Servicios Generales
        elseif ($user->hasRole('Jefe Servicios Generales')) {
            $solicitudes = Solicitud::where('tipo', 'Servicios Generales')
                ->with(['proceso', 'area', 'puestoTrabajo'])
                ->latest()
                ->paginate(15);
        }
        // Jefe Bienes y Servicios ve SOLO solicitudes de Bienes
        elseif ($user->hasRole('Jefe Bienes y Servicios')) {
            $solicitudes = Solicitud::where('tipo', 'Bienes')
                ->with(['proceso', 'area', 'detalleBienes'])
                ->latest()
                ->paginate(15);
        }
        // Otros usuarios ven sus propias solicitudes
        else {
            $solicitudes = Solicitud::where('proceso_ingreso_id', auth()->id())
                ->with(['proceso', 'area'])
                ->latest()
                ->paginate(15);
        }

        return view('solicitudes.index', compact('solicitudes'));
    }

    /**
     * Ver detalles de la solicitud - Redirige a vista específica por tipo
     */
    public function show(Solicitud $solicitude)
    {
        $this->verificarPermiso($solicitude);
        
        $solicitude->load([
            'proceso' => function($q) {
                $q->with(['cargo', 'area', 'jefe']);
            },
            'area',
            'detalleTecnologia',
            'detalleUniforme',
            'detalleBienes',
            'puestoTrabajo',
            'cursos'
        ]);

        // Redirigir a vista específica según el tipo
        $tipoView = match($solicitude->tipo) {
            'Tecnología' => 'tipo-tecnologia',
            'Dotación' => 'tipo-dotacion',
            'Servicios Generales' => 'tipo-servicios-generales',
            'Formación' => 'tipo-formacion',
            'Bienes' => 'tipo-bienes',
            default => 'show',
        };

        return view("solicitudes.{$tipoView}", compact('solicitude'));
    }

    /**
     * Guardar especificaciones de Tecnología
     */
    public function guardarTecnologia(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $request->validate([
            'necesita_computador' => 'required|boolean',
            'gama_computador' => 'nullable|required_if:necesita_computador,1|in:Básica,Media,Premium',
            'credenciales_plataformas' => 'required|string|max:2000',
        ]);

        $detalleTecnologia = DetalleTecnologia::firstOrCreate(
            ['solicitud_id' => $solicitud->id],
            []
        );

        $detalleTecnologia->update([
            'necesita_computador' => (bool) $request->necesita_computador,
            'gama_computador' => $request->gama_computador,
            'credenciales_plataformas' => $request->credenciales_plataformas,
        ]);

        return redirect()->route('solicitudes.show', $solicitud->id)
                       ->with('success', 'Especificaciones de tecnología guardadas correctamente');
    }

    /**
     * Guardar especificaciones de Dotación
     */
    public function guardarDotacion(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $request->validate([
            'necesita_dotacion' => 'required|boolean',
            'genero' => 'nullable|required_if:necesita_dotacion,1|in:Masculino,Femenino,Otro',
            'talla_pantalon' => 'nullable|required_if:necesita_dotacion,1|string|max:50',
            'talla_camiseta' => 'nullable|required_if:necesita_dotacion,1|string|max:50',
            'justificacion_no_dotacion' => 'nullable|required_if:necesita_dotacion,0|string|max:1000',
        ]);

        $detalleUniforme = DetalleUniforme::firstOrCreate(
            ['solicitud_id' => $solicitud->id],
            []
        );

        $detalleUniforme->update([
            'necesita_dotacion' => (bool) $request->necesita_dotacion,
            'genero' => $request->genero,
            'talla_pantalon' => $request->talla_pantalon,
            'talla_camiseta' => $request->talla_camiseta,
            'justificacion_no_dotacion' => $request->justificacion_no_dotacion,
        ]);

        return redirect()->route('solicitudes.show', $solicitud->id)
                       ->with('success', 'Especificaciones de dotación guardadas correctamente');
    }

    /**
     * Guardar asignación de puesto (Servicios Generales)
     */
    public function guardarServiciosGenerales(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $request->validate([
            'puesto_trabajo_id' => 'required|exists:puestos_trabajo,id',
        ]);

        $solicitud->puesto_trabajo_id = $request->puesto_trabajo_id;
        $solicitud->save();

        // Marcar el puesto como asignado
        $puesto = \App\Models\PuestoTrabajo::find($request->puesto_trabajo_id);
        if ($puesto) {
            $puesto->estado = 'Asignado';
            $puesto->save();
        }

        return redirect()->route('solicitudes.show', $solicitud->id)
                       ->with('success', 'Puesto de trabajo asignado correctamente');
    }

    /**
     * Guardar cursos de Formación
     */
    public function guardarFormacion(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $request->validate([
            'curso_ids' => 'nullable|array',
            'curso_ids.*' => 'exists:cursos,id',
        ]);

        // Limpiar cursos anteriores
        $solicitud->cursos()->detach();

        // Asignar nuevos cursos
        if ($request->has('curso_ids')) {
            $solicitud->cursos()->attach($request->curso_ids);
        }

        return redirect()->route('solicitudes.show', $solicitud->id)
                       ->with('success', 'Plan de formación guardado correctamente');
    }

    /**
     * Guardar bienes y servicios
     */
    public function guardarBienes(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $request->validate([
            'bienes' => 'nullable|array',
            'observaciones_bienes' => 'nullable|string|max:1000',
        ]);

        $detalleBienes = \App\Models\DetalleBienes::firstOrCreate(
            ['solicitud_id' => $solicitud->id],
            []
        );

        $detalleBienes->update([
            'bienes_requeridos' => json_encode($request->bienes ?? []),
            'observaciones' => $request->observaciones_bienes,
        ]);

        return redirect()->route('solicitudes.show', $solicitud->id)
                       ->with('success', 'Bienes y servicios guardados correctamente');
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

        // Root y Jefe RRHH pueden cambiar estado de cualquier solicitud
        $puedeEditar = $user->hasRole(['Root', 'Jefe RRHH']);

        // Jefes específicos pueden cambiar estado de sus tipos
        if (!$puedeEditar) {
            $tipoRolMap = [
                'Tecnología' => 'Jefe Tecnología',
                'Dotación' => 'Jefe Dotación',
                'Servicios Generales' => 'Jefe Servicios Generales',
                'Formación' => 'Jefe RRHH', // Formación la maneja Jefe RRHH
                'Bienes' => 'Jefe Bienes y Servicios',
            ];

            $rolRequerido = $tipoRolMap[$solicitud->tipo] ?? null;
            if ($rolRequerido && $user->hasRole($rolRequerido)) {
                $puedeEditar = true;
            }
        }

        if (!$puedeEditar) {
            return back()->withErrors(['error' => 'No tienes permiso para cambiar el estado de esta solicitud']);
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

        // Si todas las solicitudes están en 'Finalizada', marcar el proceso como completado
        $proceso = $solicitud->proceso;
        if ($proceso && $estadoNuevo === 'Finalizada') {
            // Verificar si TODAS las solicitudes están en Finalizada
            $todasFinalizadas = $proceso->solicitudes()->where('estado', '!=', 'Finalizada')->doesntExist();
            if ($todasFinalizadas) {
                $proceso->update(['estado' => 'Finalizado']);
                session()->flash('success', "¡Proceso de onboarding completado! Ahora puede generar el check-in consolidado.");
            }
        }

        return back()->with('success', "Solicitud actualizada a '{$estadoNuevo}' exitosamente");
    }

    /**
     * Enviar notificación de cambio de estado
     */
    /**
     * Mostrar check-in consolidado (cuando todas las solicitudes están finalizadas)
     */
    public function checkinConsolidado($procesoId)
    {
        $proceso = \App\Models\ProcesoIngreso::with(['solicitudes', 'cargo', 'area', 'jefe'])->findOrFail($procesoId);

        // Verificar que sea Jefe RRHH, Root, o un Jefe del proceso
        $user = auth()->user();
        $puedeVerCheckin = $user->hasRole(['Root', 'Jefe RRHH']) || 
                          $user->hasRole(['Jefe Tecnología', 'Jefe Dotación', 'Jefe Servicios Generales', 'Jefe Bienes y Servicios']);

        if (!$puedeVerCheckin) {
            abort(403, 'No tienes permiso para ver este check-in.');
        }

        // Verificar que TODAS las solicitudes estén finalizadas
        if ($proceso->solicitudes()->where('estado', '!=', 'Finalizada')->exists()) {
            return back()->withErrors(['error' => 'No todas las solicitudes están finalizadas. El check-in solo puede verse cuando todas sean "Finalizada".']);
        }

        // Cargar todas las solicitudes y sus detalles
        $solicitudes = $proceso->solicitudes()->with(['detalleTecnologia', 'detalleUniforme', 'detalleBienes', 'puestoTrabajo', 'cursos'])->get();

        $tecnologia = $solicitudes->firstWhere('tipo', 'Tecnología');
        $dotacion = $solicitudes->firstWhere('tipo', 'Dotación');
        $serviciosGenerales = $solicitudes->firstWhere('tipo', 'Servicios Generales');
        $formacion = $solicitudes->firstWhere('tipo', 'Formación');
        $bienes = $solicitudes->firstWhere('tipo', 'Bienes');

        return view('solicitudes.checkin-consolidado', compact(
            'proceso',
            'tecnologia',
            'dotacion',
            'serviciosGenerales',
            'formacion',
            'bienes'
        ));
    }

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

        // Root puede ver todo
        if ($user->hasRole('Root')) {
            return true;
        }

        // Jefe RRHH puede ver todas las solicitudes
        if ($user->hasRole('Jefe RRHH')) {
            return true;
        }

        // Jefe Tecnología puede ver SOLO solicitudes de Tecnología
        if ($user->hasRole('Jefe Tecnología')) {
            if ($solicitud->tipo === 'Tecnología') {
                return true;
            }
        }

        // Jefe Dotación puede ver SOLO solicitudes de Dotación
        if ($user->hasRole('Jefe Dotación')) {
            if ($solicitud->tipo === 'Dotación') {
                return true;
            }
        }

        // Jefe Servicios Generales puede ver SOLO solicitudes de Servicios Generales
        if ($user->hasRole('Jefe Servicios Generales')) {
            if ($solicitud->tipo === 'Servicios Generales') {
                return true;
            }
        }

        // Jefe Bienes y Servicios puede ver SOLO solicitudes de Bienes
        if ($user->hasRole('Jefe Bienes y Servicios')) {
            if ($solicitud->tipo === 'Bienes') {
                return true;
            }
        }

        // Si no tiene permiso, lanzar excepción
        abort(403, 'No tienes permiso para ver esta solicitud.');
    }
}

