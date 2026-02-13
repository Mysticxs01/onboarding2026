@extends('layouts.app')

@section('title', 'Validar Completación de Curso')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #1B365D;">
            Validar Completación de Curso
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Panel de Información -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Empleado -->
                    <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #1B365D;">
                        <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">👤 Empleado</h3>
                        <p class="font-semibold text-sm" style="color: #1B365D;">
                            {{ $asignacion->procesoIngreso->nombre_completo }}
                        </p>
                        <p class="text-xs text-gray-600 mt-2">
                            Cargo: {{ $asignacion->procesoIngreso->cargo->nombre }}
                        </p>
                        <p class="text-xs text-gray-600">
                            Área: {{ $asignacion->procesoIngreso->area->nombre }}
                        </p>
                    </div>

                    <!-- Curso -->
                    <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #28A745;">
                        <h3 class="text-lg font-bold mb-4" style="color: #28A745;">📚 Curso</h3>
                        <p class="font-semibold text-sm" style="color: #28A745;">
                            {{ $asignacion->curso->nombre }}
                        </p>
                        <p class="text-xs text-gray-600 mt-2">
                            {{ $asignacion->curso->duracion_horas }}h - {{ $asignacion->curso->modalidad }}
                        </p>
                    </div>

                    <!-- Fecha Límite -->
                    <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #C59D42;">
                        <h3 class="text-lg font-bold mb-4" style="color: #C59D42;">📅 Fecha Límite</h3>
                        <p class="font-semibold text-sm" style="color: #C59D42;">
                            {{ $asignacion->fecha_limite?->format('d/m/Y') ?? 'No definida' }}
                        </p>
                        @if($asignacion->fecha_limite && $asignacion->fecha_limite < now())
                            <p class="text-xs text-red-600 font-semibold mt-2">
                                ⚠️ Vencida hace {{ now()->diffInDays($asignacion->fecha_limite) }} días
                            </p>
                        @elseif($asignacion->fecha_limite)
                            <p class="text-xs text-gray-600 mt-2">
                                Falta {{ $asignacion->fecha_limite->diffInDays(now()) }} días
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Formulario de Validación -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-bold mb-6" style="color: #1B365D;">✅ Validar Completación</h3>

                        <form action="{{ route('asignaciones.marcar-completada', $asignacion) }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- Calificación -->
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">
                                    📊 Calificación (0-100)
                                </label>
                                <input type="number" 
                                       name="calificacion" 
                                       class="w-full border rounded px-4 py-2"
                                       min="0" 
                                       max="100" 
                                       step="0.5"
                                       placeholder="Ingresa la calificación obtenida"
                                       style="border-color: #1B365D;">
                                <p class="text-xs text-gray-600 mt-2">
                                    Ej: 85.5, 90, 100
                                </p>
                            </div>

                            <!-- Escala Visual de Calificación -->
                            <div class="p-4 bg-gray-50 rounded">
                                <p class="text-xs font-semibold text-gray-600 mb-3">Escala de Desempeño</p>
                                <div class="grid grid-cols-4 gap-2">
                                    <button type="button" class="p-2 text-center rounded text-xs font-semibold transition cursor-pointer hover:scale-105" 
                                            style="background-color: #FFEBEE; color: #DC3545;">
                                        <div>0-60</div>
                                        <div class="text-xs">Bajo</div>
                                    </button>
                                    <button type="button" class="p-2 text-center rounded text-xs font-semibold transition cursor-pointer hover:scale-105"
                                            style="background-color: #FFF4E1; color: #C59D42;">
                                        <div>61-80</div>
                                        <div class="text-xs">Medio</div>
                                    </button>
                                    <button type="button" class="p-2 text-center rounded text-xs font-semibold transition cursor-pointer hover:scale-105"
                                            style="background-color: #E8F5E9; color: #28A745;">
                                        <div>81-90</div>
                                        <div class="text-xs">Bueno</div>
                                    </button>
                                    <button type="button" class="p-2 text-center rounded text-xs font-semibold transition cursor-pointer hover:scale-105"
                                            style="background-color: #E7F1FF; color: #1B365D;">
                                        <div>91-100</div>
                                        <div class="text-xs">Excelente</div>
                                    </button>
                                </div>
                            </div>

                            <!-- URL del Certificado -->
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">
                                    📄 URL del Certificado (Opcional)
                                </label>
                                <input type="url" 
                                       name="certificado_url" 
                                       class="w-full border rounded px-4 py-2"
                                       placeholder="https://ejemplo.com/certificado.pdf"
                                       style="border-color: #1B365D;">
                                <p class="text-xs text-gray-600 mt-2">
                                    Proporciona el enlace al certificado de culminación del curso (si aplica)
                                </p>
                            </div>

                            <!-- Observaciones -->
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">
                                    📝 Observaciones (Opcional)
                                </label>
                                <textarea class="w-full border rounded px-4 py-2" 
                                          rows="4"
                                          placeholder="Notas adicionales sobre el desempeño o completación del curso..."
                                          style="border-color: #1B365D;"></textarea>
                            </div>

                            <!-- Información Adicional -->
                            <div class="p-4 bg-blue-50 rounded border-l-4" style="border-color: #1B365D;">
                                <h4 class="font-bold mb-2 text-sm" style="color: #1B365D;">ℹ️ Información Importante</h4>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li>✓ La calificación debe estar entre 0 y 100</li>
                                    <li>✓ El certificado es opcional pero recomendado</li>
                                    <li>✓ Puedes agregar observaciones adicionales</li>
                                    <li>✓ Una vez validado, no se puede cambiar</li>
                                </ul>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="flex gap-4 justify-end border-t pt-4">
                                <a href="{{ route('asignaciones.show', $asignacion) }}" class="btn-outline-primary">
                                    ❌ Cancelar
                                </a>
                                <button type="submit" class="btn-primary">
                                    ✅ Marcar como Completado
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Advertencia -->
                    <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded">
                        <p class="text-sm text-yellow-700">
                            <strong>⚠️ Importante:</strong> Una vez marques this asignación como completada, 
                            no se podrá revertir. Asegúrate de que toda la información seja correcta antes de continuar.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
@endsection
