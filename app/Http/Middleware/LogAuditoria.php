<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditoriaOnboarding;
use Symfony\Component\HttpFoundation\Response;

class LogAuditoria
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo auditar para usuarios autenticados
        if (auth()->check()) {
            // Métodos que auditar
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                $this->registrarOperacion($request);
            }
        }

        return $next($request);
    }

    private function registrarOperacion(Request $request): void
    {
        try {
            // Determinar entidad y acción
            $ruta = $request->route();
            
            if ($ruta) {
                $controlador = $ruta->getController();
                $accion = $this->mapearAccion($request->method());
                
                // Obtener ID de la entidad si existe
                $parametros = $ruta->parameters();
                $entidadId = null;
                
                foreach ($parametros as $param) {
                    if (is_object($param) && method_exists($param, 'getKey')) {
                        $entidadId = $param->getKey();
                        break;
                    }
                }

                if ($entidadId) {
                    // Obtener nombre de la clase modelo
                    $entidad = class_basename($parametros[array_key_first($parametros)] ?? null);

                    AuditoriaOnboarding::registrar(
                        $accion,
                        $entidad ?: 'Unknown',
                        $entidadId,
                        null,
                        null,
                        $request->all()
                    );
                }
            }
        } catch (\Exception $e) {
            // No interrumpir solicitud si hay error en auditoría
            \Log::error('Error en middleware de auditoría: ' . $e->getMessage());
        }
    }

    private function mapearAccion(string $method): string
    {
        return match($method) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'unknown',
        };
    }
}
