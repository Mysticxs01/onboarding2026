<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanCapacitacion extends Model
{
    protected $table = 'planes_capacitacion';

    protected $fillable = [
        'solicitud_id',
        'cargo_id',
        'titulo_plan',
        'descripcion',
        'duracion_horas',
        'fecha_inicio_estimada',
        'fecha_fin_estimada',
        'modulos',
        'responsable_capacitacion',
        'estado',
        'email_enviado',
        'fecha_email_enviado'
    ];

    protected $casts = [
        'modulos' => 'array',
        'email_enviado' => 'boolean',
        'fecha_email_enviado' => 'datetime',
        'fecha_inicio_estimada' => 'datetime',
        'fecha_fin_estimada' => 'datetime',
    ];

    /**
     * Estados posibles
     */
    public static function obtenerEstados()
    {
        return [
            'Diseño' => 'En Diseño',
            'Programado' => 'Programado',
            'En Ejecución' => 'En Ejecución',
            'Completado' => 'Completado',
            'Cancelado' => 'Cancelado',
        ];
    }

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    /**
     * Obtener plan estándar por cargo
     */
    public static function obtenerPlanPorCargo($cargoId)
    {
        // Buscar planes existentes para ese cargo
        $planesExistentes = self::where('cargo_id', $cargoId)
            ->where('estado', '!=', 'Cancelado')
            ->latest('created_at')
            ->first();

        if ($planesExistentes) {
            return $planesExistentes;
        }

        // Crear plan por defecto basado en cargo
        return self::crearPlanPorDefecto($cargoId);
    }

    /**
     * Crear plan estándar por cargo
     */
    public static function crearPlanPorDefecto($cargoId)
    {
        $cargo = Cargo::find($cargoId);
        if (!$cargo) return null;

        $planes = [
            // Planes por tipo de cargo (ejemplos)
            'default' => [
                'titulo_plan' => "Inducción - {$cargo->nombre}",
                'duracion_horas' => 40,
                'modulos' => [
                    'Bienvenida e Inducción Corporativa',
                    'Políticas y Procedimientos',
                    'Seguridad y Salud en el Trabajo',
                    'Ética Empresarial',
                    'Sistema de Gestión de Documentos',
                    'Introducción al Rol',
                ]
            ]
        ];

        return $planes['default'] ?? [
            'titulo_plan' => "Inducción - {$cargo->nombre}",
            'duracion_horas' => 40,
            'modulos' => [
                'Bienvenida e Inducción Corporativa',
                'Políticas y Procedimientos',
                'Seguridad y Salud en el Trabajo',
            ]
        ];
    }
}
