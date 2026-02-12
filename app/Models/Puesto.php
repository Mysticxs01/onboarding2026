<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    protected $table = 'puestos';

    protected $fillable = [
        'numero',
        'fila',
        'columna',
        'estado',
        'proceso_ingreso_id'
    ];

    public function procesoIngreso()
    {
        return $this->belongsTo(ProcesoIngreso::class);
    }
}
