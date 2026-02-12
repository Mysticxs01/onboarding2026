<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantillaSolicitud extends Model
{
    // Indicar la tabla correcta según la migración
    protected $table = 'plantilla_solicitudes';

    protected $fillable = [
        'cargo_id',
        'area_id',
        'tipo_solicitud',
        'dias_maximos'
    ];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}

