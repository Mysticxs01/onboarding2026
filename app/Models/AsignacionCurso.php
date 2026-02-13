<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsignacionCurso extends Model
{
    protected $table = 'asignacion_cursos';

    protected $fillable = [
        'proceso_ingreso_id',
        'curso_id',
        'fecha_asignacion',
        'fecha_limite',
        'fecha_completacion',
        'estado',
        'calificacion',
        'certificado_url',
        'asignado_por_id',
        'responsable_validacion_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'fecha_limite' => 'date',
        'fecha_completacion' => 'date',
    ];

    // Relaciones
    public function procesoIngreso(): BelongsTo
    {
        return $this->belongsTo(ProcesoIngreso::class);
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function asignadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignado_por_id');
    }

    public function responsableValidacion(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_validacion_id');
    }

    // Scopes
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'Asignado');
    }

    public function scopeEnProgreso($query)
    {
        return $query->where('estado', 'En Progreso');
    }

    public function scopeCompletados($query)
    {
        return $query->where('estado', 'Completado');
    }

    public function scopeVencidos($query)
    {
        return $query->where('estado', 'Vencido');
    }

    public function scopeParaValidar($query)
    {
        return $query->where('estado', 'En Progreso')
                     ->whereNull('responsable_validacion_id');
    }

    // Métodos de Estado
    public function marcarEnProgreso()
    {
        $this->update([
            'estado' => 'En Progreso',
        ]);
        AuditoriaOnboarding::registrar('update', 'AsignacionCurso', $this->id, 'Cambio a En Progreso');
    }

    public function marcarCompletado($calificacion = null, $certificado = null)
    {
        $this->update([
            'estado' => 'Completado',
            'fecha_completacion' => now(),
            'calificacion' => $calificacion,
            'certificado_url' => $certificado,
            'responsable_validacion_id' => auth()->id(),
        ]);
        AuditoriaOnboarding::registrar('update', 'AsignacionCurso', $this->id, 'Cambio a Completado');
    }

    public function marcarVencido()
    {
        $this->update([
            'estado' => 'Vencido',
        ]);
        AuditoriaOnboarding::registrar('update', 'AsignacionCurso', $this->id, 'Cambio a Vencido');
    }

    public function cancelar($motivo = null)
    {
        $this->update([
            'estado' => 'Cancelado',
            'observaciones' => $motivo,
        ]);
        AuditoriaOnboarding::registrar('delete', 'AsignacionCurso', $this->id, 'Cancelado: ' . $motivo);
    }

    public function estaBloqueada(): bool
    {
        return in_array($this->estado, ['Completado', 'Vencido', 'Cancelado']);
    }

    public function puedeProceder(): bool
    {
        return in_array($this->estado, ['Asignado', 'En Progreso']);
    }

    public function obtenerDiasRestantes(): ?int
    {
        if (!$this->fecha_limite) return null;
        
        return now()->diffInDays($this->fecha_limite);
    }

    public function estaAtrasada(): bool
    {
        if (!$this->fecha_limite) return false;
        return now()->isAfter($this->fecha_limite) && $this->estado !== 'Completado';
    }
}
