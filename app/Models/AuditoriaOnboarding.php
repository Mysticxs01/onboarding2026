<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaOnboarding extends Model
{
    protected $table = 'auditoria_onboarding';

    protected $fillable = [
        'usuario_id',
        'accion',
        'entidad',
        'entidad_id',
        'valores_anteriores',
        'valores_nuevos',
        'motivo',
        'ip_origin',
        'user_agent',
    ];

    protected $casts = [
        'valores_anteriores' => 'array',
        'valores_nuevos' => 'array',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Scopes
    public function scopePorEntidad($query, $entidad)
    {
        return $query->where('entidad', $entidad);
    }

    public function scopePorAccion($query, $accion)
    {
        return $query->where('accion', $accion);
    }

    public function scopeReciente($query, $dias = 7)
    {
        return $query->whereBetween('created_at', [
            now()->subDays($dias),
            now()
        ]);
    }

    // Métodos Estáticos
    public static function registrar($accion, $entidad, $entidadId, $motivo = null, $valoresAnteriores = null, $valoresNuevos = null)
    {
        return self::create([
            'usuario_id' => auth()->id(),
            'accion' => $accion,
            'entidad' => $entidad,
            'entidad_id' => $entidadId,
            'valores_anteriores' => $valoresAnteriores,
            'valores_nuevos' => $valoresNuevos,
            'motivo' => $motivo,
            'ip_origin' => request()?->ip(),
            'user_agent' => request()?->header('User-Agent'),
        ]);
    }

    public static function registrarCreacion($entidad, $entidadId, $valores)
    {
        return self::registrar('create', $entidad, $entidadId, null, null, $valores);
    }

    public static function registrarActualizacion($entidad, $entidadId, $valoresAnteriores, $valoresNuevos)
    {
        return self::registrar('update', $entidad, $entidadId, null, $valoresAnteriores, $valoresNuevos);
    }

    public static function registrarEliminacion($entidad, $entidadId, $motivo = null)
    {
        return self::registrar('delete', $entidad, $entidadId, $motivo);
    }

    public static function registrarExportacion($entidad, $cantidadRegistros)
    {
        return self::registrar('export', $entidad, 0, "Se exportaron {$cantidadRegistros} registros");
    }

    // Métodos
    public function obtenerCambios()
    {
        if (!$this->valores_anteriores || !$this->valores_nuevos) {
            return [];
        }

        $cambios = [];
        foreach ($this->valores_nuevos as $campo => $nuevoValor) {
            $valorAnterior = $this->valores_anteriores[$campo] ?? null;
            if ($valorAnterior !== $nuevoValor) {
                $cambios[$campo] = [
                    'anterior' => $valorAnterior,
                    'nuevo' => $nuevoValor,
                ];
            }
        }
        return $cambios;
    }

    public function obtenerDescripcion()
    {
        return match($this->accion) {
            'create' => "Creado $this->entidad #$this->entidad_id",
            'update' => "Actualizado $this->entidad #$this->entidad_id",
            'delete' => "Eliminado $this->entidad #$this->entidad_id",
            'view' => "Visualizado $this->entidad #$this->entidad_id",
            'export' => "Exportado $this->entidad",
            default => "Acción sobre $this->entidad #$this->entidad_id",
        };
    }
}
