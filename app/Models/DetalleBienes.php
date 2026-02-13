<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleBienes extends Model
{
    protected $table = 'detalles_bienes';

    protected $casts = [
        'bienes_requeridos' => 'array',
    ];

    protected $fillable = [
        'solicitud_id',
        'bienes_requeridos',
        'observaciones',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }
}
