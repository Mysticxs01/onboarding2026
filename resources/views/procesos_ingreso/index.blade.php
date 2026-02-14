<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl">Módulo de Administración de Procesos de Ingreso</h2>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <a href="{{ route('procesos-ingreso.historico') }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 text-center">
                    📚 Histórico
                </a>
                @if (Auth::user()->hasRole(['Root', 'Jefe RRHH']))
                    <a href="{{ route('procesos-ingreso.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center">
                        ➕ Nuevo Ingreso
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6">

        {{-- Mensajes --}}
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabla de procesos (Desktop) --}}
        <div class="hidden lg:block bg-white rounded shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm">Código</th>
                        <th class="px-4 py-3 text-left text-sm">Empleado</th>
                        <th class="px-4 py-3 text-left text-sm">Cargo</th>
                        <th class="px-4 py-3 text-left text-sm">Jefe</th>
                        <th class="px-4 py-3 text-left text-sm">Fecha Ingreso</th>
                        <th class="px-4 py-3 text-left text-sm">Progreso</th>
                        <th class="px-4 py-3 text-left text-sm">Estado</th>
                        <th class="px-4 py-3 text-center text-sm">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($procesos as $proceso)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold text-sm">{{ $proceso->codigo }}</td>
                            <td class="px-4 py-3 text-sm">{{ $proceso->nombre_completo }}</td>
                            <td class="px-4 py-3 text-sm">{{ $proceso->cargo->nombre }}</td>
                            <td class="px-4 py-3 text-sm">{{ $proceso->jefeCargo?->nombre ?? $proceso->cargo?->jefeInmediato?->nombre ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $proceso->fecha_ingreso }}</td>
                            <td class="px-4 py-3">
                                <div class="w-32 bg-gray-300 rounded-full h-6 flex items-center">
                                    <div 
                                        class="bg-blue-600 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                        style="width: {{ $proceso->obtenerProgreso() }}%">
                                        {{ $proceso->obtenerProgreso() }}%
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded text-white text-xs font-bold
                                    @if ($proceso->estado === 'Pendiente') bg-yellow-500
                                    @elseif ($proceso->estado === 'En Proceso') bg-blue-500
                                    @elseif ($proceso->estado === 'Finalizado') bg-green-500
                                    @else bg-red-500 @endif">
                                    {{ $proceso->estado }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2 flex-wrap">
                                    <a href="{{ route('procesos-ingreso.show', $proceso->id) }}" 
                                       class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700"
                                       title="Ver detalles">
                                        👁️
                                    </a>
                                    @if ($proceso->puedeEditar())
                                        <a href="{{ route('procesos-ingreso.edit', $proceso->id) }}" 
                                           class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700"
                                           title="Editar">
                                            ✏️
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                No hay procesos de ingreso registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tarjetas de procesos (Mobile/Tablet) --}}
        <div class="lg:hidden space-y-4">
            @forelse ($procesos as $proceso)
                <div class="bg-white rounded shadow p-4 border-l-4 
                    @if ($proceso->estado === 'Pendiente') border-yellow-500
                    @elseif ($proceso->estado === 'En Proceso') border-blue-500
                    @elseif ($proceso->estado === 'Finalizado') border-green-500
                    @else border-red-500 @endif">
                    
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-bold text-lg text-gray-800">{{ $proceso->codigo }}</p>
                            <p class="text-sm text-gray-600">{{ $proceso->nombre_completo }}</p>
                        </div>
                        <span class="px-3 py-1 rounded text-white text-xs font-bold
                            @if ($proceso->estado === 'Pendiente') bg-yellow-500
                            @elseif ($proceso->estado === 'En Proceso') bg-blue-500
                            @elseif ($proceso->estado === 'Finalizado') bg-green-500
                            @else bg-red-500 @endif">
                            {{ $proceso->estado }}
                        </span>
                    </div>
                    
                    <div class="space-y-2 mb-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Cargo:</span>
                            <span class="font-semibold">{{ $proceso->cargo->nombre }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jefe:</span>
                            <span class="font-semibold">{{ $proceso->jefeCargo?->nombre ?? $proceso->cargo?->jefeInmediato?->nombre ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fecha Ingreso:</span>
                            <span class="font-semibold">{{ $proceso->fecha_ingreso }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Progreso:</span>
                            <span class="text-sm font-bold text-blue-600">{{ $proceso->obtenerProgreso() }}%</span>
                        </div>
                        <div class="w-full bg-gray-300 rounded-full h-4 flex items-center">
                            <div 
                                class="bg-blue-600 h-4 rounded-full"
                                style="width: {{ $proceso->obtenerProgreso() }}%">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('procesos-ingreso.show', $proceso->id) }}" 
                           class="flex-1 bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 text-center font-semibold">
                            👁️ Ver
                        </a>
                        @if ($proceso->puedeEditar())
                            <a href="{{ route('procesos-ingreso.edit', $proceso->id) }}" 
                               class="flex-1 bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700 text-center font-semibold">
                                ✏️ Editar
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded shadow p-6 text-center text-gray-500">
                    No hay procesos de ingreso registrados
                </div>
            @endforelse
        </div>

        {{-- Resumen de estadísticas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-600 text-sm font-semibold">Total de Procesos</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $procesos->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-600 text-sm font-semibold">Pendientes</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $procesos->where('estado', 'Pendiente')->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-600 text-sm font-semibold">En Proceso</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $procesos->where('estado', 'En Proceso')->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-600 text-sm font-semibold">Completados</h3>
                <p class="text-3xl font-bold text-green-600">{{ $procesos->where('estado', 'Finalizado')->count() }}</p>
            </div>
        </div>

    </div>
</x-app-layout>

