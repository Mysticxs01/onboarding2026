<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudServiciosGenerales extends Model
{
    protected $table = 'solicitudes_servicios_generales';

    protected $fillable = [
        'solicitud_id',
        'puesto_trabajo_id',
        'carnet_generado',
        'numero_carnet',
        'fecha_carnetizacion',
        'observaciones'
    ];

    protected $casts = [
        'carnet_generado' => 'boolean',
        'fecha_carnetizacion' => 'datetime',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function puestoTrabajo()
    {
        return $this->belongsTo(PuestoTrabajo::class);
    }
}
