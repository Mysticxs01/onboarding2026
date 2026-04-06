@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">📈 Estadísticas de Accesos - {{ $area->nombre }}</h1>
        <p class="text-gray-600">Análisis detallado de accesos en el área: <span class="font-semibold">{{ $area->nombre }}</span></p>
    </div>

    <!-- Estadísticas Principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Accesos Hoy -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold mb-1">Accesos Hoy</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $estadisticas['hoy'] }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.2 3.2.9-1.5-4.6-2.7z"></path>
                </svg>
            </div>
        </div>

        <!-- Accesos Esta Semana -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold mb-1">Esta Semana</p>
                    <p class="text-3xl font-bold text-green-600">{{ $estadisticas['semana'] }}</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 7V3m8 4V3m-9 8h10m5 8H4a2 2 0 01-2-2V7a2 2 0 012-2h16a2 2 0 012 2v12a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>

        <!-- Accesos Este Mes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold mb-1">Este Mes</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $estadisticas['mes'] }}</p>
                </div>
                <svg class="w-12 h-12 text-purple-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>

        <!-- Usuarios Activos -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold mb-1">Usuarios Activos</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $estadisticas['usuariosActivos'] }}</p>
                </div>
                <svg class="w-12 h-12 text-orange-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Análisis Comparativo -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Tendencia -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Comparativa</h3>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Hoy</span>
                        <span class="text-sm font-bold text-blue-600">{{ $estadisticas['hoy'] }} accesos</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: @if($estadisticas['mes'] > 0){{ min(100, ($estadisticas['hoy'] / ($estadisticas['mes'] / 30)) * 100) }}@else 0 @endif%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Esta Semana Prom.</span>
                        <span class="text-sm font-bold text-green-600">{{ round($estadisticas['semana'] / 7, 1) }} accesos/día</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full transition-all" style="width: @if($estadisticas['mes'] > 0){{ min(100, ((round($estadisticas['semana'] / 7, 1)) / ($estadisticas['mes'] / 30)) * 100) }}@else 0 @endif%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Este Mes Prom.</span>
                        <span class="text-sm font-bold text-purple-600">{{ round($estadisticas['mes'] / 30, 1) }} accesos/día</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado del Área -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">🏢 Estado del Área</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">Actividad</span>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium 
                        @if($estadisticas['hoy'] > 0)
                            bg-green-100 text-green-800
                        @else
                            bg-gray-100 text-gray-800
                        @endif
                    ">
                        @if($estadisticas['hoy'] > 0) Activa @else Inactiva @endif
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">Usuarios</span>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                        {{ $estadisticas['usuariosActivos'] }} en línea
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">Ocupación</span>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium 
                        @if($estadisticas['usuariosActivos'] > 0)
                            bg-orange-100 text-orange-800
                        @else
                            bg-gray-100 text-gray-800
                        @endif
                    ">
                        {{ $estadisticas['usuariosActivos'] }}% - @if($estadisticas['usuariosActivos'] > 50) Alta @elseif($estadisticas['usuariosActivos'] > 25) Media @else Baja @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Información del Área -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">ℹ️ Información</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">Nombre del Área</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $area->nombre }}</p>
                </div>
                
                <div>
                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">Descripción</p>
                    <p class="text-gray-700">{{ $area->descripcion ?? 'Sin descripción disponible' }}</p>
                </div>
                
                <div class="pt-3 border-t border-gray-200">
                    <p class="text-xs text-gray-600">Última actualización: {{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos Recientes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Últimos 10 Accesos Registrados</h2>
            <p class="text-sm text-gray-600 mt-1">Actividad más reciente en esta área</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Fecha y Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Dispositivo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Navegador</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($ultimosAccesos as $acceso)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $acceso->usuario->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $acceso->usuario->email }}</p>
                                </div>
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-2.5 py-0.5 rounded text-sm bg-gray-100 text-gray-800">
                                    {{ $acceso->navegador ?? 'Desconocido' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                {{ $acceso->ip_address ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-600">
                                No hay accesos registrados en esta área
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="mt-6 flex gap-4">
        <a href="{{ route('checkin-acceso.panel-admin') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Ir al Panel Admin
        </a>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Volver al Dashboard
        </a>
    </div>
</div>
@endsection
