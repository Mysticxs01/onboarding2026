<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Posicion extends Model
{
    use SoftDeletes;

    protected $table = 'posiciones';

    protected $fillable = [
        'cargo_id',
        'area_id',
        'usuario_id',
        'puesto_trabajo_id',
        'sucursal',
        'estado',
        'razon_bloqueo',
        'fecha_disponible_desde',
        'fecha_disponible_hasta',
        'observaciones',
    ];

    protected $casts = [
        'fecha_disponible_desde' => 'datetime',
        'fecha_disponible_hasta' => 'datetime',
    ];

    // Relaciones
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function puestoTrabajo(): BelongsTo
    {
        return $this->belongsTo(PuestoTrabajo::class, 'puesto_trabajo_id');
    }

    public function historicoMovimientos(): HasMany
    {
        return $this->hasMany(HistoricoPosicion::class);
    }

    // Scopes
    public function scopeLibres($query)
    {
        return $query->where('estado', 'Libre');
    }

    public function scopeOcupadas($query)
    {
        return $query->where('estado', 'Ocupada');
    }

    public function scopeBloqueadas($query)
    {
        return $query->where('estado', 'Bloqueada');
    }

    public function scopePorArea($query, $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    public function scopePorCargo($query, $cargoId)
    {
        return $query->where('cargo_id', $cargoId);
    }

    public function scopePorUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    // Métodos útiles
    public function asignarUsuario(User $usuario): void
    {
        $usuarioAnterior = $this->usuario;
        
        $this->update([
            'usuario_id' => $usuario->id,
            'estado' => 'Ocupada',
        ]);

        // Registrar en historial
        HistoricoPosicion::create([
            'posicion_id' => $this->id,
            'usuario_anterior_id' => $usuarioAnterior?->id,
            'usuario_nuevo_id' => $usuario->id,
            'tipo_movimiento' => $usuarioAnterior ? 'Cambio Usuario' : 'Asignacion',
            'datos_anteriores' => [
                'usuario_id' => $usuarioAnterior?->id,
                'estado' => $this->getOriginal('estado'),
            ],
            'datos_nuevos' => [
                'usuario_id' => $usuario->id,
                'estado' => 'Ocupada',
            ],
        ]);
    }

    public function liberarPosicion(?string $razon = null): void
    {
        $usuarioAnterior = $this->usuario;

        $this->update([
            'usuario_id' => null,
            'estado' => 'Libre',
        ]);

        // Registrar en historial
        HistoricoPosicion::create([
            'posicion_id' => $this->id,
            'usuario_anterior_id' => $usuarioAnterior?->id,
            'usuario_nuevo_id' => null,
            'tipo_movimiento' => 'Liberacion',
            'razon' => $razon,
            'datos_anteriores' => [
                'usuario_id' => $usuarioAnterior?->id,
                'estado' => $this->getOriginal('estado'),
            ],
            'datos_nuevos' => [
                'usuario_id' => null,
                'estado' => 'Libre',
            ],
        ]);
    }

    public function bloquear(?string $razon = null): void
    {
        $estadoAnterior = $this->estado;

        $this->update([
            'estado' => 'Bloqueada',
            'razon_bloqueo' => $razon,
        ]);

        // Registrar en historial
        HistoricoPosicion::create([
            'posicion_id' => $this->id,
            'tipo_movimiento' => 'Bloqueo',
            'razon' => $razon,
            'datos_anteriores' => ['estado' => $estadoAnterior],
            'datos_nuevos' => ['estado' => 'Bloqueada'],
        ]);
    }

    public function desbloquear(): void
    {
        $estadoAnterior = $this->estado;

        $this->update([
            'estado' => 'Libre',
            'razon_bloqueo' => null,
        ]);

        // Registrar en historial
        HistoricoPosicion::create([
            'posicion_id' => $this->id,
            'tipo_movimiento' => 'Desbloqueo',
            'datos_anteriores' => ['estado' => $estadoAnterior],
            'datos_nuevos' => ['estado' => 'Libre'],
        ]);
    }

    public function isLibre(): bool
    {
        return $this->estado === 'Libre' && $this->usuario_id === null;
    }

    public function isOcupada(): bool
    {
        return $this->estado === 'Ocupada' && $this->usuario_id !== null;
    }

    public function isBloqueada(): bool
    {
        return $this->estado === 'Bloqueada';
    }
}
