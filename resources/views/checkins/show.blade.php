<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">Detalles del Check-in</h2>
            <a href="{{ route('checkins.index') }}" class="text-blue-600 hover:text-blue-800">Volver</a>
        </div>
    </x-slot>

    <div class="p-6 max-w-4xl">

        {{-- Información del proceso --}}
        <div class="bg-white p-6 rounded shadow mb-6">
            <h3 class="text-lg font-bold mb-4">Información del Empleado</h3>
            <div class="grid grid-cols-2 gap-4">
                <p><strong>Código Proceso:</strong> {{ $checkin->procesoIngreso->codigo }}</p>
                <p><strong>Nombre Completo:</strong> {{ $checkin->procesoIngreso->nombre_completo }}</p>
                <p><strong>Cargo:</strong> {{ $checkin->procesoIngreso->cargo->nombre }}</p>
                <p><strong>Área:</strong> {{ $checkin->procesoIngreso->area->nombre }}</p>
                <p><strong>Jefe Inmediato:</strong> {{ $checkin->procesoIngreso->jefeCargo?->nombre ?? $checkin->procesoIngreso->cargo?->jefeInmediato?->nombre ?? '—' }}</p>
                <p><strong>Fecha de Ingreso:</strong> {{ $checkin->procesoIngreso->fecha_ingreso }}</p>
            </div>
        </div>

        {{-- Información del check-in --}}
        <div class="bg-white p-6 rounded shadow mb-6">
            <h3 class="text-lg font-bold mb-4">Estado del Check-in</h3>
            <div class="grid grid-cols-3 gap-4">
                <p>
                    <strong>Código Verificación:</strong><br>
                    <span class="font-mono text-sm bg-gray-100 p-2 rounded">{{ $checkin->codigo_verificacion }}</span>
                </p>
                <p>
                    <strong>Estado:</strong><br>
                    <span class="px-3 py-1 rounded text-white text-sm font-bold inline-block
                        {{ $checkin->estado_checkin === 'Pendiente' ? 'bg-yellow-500' : 'bg-green-500' }}">
                        {{ $checkin->estado_checkin }}
                    </span>
                </p>
                <p>
                    <strong>Progreso:</strong><br>
                    <div class="w-full bg-gray-300 rounded h-6 mt-2">
                        <div class="bg-blue-600 h-6 rounded flex items-center justify-center text-white text-xs font-bold"
                             style="width: {{ $checkin->obtenerPorcentajeCompletado() }}%">
                            {{ $checkin->obtenerPorcentajeCompletado() }}%
                        </div>
                    </div>
                </p>
            </div>
        </div>

        {{-- Listado de activos --}}
        <div class="bg-white p-6 rounded shadow mb-6">
            <h3 class="text-lg font-bold mb-4">📦 Activos a Entregar</h3>
            <div class="space-y-3">
                @if ($checkin->activos_entregados)
                    @foreach ($checkin->activos_entregados as $index => $activo)
                        <div class="border-l-4 {{ $activo['entregado'] ? 'border-green-500' : 'border-yellow-500' }} p-4 bg-gray-50 rounded">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold">{{ $activo['item'] }}</p>
                                    @if ($activo['especificaciones'])
                                        <p class="text-sm text-gray-600">{{ $activo['especificaciones'] }}</p>
                                    @endif
                                </div>
                                <span class="px-3 py-1 rounded text-white text-sm font-bold
                                    {{ $activo['entregado'] ? 'bg-green-500' : 'bg-yellow-500' }}">
                                    {{ $activo['entregado'] ? '✓ Entregado' : '⏳ Pendiente' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Botones de acción --}}
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">Acciones</h3>
            <div class="flex gap-2">
                <a href="{{ route('checkins.pdf', $checkin->id) }}" 
                   class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    📄 Descargar Acta PDF
                </a>
                
                @if ($checkin->estado_checkin === 'Pendiente')
                    <p class="text-blue-600 font-semibold mt-2">
                        ℹ️ Se ha enviado un código al correo del empleado para confirmar la recepción de activos.
                    </p>
                @else
                    <div>
                        <p class="text-green-600 font-semibold mb-2">✓ Check-in completado</p>
                        <p class="text-sm text-gray-600">
                            Confirmado el: {{ $checkin->fecha_confirmacion->format('d/m/Y H:i:s') }}<br>
                            Dispositivo: {{ $checkin->dispositivo_confirmacion }}<br>
                            IP: {{ $checkin->ip_confirmacion }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
