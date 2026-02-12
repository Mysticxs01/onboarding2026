<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $table = 'checkins';

    protected $fillable = [
        'proceso_ingreso_id',
        'codigo_verificacion',
        'activos_entregados',
        'estado_checkin',
        'fecha_generacion',
        'fecha_confirmacion',
        'email_empleado',
        'email_enviado',
        'email_enviado_at',
        'firma_digital',
        'dispositivo_confirmacion',
        'ip_confirmacion'
    ];

    protected $casts = [
        'fecha_generacion' => 'datetime',
        'fecha_confirmacion' => 'datetime',
        'email_enviado_at' => 'datetime',
        'activos_entregados' => 'array',
    ];

    public function procesoIngreso()
    {
        return $this->belongsTo(ProcesoIngreso::class);
    }

    /**
     * Generar código de verificación único
     */
    public static function generarCodigoVerificacion()
    {
        do {
            $codigo = strtoupper(bin2hex(random_bytes(5))); // Ejemplo: A3F2B8C1
        } while (self::where('codigo_verificacion', $codigo)->exists());

        return $codigo;
    }

    /**
     * Obtener porcentaje completado
     */
    public function obtenerPorcentajeCompletado()
    {
        if (!$this->activos_entregados) return 0;
        
        $activos = $this->activos_entregados;
        if (empty($activos)) return 0;

        $completados = count(array_filter($activos, fn($item) => $item['entregado'] === true));
        return round(($completados / count($activos)) * 100);
    }

    /**
     * Marcar como confirmado
     */
    public function confirmar($fimaDigital = null, $dispositivo = null, $ip = null)
    {
        $this->estado_checkin = 'Completado';
        $this->fecha_confirmacion = now();
        $this->firma_digital = $fimaDigital;
        $this->dispositivo_confirmacion = $dispositivo;
        $this->ip_confirmacion = $ip;
        $this->save();

        return true;
    }
}
