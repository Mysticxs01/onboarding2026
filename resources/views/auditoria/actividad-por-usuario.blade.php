<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #1B365D;">
            👥 Actividad por Usuario
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="grid gap-8">
            @forelse($usuarios as $usuario)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4 pb-4 border-b-2">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
                                {{ substr($usuario->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $usuario->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $usuario->email }}</p>
                            </div>
                        </div>
                        <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-bold">
                            {{ $usuario->auditorias_count ?? count($usuario->auditorias) }} registros
                        </span>
                    </div>

                    {{-- Tabla de actividades del usuario --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr style="background-color: #f8f9fa; border-bottom: 1px solid #e5e7eb;">
                                    <th class="text-left p-3 text-gray-700 font-bold">Fecha/Hora</th>
                                    <th class="text-left p-3 text-gray-700 font-bold">Acción</th>
                                    <th class="text-left p-3 text-gray-700 font-bold">Entidad</th>
                                    <th class="text-left p-3 text-gray-700 font-bold">ID</th>
                                    <th class="text-left p-3 text-gray-700 font-bold">IP</th>
                                    <th class="text-center p-3 text-gray-700 font-bold">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuario->auditorias as $auditoria)
                                    <tr style="border-bottom: 1px solid #f0f0f0;">
                                        <td class="p-3 text-gray-700">{{ $auditoria->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td class="p-3">
                                            @switch($auditoria->accion)
                                                @case('create')
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-bold">✓ Creación</span>
                                                    @break
                                                @case('update')
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-bold">✎ Actualización</span>
                                                    @break
                                                @case('delete')
                                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-bold">✕ Eliminación</span>
                                                    @break
                                                @default
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-bold">{{ ucfirst($auditoria->accion) }}</span>
                                            @endswitch
                                        </td>
                                        <td class="p-3 text-gray-700 font-semibold">{{ $auditoria->entidad }}</td>
                                        <td class="p-3 text-gray-700">{{ $auditoria->entidad_id }}</td>
                                        <td class="p-3 text-gray-700 text-xs">{{ $auditoria->ip_origin }}</td>
                                        <td class="p-3 text-center">
                                            <a href="{{ route('auditoria.show', $auditoria->id) }}" class="text-blue-600 hover:text-blue-800 font-bold text-xs">
                                                Ver
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-gray-500">
                                            Sin registros de auditoría
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                    <p class="text-blue-800">No hay usuarios con registros de auditoría</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            <a href="{{ route('auditoria.dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                ← Volver al Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
