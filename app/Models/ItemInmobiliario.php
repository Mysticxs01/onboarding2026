<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemInmobiliario extends Model
{
    protected $table = 'items_inmobiliario';

    protected $fillable = [
        'solicitud_id',
        'tipo_item',
        'descripcion',
        'cantidad',
        'estado',
        'observaciones',
        'entregado_por',
        'fecha_entrega'
    ];

    protected $casts = [
        'fecha_entrega' => 'datetime',
    ];

    /**
     * Tipos de inmobiliario disponibles
     */
    public static function obtenerTipos()
    {
        return [
            'Silla' => 'Silla Ejecutiva',
            'Escritorio' => 'Escritorio',
            'Estantería' => 'Estantería / Archivador',
            'Lámpara' => 'Lámpara de Escritorio',
            'Papelería' => 'Set de Papelería',
            'Otro' => 'Otro',
        ];
    }

    /**
     * Estados posibles
     */
    public static function obtenerEstados()
    {
        return [
            'Pendiente' => 'Pendiente',
            'Disponible' => 'Disponible',
            'Asignado' => 'Asignado',
            'Entregado' => 'Entregado',
            'No Disponible' => 'No Disponible',
        ];
    }

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    /**
     * Obtener kit estándar por cargo
     */
    public static function obtenerKitEstandarPorCargo($cargoId)
    {
        // Kits predefinidos por tipo de cargo
        $kitsEstandar = [
            'Ejecutivo' => [
                ['tipo_item' => 'Silla', 'cantidad' => 1],
                ['tipo_item' => 'Escritorio', 'cantidad' => 1],
                ['tipo_item' => 'Estantería', 'cantidad' => 2],
                ['tipo_item' => 'Lámpara', 'cantidad' => 1],
                ['tipo_item' => 'Papelería', 'cantidad' => 1],
            ],
            'Operativo' => [
                ['tipo_item' => 'Silla', 'cantidad' => 1],
                ['tipo_item' => 'Escritorio', 'cantidad' => 1],
                ['tipo_item' => 'Papelería', 'cantidad' => 1],
            ],
            'default' => [
                ['tipo_item' => 'Silla', 'cantidad' => 1],
                ['tipo_item' => 'Escritorio', 'cantidad' => 1],
                ['tipo_item' => 'Papelería', 'cantidad' => 1],
            ],
        ];

        $cargo = Cargo::find($cargoId);
        $tipoKit = $cargo ? 'Ejecutivo' : 'default';

        return $kitsEstandar[$tipoKit] ?? $kitsEstandar['default'];
    }
}
