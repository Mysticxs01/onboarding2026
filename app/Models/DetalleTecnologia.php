<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleTecnologia extends Model
{
    protected $table = 'detalles_tecnologia';

    protected $fillable = [
        'solicitud_id',
        'proceso_ingreso_id',
        'tipo_computador',
        'marca_computador',
        'especificaciones',
        'software_requerido',
        'monitor_adicional',
        'mouse_teclado'
    ];

    protected $casts = [
        'monitor_adicional' => 'boolean',
        'mouse_teclado' => 'boolean',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function procesoIngreso()
    {
        return $this->belongsTo(ProcesoIngreso::class);
    }

    /**
     * Obtener el kit estándar de tecnología para un cargo específico
     * basado en ingresos anteriores
     */
    public static function obtenerKitEstandar($cargoId)
    {
        if (!$cargoId) {
            return null;
        }

        // Buscar el detalle técnico más reciente para este cargo
        return self::whereHas('procesoIngreso', function ($query) use ($cargoId) {
            $query->where('cargo_id', $cargoId)
                  ->whereIn('estado', ['Finalizado', 'En Proceso']);
        })
        ->latest('created_at')
        ->first();
    }

    /**
     * Obtener estadísticas de lo solicitado para un cargo
     * para mostrar como sugerencias
     */
    public static function obtenerEstadisticasCargo($cargoId)
    {
        if (!$cargoId) {
            return [];
        }

        // Obtener todos los detalles técnicos para este cargo
        $detalles = self::whereHas('procesoIngreso', function ($query) use ($cargoId) {
            $query->where('cargo_id', $cargoId);
        })->get();

        if ($detalles->isEmpty()) {
            return [];
        }

        // Contar tipos de computador más solicitados
        $tiposComputadora = $detalles->groupBy('tipo_computador')
            ->map->count()
            ->sortDesc();

        // Marcas más solicitadas
        $marcas = $detalles->groupBy('marca_computador')
            ->map->count()
            ->sortDesc();

        // Software más solicitado (split por comas, si es aplicable)
        $softwareLista = [];
        foreach ($detalles as $detalle) {
            if ($detalle->software_requerido) {
                $softwareLista[] = $detalle->software_requerido;
            }
        }

        // Accesorios más comunes
        $monitorAdicional = $detalles->where('monitor_adicional', true)->count() > 
                            $detalles->where('monitor_adicional', false)->count();
        $mouseKeyboard = $detalles->where('mouse_teclado', true)->count() > 
                         $detalles->where('mouse_teclado', false)->count();

        return [
            'total_solicitudes' => $detalles->count(),
            'tipo_computador_sugerido' => $tiposComputadora->keys()->first(),
            'marca_sugerida' => $marcas->keys()->first(),
            'software_frecuente' => $softwareLista,
            'monitor_adicional_comun' => $monitorAdicional,
            'mouse_keyboard_comun' => $mouseKeyboard,
            'especificaciones_recientes' => $detalles->pluck('especificaciones')->unique()->values()->take(3)->toArray(),
        ];
    }
}
