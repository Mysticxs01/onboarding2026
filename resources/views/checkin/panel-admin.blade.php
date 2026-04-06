@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">📊 Panel de Administración de Check-ins</h1>
        <p class="text-gray-600">Gestiona y supervisa todos los accesos de usuarios al sistema</p>
    </div>

    <!-- Estadísticas de Hoy -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Accesos Hoy -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold mb-1">Accesos Hoy</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $estadisticas['totalHoy'] }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.2 3.2.9-1.5-4.6-2.7z"></path>
                </svg>
            </div>
        </div>

        <!-- Usuarios Únicos -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold mb-1">Usuarios Únicos</p>
                    <p class="text-3xl font-bold text-green-600">{{ $estadisticas['usuariosUnicos'] }}</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"></path>
                </svg>
            </div>
        </div>

        <!-- Promedio por Área -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-3">Accesos por Área (Hoy)</p>
                <div class="space-y-2">
                    @foreach($estadisticas['porArea'] as $area)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-700">{{ $area->area->nombre ?? 'Sin Área' }}</span>
                            <span class="font-bold text-purple-600">{{ $area->total }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No hay datos disponibles</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filtros de Búsqueda</h2>
        <form method="GET" action="{{ route('checkin-acceso.panel-admin') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Área -->
            <div>
                <label for="area_id" class="block text-sm font-medium text-gray-700 mb-1">Área</label>
                <select name="area_id" id="area_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Todas las Áreas --</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" @selected(request('area_id') == $area->id)>
                            {{ $area->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Usuario -->
            <div>
                <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                <select name="usuario_id" id="usuario_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Todos los Usuarios --</option>
                    @foreach($usuarios->groupBy('area_id') as $areaId => $usuariosGrupo)
                        <optgroup label="{{ $usuariosGrupo->first()->area->nombre ?? 'Sin Área' }}">
                            @foreach($usuariosGrupo as $usuario)
                                <option value="{{ $usuario->id }}" @selected(request('usuario_id') == $usuario->id)>
                                    {{ $usuario->name }} ({{ $usuario->email }})
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <!-- Fecha Desde -->
            <div>
                <label for="fecha_desde" class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Fecha Hasta -->
            <div>
                <label for="fecha_hasta" class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Botones de Acción -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    Filtrar
                </button>
                <a href="{{ route('checkin-acceso.panel-admin') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg text-center transition">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de Accesos -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Registros de Check-in</h2>
                <p class="text-sm text-gray-600 mt-1">Mostrando {{ $accesos->count() }} de {{ $accesos->total() }} registros</p>
            </div>
            <a href="{{ route('checkin-acceso.exportar') }}" method="POST" onclick="if(confirm('¿Exportar todos los registros?')) { document.getElementById('exportForm').submit(); }" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                Exportar JSON
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Área</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Fecha y Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Dispositivo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">IP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Navegador</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($accesos as $acceso)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $acceso->usuario->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $acceso->usuario->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    {{ $acceso->area->nombre ?? 'Sin Área' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($acceso->fecha_acceso)->format('d/m/Y') }}</p>
                                <p class="text-gray-600">{{ $acceso->hora_acceso }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                    @if($acceso->dispositivo_tipo === 'Mobile')
                                        bg-blue-100 text-blue-800
                                    @elseif($acceso->dispositivo_tipo === 'Tablet')
                                        bg-purple-100 text-purple-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ $acceso->dispositivo_tipo ?? 'Desconocido' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                {{ $acceso->ip_address ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-2.5 py-0.5 rounded text-sm bg-gray-100 text-gray-800">
                                    {{ $acceso->navegador ?? 'Desconocido' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-gray-600 font-semibold">No hay accesos registrados</p>
                                    <p class="text-gray-500 text-sm">Ajusta los filtros e intenta de nuevo</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($accesos->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $accesos->links() }}
            </div>
        @endif
    </div>

    <!-- Botones de Acción -->
    <div class="mt-6 flex gap-4">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Volver al Dashboard
        </a>
    </div>
</div>

<!-- Formulario oculto para exportar -->
<form id="exportForm" action="{{ route('checkin-acceso.exportar') }}" method="POST" class="hidden">
    @csrf
    @if(request('area_id'))
        <input type="hidden" name="area_id" value="{{ request('area_id') }}">
    @endif
    @if(request('usuario_id'))
        <input type="hidden" name="usuario_id" value="{{ request('usuario_id') }}">
    @endif
    @if(request('fecha_desde'))
        <input type="hidden" name="fecha_desde" value="{{ request('fecha_desde') }}">
    @endif
    @if(request('fecha_hasta'))
        <input type="hidden" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
    @endif
</form>
@endsection
