<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight" style="color: #1B365D;">
                🏢 Solicitud de Servicios Generales #{{ $solicitude->id }}
            </h2>
            <a href="{{ route('solicitudes.index') }}" class="btn-outline-primary">← Volver</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                    <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
            @endif

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

                <!-- Panel Izquierdo -->
                <div class="lg:col-span-1 space-y-4">
                    @if ($solicitude->proceso)
                        <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #1B365D;">
                            <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">👤 Empleado</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-600 uppercase">Nombre</p>
                                    <p class="font-semibold text-sm" style="color: #1B365D;">{{ $solicitude->proceso->nombre_completo }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 uppercase">Cargo</p>
                                    <p class="font-semibold text-sm" style="color: #1B365D;">{{ $solicitude->proceso->cargo->nombre }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 uppercase">Área</p>
                                    <p class="font-semibold text-sm" style="color: #28A745;">{{ $solicitude->proceso->area->nombre }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #C59D42;">
                        <h3 class="text-lg font-bold mb-4" style="color: #C59D42;">📊 Estado</h3>
                        <p class="text-2xl font-bold text-center" style="color: #C59D42;">{{ $solicitude->estado }}</p>
                        <p class="text-sm text-gray-600 text-center mt-3">{{ $solicitude->fecha_limite?->format('d/m/Y') ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Panel Central y Derecho -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #1B365D;">
                        <h3 class="text-xl font-bold mb-6" style="color: #1B365D;">🏢 Asignación de Puesto Físico</h3>

                        @if($solicitude->puestoTrabajo)
                            <!-- Mostrar Puesto Asignado -->
                            <div class="p-4 bg-green-50 rounded border-l-4 border-green-500 mb-6">
                                <h4 class="font-bold text-green-900 mb-3">✅ Puesto Asignado</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-600 uppercase text-xs mb-1">Número de Puesto</p>
                                        <p class="font-semibold" style="color: #1B365D;">{{ $solicitude->puestoTrabajo->numero_puesto }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 uppercase text-xs mb-1">Sección</p>
                                        <p class="font-semibold" style="color: #1B365D;">{{ $solicitude->puestoTrabajo->seccion ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 uppercase text-xs mb-1">Piso</p>
                                        <p class="font-semibold" style="color: #1B365D;">{{ $solicitude->puestoTrabajo->piso ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 uppercase text-xs mb-1">Estado del Puesto</p>
                                        <span class="inline-block px-3 py-1 rounded text-xs font-semibold"
                                              style="background-color: #E8F5E9; color: #28A745;">
                                            ✅ {{ $solicitude->puestoTrabajo->estado }}
                                        </span>
                                    </div>
                                </div>

                                @if(Auth::user()->hasRole('Root') || Auth::user()->getRoleNames()->contains(fn($role) => strpos($role, 'Servicios') !== false))
                                    <div class="mt-4">
                                        <button onclick="document.getElementById('formulario-puesto').style.display = 'block'" class="btn-secondary">
                                            ✏️ Cambiar Puesto
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-600 mb-6">No se ha asignado puesto físico. Selecciona uno a continuación:</p>
                        @endif

                        <!-- Formulario -->
                        <div id="formulario-puesto" style="display: {{ $solicitude->puestoTrabajo ? 'none' : 'block' }};">
                            <form action="{{ route('solicitudes.guardar-servicios-generales', $solicitude->id) }}" method="POST" class="space-y-6">
                                @csrf

                                <div>
                                    <label class="block text-sm font-bold mb-3" style="color: #1B365D;">
                                        🏢 Selecciona Puesto de Trabajo Disponible
                                    </label>

                                    @php
                                        $puestosDisponibles = \App\Models\PuestoTrabajo::where('estado', 'Disponible')
                                            ->orderBy('piso', 'ASC')
                                            ->orderBy('numero_puesto', 'ASC')
                                            ->get();
                                    @endphp

                                    @if($puestosDisponibles->count() > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($puestosDisponibles as $puesto)
                                                <label class="block border rounded-lg p-4 cursor-pointer hover:border-blue-500 transition
                                                    {{ $solicitude->puestoTrabajo?->id === $puesto->id ? 'border-blue-600 bg-blue-50' : 'border-gray-200 bg-white' }}">
                                                    <input type="radio" name="puesto_trabajo_id" value="{{ $puesto->id }}" class="sr-only"
                                                           {{ $solicitude->puestoTrabajo?->id === $puesto->id ? 'checked' : '' }}
                                                           {{ $loop->first ? 'required' : '' }}>
                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <p class="text-xs text-gray-600 uppercase">Puesto</p>
                                                            <p class="text-lg font-bold" style="color: #1B365D;">{{ $puesto->numero_puesto }}</p>
                                                            <p class="text-xs text-gray-600 mt-1">Sección: {{ $puesto->seccion ?? 'General' }}</p>
                                                            <p class="text-xs text-gray-600">Piso: {{ $puesto->piso }}</p>
                                                        </div>
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded"
                                                              style="background-color: #E8F5E9; color: #28A745;">
                                                            Disponible
                                                        </span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="p-4 bg-red-50 border border-red-200 rounded">
                                            <p class="text-red-700">
                                                ⚠️ No hay puestos disponibles en este momento. 
                                                Por favor contacta a recursos humanos.
                                            </p>
                                        </div>
                                    @endif

                                    <p class="text-xs text-gray-600 mt-3">
                                        ℹ️ Solo puedes seleccionar de los puestos que están disponibles.
                                        La asignación final se realiza aquí sin necesidad de un módulo separado.
                                    </p>
                                </div>

                                @if($puestosDisponibles->count() > 0)
                                    <!-- Botones -->
                                    <div class="flex gap-4 pt-4 border-t">
                                        <button type="submit" class="btn-primary">
                                            ✅ Asignar Puesto
                                        </button>
                                        @if($solicitude->puestoTrabajo)
                                            <button type="button" onclick="document.getElementById('formulario-puesto').style.display = 'none'" class="btn-outline-primary">
                                                ❌ Cancelar
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>

                    <!-- Cambiar Estado -->
                    @if(Auth::user()->getRoleNames()->contains(fn($role) => strpos($role, 'Servicios') !== false || strpos($role, 'Admin') !== false) || Auth::user()->hasRole('Root'))
                        <div class="bg-white rounded-lg shadow p-6 mt-6">
                            <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">🔧 Cambiar Estado</h3>
                            
                            <form action="{{ route('solicitudes.cambiar-estado', $solicitude->id) }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">Nuevo Estado</label>
                                    <select name="estado" class="w-full border rounded px-4 py-2" style="border-color: #1B365D;">
                                        <option value="Pendiente" {{ $solicitude->estado === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="En Proceso" {{ $solicitude->estado === 'En Proceso' ? 'selected' : '' }}>En Proceso</option>
                                        <option value="Finalizada" {{ $solicitude->estado === 'Finalizada' ? 'selected' : '' }}>Finalizada</option>
                                    </select>
                                </div>

                                <button type="submit" class="w-full btn-secondary">
                                    🔄 Actualizar Estado
                                </button>
                            </form>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
