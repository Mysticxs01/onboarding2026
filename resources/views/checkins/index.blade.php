<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Módulo de Check-in y Recepción de Activos</h2>
    </x-slot>

    <div class="p-6">

        {{-- Tabla de check-ins (Desktop) --}}
        <div class="hidden lg:block bg-white rounded shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm">Código</th>
                        <th class="px-4 py-3 text-left text-sm">Employado</th>
                        <th class="px-4 py-3 text-left text-sm">Código Verificación</th>
                        <th class="px-4 py-3 text-left text-sm">Estado</th>
                        <th class="px-4 py-3 text-left text-sm">Generado</th>
                        <th class="px-4 py-3 text-left text-sm">Confirmado</th>
                        <th class="px-4 py-3 text-center text-sm">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($checkins as $checkin)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold text-sm">{{ $checkin->procesoIngreso->codigo }}</td>
                            <td class="px-4 py-3 text-sm">{{ $checkin->procesoIngreso->nombre_completo }}</td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $checkin->codigo_verificacion }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-3 py-1 rounded text-white text-xs font-bold
                                    {{ $checkin->estado_checkin === 'Pendiente' ? 'bg-yellow-500' : 'bg-green-500' }}">
                                    {{ $checkin->estado_checkin }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $checkin->fecha_generacion->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">
                                {{ $checkin->fecha_confirmacion?->format('d/m/Y H:i') ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('checkins.show', $checkin->id) }}" 
                                       class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                        👁️
                                    </a>
                                    <a href="{{ route('checkins.pdf', $checkin->id) }}" 
                                       class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700"
                                       title="Descargar PDF">
                                        📄
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                No hay check-ins registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tarjetas de check-ins (Mobile/Tablet) --}}
        <div class="lg:hidden space-y-4">
            @forelse ($checkins as $checkin)
                <div class="bg-white rounded shadow p-4 border-l-4 
                    {{ $checkin->estado_checkin === 'Pendiente' ? 'border-yellow-500' : 'border-green-500' }}">
                    
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-bold text-lg text-gray-800">{{ $checkin->procesoIngreso->codigo }}</p>
                            <p class="text-sm text-gray-600">{{ $checkin->procesoIngreso->nombre_completo }}</p>
                        </div>
                        <span class="px-3 py-1 rounded text-white text-xs font-bold
                            {{ $checkin->estado_checkin === 'Pendiente' ? 'bg-yellow-500' : 'bg-green-500' }}">
                            {{ $checkin->estado_checkin }}
                        </span>
                    </div>
                    
                    <div class="space-y-2 mb-3 text-sm">
                        <div>
                            <p class="text-gray-600 text-xs">Código Verificación:</p>
                            <p class="font-mono font-semibold text-gray-800">{{ $checkin->codigo_verificacion }}</p>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Generado:</span>
                            <span class="font-semibold">{{ $checkin->fecha_generacion->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Confirmado:</span>
                            <span class="font-semibold">{{ $checkin->fecha_confirmacion?->format('d/m/Y H:i') ?? '-' }}</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('checkins.show', $checkin->id) }}" 
                           class="flex-1 bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 text-center font-semibold">
                            👁️ Ver
                        </a>
                        <a href="{{ route('checkins.pdf', $checkin->id) }}" 
                           class="flex-1 bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700 text-center font-semibold">
                            📄 PDF
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded shadow p-6 text-center text-gray-500">
                    No hay check-ins registrados
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        <div class="mt-6">
            {{ $checkins->links() }}
        </div>

    </div>
</x-app-layout>
