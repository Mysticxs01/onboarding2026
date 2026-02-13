<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use App\Models\Area;
use App\Models\Cargo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'area_id',
        'cargo_id',
        'rol_onboarding',
        'puede_aprobar_solicitudes',
        'jefe_directo_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* =========================
       Relaciones
       ========================= */

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    /**
     * Jefe directo del usuario (para cadena de mando)
     */
    public function jefe()
    {
        return $this->belongsTo(User::class, 'jefe_directo_id');
    }

    /**
     * Empleados que reportan a este usuario
     */
    public function subordinados()
    {
        return $this->hasMany(User::class, 'jefe_directo_id');
    }

    /* =========================
       Scope Queries
       ========================= */

    /**
     * Scope para obtener solo usuarios que pueden aprobar solicitudes
     */
    public function scopeAprobadores($query)
    {
        return $query->where('puede_aprobar_solicitudes', true)
                     ->where('activo', true);
    }

    /**
     * Scope para obtener jefes de área
     */
    public function scopeJefesArea($query)
    {
        return $query->where('rol_onboarding', 'jefe_area')
                     ->where('activo', true);
    }

    /**
     * Scope para obtener coordinadores
     */
    public function scopeCoordinadores($query)
    {
        return $query->where('rol_onboarding', 'coordinador')
                     ->where('activo', true);
    }

    /**
     * Scope para obtener administradores
     */
    public function scopeAdministradores($query)
    {
        return $query->where('rol_onboarding', 'admin')
                     ->where('activo', true);
    }

    /* =========================
       Métodos Útiles
       ========================= */

    /**
     * Obtener nombre completo del cargo del usuario
     */
    public function getNombreCargoCompleto()
    {
        return $this->cargo?->nombre ?? 'Sin cargo asignado';
    }

    /**
     * Obtener nombre del área del usuario
     */
    public function getNombreArea()
    {
        return $this->area?->nombre ?? 'Sin área asignada';
    }

    /**
     * Verificar si el usuario puede aprobar solicitudes
     */
    public function puedeAprobarSolicitudes(): bool
    {
        return $this->puede_aprobar_solicitudes && $this->activo;
    }

    /**
     * Obtener todos los usuarios que este usuario supervisa (direct + indirect)
     */
    public function obtenerSupervisionados()
    {
        $supervisionados = $this->subordinados()->get();
        
        foreach ($supervisionados as $subordinado) {
            $supervisionados = $supervisionados->merge($subordinado->obtenerSupervisionados());
        }
        
        return $supervisionados;
    }
}
