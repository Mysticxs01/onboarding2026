@extends('layouts.app')

@section('title', 'Panel de Asignación de Cursos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Panel de Asignación de Cursos</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Panel de Procesos Pendientes --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-blue-900 mb-4">Procesos sin Asignaciones</h2>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($procesos as $proceso)
                        <a href="{{ route('asignaciones.asignar', $proceso) }}" class="block p-3 border rounded hover:bg-blue-50 transition">
                            <div class="font-semibold text-sm text-blue-900">{{ $proceso->nombre_completo }}</div>
                            <div class="text-xs text-gray-600">{{ $proceso->cargo->nombre }}</div>
                            <div class="text-xs text-gray-500">{{ $proceso->area->nombre }}</div>
                        </a>
                    @empty
                        <p class="text-gray-500 text-sm text-center py-4">No hay procesos pendientes</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Panel de Cursos Disponibles --}}
        <div class="lg:col-span-2">
            @if(isset($procesoActual))
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-blue-900 mb-4">
                        Asignar Cursos a {{ $procesoActual->nombre_completo }}
                    </h2>

                    <form action="{{ route('asignaciones.guardar', $procesoActual) }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="bg-blue-50 p-4 rounded">
                            <p class="text-sm text-blue-900"><strong>Cargo:</strong> {{ $procesoActual->cargo->nombre }}</p>
                            <p class="text-sm text-blue-900"><strong>Área:</strong> {{ $procesoActual->area->nombre }}</p>
                        </div>

                        {{-- Cursos Sugeridos --}}
                        @if(!empty($cursosSugeridos) && $cursosSugeridos->count() > 0)
                            <div class="border-l-4 border-green-500 pl-4 py-2 bg-green-50">
                                <h3 class="font-semibold text-green-900 mb-3">Cursos Sugeridos (Obligatorios)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($cursosSugeridos as $curso)
                                        <label class="flex items-start p-3 border rounded hover:bg-green-50 cursor-pointer">
                                            <input type="checkbox" name="curso_ids[]" value="{{ $curso->id }}" class="mt-1 mr-3" checked>
                                            <div class="text-sm">
                                                <div class="font-semibold text-green-900">{{ $curso->nombre }}</div>
                                                <div class="text-xs text-green-700">{{ $curso->duracion_horas }}h - {{ $curso->modalidad }}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Todos los Cursos Disponibles --}}
                        <div>
                            <h3 class="font-semibold text-blue-900 mb-3">Cursos Adicionales Disponibles</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-64 overflow-y-auto">
                                @foreach($cursosDisponibles as $curso)
                                    @if(empty($cursosSugeridos) || !$cursosSugeridos->contains($curso->id))
                                        <label class="flex items-start p-3 border rounded hover:bg-gray-50 cursor-pointer">
                                            <input type="checkbox" name="curso_ids[]" value="{{ $curso->id }}" class="mt-1 mr-3">
                                            <div class="text-sm">
                                                <div class="font-semibold text-blue-900">{{ $curso->nombre }}</div>
                                                <div class="text-xs text-gray-600">{{ $curso->duracion_horas }}h - {{ $curso->modalidad }}</div>
                                                <div class="text-xs text-gray-500">{{ $curso->categoria }}</div>
                                            </div>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-blue-900 mb-2">Fecha Límite de Cumplimiento</label>
                            <input type="date" name="fecha_limite" class="w-full border rounded px-3 py-2">
                        </div>

                        <div class="flex gap-4 pt-4 border-t">
                            <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-800">
                                Asignar Cursos Seleccionados
                            </button>
                            <a href="{{ route('asignaciones.panel') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded hover:bg-gray-400">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
                    <p>Selecciona un proceso para asignar cursos</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
