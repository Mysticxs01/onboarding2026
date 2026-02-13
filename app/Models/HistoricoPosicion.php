<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoricoPosicion extends Model
{
    protected $table = 'historico_posiciones';

    protected $fillable = [
        'posicion_id',
        'usuario_anterior_id',
        'usuario_nuevo_id',
        'tipo_movimiento',
        'razon',
        'realizado_por',
        'datos_anteriores',
        'datos_nuevos',
    ];

    protected $casts = [
        'datos_anteriores' => 'array',
        'datos_nuevos' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function posicion(): BelongsTo
    {
        return $this->belongsTo(Posicion::class);
    }

    public function usuarioAnterior(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_anterior_id');
    }

    public function usuarioNuevo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_nuevo_id');
    }

    // Scopes
    public function scopePorPosicion($query, $posicionId)
    {
        return $query->where('posicion_id', $posicionId);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_movimiento', $tipo);
    }

    public function scopeEntreFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('created_at', [$desde, $hasta]);
    }
}
