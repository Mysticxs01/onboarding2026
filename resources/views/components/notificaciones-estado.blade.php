{{-- Panel de Estado de Notificaciones (HU15) --}}
<div class="bg-white p-6 rounded shadow mb-6">
    <h3 class="text-lg font-bold mb-4 flex items-center">
        <span class="text-xl mr-2">📧</span> Estado de Notificaciones
    </h3>

    @php
        // Obtener auditoría de notificaciones para este proceso
        $notificaciones = \App\Models\AuditoriaOnboarding::where('entidad', 'ProcesoIngreso')
            ->where('entidad_id', $proceso->id)
            ->whereIn('accion', ['notificacion_enviada', 'notificacion_fallida', 'notificacion_reintentada'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Agrupar por tipo y área
        $enviosPorArea = [];
        $ultimoEstado = [];

        foreach ($notificaciones as $notif) {
            $detalles = is_array($notif->valores_nuevos) ? $notif->valores_nuevos : json_decode($notif->valores_nuevos, true);
            if (is_array($detalles) && isset($detalles['area_id'])) {
                $areaId = $detalles['area_id'];
                if (!isset($ultimoEstado[$areaId])) {
                    $area = \App\Models\Area::find($areaId);
                    $ultimoEstado[$areaId] = [
                        'area_nombre' => $area?->nombre ?? 'Área desconocida',
                        'accion' => $notif->accion,
                        'destinatario' => $detalles['destinatario'] ?? 'N/A',
                        'error' => $detalles['error'] ?? null,
                        'timestamp' => $detalles['timestamp'] ?? $notif->created_at,
                    ];
                }
            }
        }
    @endphp

    @if ($notificaciones->isEmpty())
        <div class="p-4 bg-gray-100 rounded text-gray-600 text-center">
            <p class="text-sm">No hay registros de notificaciones enviadas aún.</p>
            <p class="text-xs text-gray-500 mt-2">Las notificaciones se enviarán automáticamente cuando se creen las solicitudes.</p>
        </div>
    @else
        <div class="space-y-3">
            @forelse ($ultimoEstado as $areaId => $estado)
                <div class="border-l-4 p-4 rounded
                    @if ($estado['accion'] === 'notificacion_enviada')
                        border-green-500 bg-green-50
                    @elseif ($estado['accion'] === 'notificacion_fallida')
                        border-red-500 bg-red-50
                    @else
                        border-blue-500 bg-blue-50
                    @endif
                ">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm">{{ $estado['area_nombre'] }}</h4>
                            <p class="text-xs text-gray-600 mt-1">
                                📧 {{ $estado['destinatario'] }}
                            </p>
                            
                            @if ($estado['accion'] === 'notificacion_enviada')
                                <span class="inline-block mt-2 px-2 py-1 bg-green-200 text-green-800 text-xs rounded font-semibold">
                                    ✅ Enviado
                                </span>
                            @elseif ($estado['accion'] === 'notificacion_fallida')
                                <span class="inline-block mt-2 px-2 py-1 bg-red-200 text-red-800 text-xs rounded font-semibold">
                                    ❌ Fallido
                                </span>
                                @if ($estado['error'])
                                    <p class="text-xs text-red-700 mt-1">
                                        <strong>Error:</strong> {{ $estado['error'] }}
                                    </p>
                                @endif
                            @else
                                <span class="inline-block mt-2 px-2 py-1 bg-blue-200 text-blue-800 text-xs rounded font-semibold">
                                    🔄 Reintentando
                                </span>
                            @endif

                            <p class="text-xs text-gray-500 mt-2">
                                {{ \Carbon\Carbon::parse($estado['timestamp'])->format('d/m/Y H:i:s') }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-600">Sin información de envío.</p>
            @endforelse
        </div>
    @endif

    {{-- Botón de Reintento --}}
    @if (auth()->user()->hasRole(['Root', 'Jefe RRHH']))
        <div class="mt-6 pt-4 border-t">
            <form method="POST" action="{{ route('procesos-ingreso.reintentar-notificaciones', $proceso->id) }}" 
                  onsubmit="return confirm('¿Reintentar el envío de notificaciones a todas las áreas?');">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold text-sm">
                    🔄 Reintentar Envío de Notificaciones
                </button>
            </form>
        </div>
    @endif
</div>
