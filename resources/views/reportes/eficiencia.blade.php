<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #1B365D;">
            Reporte de eficiencia
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Resumen general</h3>
                <div class="space-y-3 text-sm text-gray-700">
                    <div class="flex items-center justify-between">
                        <span>Total de solicitudes</span>
                        <span class="font-semibold text-blue-900">{{ $totals['total'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>A tiempo</span>
                        <span class="font-semibold text-emerald-600">
                            {{ $totals['on_time'] }} ({{ $totals['on_time_pct'] }}%)
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Retrasos</span>
                        <span class="font-semibold text-rose-600">
                            {{ $totals['late'] }} ({{ $totals['late_pct'] }}%)
                        </span>
                    </div>
                </div>
                <p class="mt-4 text-xs text-gray-500">
                    A tiempo: solicitudes finalizadas en o antes de la fecha de ingreso.
                    Retrasos: solicitudes finalizadas despues de la fecha de ingreso o pendientes vencidas.
                </p>
            </div>

            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Eficiencia por area</h3>

                <div class="space-y-4">
                    @forelse ($porArea as $fila)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="font-semibold text-gray-900">{{ $fila['area'] }}</div>
                                <div class="text-xs text-gray-600">Total: {{ $fila['total'] }}</div>
                            </div>

                            <div class="mt-3">
                                <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                    <span>A tiempo</span>
                                    <span>{{ $fila['on_time'] }} ({{ $fila['on_time_pct'] }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-emerald-500 h-3 rounded-full" style="width: {{ $fila['on_time_pct'] }}%"></div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                    <span>Retrasos</span>
                                    <span>{{ $fila['late'] }} ({{ $fila['late_pct'] }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-rose-500 h-3 rounded-full" style="width: {{ $fila['late_pct'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">No hay datos para mostrar.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
