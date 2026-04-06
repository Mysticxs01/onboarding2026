<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #1B365D;">
            📊 Reporte de Auditoría por Área
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="grid gap-8">
            @forelse($reportes as $reporte)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4 pb-4 border-b-2 border-blue-600">
                        <h3 class="text-xl font-bold text-gray-800">{{ $reporte['area'] }}</h3>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-blue-600">{{ $reporte['cantidad_cambios'] }}</p>
                            <p class="text-sm text-gray-600">cambios registrados</p>
                        </div>
                    </div>

                    {{-- Barra de Progreso --}}
                    <div class="mb-6">
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full" style="width: {{ min(($reporte['cantidad_cambios'] / array_reduce($reportes, function($max, $item) { return max($max, $item['cantidad_cambios']); }, 0)) * 100, 100) }}%"></div>
                        </div>
                    </div>

                    {{-- Estadísticas Rápidas --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <p class="text-sm text-gray-600">Actividad</p>
                            <p class="text-2xl font-bold text-green-600">
                                @if($reporte['cantidad_cambios'] > 0)
                                    ✓ Activa
                                @else
                                    ○ Sin Cambios
                                @endif
                            </p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <p class="text-sm text-gray-600">Porcentaje</p>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ round(($reporte['cantidad_cambios'] / array_sum(array_column($reportes, 'cantidad_cambios'))) * 100, 1) }}%
                            </p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <p class="text-sm text-gray-600">Estatus</p>
                            <p class="text-2xl font-bold text-purple-600">
                                @if($reporte['cantidad_cambios'] > 50)
                                    🔴 Alto
                                @elseif($reporte['cantidad_cambios'] > 20)
                                    🟡 Medio
                                @else
                                    🟢 Bajo
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
                    <p class="text-blue-800 font-semibold">No hay datos de auditoría disponibles</p>
                </div>
            @endforelse
        </div>

        {{-- Resumen General --}}
        @if(count($reportes) > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mt-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6 pb-4 border-b-2 border-blue-600">📈 Resumen General</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <p class="text-4xl font-bold text-blue-600">{{ array_sum(array_column($reportes, 'cantidad_cambios')) }}</p>
                        <p class="text-gray-600 font-semibold mt-2">Total de Cambios</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl font-bold text-green-600">{{ count($reportes) }}</p>
                        <p class="text-gray-600 font-semibold mt-2">Áreas Monitoreadas</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl font-bold text-purple-600">
                            {{ round(array_sum(array_column($reportes, 'cantidad_cambios')) / max(count($reportes), 1), 1) }}
                        </p>
                        <p class="text-gray-600 font-semibold mt-2">Promedio por Área</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-8">
            <a href="{{ route('auditoria.dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                ← Volver al Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
