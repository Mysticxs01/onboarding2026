<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl">👕 Asignación de Dotación (EPP)</h2>
                <p class="text-sm text-gray-600 mt-1">
                    Uniformes y elementos de protección personal
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
                    <p class="text-gray-600 font-semibold text-sm">Área</p>
                    <p class="text-lg">{{ $solicitud->proceso->area->nombre }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kit Recomendado (Izquierda) --}}
            <div class="lg:col-span-2">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-6">
                    <h3 class="font-bold text-blue-900 mb-2">💡 Kit Estándar Recomendado</h3>
                    <button onclick="cargarKitRecomendado()" class="text-sm text-blue-700 hover:text-blue-900 font-semibold">
                        ↻ Cargar kit automático
                    </button>
                </div>

                {{-- Formulario EPP --}}
                <form method="POST" action="{{ route('dotacion.guardar', $solicitud->id) }}" id="form-epp">
                    @csrf
                    <div class="space-y-6">

                        {{-- Elemento de Protección --}}
                        <div class="bg-white p-6 rounded shadow">
                            <h3 class="font-bold text-lg mb-4">Elementos de Protección Personal</h3>

                            <div id="elementos-proteccion" class="space-y-4">
                                @forelse($elementosProteccion ?? [] as $elemento)
                                    <div class="border-l-4 border-orange-500 p-4 bg-orange-50 rounded">
                                        <input type="hidden" name="elementos[]" value="{{ $elemento->id }}">
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    {{ $elemento->tipo }}
                                                </label>
                                                <input type="number" 
                                                       name="elemento_cantidad[{{ $elemento->id }}]" 
                                                       value="{{ $elemento->cantidad }}"
                                                       min="1"
                                                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                                                       placeholder="Cantidad">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Talla</label>
                                                <select name="elemento_talla[{{ $elemento->id }}]" 
                                                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-500">
                                                    <option value="">Seleccionar...</option>
                                                    @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Talla Única'] as $talla)
                                                        <option value="{{ $talla }}" {{ $elemento->talla === $talla ? 'selected' : '' }}>
                                                            {{ $talla }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Color</label>
                                                <select name="elemento_color[{{ $elemento->id }}]"
                                                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-500">
                                                    <option value="">Seleccionar...</option>
                                                    @foreach(['Negro', 'Blanco', 'Azul', 'Verde', 'Rojo', 'Amarillo', 'Naranja', 'Gris'] as $color)
                                                        <option value="{{ $color }}" {{ $elemento->color === $color ? 'selected' : '' }}>
                                                            {{ $color }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="flex items-end">
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" 
                                                           name="elemento_entregado[{{ $elemento->id }}]"
                                                           {{ $elemento->entregado ? 'checked' : '' }}
                                                           class="rounded">
                                                    <span class="text-sm font-semibold">Entregado</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 italic">No hay elementos agregados. Carga el kit recomendado.</p>
                                @endforelse
                            </div>

                            <button type="button" onclick="agregarElemento()" class="mt-4 px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 font-semibold text-sm">
                                + Agregar Elemento
                            </button>
                        </div>

                        {{-- Uniformes --}}
                        <div class="bg-white p-6 rounded shadow">
                            <h3 class="font-bold text-lg mb-4">Entrega de Uniformes</h3>

                            <div class="space-y-3">
                                @php
                                    $uniformesEstándar = ['Uniforme Diario', 'Uniforme Especial', 'Uniforme Gala'];
                                @endphp
                                @foreach($uniformesEstándar as $uniforme)
                                    <label class="flex items-center gap-3 p-3 border rounded cursor-pointer hover:bg-gray-50">
                                        <input type="checkbox" 
                                               name="uniformes[]" 
                                               value="{{ $uniforme }}"
                                               class="rounded">
                                        <span class="font-semibold text-gray-700">{{ $uniforme }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    {{-- Botones --}}
                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-3 rounded hover:bg-blue-700 font-semibold">
                            ✓ Guardar Asignación
                        </button>
                        <a href="{{ route('solicitudes.show', $solicitud->id) }}" class="flex-1 bg-gray-300 text-gray-800 px-4 py-3 rounded hover:bg-gray-400 font-semibold text-center">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>

            {{-- Resumen (Derecha) --}}
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded shadow sticky top-20">
                    <h3 class="font-bold text-lg mb-4">📋 Resumen</h3>

                    <div class="space-y-3 text-sm">
                        <div class="p-3 bg-orange-50 rounded">
                            <p class="text-gray-600">EPP Agregados</p>
                            <p class="text-2xl font-bold text-orange-600" id="count-epp">0</p>
                        </div>

                        <div class="p-3 bg-purple-50 rounded">
                            <p class="text-gray-600">Uniformes checkeados</p>
                            <p class="text-2xl font-bold text-purple-600" id="count-uniformes">0</p>
                        </div>

                        <hr class="my-4">

                        <div>
                            <p class="text-gray-600 font-semibold mb-2">Tipos de EPP</p>
                            <ul class="text-xs space-y-1">
                                <li>✓ Casco / Gafas</li>
                                <li>✓ Chaleco</li>
                                <li>✓ Guantes</li>
                                <li>✓ Zapatos</li>
                                <li>✓ Tapabocas</li>
                                <li>✓ Cinturón</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script>
        function agregarElemento() {
            const elementosDiv = document.getElementById('elementos-proteccion');
            // Add new element template here
            alert('Funcionalidad de agregar elemento - implementar');
        }

        function cargarKitRecomendado() {
            alert('Implementar carga automática de kit para cargo: {{ $solicitud->proceso->cargo->nombre }}');
        }

        // Update counts
        document.addEventListener('DOMContentLoaded', function() {
            updateCounts();
        });

        function updateCounts() {
            const epp = document.querySelectorAll('[name^="elemento_"]').length;
            const uniformes = document.querySelectorAll('[name="uniformes[]"]:checked').length;
            document.getElementById('count-epp').textContent = epp;
            document.getElementById('count-uniformes').textContent = uniformes;
        }

        document.addEventListener('change', updateCounts);
    </script>
</x-app-layout>
