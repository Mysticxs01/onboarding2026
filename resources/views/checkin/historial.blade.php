@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Mi Historial de Accesos</h1>
        <p class="text-gray-600">Visualiza todos tus accesos registrados en el sistema</p>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total de Accesos -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold mb-1">Total de Accesos</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $estadisticas['total'] }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
            </div>
        </div>

        <!-- Accesos Este Mes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold mb-1">Este Mes</p>
                    <p class="text-3xl font-bold text-green-600">{{ $estadisticas['mes'] }}</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 7V3m8 4V3m-9 8h10m5 8H4a2 2 0 01-2-2V7a2 2 0 012-2h16a2 2 0 012 2v12a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>

        <!-- Accesos Esta Semana -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold mb-1">Esta Semana</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $estadisticas['semana'] }}</p>
                </div>
                <svg class="w-12 h-12 text-purple-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Tabla de Accesos -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Registro Detallado de Accesos</h2>
            <p class="text-sm text-gray-600 mt-1">Mostrando {{ $accesos->count() }} de {{ $accesos->total() }} registros</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Fecha y Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Dispositivo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Navegador</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Dirección IP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($accesos as $acceso)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($acceso->fecha_acceso)->format('d/m/Y') }}</p>
                                    <p class="text-sm text-gray-600">{{ $acceso->hora_acceso }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    @if($acceso->dispositivo_tipo === 'Mobile')
                                        bg-blue-100 text-blue-800
                                    @elseif($acceso->dispositivo_tipo === 'Tablet')
                                        bg-purple-100 text-purple-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    @if($acceso->dispositivo_tipo === 'Mobile')
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1a1 1 0 011 1v1a1 1 0 11-2 0V2a1 1 0 011-1zM4.22 4.22a1 1 0 011.415 0l.707.707a1 1 0 11-1.415 1.415l-.707-.707a1 1 0 010-1.415zm11.313 0a1 1 0 011.416 0l.707.707a1 1 0 01-1.415 1.415l-.708-.707a1 1 0 010-1.415zM4 12a1 1 0 110 2h1a1 1 0 110-2H4zm15 0a1 1 0 110 2h1a1 1 0 110-2h-1zM4.22 19.78a1 1 0 011.415 0l.707.707a1 1 0 11-1.415 1.414l-.707-.707a1 1 0 010-1.414zM16.95 20.49l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 111.414-1.414zM12 20a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1z"></path></svg>
                                    @elseif($acceso->dispositivo_tipo === 'Tablet')
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2a2 2 0 012-2h6a2 2 0 012 2v16a2 2 0 01-2 2H9a2 2 0 01-2-2V2z"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M4 5a2 2 0 012-2h12a2 2 0 012 2v7H4V5zm14 9H4v5a2 2 0 002 2h12a2 2 0 002-2v-5z"></path></svg>
                                    @endif
                                    {{ $acceso->dispositivo_tipo ?? 'Desconocido' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    {{ $acceso->navegador ?? 'Desconocido' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                {{ $acceso->ip_address ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm" onclick="verDetalles(this)">
                                    Ver →
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-gray-600 font-semibold">No hay accesos registrados</p>
                                    <p class="text-gray-500 text-sm">Tus accesos aparecerán aquí</p>
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
        <a href="{{ route('checkin-acceso.mostrar-bienvenida') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Registrar Nuevo Check-in
        </a>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Volver al Dashboard
        </a>
    </div>
</div>

<script>
    function verDetalles(btn) {
        // Aquí se podría implementar un modal con más detalles
        alert('Funcionalidad de detalles disponible próximamente');
    }
</script>
@endsection
