<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">Proceso {{ $proceso->codigo }}</h2>
            <a href="{{ route('procesos-ingreso.index') }}" class="text-blue-600 hover:text-blue-800">Volver</a>
        </div>
    </x-slot>

    <div class="p-6 max-w-6xl">

        {{-- Errores --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Información del proceso --}}
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">Información del Empleado</h3>
                <p><strong>Nombre:</strong> {{ $proceso->nombre_completo }}</p>
                <p><strong>Documento:</strong> {{ $proceso->tipo_documento }} - {{ $proceso->documento }}</p>
                <p><strong>Cargo:</strong> {{ $proceso->cargo->nombre }}</p>
                <p><strong>Área:</strong> {{ $proceso->area->nombre }}</p>
                <p><strong>Jefe Inmediato:</strong> {{ $proceso->jefeCargo?->nombre ?? $proceso->cargo?->jefeInmediato?->nombre ?? '—' }}</p>
                <p><strong>Fecha de Ingreso:</strong> {{ $proceso->fecha_ingreso }}</p>
                <p><strong>Estado:</strong> 
                    <span class="px-3 py-1 rounded text-white text-sm
                        @if ($proceso->estado === 'Pendiente') bg-yellow-500
                        @elseif ($proceso->estado === 'En Proceso') bg-blue-500
                        @elseif ($proceso->estado === 'Finalizado') bg-green-500
                        @else bg-red-500 @endif">
                        {{ $proceso->estado }}
                    </span>
                </p>
                @if ($proceso->fecha_finalizacion)
                    <p><strong>Finalizado:</strong> {{ $proceso->fecha_finalizacion->format('d/m/Y H:i') }}</p>
                @endif
                @if ($proceso->fecha_cancelacion)
                    <p><strong>Cancelado:</strong> {{ $proceso->fecha_cancelacion->format('d/m/Y H:i') }}</p>
                    @if ($proceso->observaciones)
                        <p><strong>Motivo:</strong> {{ $proceso->observaciones }}</p>
                    @endif
                @endif
            </div>

            {{-- Progreso --}}
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">Progreso del Onboarding</h3>
                <div class="w-full bg-gray-300 rounded-full h-8 mb-2">
                    <div class="bg-blue-600 h-8 rounded-full flex items-center justify-center text-white font-bold" style="width: {{ $progreso }}%">
                        {{ $progreso }}%
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">
                    {{ $proceso->solicitudes()->where('estado', 'Finalizada')->count() }} de {{ $proceso->solicitudes()->count() }} solicitudes completadas
                </p>

                @if($progreso >= 100 && $proceso->solicitudes()->count() > 0)
                    <div class="space-y-2">
                        <a href="{{ route('solicitudes.checkin-consolidado', $proceso->id) }}" class="inline-block w-full text-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            ✅ Ver Check-in Consolidado
                        </a>

                        @if($proceso->checkin)
                            <a href="{{ route('checkins.show', $proceso->checkin->id) }}" class="inline-block w-full text-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                📋 Ver Acta de Entrega
                            </a>
                            <a href="{{ route('checkins.pdf', $proceso->checkin->id) }}" class="inline-block w-full text-center bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                📄 Descargar Acta PDF
                            </a>
                        @else
                            <form method="POST" action="{{ route('checkins.generar', $proceso->id) }}">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    📄 Generar Acta de Entrega
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Acciones --}}
        @if ($proceso->puedeEditar())
            <div class="mb-6 flex gap-2">
                <a href="{{ route('procesos-ingreso.edit', $proceso->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    ✏️ Editar Información
                </a>
                <a href="{{ route('procesos-ingreso.cambiar-fecha', $proceso->id) }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                    📅 Cambiar Fecha
                </a>
                <a href="{{ route('procesos-ingreso.mostrar-cancelacion', $proceso->id) }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    ❌ Cancelar Proceso
                </a>
            </div>
        @else
            <div class="mb-6 p-3 bg-yellow-100 text-yellow-700 rounded">
                ⚠️ No se puede editar este proceso porque ya hay solicitudes finalizadas.
            </div>
        @endif

        {{-- Solicitudes por área --}}
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">Solicitudes por Área</h3>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="border p-3 text-left">Área</th>
                        <th class="border p-3 text-left">Tipo de Solicitud</th>
                        <th class="border p-3 text-left">Fecha Límite</th>
                        <th class="border p-3 text-left">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proceso->solicitudes->where('estado', 'En Proceso') as $solicitud)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="border p-3">{{ $solicitud->area->nombre }}</td>
                            <td class="border p-3">{{ $solicitud->tipo }}</td>
                            <td class="border p-3">{{ $solicitud->fecha_limite?->format('d/m/Y') ?? 'N/A' }}</td>
                            <td class="border p-3">
                                <span class="px-3 py-1 rounded text-white text-sm bg-blue-500">
                                    En Proceso
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="border p-3 text-center text-gray-600">
                                No hay solicitudes en proceso en este momento
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
