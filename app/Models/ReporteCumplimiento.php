<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteCumplimiento extends Model
{
    protected $table = 'reporte_cumplimiento';

    protected $fillable = [
        'fecha_reporte',
        'area_id',
        'total_procesos',
        'procesos_completados',
        'procesos_retrasados',
        'procesos_pendientes',
        'porcentaje_cumplimiento',
        'dias_promedio_completacion',
    ];

    protected $casts = [
        'fecha_reporte' => 'date',
    ];

    // Relaciones
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    // Métodos Estáticos
    public static function generarPorArea($areaId, $fecha = null)
    {
        $fecha = $fecha ?? now()->format('Y-m-d');
        
        $procesos = ProcesoIngreso::where('area_id', $areaId)->get();
        $total = $procesos->count();
        
        if ($total === 0) {
            return null;
        }

        $completados = $procesos->where('estado', 'Finalizado')->count();
        $retrasados = $procesos->filter(function ($p) {
            return $p->estaRetrasado();
        })->count();
        $pendientes = $procesos->whereIn('estado', ['Pendiente', 'En Proceso'])->count();

        $porcentaje = ($completados / $total) * 100;
        
        $diasPromedio = 0;
        $procesosConFecha = $procesos->whereNotNull('fecha_finalizacion');
        if ($procesosConFecha->count() > 0) {
            $diasPromedio = $procesosConFecha->map(function ($p) {
                return $p->fecha_ingreso->diffInDays($p->fecha_finalizacion);
            })->average();
        }

        return self::updateOrCreate(
            [
                'fecha_reporte' => $fecha,
                'area_id' => $areaId,
            ],
            [
                'total_procesos' => $total,
                'procesos_completados' => $completados,
                'procesos_retrasados' => $retrasados,
                'procesos_pendientes' => $pendientes,
                'porcentaje_cumplimiento' => round($porcentaje, 2),
                'dias_promedio_completacion' => round($diasPromedio, 2),
            ]
        );
    }

    public static function generarTodosLosAreas($fecha = null)
    {
        $areas = Area::all();
        $reportes = [];

        foreach ($areas as $area) {
            $reporte = self::generarPorArea($area->id, $fecha);
            if ($reporte) {
                $reportes[] = $reporte;
            }
        }

        return collect($reportes);
    }
}
