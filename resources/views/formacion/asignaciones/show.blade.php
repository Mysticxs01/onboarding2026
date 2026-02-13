@extends('layouts.app')

@section('title', 'Ver Asignación de Curso')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #1B365D;">
            Detalles de Asignación de Curso
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Panel Izquierdo: Empleado y Curso -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Información del Empleado -->
                    <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #1B365D;">
                        <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">👤 Empleado</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Nombre</p>
                                <p class="text-sm font-semibold" style="color: #1B365D;">
                                    {{ $asignacion->procesoIngreso->nombre_completo }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Cargo</p>
                                <p class="text-sm font-semibold" style="color: #1B365D;">
                                    {{ $asignacion->procesoIngreso->cargo->nombre }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Área</p>
                                <p class="text-sm font-semibold" style="color: #28A745;">
                                    {{ $asignacion->procesoIngreso->area->nombre }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Estado Actual -->
                    <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #C59D42;">
                        <h3 class="text-lg font-bold mb-4" style="color: #C59D42;">📊 Estado</h3>
                        @php
                            $colors = [
                                'Asignado' => ['bg' => '#E7F1FF', 'text' => '#1B365D', 'icon' => '📋'],
                                'En Progreso' => ['bg' => '#FFF4E1', 'text' => '#C59D42', 'icon' => '🔄'],
                                'Completado' => ['bg' => '#E8F5E9', 'text' => '#28A745', 'icon' => '✅'],
                                'Cancelado' => ['bg' => '#FFEBEE', 'text' => '#DC3545', 'icon' => '❌'],
                            ];
                            $estilo = $colors[$asignacion->estado] ?? $colors['Asignado'];
                        @endphp
                        <p class="text-center px-4 py-3 rounded text-sm font-bold mb-4"
                           style="background-color: {{ $estilo['bg'] }}; color: {{ $estilo['text'] }};">
                            {{ $estilo['icon'] }} {{ $asignacion->estado }}
                        </p>

                        <div class="space-y-2 text-sm">
                            <div>
                                <p class="text-xs text-gray-600">Asignado el</p>
                                <p class="font-semibold" style="color: #1B365D;">
                                    {{ $asignacion->fecha_asignacion->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Fecha Límite</p>
                                <p class="font-semibold" style="color: #C59D42;">
                                    {{ $asignacion->fecha_limite?->format('d/m/Y') ?? 'No definida' }}
                                </p>
                            </div>
                            @if($asignacion->fecha_completado)
                                <div>
                                    <p class="text-xs text-gray-600">Completado el</p>
                                    <p class="font-semibold" style="color: #28A745;">
                                        {{ $asignacion->fecha_completado->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Asignado Por -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-sm font-bold mb-3 text-gray-600">Asignado Por</h3>
                        <p class="font-semibold" style="color: #1B365D;">
                            {{ $asignacion->asignadoPor?->name ?? 'Sistema' }}
                        </p>
                    </div>
                </div>

                <!-- Panel Central: Información del Curso -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow p-6 mb-6" style="border-left: 4px solid #28A745;">
                        <h3 class="text-2xl font-bold mb-4" style="color: #28A745;">📚 {{ $asignacion->curso->nombre }}</h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="p-3 bg-blue-50 rounded text-center">
                                <p class="text-xs text-gray-600 uppercase mb-1">Duración</p>
                                <p class="text-xl font-bold" style="color: #1B365D;">{{ $asignacion->curso->duracion_horas }}h</p>
                            </div>
                            <div class="p-3 bg-green-50 rounded text-center">
                                <p class="text-xs text-gray-600 uppercase mb-1">Modalidad</p>
                                <p class="text-lg font-bold" style="color: #28A745;">{{ ucfirst($asignacion->curso->modalidad) }}</p>
                            </div>
                            <div class="p-3 bg-yellow-50 rounded text-center">
                                <p class="text-xs text-gray-600 uppercase mb-1">Categoría</p>
                                <p class="text-sm font-bold" style="color: #C59D42;">{{ $asignacion->curso->categoria ?? 'General' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded text-center">
                                <p class="text-xs text-gray-600 uppercase mb-1">Créditos</p>
                                <p class="text-xl font-bold" style="color: #1B365D;">{{ $asignacion->curso->creditos ?? 0 }}</p>
                            </div>
                        </div>

                        <div class="border-t pt-4 mb-4">
                            <h4 class="font-bold mb-2" style="color: #1B365D;">Descripción</h4>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                {{ $asignacion->curso->descripcion ?? 'No hay descripción disponible' }}
                            </p>
                        </div>

                        @if($asignacion->curso->objetivos)
                            <div class="border-t pt-4 mb-4">
                                <h4 class="font-bold mb-2" style="color: #1B365D;">Objetivos</h4>
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    {{ $asignacion->curso->objetivos }}
                                </p>
                            </div>
                        @endif

                        @if($asignacion->curso->facilitador)
                            <div class="border-t pt-4">
                                <h4 class="font-bold mb-2" style="color: #1B365D;">Facilitador</h4>
                                <p class="text-sm" style="color: #1B365D;">{{ $asignacion->curso->facilitador }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Información de Calificación (si está completado) -->
                    @if($asignacion->estado === 'Completado')
                        <div class="bg-white rounded-lg shadow p-6 mb-6" style="border-left: 4px solid #28A745;">
                            <h3 class="text-lg font-bold mb-4" style="color: #28A745;">✅ Resultado</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                @if($asignacion->calificacion)
                                    <div>
                                        <p class="text-xs text-gray-600 uppercase mb-1">Calificación</p>
                                        <div class="text-3xl font-bold mb-2" style="color: #28A745;">
                                            {{ $asignacion->calificacion }}/100
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full" 
                                                 style="width: {{ min($asignacion->calificacion, 100) }}%"></div>
                                        </div>
                                    </div>
                                @endif

                                @if($asignacion->certificado_url)
                                    <div>
                                        <p class="text-xs text-gray-600 uppercase mb-2">Certificado</p>
                                        <a href="{{ $asignacion->certificado_url }}" 
                                           target="_blank"
                                           class="inline-block btn-secondary">
                                            📄 Descargar Certificado
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Acciones -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">🔧 Acciones</h3>
                        
                        <div class="space-y-3">
                            @if(Auth::user()->hasRole(['Jefe RRHH', 'Admin']))
                                @if($asignacion->estado === 'Asignado')
                                    <form action="{{ route('asignaciones.marcar-progreso', $asignacion) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full btn-secondary">
                                            ▶️ Marcar como En Progreso
                                        </button>
                                    </form>
                                @elseif($asignacion->estado === 'En Progreso')
                                    <a href="{{ route('asignaciones.validar', $asignacion) }}" class="block btn-primary text-center">
                                        ✓ Validar Completación
                                    </a>
                                @elseif($asignacion->estado === 'Completado')
                                    <div class="p-4 bg-green-50 rounded text-center">
                                        <p class="text-green-700 font-semibold">✅ Curso completado exitosamente</p>
                                    </div>
                                @endif

                                @if(in_array($asignacion->estado, ['Asignado', 'En Progreso']))
                                    <form action="{{ route('asignaciones.cancelar', $asignacion) }}" method="POST" class="mt-2">
                                        @csrf
                                        <div class="mb-2">
                                            <label class="block text-xs font-semibold mb-1" style="color: #1B365D;">
                                                Motivo de Cancelación
                                            </label>
                                            <textarea name="motivo" class="w-full border rounded px-3 py-2 text-sm" 
                                                      rows="2" placeholder="Explica por qué se cancela."></textarea>
                                        </div>
                                        <button type="submit" class="w-full btn-outline-primary">
                                            ❌ Cancelar Asignación
                                        </button>
                                    </form>
                                @endif
                            @else
                                <p class="text-gray-600 text-sm italic">
                                    Solo administradores pueden cambiar el estado de las asignaciones.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Botón de Retorno -->
            <div class="mt-8">
                <a href="{{ route('asignaciones.index') }}" class="btn-outline-primary">
                    ← Volver a Asignaciones
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
@endsection
