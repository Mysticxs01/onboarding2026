<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleUniforme extends Model
{
    protected $table = 'detalles_uniformes';

    protected $fillable = [
        'solicitud_id',
        'proceso_ingreso_id',
        'talla_camisa',
        'talla_pantalon',
        'talla_zapatos',
        'genero',
        'cantidad_uniformes',
        'observaciones'
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
     * Obtener el kit estándar (más reciente) de uniformes para un cargo
     */
    public static function obtenerKitEstandar($cargoId)
    {
        return self::whereHas('procesoIngreso', function ($query) use ($cargoId) {
            $query->where('cargo_id', $cargoId)
                  ->whereIn('estado', ['completado', 'en progreso']);
        })
        ->latest('created_at')
        ->first();
    }

    /**
     * Obtener estadísticas agregadas de tallas y uniformes para un cargo
     */
    public static function obtenerEstadisticasCargo($cargoId)
    {
        $detalles = self::whereHas('procesoIngreso', function ($query) use ($cargoId) {
            $query->where('cargo_id', $cargoId)
                  ->whereIn('estado', ['completado', 'en progreso']);
        })->get();

        $tallas_camisa = $detalles->pluck('talla_camisa')->filter()->countBy()->sort()->reverse();
        $tallas_pantalon = $detalles->pluck('talla_pantalon')->filter()->countBy()->sort()->reverse();
        $tallas_zapatos = $detalles->pluck('talla_zapatos')->filter()->countBy()->sort()->reverse();
        $generos = $detalles->pluck('genero')->filter()->countBy();
        $cantidades = $detalles->pluck('cantidad_uniformes')->filter()->avg();

        return [
            'total_solicitudes' => $detalles->count(),
            'talla_camisa_sugerida' => $tallas_camisa->first() ?? null,
            'talla_pantalon_sugerida' => $tallas_pantalon->first() ?? null,
            'talla_zapatos_sugerida' => $tallas_zapatos->first() ?? null,
            'genero_predominante' => $generos->sortDesc()->first() ? $generos->sortDesc()->keys()->first() : null,
            'cantidad_promedio' => $cantidades ? floor($cantidades) : null,
            'distribucion_tallas_camisa' => $tallas_camisa->toArray(),
            'distribucion_tallas_pantalon' => $tallas_pantalon->toArray(),
            'distribucion_tallas_zapatos' => $tallas_zapatos->toArray(),
        ];
    }
}
