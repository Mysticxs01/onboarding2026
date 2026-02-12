<?php

namespace App\Services;

use App\Models\Solicitud;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.notifications.api_key');
        $this->apiUrl = config('services.notifications.api_url', 'https://api.example.com');
    }

    /**
     * Enviar notificación por cambio de estado de solicitud
     */
    public function notificarCambioEstado(Solicitud $solicitud, string $estadoAnterior, string $estadoNuevo): bool
    {
        try {
            $proceso = $solicitud->proceso;
            $empleado = $proceso->email_empleado ?? $proceso->nombre_completo;

            $mensaje = $this->construirMensaje($solicitud, $estadoAnterior, $estadoNuevo);

            // Intenta enviar con la API configurada
            if ($this->apiKey && $this->apiUrl) {
                return $this->enviarViaAPI($empleado, $mensaje, $solicitud);
            }

            // Fallback: registrar en logs
            Log::info('cambio_estado_solicitud', [
                'solicitud_id' => $solicitud->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $estadoNuevo,
                'mensaje' => $mensaje,
                'timestamp' => now(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('notificacion_error: ' . $e->getMessage(), [
                'solicitud_id' => $solicitud->id,
                'exception' => get_class($e),
            ]);
            return false;
        }
    }

    /**
     * Construir mensaje de notificación
     */
    protected function construirMensaje(Solicitud $solicitud, string $estadoAnterior, string $estadoNuevo): string
    {
        $proceso = $solicitud->proceso;
        $tipo = $solicitud->tipo;

        return "Solicitud de {$tipo} para {$proceso->nombre_completo} cambió de '{$estadoAnterior}' a '{$estadoNuevo}'. " .
               "Empleado: {$proceso->nombre_completo}, Área: {$solicitud->area->nombre}";
    }

    /**
     * Enviar notificación vía API
     */
    protected function enviarViaAPI(string $destinatario, string $mensaje, Solicitud $solicitud): bool
    {
        try {
            $response = $this->client->post($this->apiUrl . '/notifications/send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'destinatario' => $destinatario,
                    'asunto' => "Actualización de Solicitud: {$solicitud->tipo}",
                    'mensaje' => $mensaje,
                    'tipo' => 'estado_solicitud',
                    'referencia' => $solicitud->id,
                    'area' => $solicitud->area->nombre,
                ],
                'timeout' => 10,
            ]);

            if ($response->getStatusCode() === 200 || $response->getStatusCode() === 201) {
                Log::info('notificacion_enviada', [
                    'solicitud_id' => $solicitud->id,
                    'destinatario' => $destinatario,
                    'status_code' => $response->getStatusCode(),
                ]);
                return true;
            }

            return false;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            Log::warning('api_notificacion_fallo', [
                'solicitud_id' => $solicitud->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Enviar credenciales de empleado nuevo
     */
    public function enviarCredenciales(array $empleadoData): bool
    {
        try {
            if (!$this->apiKey || !$this->apiUrl) {
                Log::info('credenciales_no_enviadas_api_no_configurada', ['empleado' => $empleadoData['nombre']]);
                return true;
            }

            $response = $this->client->post($this->apiUrl . '/credentials/send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'email' => $empleadoData['email'],
                    'nombre' => $empleadoData['nombre'],
                    'usuario' => $empleadoData['usuario'],
                    'contraseña_temporal' => $empleadoData['contraseña_temporal'] ?? null,
                    'empresa' => config('app.name'),
                    'fecha_inicio' => $empleadoData['fecha_inicio'] ?? now(),
                ],
                'timeout' => 15,
            ]);

            if ($response->getStatusCode() === 200 || $response->getStatusCode() === 201) {
                Log::info('credenciales_enviadas', [
                    'empleado_email' => $empleadoData['email'],
                    'nombre' => $empleadoData['nombre'],
                ]);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::warning('envio_credenciales_fallo: ' . $e->getMessage(), [
                'empleado' => $empleadoData['nombre'] ?? 'desconocido',
            ]);
            return false;
        }
    }

    /**
     * Notificar entrega de equipos
     */
    public function notificarEntrega(Solicitud $solicitud, array $itemsEntregados): bool
    {
        try {
            $mensaje = "Se han entregado los siguientes items:\n" .
                      implode("\n", array_map(fn($item) => "- {$item}", $itemsEntregados)) .
                      "\nPara: {$solicitud->proceso->nombre_completo}";

            return $this->notificarCambioEstado($solicitud, 'En Proceso', 'Entregado');
        } catch (\Exception $e) {
            Log::error('notificacion_entrega_error: ' . $e->getMessage());
            return false;
        }
    }
}
