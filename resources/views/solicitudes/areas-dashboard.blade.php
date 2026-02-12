<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl">📋 Solicitud de Incorporación</h2>
                <p class="text-sm text-gray-600 mt-1">
                    Gestión completa del proceso de onboarding
                </p>
            </div>
            <a href="{{ route('solicitudes.index') }}" class="text-blue-600 hover:text-blue-800 whitespace-nowrap">← Volver a Solicitudes</a>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6">

        {{-- Información del Empleado --}}
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded shadow mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <p class="text-blue-100 font-semibold text-sm">Empleado</p>
                    <p class="text-lg font-bold">{{ $solicitud->proceso->nombre_completo }}</p>
                </div>
                <div>
                    <p class="text-blue-100 font-semibold text-sm">Cargo</p>
                    <p class="text-lg font-bold">{{ $solicitud->proceso->cargo->nombre }}</p>
                </div>
                <div>
                    <p class="text-blue-100 font-semibold text-sm">Área</p>
                    <p class="text-lg font-bold">{{ $solicitud->proceso->area->nombre }}</p>
                </div>
                <div>
                    <p class="text-blue-100 font-semibold text-sm">Estado General</p>
                    <p class="text-lg font-bold">{{ $solicitud->estado }}</p>
                </div>
            </div>
        </div>

        {{-- Grid de Áreas Operacionales --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">

            {{-- SERVICIOS GENERALES --}}
            <div class="bg-white rounded shadow hover:shadow-lg transition-shadow overflow-hidden border-t-4 border-blue-500">
                <div class="bg-blue-50 p-4 border-b">
                    <h3 class="font-bold text-blue-900">🏢 Servicios Generales</h3>
                    <p class="text-xs text-blue-700 mt-1">Puesto de trabajo</p>
                </div>
                <div class="p-4 space-y-3">
                    @php
                        $asignacionSG = $solicitud->solicitudServiciosGenerales;
                    @endphp
                    
                    @if($asignacionSG?->puesto_trabajo_id)
                        <div class="text-sm">
                            <p class="font-semibold text-gray-700">Puesto Asignado</p>
                            <p class="text-blue-600 font-bold">{{ $asignacionSG->puestoTrabajo->numero_puesto }}</p>
                        </div>
                        @if($asignacionSG->carnet_generado)
                            <div class="text-sm p-2 bg-green-50 rounded">
                                <p class="font-semibold text-green-700">✓ Carnet Generado</p>
                                <p class="text-xs text-green-600">{{ $asignacionSG->numero_carnet }}</p>
                            </div>
                        @else
                            <a href="{{ route('servicios-generales.generar-carnet', $solicitud->id) }}" 
                               class="inline-block w-full text-center px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold text-sm">
                                Generar Carnet
                            </a>
                        @endif
                    @else
                        <a href="{{ route('servicios-generales.plano', $solicitud->id) }}" 
                           class="block w-full text-center px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold text-sm">
                            Asignar Puesto
                        </a>
                    @endif
                </div>
            </div>

            {{-- DOTACIÓN --}}
            <div class="bg-white rounded shadow hover:shadow-lg transition-shadow overflow-hidden border-t-4 border-orange-500">
                <div class="bg-orange-50 p-4 border-b">
                    <h3 class="font-bold text-orange-900">👕 Dotación</h3>
                    <p class="text-xs text-orange-700 mt-1">EPP y uniformes</p>
                </div>
                <div class="p-4 space-y-3">
                    @php
                        $epp = $solicitud->elementosProteccion()->count();
                    @endphp
                    
                    @if($epp > 0)
                        <div class="text-sm">
                            <p class="font-semibold text-gray-700">Items EPP</p>
                            <p class="text-2xl font-bold text-orange-600">{{ $epp }}</p>
                        </div>
                    @endif
                    
                    <a href="{{ route('dotacion.formulario', $solicitud->id) }}" 
                       class="block w-full text-center px-3 py-2 {{ $epp > 0 ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-orange-600 text-white hover:bg-orange-700' }} rounded font-semibold text-sm">
                        {{ $epp > 0 ? 'Editar' : 'Asignar' }} Dotación
                    </a>
                </div>
            </div>

            {{-- FORMACIÓN --}}
            <div class="bg-white rounded shadow hover:shadow-lg transition-shadow overflow-hidden border-t-4 border-purple-500">
                <div class="bg-purple-50 p-4 border-b">
                    <h3 class="font-bold text-purple-900">📚 Formación</h3>
                    <p class="text-xs text-purple-700 mt-1">Plan capacitación</p>
                </div>
                <div class="p-4 space-y-3">
                    @php
                        $plan = $solicitud->planCapacitacion;
                    @endphp
                    
                    @if($plan)
                        <div class="text-sm">
                            <p class="font-semibold text-gray-700">Módulos</p>
                            <p class="text-2xl font-bold text-purple-600">{{ count($plan->modulos ?? []) }}</p>
                        </div>
                        <div class="text-xs p-2 bg-purple-50 rounded">
                            <span class="font-semibold text-purple-700">Estado:</span>
                            <span class="text-purple-600">{{ $plan->estado }}</span>
                        </div>
                    @endif
                    
                    <a href="{{ route('formacion.formulario', $solicitud->id) }}" 
                       class="block w-full text-center px-3 py-2 {{ $plan ? 'bg-purple-100 text-purple-700 hover:bg-purple-200' : 'bg-purple-600 text-white hover:bg-purple-700' }} rounded font-semibold text-sm">
                        {{ $plan ? 'Editar' : 'Crear' }} Plan
                    </a>
                </div>
            </div>

            {{-- BIENES Y SERVICIOS --}}
            <div class="bg-white rounded shadow hover:shadow-lg transition-shadow overflow-hidden border-t-4 border-green-500">
                <div class="bg-green-50 p-4 border-b">
                    <h3 class="font-bold text-green-900">🛋️ Bienes</h3>
                    <p class="text-xs text-green-700 mt-1">Inmobiliario</p>
                </div>
                <div class="p-4 space-y-3">
                    @php
                        $items = $solicitud->itemsInmobiliario()->count();
                    @endphp
                    
                    @if($items > 0)
                        <div class="text-sm">
                            <p class="font-semibold text-gray-700">Items</p>
                            <p class="text-2xl font-bold text-green-600">{{ $items }}</p>
                        </div>
                    @endif
                    
                    <a href="{{ route('bienes.formulario', $solicitud->id) }}" 
                       class="block w-full text-center px-3 py-2 {{ $items > 0 ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-green-600 text-white hover:bg-green-700' }} rounded font-semibold text-sm">
                        {{ $items > 0 ? 'Editar' : 'Asignar' }} Items
                    </a>
                </div>
            </div>

            {{-- TECNOLOGÍA --}}
            <div class="bg-white rounded shadow hover:shadow-lg transition-shadow overflow-hidden border-t-4 border-cyan-500">
                <div class="bg-cyan-50 p-4 border-b">
                    <h3 class="font-bold text-cyan-900">💻 Tecnología</h3>
                    <p class="text-xs text-cyan-700 mt-1">IT & Accesos</p>
                </div>
                <div class="p-4 space-y-3">
                    @php
                        $detalleTI = $solicitud->detalleTecnologia;
                    @endphp
                    
                    @if($detalleTI)
                        <div class="text-sm">
                            <p class="font-semibold text-gray-700">Usuario AD</p>
                            <p class="text-cyan-600 font-mono text-xs">{{ $detalleTI->usuario_ad }}</p>
                        </div>
                        <div class="text-xs p-2 bg-cyan-50 rounded">
                            <span class="font-semibold text-cyan-700">Estado:</span>
                            <span class="text-cyan-600">{{ $detalleTI->estado }}</span>
                        </div>
                    @endif
                    
                    <a href="{{ route('tecnologia.formulario', $solicitud->id) }}" 
                       class="block w-full text-center px-3 py-2 {{ $detalleTI ? 'bg-cyan-100 text-cyan-700 hover:bg-cyan-200' : 'bg-cyan-600 text-white hover:bg-cyan-700' }} rounded font-semibold text-sm">
                        {{ $detalleTI ? 'Editar' : 'Configurar' }} TI
                    </a>
                </div>
            </div>
        </div>

        {{-- Checklist General --}}
        <div class="bg-white p-6 rounded shadow">
            <h3 class="font-bold text-lg mb-4">✓ Checklist de Incorporación</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <label class="flex items-center gap-3 p-3 border rounded cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" {{ $solicitud->solicitudServiciosGenerales ? 'checked' : '' }} disabled class="rounded">
                    <span class="font-semibold text-gray-700">Puesto de trabajo asignado</span>
                </label>

                <label class="flex items-center gap-3 p-3 border rounded cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" {{ $solicitud->elementosProteccion()->count() > 0 ? 'checked' : '' }} disabled class="rounded">
                    <span class="font-semibold text-gray-700">Dotación completada</span>
                </label>

                <label class="flex items-center gap-3 p-3 border rounded cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" {{ $solicitud->planCapacitacion ? 'checked' : '' }} disabled class="rounded">
                    <span class="font-semibold text-gray-700">Plan de formación</span>
                </label>

                <label class="flex items-center gap-3 p-3 border rounded cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" {{ $solicitud->itemsInmobiliario()->count() > 0 ? 'checked' : '' }} disabled class="rounded">
                    <span class="font-semibold text-gray-700">Inmobiliario asignado</span>
                </label>

                <label class="flex items-center gap-3 p-3 border rounded cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" {{ $solicitud->detalleTecnologia ? 'checked' : '' }} disabled class="rounded">
                    <span class="font-semibold text-gray-700">Credenciales TI creadas</span>
                </label>

                <label class="flex items-center gap-3 p-3 border rounded cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" {{ $solicitud->estado === 'Completado' ? 'checked' : '' }} disabled class="rounded">
                    <span class="font-semibold text-gray-700">Proceso finalizado</span>
                </label>
            </div>
        </div>

        {{-- Observaciones --}}
        @if($solicitud->observaciones)
            <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <h4 class="font-bold text-yellow-900 mb-2">📝 Observaciones</h4>
                <p class="text-yellow-800">{{ $solicitud->observaciones }}</p>
            </div>
        @endif

    </div>

</x-app-layout>
