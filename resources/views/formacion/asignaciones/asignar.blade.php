@extends('layouts.app')

@section('title', 'Asignar Cursos')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #1B365D;">
            {{ __('Asignar Cursos de Formación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                <!-- Panel Izquierdo: Información del Proceso -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #1B365D;">
                        <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">📋 Empleado</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Nombre Completo</p>
                                <p class="text-sm font-semibold" style="color: #1B365D;">{{ $proceso->nombre_completo }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Cargo</p>
                                <p class="text-sm font-semibold" style="color: #1B365D;">{{ $proceso->cargo->nombre }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Área</p>
                                <p class="text-sm font-semibold" style="color: #28A745;">{{ $proceso->area->nombre }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Gerencia</p>
                                <p class="text-sm font-semibold" style="color: #C59D42;">{{ $proceso->area->gerencia?->nombre ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="border-t mt-4 pt-4" style="border-color: #1B365D;">
                            <p class="text-xs text-gray-600 uppercase tracking-wide mb-2">Cursos Asignados</p>
                            <p class="text-2xl font-bold" style="color: #1B365D;">
                                {{ count($cursosAsignados) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Panel Central y Derecho: Formulario de Asignación -->
                <div class="lg:col-span-3">
                    <form action="{{ route('asignaciones.guardar', $proceso) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Cursos Sugeridos (Kit Estándar) -->
                        @if($cursosSugeridos->count() > 0)
                            <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #28A745;">
                                <h3 class="text-lg font-bold mb-4" style="color: #28A745;">
                                    ✅ Kit de Formación Sugerido (Obligatorios)
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    Estos cursos están diseñados específicamente para el cargo <strong>{{ $proceso->cargo->nombre }}</strong>
                                </p>

                                <div class="space-y-3">
                                    @foreach($cursosSugeridos as $curso)
                                        <label class="flex items-start p-4 border-2 rounded cursor-pointer transition hover:bg-green-50" 
                                               style="border-color: #28A745;">
                                            <input type="checkbox" 
                                                   name="curso_ids[]" 
                                                   value="{{ $curso->id }}" 
                                                   class="mt-1 mr-3 w-5 h-5"
                                                   @if(in_array($curso->id, $cursosAsignados)) checked @endif>
                                            <div class="flex-1">
                                                <div class="font-semibold" style="color: #28A745;">{{ $curso->nombre }}</div>
                                                <div class="text-sm text-gray-600 mt-1">
                                                    <span class="badge badge-secondary">{{ $curso->duracion_horas }}h</span>
                                                    <span class="badge badge-secondary">{{ $curso->modalidad }}</span>
                                                    <span class="text-xs text-gray-500 mt-1 block">{{ $curso->descripcion }}</span>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Cursos Adicionales Disponibles -->
                        <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #C59D42;">
                            <h3 class="text-lg font-bold mb-4" style="color: #C59D42;">
                                📚 Cursos Adicionales Disponibles
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Selecciona cursos adicionales para complementar la formación del empleado
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto p-4 bg-gray-50 rounded">
                                @forelse($cursosDisponibles as $curso)
                                    @if(!$cursosSugeridos->contains($curso->id))
                                        <label class="flex items-start p-4 border rounded cursor-pointer transition hover:bg-white bg-white">
                                            <input type="checkbox" 
                                                   name="curso_ids[]" 
                                                   value="{{ $curso->id }}"
                                                   class="mt-1 mr-3 w-5 h-5"
                                                   @if(in_array($curso->id, $cursosAsignados)) checked @endif>
                                            <div class="flex-1">
                                                <div class="font-semibold" style="color: #1B365D;">{{ $curso->nombre }}</div>
                                                <div class="text-xs text-gray-600 mt-1">
                                                    <div>⏱️ {{ $curso->duracion_horas }} horas</div>
                                                    <div>🎓 {{ $curso->modalidad }}</div>
                                                    @if($curso->categoria)
                                                        <div>📂 {{ $curso->categoria }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </label>
                                    @endif
                                @empty
                                    <p class="text-gray-500 text-sm col-span-2">No hay más cursos disponibles</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Fecha Límite -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">
                                📅 Fecha Límite de Cumplimiento (Opcional)
                            </label>
                            <input type="date" 
                                   name="fecha_limite" 
                                   class="w-full border rounded px-4 py-2"
                                   style="border-color: #1B365D;">
                            <p class="text-xs text-gray-600 mt-2">
                                Si no especificas, se establecerá 3 meses desde hoy
                            </p>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex gap-4 justify-end">
                            <a href="{{ route('asignaciones.panel') }}" class="btn-outline-primary">
                                ❌ Cancelar
                            </a>
                            <button type="submit" class="btn-primary">
                                ✅ Asignar Cursos Seleccionados
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- Resumen de Cursos Seleccionados (Sticky) -->
            <div class="mt-8 bg-white rounded-lg shadow p-6" style="border-left: 4px solid #1B365D;">
                <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">📊 Resumen</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded">
                        <p class="text-xs text-gray-600 uppercase mb-2">Total de Cursos Disponibles</p>
                        <p class="text-3xl font-bold" style="color: #1B365D;">{{ $cursosDisponibles->count() }}</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded">
                        <p class="text-xs text-gray-600 uppercase mb-2">Cursos Sugeridos</p>
                        <p class="text-3xl font-bold" style="color: #28A745;">{{ $cursosSugeridos->count() }}</p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded">
                        <p class="text-xs text-gray-600 uppercase mb-2">Ya Asignados</p>
                        <p class="text-3xl font-bold" style="color: #C59D42;">{{ count($cursosAsignados) }}</p>
                    </div>
                </div>

                <p class="text-sm text-gray-600 mt-4">
                    ℹ️ <strong>Nota:</strong> Los cursos sugeridos están pre-marcados. Puedes desmarcarlos si es necesario.
                    Selecciona cursos adicionales según las necesidades de formación del empleado.
                </p>
            </div>

        </div>
    </div>
</x-app-layout>
@endsection
