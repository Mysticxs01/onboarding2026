<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaestroCargo extends Model
{
    use HasFactory;

    protected $table = 'maestro_cargos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'area_id',
        'nivel_jerarquico',
        'es_puesto_entrada',
        'activo',
    ];

    /* =========================
       Relaciones
       ========================= */

    /**
     * Un cargo maestro pertenece a un área
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /* =========================
       Scope Queries
       ========================= */

    /**
     * Scope para obtener solo cargos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para obtener puestos de entrada
     */
    public function scopePuestosEntrada($query)
    {
        return $query->where('es_puesto_entrada', true)
                     ->where('activo', true);
    }

    /**
     * Scope para obtener cargos por área
     */
    public function scopePorArea($query, $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    /**
     * Scope para obtener cargos por nivel jerárquico
     */
    public function scopePorNivel($query, $nivel)
    {
        return $query->where('nivel_jerarquico', $nivel);
    }

    /* =========================
       Métodos Útiles
       ========================= */

    /**
     * Verificar si es un puesto de entrada
     */
    public function esPuestoEntrada(): bool
    {
        return $this->es_puesto_entrada && $this->activo;
    }

    /**
     * Obtener descripción del nivel jerárquico
     */
    public function obtenerNivelDescripcion(): string
    {
        $nivelDescriptivo = [
            1 => 'Puesto de Entrada',
            2 => 'Especialista/Analista',
            3 => 'Coordinador',
            4 => 'Jefe de Departamento',
            5 => 'Gerencia',
        ];

        return $nivelDescriptivo[$this->nivel_jerarquico] ?? 'Nivel sin definir';
    }

    /**
     * Obtener todos los cargos relacionados en la tabla cargos
     * (si existen como puestos de entrada con vacantes)
     */
    public function cargoActivo()
    {
        return Cargo::where('nombre', $this->nombre)->first();
    }
}
