<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl">🛋️ Asignación de Inmobiliario</h2>
                <p class="text-sm text-gray-600 mt-1">
                    Muebles y artículos de oficina
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
                    <p class="text-gray-600 font-semibold text-sm">Departamento</p>
                    <p class="text-lg">{{ $solicitud->proceso->area->nombre }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Formulario (Izquierda) --}}
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('bienes.guardar', $solicitud->id) }}" class="space-y-6">
                    @csrf

                    {{-- Kit Recomendado --}}
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <h3 class="font-bold text-blue-900 mb-2">💡 Kit Estándar</h3>
                        <button type="button" onclick="cargarKitEstandar()" class="text-sm text-blue-700 hover:text-blue-900 font-semibold">
                            ↻ Cargar kit automático para {{ $solicitud->proceso->cargo->nombre }}
                        </button>
                    </div>

                    {{-- Container de Items --}}
                    <div class="space-y-4">
                        <h3 class="font-bold text-lg">Items de Inmobiliario</h3>
                        
                        <div id="items-container" class="space-y-3">
                            @php
                                $itemsDefault = [
                                    ['nombre' => 'Silla Ergonómica', 'cantidad' => 1],
                                    ['nombre' => 'Escritorio', 'cantidad' => 1],
                                    ['nombre' => 'Estantería', 'cantidad' => 1],
                                    ['nombre' => 'Lámpara de Escritorio', 'cantidad' => 1],
                                ];
                            @endphp

                            @foreach($itemsDefault as $index => $item)
                                <div class="item-row bg-white p-4 rounded border-l-4 border-blue-500 flex items-end justify-between gap-3">
                                    <div class="flex-1">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Artículo</label>
                                        <input type="text" 
                                               name="items[{{ $index }}][nombre]"
                                               value="{{ $item['nombre'] }}"
                                               class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="Nombre del artículo" required>
                                    </div>

                                    <div class="w-24">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cantidad</label>
                                        <input type="number" 
                                               name="items[{{ $index }}][cantidad]"
                                               value="{{ $item['cantidad'] }}"
                                               min="1"
                                               class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               required>
                                    </div>

                                    <div class="w-28">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Estado</label>
                                        <select name="items[{{ $index }}][estado]"
                                                class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="En Almacén">En Almacén</option>
                                            <option value="En Tránsito">En Tránsito</option>
                                            <option value="Entregado">Entregado</option>
                                        </select>
                                    </div>

                                    <button type="button" onclick="this.parentElement.remove()" 
                                            class="text-red-600 hover:text-red-800 font-bold text-lg pb-2">
                                        ✕
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" onclick="agregarItem()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold text-sm">
                            + Agregar Item
                        </button>
                    </div>

                    {{-- Papelería --}}
                    <div class="bg-white p-4 rounded border-l-4 border-green-500">
                        <h3 class="font-bold text-lg mb-4">Artículos de Papelería</h3>
                        <div class="space-y-2 text-sm">
                            @php
                                $articulos = ['Libreta', 'Bolígrafos', 'Lápices', 'Marcadores', 'Post-its', 'Papel A4', 'Carpetas'];
                            @endphp
                            @foreach($articulos as $articulo)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="papeleria[]" value="{{ $articulo }}" class="rounded">
                                    <span>{{ $articulo }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Observaciones --}}
                    <div class="bg-white p-4 rounded border-l-4 border-orange-500">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Observaciones</label>
                        <textarea name="observaciones"
                                  rows="3"
                                  class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                                  placeholder="Notas especiales o requerimientos adicionales"></textarea>
                    </div>

                    {{-- Botones --}}
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-3 rounded hover:bg-blue-700 font-semibold">
                            ✓ Guardar Asignación
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
                    <h3 class="font-bold text-lg">📦 Resumen</h3>

                    {{-- Contador --}}
                    <div class="p-3 bg-blue-50 rounded">
                        <p class="text-gray-600 text-sm">Items agregados</p>
                        <p class="text-2xl font-bold text-blue-600" id="item-count">4</p>
                    </div>

                    <div class="p-3 bg-green-50 rounded">
                        <p class="text-gray-600 text-sm">Papelería checkeada</p>
                        <p class="text-2xl font-bold text-green-600" id="paper-count">0</p>
                    </div>

                    <hr>

                    {{-- Estados --}}
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Estados de Items</p>
                        <ul class="text-xs space-y-1 text-gray-600">
                            <li>⏰ Pendiente</li>
                            <li>📦 En Almacén</li>
                            <li>🚚 En Tránsito</li>
                            <li>✅ Entregado</li>
                        </ul>
                    </div>

                    <hr>

                    {{-- Tipos Disponibles --}}
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Tipos de Inmobiliario</p>
                        <ul class="text-xs space-y-1 text-gray-600">
                            <li>• Silla</li>
                            <li>• Escritorio</li>
                            <li>• Estantería</li>
                            <li>• Lámpara</li>
                            <li>• Papelería</li>
                            <li>• Otro</li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <script>
        let itemIndex = 4;

        function agregarItem() {
            const container = document.getElementById('items-container');
            const newItem = document.createElement('div');
            newItem.className = 'item-row bg-white p-4 rounded border-l-4 border-blue-500 flex items-end justify-between gap-3';
            newItem.innerHTML = `
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Artículo</label>
                    <input type="text" 
                           name="items[${itemIndex}][nombre]"
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nombre del artículo" required>
                </div>

                <div class="w-24">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cantidad</label>
                    <input type="number" 
                           name="items[${itemIndex}][cantidad]"
                           value="1"
                           min="1"
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div class="w-28">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Estado</label>
                    <select name="items[${itemIndex}][estado]"
                            class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Pendiente">Pendiente</option>
                        <option value="En Almacén">En Almacén</option>
                        <option value="En Tránsito">En Tránsito</option>
                        <option value="Entregado">Entregado</option>
                    </select>
                </div>

                <button type="button" onclick="this.parentElement.remove(); updateItemCount();" 
                        class="text-red-600 hover:text-red-800 font-bold text-lg pb-2">
                    ✕
                </button>
            `;
            container.appendChild(newItem);
            itemIndex++;
            updateItemCount();
        }

        function cargarKitEstandar() {
            alert('Cargar kit estándar para {{ $solicitud->proceso->cargo->nombre }}');
        }

        function updateItemCount() {
            const items = document.querySelectorAll('.item-row').length;
            document.getElementById('item-count').textContent = items;
        }

        function updatePapelCount() {
            const papers = document.querySelectorAll('[name="papeleria[]"]:checked').length;
            document.getElementById('paper-count').textContent = papers;
        }

        document.addEventListener('change', updatePapelCount);
    </script>
</x-app-layout>
