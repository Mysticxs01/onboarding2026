@extends('layouts.app')

@section('title', 'Listado de Asignaciones de Cursos')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight" style="color: #1B365D;">
                {{ __('Mis Asignaciones de Cursos') }}
            </h2>
            @if(Auth::user()->hasRole(['Jefe RRHH', 'Admin']))
                <a href="{{ route('asignaciones.panel') }}" class="btn-primary">
                    ➕ Nueva Asignación
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">Estado</label>
                        <select class="w-full border rounded px-3 py-2" id="filtroEstado" onchange="filtrar()">
                            <option value="">Todos los estados</option>
                            <option value="Asignado">Asignado</option>
                            <option value="En Progreso">En Progreso</option>
                            <option value="Completado">Completado</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">Búsqueda</label>
                        <input type="text" id="busqueda" class="w-full border rounded px-3 py-2" placeholder="Nombre del curso..." onkeyup="filtrar()">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">Mi Rol</label>
                        <p class="text-sm mt-2">{{ Auth::user()->getRoleNames()->implode(', ') }}</p>
                    </div>
                </div>
            </div>

            <!-- Tabla de Asignaciones -->
            @if($asignaciones->count() > 0)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="w-full">
                        <thead style="background-color: #1B365D; color: white;">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Empleado</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Curso</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Estado</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Fecha Límite</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($asignaciones as $asignacion)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold" style="color: #1B365D;">
                                            {{ $asignacion->procesoIngreso->nombre_completo }}
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            {{ $asignacion->procesoIngreso->cargo->nombre }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold" style="color: #1B365D;">
                                            {{ $asignacion->curso->nombre }}
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            {{ $asignacion->curso->duracion_horas }}h - {{ $asignacion->curso->modalidad }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $colors = [
                                                'Asignado' => ['bg' => '#E7F1FF', 'text' => '#1B365D', 'icon' => '📋'],
                                                'En Progreso' => ['bg' => '#FFF4E1', 'text' => '#C59D42', 'icon' => '🔄'],
                                                'Completado' => ['bg' => '#E8F5E9', 'text' => '#28A745', 'icon' => '✅'],
                                                'Cancelado' => ['bg' => '#FFEBEE', 'text' => '#DC3545', 'icon' => '❌'],
                                            ];
                                            $estilo = $colors[$asignacion->estado] ?? $colors['Asignado'];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                              style="background-color: {{ $estilo['bg'] }}; color: {{ $estilo['text'] }};">
                                            {{ $estilo['icon'] }} {{ $asignacion->estado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            {{ $asignacion->fecha_limite?->format('d/m/Y') ?? 'No definida' }}
                                        </div>
                                        @if($asignacion->fecha_limite && $asignacion->fecha_limite < now())
                                            <div class="text-xs text-red-600 font-semibold">⚠️ Vencida</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('asignaciones.show', $asignacion) }}" 
                                               class="text-sm px-3 py-1 rounded transition"
                                               style="background-color: #F0F0F0; color: #1B365D;">
                                                👁️ Ver
                                            </a>
                                            
                                            @if(Auth::user()->hasRole(['Jefe RRHH', 'Admin']))
                                                @if($asignacion->estado === 'Asignado')
                                                    <form action="{{ route('asignaciones.marcar-progreso', $asignacion) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-sm px-3 py-1 rounded transition btn-secondary">
                                                            ▶️ Iniciar
                                                        </button>
                                                    </form>
                                                @elseif($asignacion->estado === 'En Progreso')
                                                    <a href="{{ route('asignaciones.validar', $asignacion) }}"
                                                       class="text-sm px-3 py-1 rounded transition btn-primary">
                                                        ✓ Validar
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-6">
                    {{ $asignaciones->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <p class="text-gray-500 text-lg mb-4">No hay asignaciones de cursos</p>
                    @if(Auth::user()->hasRole(['Jefe RRHH', 'Admin']))
                        <a href="{{ route('asignaciones.panel') }}" class="btn-primary">
                            ➕ Crear Primera Asignación
                        </a>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
@endsection

<script>
function filtrar() {
    const estado = document.getElementById('filtroEstado').value;
    const busqueda = document.getElementById('busqueda').value.toLowerCase();
    
    const filas = document.querySelectorAll('tbody tr');
    
    filas.forEach(fila => {
        const estadoFila = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const nombreFila = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
        
        const coincideEstado = !estado || estadoFila.includes(estado.toLowerCase());
        const coincideBusqueda = !busqueda || nombreFila.includes(busqueda);
        
        fila.style.display = (coincideEstado && coincideBusqueda) ? '' : 'none';
    });
}
</script>
