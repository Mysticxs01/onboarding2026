<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl">📚 Plan de Capacitación</h2>
                <p class="text-sm text-gray-600 mt-1">
                    Diseña e implementa planes de formación para los empleados
                </p>
            </div>
            <a href="{{ route('solicitudes.index') }}" class="text-blue-600 hover:text-blue-800 whitespace-nowrap">← Volver a Solicitudes</a>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6">

        {{-- Mensajes --}}
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        {{-- Información del Empleado --}}
        <div class="bg-white p-6 rounded shadow mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-600 font-semibold text-sm">Empleado</p>
                    <p class="text-lg">{{ $solicitud->proceso->nombre_completo }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold text-sm">Cargo</p>
                    <p class="text-lg">{{ $solicitud->proceso->cargo->nombre }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold text-sm">Email</p>
                    <p class="text-lg">{{ $solicitud->proceso->email }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Formulario (Izquierda) --}}
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('formacion.guardar', $solicitud->id) }}" class="space-y-6">
                    @csrf

                    {{-- Plan Estándar --}}
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <h3 class="font-bold text-blue-900 mb-2">💡 Plan Estándar</h3>
                        <button type="button" onclick="cargarPlanEstandar()" class="text-sm text-blue-700 hover:text-blue-900 font-semibold">
                            ↻ Cargar plan automático para {{ $solicitud->proceso->cargo->nombre }}
                        </button>
                    </div>

                    {{-- Container de Módulos --}}
                    <div class="space-y-4">
                        <h3 class="font-bold text-lg">Módulos de Capacitación</h3>
                        
                        <div id="modulos-container" class="space-y-3">
                            @php
                                $modulosDefault = [
                                    'Inducción Corporativa',
                                    'Seguridad y Salud en el Trabajo',
                                    'Políticas de la Empresa',
                                    'Sistema de Calidad',
                                    'Herramientas Digitales'
                                ];
                            @endphp

                            @foreach($modulosDefault as $index => $modulo)
                                <div class="modulo-item bg-white p-4 rounded border-l-4 border-purple-500">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1">
                                            <input type="text" 
                                                   name="modulos[{{ $index }}][nombre]"
                                                   value="{{ $modulo }}"
                                                   class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500 font-semibold"
                                                   placeholder="Nombre del módulo">
                                            <textarea name="modulos[{{ $index }}][descripcion]"
                                                      class="w-full px-3 py-2 border rounded mt-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                                                      rows="2"
                                                      placeholder="Descripción"></textarea>
                                            <div class="grid grid-cols-2 gap-3 mt-2">
                                                <input type="number" 
                                                       name="modulos[{{ $index }}][duracion_horas]"
                                                       value="8"
                                                       min="1"
                                                       class="px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                                                       placeholder="Duración (horas)">
                                                <select name="modulos[{{ $index }}][responsable]"
                                                        class="px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                                                    <option value="">Responsable</option>
                                                    <option value="RH">Recursos Humanos</option>
                                                    <option value="Seguridad">Seguridad</option>
                                                    <option value="Operaciones">Operaciones</option>
                                                    <option value="IT">IT</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" onclick="this.parentElement.parentElement.remove()" 
                                                class="text-red-600 hover:text-red-800 font-bold">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" onclick="agregarModulo()" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 font-semibold text-sm">
                            + Agregar Módulo
                        </button>
                    </div>

                    {{-- Duración Total --}}
                    <div class="bg-white p-4 rounded border-l-4 border-green-500">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Duración Total (horas)
                        </label>
                        <input type="number" 
                               name="duracion_horas"
                               value="40"
                               min="1"
                               class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg font-bold">
                    </div>

                    {{-- Responsable de Capacitación --}}
                    <div class="bg-white p-4 rounded border-l-4 border-orange-500">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Responsable de Capacitación
                        </label>
                        <input type="text" 
                               name="responsable_capacitacion"
                               class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                               placeholder="Nombre del responsable">
                    </div>

                    {{-- Enviar Notificación por Email --}}
                    <label class="flex items-center gap-3 p-4 bg-blue-50 border border-blue-200 rounded cursor-pointer hover:bg-blue-100">
                        <input type="checkbox" 
                               name="enviar_notificacion"
                               value="1"
                               checked
                               class="rounded">
                        <span class="font-semibold text-blue-900">📧 Enviar plan por email al empleado</span>
                    </label>

                    {{-- Botones --}}
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-3 rounded hover:bg-blue-700 font-semibold">
                            ✓ Guardar Plan de Capacitación
                        </button>
                        <a href="{{ route('solicitudes.show', $solicitud->id) }}" class="flex-1 bg-gray-300 text-gray-800 px-4 py-3 rounded hover:bg-gray-400 font-semibold text-center">
                            Cancelar
                        </a>
                    </div>

                </form>
            </div>

            {{-- Panel Derecho --}}
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded shadow sticky top-20 space-y-4">
                    <h3 class="font-bold text-lg">📊 Resumen</h3>

                    {{-- Estados --}}
                    <div class="space-y-2">
                        <div class="flex justify-between p-2 bg-pink-50 rounded">
                            <span class="text-sm font-semibold">Módulos:</span>
                            <span class="text-xl font-bold text-pink-600" id="count-modulos">5</span>
                        </div>
                        <div class="flex justify-between p-2 bg-green-50 rounded">
                            <span class="text-sm font-semibold">Horas Totales:</span>
                            <span class="text-xl font-bold text-green-600" id="total-horas">40</span>
                        </div>
                    </div>

                    <hr>

                    {{-- Estados del Plan --}}
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Estados Posibles</p>
                        <ul class="text-xs space-y-1 text-gray-600">
                            <li>🎨 Diseño</li>
                            <li>📅 Programado</li>
                            <li>▶️ En Ejecución</li>
                            <li>✅ Completado</li>
                            <li>❌ Cancelado</li>
                        </ul>
                    </div>

                    <hr>

                    {{-- Información --}}
                    <div class="bg-blue-50 p-3 rounded">
                        <p class="text-xs font-semibold text-blue-900 mb-1">💡 Consejo</p>
                        <p class="text-xs text-blue-700">
                            Carga el plan estándar para {{ $solicitud->proceso->cargo->nombre }} y personalízalo según necesites
                        </p>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <script>
        let moduloIndex = 5;

        function agregarModulo() {
            const container = document.getElementById('modulos-container');
            const newModulo = document.createElement('div');
            newModulo.className = 'modulo-item bg-white p-4 rounded border-l-4 border-purple-500';
            newModulo.innerHTML = `
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1">
                        <input type="text" 
                               name="modulos[${moduloIndex}][nombre]"
                               class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500 font-semibold"
                               placeholder="Nombre del módulo">
                        <textarea name="modulos[${moduloIndex}][descripcion]"
                                  class="w-full px-3 py-2 border rounded mt-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                                  rows="2"
                                  placeholder="Descripción"></textarea>
                        <div class="grid grid-cols-2 gap-3 mt-2">
                            <input type="number" 
                                   name="modulos[${moduloIndex}][duracion_horas]"
                                   value="8"
                                   min="1"
                                   class="px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="Duración (horas)">
                            <select name="modulos[${moduloIndex}][responsable]"
                                    class="px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">Responsable</option>
                                <option value="RH">Recursos Humanos</option>
                                <option value="Seguridad">Seguridad</option>
                                <option value="Operaciones">Operaciones</option>
                                <option value="IT">IT</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" 
                            class="text-red-600 hover:text-red-800 font-bold">
                        ✕
                    </button>
                </div>
            `;
            container.appendChild(newModulo);
            moduloIndex++;
            updateCounts();
        }

        function cargarPlanEstandar() {
            const cargo = "{{ $solicitud->proceso->cargo->nombre }}";
            // Implementar carga de plan estándar
            alert('Cargar plan estándar para ' + cargo);
        }

        function updateCounts() {
            const modulos = document.querySelectorAll('.modulo-item').length;
            document.getElementById('count-modulos').textContent = modulos;
        }
    </script>
</x-app-layout>
