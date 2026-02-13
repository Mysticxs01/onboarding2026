<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Area;
use App\Models\User;

class Cargo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'area_id',
        'vacantes_disponibles',
        'activo',
    ];

    /* =========================
       Relaciones
       ========================= */

    // Un cargo pertenece a un área
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    // Un cargo tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class);
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
     * Scope para obtener cargos con vacantes disponibles
     */
    public function scopeConVacantes($query)
    {
        return $query->where('vacantes_disponibles', '>', 0)
                     ->where('activo', true);
    }

    /* =========================
       Métodos Útiles
       ========================= */

    /**
     * Verificar si el cargo tiene vacantes disponibles
     */
    public function tieneVacantes(): bool
    {
        return $this->activo && $this->vacantes_disponibles > 0;
    }

    /**
     * Obtener cantidad de usuarios con este cargo
     */
    public function obtenerCantidadEmpleados()
    {
        return $this->users()->count();
    }
}
