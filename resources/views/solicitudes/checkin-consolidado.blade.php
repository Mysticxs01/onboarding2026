<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight" style="color: #1B365D;">
                ✅ Check-in Consolidado de Onboarding
            </h2>
            <a href="{{ route('procesos-ingreso.show', $proceso->id) }}" class="btn-outline-primary">← Volver</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                    <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <!-- ENCABEZADO -->
            <div class="bg-white rounded-lg shadow p-8 mb-6" style="border-top: 4px solid #1B365D;">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <p class="text-xs text-gray-600 uppercase font-semibold">Código de Proceso</p>
                        <p class="text-2xl font-bold" style="color: #1B365D;">{{ $proceso->codigo }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 uppercase font-semibold">Empleado</p>
                        <p class="text-xl font-bold" style="color: #1B365D;">{{ $proceso->nombre_completo }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 uppercase font-semibold">Fecha de Ingreso</p>
                        <p class="text-xl font-bold" style="color: #1B365D;">{{ $proceso->fecha_ingreso->format('d/m/Y') }}</p>
                    </div>
                </div>

                <hr class="my-6">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-600 uppercase">Cargo</p>
                        <p class="font-semibold">{{ $proceso->cargo->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 uppercase">Área</p>
                        <p class="font-semibold">{{ $proceso->area->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 uppercase">Jefe Inmediato</p>
                        <p class="font-semibold">{{ $proceso->jefe->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 uppercase">Estado General</p>
                        <span class="inline-block px-3 py-1 rounded text-sm font-semibold" style="background-color: #E8F5E9; color: #28A745;">
                            ✅ Onboarding Completado
                        </span>
                    </div>
                </div>
            </div>

            <!-- INFORMACIÓN CONSOLIDADA POR TIPO -->
            <div class="space-y-6">

                <!-- TECNOLOGÍA -->
                @if($tecnologia)
                <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #0066CC;">
                    <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">💻 Solicitud de Tecnología</h3>
                    @php
                        $tecnologiaDetalle = $tecnologia->detalleTecnologia;
                    @endphp
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-blue-50 p-4 rounded">
                        <div>
                            <p class="text-xs text-gray-600 uppercase font-semibold">¿Necesita Computador?</p>
                            <p class="text-lg font-bold">
                                @if($tecnologiaDetalle?->necesita_computador)
                                    <span style="color: #28A745;">✅ SÍ</span>
                                @else
                                    <span style="color: #C59D42;">❌ NO</span>
                                @endif
                            </p>
                        </div>
                        @if($tecnologiaDetalle?->necesita_computador)
                            <div>
                                <p class="text-xs text-gray-600 uppercase font-semibold">Gama del Computador</p>
                                <p class="text-lg font-bold" style="color: #1B365D;">{{ $tecnologiaDetalle->gama_computador }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-gray-600 uppercase font-semibold">Estado</p>
                            <span class="inline-block px-3 py-1 rounded text-sm font-semibold" style="background-color: #E8F5E9; color: #28A745;">
                                ✅ {{ $tecnologia->estado }}
                            </span>
                        </div>
                    </div>
                    
                    @if($tecnologiaDetalle?->credenciales_plataformas)
                        <div class="mt-4">
                            <p class="text-xs text-gray-600 uppercase font-semibold mb-2">Credenciales y Plataformas</p>
                            <div class="bg-gray-50 p-4 rounded border border-gray-200 text-sm whitespace-pre-wrap">
                                {{ $tecnologiaDetalle->credenciales_plataformas }}
                            </div>
                        </div>
                    @endif
                </div>
                @endif

                <!-- DOTACIÓN -->
                @if($dotacion)
                <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #F76707;">
                    <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">👔 Solicitud de Dotación</h3>
                    @php
                        $dotacionDetalle = $dotacion->detalleUniforme;
                    @endphp
                    
                    @if($dotacionDetalle?->necesita_dotacion)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-orange-50 p-4 rounded">
                            <div>
                                <p class="text-xs text-gray-600 uppercase font-semibold">¿Necesita Dotación?</p>
                                <p class="text-lg font-bold" style="color: #28A745;">✅ SÍ</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase font-semibold">Género</p>
                                <p class="text-lg font-bold" style="color: #1B365D;">{{ $dotacionDetalle->genero }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase font-semibold">Talla Pantalón</p>
                                <p class="text-lg font-bold" style="color: #1B365D;">{{ $dotacionDetalle->talla_pantalon }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase font-semibold">Talla Camiseta</p>
                                <p class="text-lg font-bold" style="color: #1B365D;">{{ $dotacionDetalle->talla_camiseta }}</p>
                            </div>
                        </div>
                    @else
                        <div class="bg-orange-50 p-4 rounded">
                            <p class="text-lg font-bold mb-2">❌ No Requiere Dotación</p>
                            @if($dotacionDetalle?->justificacion_no_dotacion)
                                <p class="text-sm text-gray-700 mt-2">
                                    <strong>Justificación:</strong><br>
                                    {{ $dotacionDetalle->justificacion_no_dotacion }}
                                </p>
                            @endif
                        </div>
                    @endif

                    <div class="mt-4">
                        <span class="inline-block px-3 py-1 rounded text-sm font-semibold" style="background-color: #E8F5E9; color: #28A745;">
                            ✅ {{ $dotacion->estado }}
                        </span>
                    </div>
                </div>
                @endif

                <!-- SERVICIOS GENERALES -->
                @if($serviciosGenerales)
                <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #28A745;">
                    <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">🏢 Solicitud de Servicios Generales</h3>
                    
                    @if($serviciosGenerales->puestoTrabajo)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-green-50 p-4 rounded">
                            <div>
                                <p class="text-xs text-gray-600 uppercase font-semibold">Número de Puesto</p>
                                <p class="text-lg font-bold" style="color: #1B365D;">{{ $serviciosGenerales->puestoTrabajo->numero_puesto }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase font-semibold">Sección</p>
                                <p class="text-lg font-bold" style="color: #1B365D;">{{ $serviciosGenerales->puestoTrabajo->seccion ?? 'General' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase font-semibold">Piso</p>
                                <p class="text-lg font-bold" style="color: #1B365D;">{{ $serviciosGenerales->puestoTrabajo->piso }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase font-semibold">Estado del Puesto</p>
                                <span class="inline-block px-3 py-1 rounded text-sm font-semibold" style="background-color: #E8F5E9; color: #28A745;">
                                    ✅ {{ $serviciosGenerales->puestoTrabajo->estado }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 p-4 rounded border border-red-200">
                            <p class="text-red-700">⚠️ No se ha asignado puesto de trabajo</p>
                        </div>
                    @endif

                    <div class="mt-4">
                        <span class="inline-block px-3 py-1 rounded text-sm font-semibold" style="background-color: #E8F5E9; color: #28A745;">
                            ✅ {{ $serviciosGenerales->estado ?? 'Finalizada' }}
                        </span>
                    </div>
                </div>
                @endif

                <!-- FORMACIÓN -->
                @if($formacion && $formacion->cursos->count() > 0)
                <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #9C27B0;">
                    <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">📚 Plan de Formación</h3>
                    
                    <div class="bg-purple-50 p-4 rounded">
                        <p class="font-semibold mb-4">Cursos Asignados ({{ $formacion->cursos->count() }} total):</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($formacion->cursos as $curso)
                                <div class="bg-white p-3 rounded border border-purple-200">
                                    <p class="font-semibold text-sm" style="color: #1B365D;">{{ $curso->nombre }}</p>
                                    <div class="text-xs text-gray-600 mt-2 space-y-1">
                                        <p>📖 <strong>Categoría:</strong> {{ $curso->categoria }}</p>
                                        <p>⏱️ <strong>Horas:</strong> {{ $curso->duracion_horas }}</p>
                                        <p>🎓 <strong>Modalidad:</strong> {{ $curso->modalidad }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4">
                        <span class="inline-block px-3 py-1 rounded text-sm font-semibold" style="background-color: #E8F5E9; color: #28A745;">
                            ✅ {{ $formacion->estado }}
                        </span>
                    </div>
                </div>
                @endif

                <!-- BIENES -->
                @if($bienes)
                <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #FF9800;">
                    <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">🛋️ Solicitud de Bienes y Servicios</h3>
                    @php
                        $bienesDetalle = $bienes->detalleBienes;
                        $bienesLista = $bienesDetalle?->bienes_requeridos;
                        if (is_string($bienesLista)) {
                            $bienesLista = json_decode($bienesLista, true);
                        }
                        if (!is_array($bienesLista)) {
                            $bienesLista = [];
                        }
                    @endphp
                    
                    @if(count($bienesLista) > 0)
                        <div class="bg-amber-50 p-4 rounded">
                            <p class="font-semibold mb-4">Bienes Requeridos:</p>
                            
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @php
                                    $iconos = [
                                        'silla' => '🪑',
                                        'escritorio' => '🖥️',
                                        'papelera' => '🗑️',
                                        'organizador' => '📦',
                                        'cuadernos' => '📓',
                                        'lapiceros' => '✏️',
                                        'post_it' => '📌',
                                        'archivador' => '📂',
                                        'mouse_pad' => '🖱️',
                                        'cable_cargador' => '🔌',
                                    ];
                                @endphp
                                
                                @foreach($bienesLista as $bien)
                                    <div class="bg-white p-3 rounded border border-amber-200 text-center">
                                        <p class="text-2xl">{{ $iconos[$bien] ?? '📦' }}</p>
                                        <p class="text-sm font-semibold capitalize">{{ str_replace('_', ' ', $bien) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-amber-50 p-4 rounded">
                            <p class="text-gray-700">No se requieren bienes adicionales</p>
                        </div>
                    @endif

                    @if($bienesDetalle?->observaciones)
                        <div class="mt-4">
                            <p class="text-xs text-gray-600 uppercase font-semibold mb-2">Observaciones</p>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200 text-sm">
                                {{ $bienesDetalle->observaciones }}
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <span class="inline-block px-3 py-1 rounded text-sm font-semibold" style="background-color: #E8F5E9; color: #28A745;">
                            ✅ {{ $bienes->estado ?? 'Finalizada' }}
                        </span>
                    </div>
                </div>
                @endif

            </div>

            <!-- RESUMEN FINAL -->
            <div class="mt-8 bg-white rounded-lg shadow p-6" style="border-left: 4px solid #1B365D;">
                <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">📊 Resumen del Onboarding</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded">
                        <p class="text-3xl font-bold" style="color: #0066CC;">💻</p>
                        <p class="text-sm font-semibold mt-2">Tecnología</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $tecnologia ? '✅ Completada' : '❌ Pendiente' }}</p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded">
                        <p class="text-3xl font-bold" style="color: #F76707;">👔</p>
                        <p class="text-sm font-semibold mt-2">Dotación</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $dotacion ? '✅ Completada' : '❌ Pendiente' }}</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded">
                        <p class="text-3xl font-bold" style="color: #28A745;">🏢</p>
                        <p class="text-sm font-semibold mt-2">Servicios Generales</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $serviciosGenerales ? '✅ Completada' : '❌ Pendiente' }}</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded">
                        <p class="text-3xl font-bold" style="color: #9C27B0;">📚</p>
                        <p class="text-sm font-semibold mt-2">Formación</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $formacion ? '✅ Completada' : '❌ Pendiente' }}</p>
                    </div>
                    <div class="text-center p-4 bg-amber-50 rounded">
                        <p class="text-3xl font-bold" style="color: #FF9800;">🛋️</p>
                        <p class="text-sm font-semibold mt-2">Bienes</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $bienes ? '✅ Completada' : '❌ Pendiente' }}</p>
                    </div>
                </div>
            </div>

            <!-- ACCIONES -->
            <div class="mt-8 flex gap-4 justify-center">
                <a href="{{ route('procesos-ingreso.show', $proceso->id) }}" class="btn-primary">
                    👁️ Ver Proceso Completo
                </a>
                <button onclick="window.print()" class="btn-secondary">
                    🖨️ Imprimir Check-in
                </button>
            </div>

        </div>
    </div>
</x-app-layout>
