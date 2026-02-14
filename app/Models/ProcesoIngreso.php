<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcesoIngreso extends Model
{
    protected $table = 'procesos_ingresos';

    protected $fillable = [
        'codigo',
        'nombre_completo',
        'tipo_documento',
        'documento',
        'cargo_id',
        'area_id',
        'fecha_ingreso',
        'email',
        'telefono',
        'fecha_esperada_finalizacion',
        'jefe_id',
        'estado',
        'observaciones',
        'fecha_cancelacion',
        'fecha_finalizacion'
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_cancelacion' => 'datetime',
        'fecha_finalizacion' => 'datetime',
        'fecha_esperada_finalizacion' => 'date',
    ];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function jefe()
    {
        return $this->belongsTo(User::class, 'jefe_id');
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'proceso_ingreso_id');
    }

    public function puesto()
    {
        return $this->hasOne(Puesto::class, 'proceso_ingreso_id');
    }

    public function checkin()
    {
        return $this->hasOne(Checkin::class, 'proceso_ingreso_id');
    }

    public function asignacionesCursos()
    {
        return $this->hasMany(AsignacionCurso::class, 'proceso_ingreso_id');
    }

    /**
     * Verificar si el proceso puede ser editado
     * No puede editarse si alguna solicitud está finalizada
     */
    public function puedeEditar()
    {
        return !$this->solicitudes()->where('estado', 'Finalizada')->exists();
    }

    /**
     * Verificar si el proceso puede ser cancelado
     * No puede cancelarse si alguna solicitud está finalizada
     */
    public function puedeCancelar()
    {
        return $this->puedeEditar();
    }

    /**
     * Cambiar la fecha de ingreso (solo se puede postergar, no adelantar)
     */
    public function cambiarFechaIngreso($nuevaFecha)
    {
        $nueva = \Carbon\Carbon::parse($nuevaFecha);

        if ($nueva->lt($this->fecha_ingreso)) {
            throw new \Exception('No se puede adelantar la fecha de ingreso, solo postergarla.');
        }

        $original = $this->fecha_ingreso instanceof \Carbon\Carbon ? $this->fecha_ingreso : \Carbon\Carbon::parse($this->fecha_ingreso);

        $dias = $original->diffInDays($nueva);

        $this->fecha_ingreso = $nueva->toDateString();
        $this->save();

        // Actualizar las fechas límite de las solicitudes
        foreach ($this->solicitudes as $solicitud) {
            if ($solicitud->estado !== 'Finalizada') {
                $solicitud->fecha_limite = \Carbon\Carbon::parse($solicitud->fecha_limite)->addDays($dias)->toDateString();
                $solicitud->save();
            }
        }

        return true;
    }

    /**
     * Cancelar el proceso de ingreso
     */
    public function cancelar($motivo = null)
    {
        if (!$this->puedeCancelar()) {
            throw new \Exception('No se puede cancelar un proceso con solicitudes finalizadas.');
        }

        $this->estado = 'Cancelado';
        $this->observaciones = $motivo;
        $this->fecha_cancelacion = now();
        $this->save();

        // Liberar puestos de trabajo asignados en Servicios Generales
        $this->solicitudes()
            ->whereNotNull('puesto_trabajo_id')
            ->with('puestoTrabajo')
            ->get()
            ->each(function ($solicitud) {
                if ($solicitud->puestoTrabajo) {
                    $solicitud->puestoTrabajo->update(['estado' => 'Disponible']);
                }
                $solicitud->update(['puesto_trabajo_id' => null]);
            });

        return true;
    }

    /**
     * Marcar como completado exitosamente
     */
    public function marcarExitoso()
    {
        if ($this->solicitudes()->where('estado', '!=', 'Finalizada')->exists()) {
            throw new \Exception('No todas las solicitudes están finalizadas.');
        }

        $this->estado = 'Finalizado';
        $this->fecha_finalizacion = now();
        $this->save();

        return true;
    }

    /**
     * Obtener el progreso del onboarding en porcentaje
     */
    public function obtenerProgreso()
    {
        $total = $this->solicitudes()->count();
        if ($total === 0) return 0;

        $finalizadas = $this->solicitudes()->where('estado', 'Finalizada')->count();
        return round(($finalizadas / $total) * 100);
    }
}

