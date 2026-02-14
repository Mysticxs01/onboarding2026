<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">Histórico de Ingresos Exitosos</h2>
            <a href="{{ route('procesos-ingreso.index') }}" class="text-blue-600 hover:text-blue-800">Volver</a>
        </div>
    </x-slot>

    <div class="p-6">

        {{-- Tabla de ingresos finalizados --}}
        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-green-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left">Código</th>
                        <th class="px-4 py-3 text-left">Empleado</th>
                        <th class="px-4 py-3 text-left">Cargo</th>
                        <th class="px-4 py-3 text-left">Área</th>
                        <th class="px-4 py-3 text-left">Fecha Ingreso</th>
                        <th class="px-4 py-3 text-left">Fecha Finalización</th>
                        <th class="px-4 py-3 text-left">Puesto (Servicios Generales)</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ingresosFinal as $proceso)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold">{{ $proceso->codigo }}</td>
                            <td class="px-4 py-3">{{ $proceso->nombre_completo }}</td>
                            <td class="px-4 py-3">{{ $proceso->cargo->nombre }}</td>
                            <td class="px-4 py-3">{{ $proceso->area->nombre }}</td>
                            <td class="px-4 py-3">{{ $proceso->fecha_ingreso }}</td>
                            <td class="px-4 py-3">{{ $proceso->fecha_finalizacion?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                {{ $proceso->solicitudes
                                    ->firstWhere('tipo', 'Servicios Generales')
                                    ?->puestoTrabajo
                                    ?->numero_puesto ?? 'No asignado' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('procesos-ingreso.show', $proceso->id) }}" 
                                   class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700"
                                   title="Ver detalles">
                                    👁️ Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                No hay ingresos exitosos registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tabla de procesos cancelados --}}
        <div class="mt-8">
            <h3 class="text-lg font-bold mb-4">Procesos Cancelados</h3>
            <div class="bg-white rounded shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-red-100 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left">Código</th>
                            <th class="px-4 py-3 text-left">Empleado</th>
                            <th class="px-4 py-3 text-left">Cargo</th>
                            <th class="px-4 py-3 text-left">Fecha Cancelación</th>
                            <th class="px-4 py-3 text-left">Motivo</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($procesoCancelados as $proceso)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-bold">{{ $proceso->codigo }}</td>
                                <td class="px-4 py-3">{{ $proceso->nombre_completo }}</td>
                                <td class="px-4 py-3">{{ $proceso->cargo->nombre }}</td>
                                <td class="px-4 py-3">{{ $proceso->fecha_cancelacion?->format('d/m/Y') ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-sm text-gray-600">
                                        {{ $proceso->observaciones ?? 'Sin especificar' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('procesos-ingreso.show', $proceso->id) }}" 
                                       class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700"
                                       title="Ver detalles">
                                        👁️
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    No hay procesos cancelados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
