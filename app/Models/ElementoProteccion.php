<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElementoProteccion extends Model
{
    protected $table = 'elementos_proteccion';

    protected $fillable = [
        'solicitud_id',
        'tipo_elemento',
        'descripcion',
        'cantidad',
        'talla',
        'color',
        'estado',
        'entregado',
        'fecha_entrega',
        'observaciones'
    ];

    protected $casts = [
        'entregado' => 'boolean',
        'fecha_entrega' => 'datetime',
    ];

    /**
     * Tipos de equipos de protección
     */
    public static function obtenerTipos()
    {
        return [
            'Casco' => 'Casco de Seguridad',
            'Chaleco' => 'Chaleco Reflectivo',
            'Guantes' => 'Guantes de Protección',
            'Zapatos' => 'Zapatos de Seguridad',
            'Gafas' => 'Gafas de Protección',
            'Tapabocas' => 'Tapabocas / Mascarilla',
            'Cinturón' => 'Cinturón de Seguridad',
            'Otro' => 'Otro Equipo',
        ];
    }

    /**
     * Tallas disponibles
     */
    public static function obtenerTallas()
    {
        return [
            'XS' => 'Extra Pequeño (XS)',
            'S' => 'Pequeño (S)',
            'M' => 'Mediano (M)',
            'L' => 'Grande (L)',
            'XL' => 'Extra Grande (XL)',
            'XXL' => 'Doble Extra Grande (XXL)',
            'Único' => 'Talla Única',
        ];
    }

    /**
     * Colores disponibles
     */
    public static function obtenerColores()
    {
        return [
            'Negro' => 'Negro',
            'Blanco' => 'Blanco',
            'Rojo' => 'Rojo',
            'Azul' => 'Azul',
            'Amarillo' => 'Amarillo',
            'Naranja' => 'Naranja',
            'Verde' => 'Verde',
            'Otro' => 'Otro',
        ];
    }

    /**
     * Estados posibles del elemento
     */
    public static function obtenerEstados()
    {
        return [
            'Pendiente' => 'Pendiente de Entrega',
            'Disponible' => 'Disponible para Entregar',
            'Entregado' => 'Entregado',
            'Insuficiente Talla' => 'Sin Talla Disponible',
            'Descontinuado' => 'Artículo Descontinuado',
        ];
    }

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    /**
     * Obtener elementos de protección estándar por cargo
     */
    public static function obtenerKitEstandarPorCargo($cargoId)
    {
        $cargo = Cargo::find($cargoId);
        
        // Kits estándar por nivel/tipo de cargo
        $kits = [
            'Operario' => [
                ['tipo_elemento' => 'Casco', 'cantidad' => 1],
                ['tipo_elemento' => 'Chaleco', 'cantidad' => 2],
                ['tipo_elemento' => 'Guantes', 'cantidad' => 2],
                ['tipo_elemento' => 'Zapatos', 'cantidad' => 1],
            ],
            'Administrativo' => [
                ['tipo_elemento' => 'Tapabocas', 'cantidad' => 10],
            ],
            'default' => [
                ['tipo_elemento' => 'Tapabocas', 'cantidad' => 10],
                ['tipo_elemento' => 'Guantes', 'cantidad' => 1],
            ],
        ];

        $tipoKit = $cargo ? 'Operario' : 'default';
        return $kits[$tipoKit] ?? $kits['default'];
    }
}
