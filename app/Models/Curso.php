<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Curso extends Model
{
    use SoftDeletes;

    protected $table = 'cursos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria',
        'modalidad',
        'duracion_horas',
        'objetivo',
        'contenido',
        'area_responsable_id',
        'costo',
        'requiere_certificado',
        'vigencia_meses',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'requiere_certificado' => 'boolean',
        'costo' => 'decimal:2',
    ];

    // Relaciones
    public function areaResponsable(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_responsable_id');
    }

    public function cargos(): BelongsToMany
    {
        return $this->belongsToMany(Cargo::class, 'curso_x_cargo')
                    ->withPivot('es_obligatorio', 'orden_secuencia', 'fecha_desde', 'fecha_hasta')
                    ->withTimestamps();
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionCurso::class);
    }

    public function rutas(): BelongsToMany
    {
        return $this->belongsToMany(RutaFormacion::class, 'ruta_x_curso')
                    ->withPivot('numero_secuencia', 'es_obligatorio', 'es_requisito_previo')
                    ->withTimestamps();
    }

    public function solicitudes(): BelongsToMany
    {
        return $this->belongsToMany(Solicitud::class, 'solicitud_curso');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopePorModalidad($query, $modalidad)
    {
        return $query->where('modalidad', $modalidad);
    }

    public function scopeObligatorios($query)
    {
        return $query->where('categoria', 'Obligatorio');
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'like', "%{$termino}%")
                     ->orWhere('codigo', 'like', "%{$termino}%")
                     ->orWhere('descripcion', 'like', "%{$termino}%");
    }

    // Métodos
    public function esObligatorioParaCargo($cargoId): bool
    {
        return $this->cargos()
                    ->where('cargo_id', $cargoId)
                    ->wherePivot('es_obligatorio', true)
                    ->exists();
    }

    public function obtenerTasaCompletacion(): float
    {
        $total = $this->asignaciones()->count();
        if ($total === 0) return 0;
        
        $completados = $this->asignaciones()
                            ->where('estado', 'Completado')
                            ->count();
        
        return ($completados / $total) * 100;
    }

    public function obtenerDuracionTotal()
    {
        return $this->duracion_horas;
    }

    public function estaVigente(): bool
    {
        return $this->activo && !$this->trashed();
    }
}
