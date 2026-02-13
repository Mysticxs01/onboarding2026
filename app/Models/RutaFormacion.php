<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RutaFormacion extends Model
{
    use SoftDeletes;

    protected $table = 'rutas_formacion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'cargo_id',
        'area_id',
        'version',
        'activa',
        'duracion_total_horas',
        'fecha_vigencia',
        'responsable_rrhh_id',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'fecha_vigencia' => 'date',
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

    public function responsableRRHH(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_rrhh_id');
    }

    public function cursos(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class, 'ruta_x_curso')
                    ->withPivot('numero_secuencia', 'es_obligatorio', 'es_requisito_previo')
                    ->orderBy('numero_secuencia')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopePorCargo($query, $cargoId)
    {
        return $query->where('cargo_id', $cargoId);
    }

    public function scopePorArea($query, $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    public function scopeVigentes($query)
    {
        return $query->where('activa', true)
                     ->where(function ($q) {
                         $q->whereNull('fecha_vigencia')
                           ->orWhere('fecha_vigencia', '>=', now());
                     });
    }

    // Métodos
    public function obtenerCursosObligatorios()
    {
        return $this->cursos()
                    ->wherePivot('es_obligatorio', true)
                    ->orderBy('numero_secuencia')
                    ->get();
    }

    public function obtenerCursosOpcionales()
    {
        return $this->cursos()
                    ->wherePivot('es_obligatorio', false)
                    ->orderBy('numero_secuencia')
                    ->get();
    }

    public function obtenerCursosSecuenciados()
    {
        return $this->cursos()
                    ->orderBy('numero_secuencia')
                    ->get();
    }

    public function calcularDuracionTotal()
    {
        return $this->cursos()->sum('duracion_horas');
    }

    public function agregarCurso($cursoId, $numeroSecuencia, $esObligatorio = true, $esRequisitoPrevio = false)
    {
        $this->cursos()->attach($cursoId, [
            'numero_secuencia' => $numeroSecuencia,
            'es_obligatorio' => $esObligatorio,
            'es_requisito_previo' => $esRequisitoPrevio,
        ]);
    }

    public function removerCurso($cursoId)
    {
        $this->cursos()->detach($cursoId);
    }

    public function estaVigente(): bool
    {
        if (!$this->activa) return false;
        if ($this->fecha_vigencia && now()->isAfter($this->fecha_vigencia)) return false;
        return true;
    }
}
