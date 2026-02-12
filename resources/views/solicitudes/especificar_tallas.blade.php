<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">Especificar Tallas de Uniformes</h2>
            <a href="{{ route('solicitudes.show', $solicitud->id) }}" class="text-blue-600 hover:text-blue-800">Volver</a>
        </div>
    </x-slot>

    <div class="p-6 max-w-3xl">

        {{-- Errores --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-6 rounded shadow">
            
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
                <p class="mb-2"><strong>Empleado:</strong> {{ $solicitud->proceso->nombre_completo }}</p>
                <p class="mb-2"><strong>Cargo:</strong> {{ $solicitud->proceso->cargo->nombre }}</p>
                <p><strong>Fecha de Ingreso:</strong> {{ $solicitud->proceso->fecha_ingreso }}</p>
            </div>

            {{-- Panel de Recomendación Inteligente --}}
            @if ($kitEstandar || $estadisticas['total_solicitudes'] > 0)
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                    <h3 class="font-bold text-lg mb-3 text-green-700">💡 Tallas Recomendadas</h3>
                    
                    @if ($estadisticas['total_solicitudes'] > 0)
                        <p class="text-sm text-gray-600 mb-3">
                            Basado en {{ $estadisticas['total_solicitudes'] }} 
                            {{ $estadisticas['total_solicitudes'] == 1 ? 'solicitud anterior' : 'solicitudes anteriores' }}
                            del cargo <strong>{{ $solicitud->proceso->cargo->nombre }}</strong>:
                        </p>

                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                            @if ($estadisticas['talla_camisa_sugerida'])
                                <div>
                                    <p class="font-semibold text-gray-700">Talla Camisa:</p>
                                    <p class="text-green-700 text-lg font-bold">{{ $estadisticas['talla_camisa_sugerida'] }}</p>
                                    @if (count($estadisticas['distribucion_tallas_camisa']) > 1)
                                        <p class="text-gray-500 text-xs">
                                            También: {{ implode(', ', array_keys(array_slice($estadisticas['distribucion_tallas_camisa'], 1, 2))) }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                            
                            @if ($estadisticas['talla_pantalon_sugerida'])
                                <div>
                                    <p class="font-semibold text-gray-700">Talla Pantalón:</p>
                                    <p class="text-green-700 text-lg font-bold">{{ $estadisticas['talla_pantalon_sugerida'] }}</p>
                                    @if (count($estadisticas['distribucion_tallas_pantalon']) > 1)
                                        <p class="text-gray-500 text-xs">
                                            También: {{ implode(', ', array_keys(array_slice($estadisticas['distribucion_tallas_pantalon'], 1, 2))) }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            @if ($estadisticas['talla_zapatos_sugerida'])
                                <div>
                                    <p class="font-semibold text-gray-700">Talla Zapatos:</p>
                                    <p class="text-green-700 text-lg font-bold">{{ $estadisticas['talla_zapatos_sugerida'] }}</p>
                                    @if (count($estadisticas['distribucion_tallas_zapatos']) > 1)
                                        <p class="text-gray-500 text-xs">
                                            También: {{ implode(', ', array_keys(array_slice($estadisticas['distribucion_tallas_zapatos'], 1, 2))) }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            @if ($estadisticas['genero_predominante'])
                                <div>
                                    <p class="font-semibold text-gray-700">Género Predominante:</p>
                                    <p class="text-green-700">{{ $estadisticas['genero_predominante'] }}</p>
                                </div>
                            @endif
                        </div>

                        @if ($estadisticas['cantidad_promedio'])
                            <div class="mb-3 text-sm">
                                <p class="text-gray-600">
                                    <strong>Cantidad promedio de uniformes:</strong> {{ $estadisticas['cantidad_promedio'] }}
                                </p>
                            </div>
                        @endif
                    @endif

                    @if ($kitEstandar)
                        <button type="button" id="usarKitBtn" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm font-semibold">
                            ✓ Usar Tallas Recomendadas
                        </button>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('solicitudes.guardar-tallas', $solicitud->id) }}">
                @csrf

                <div class="mb-4">
                    <label class="block font-bold mb-2">Género *</label>
                    <select name="genero" id="genero" class="w-full p-2 border rounded" required>
                        <option value="">Seleccione género</option>
                        <option value="Masculino" {{ old('genero', $detalle->genero) === 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Femenino" {{ old('genero', $detalle->genero) === 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="Otro" {{ old('genero', $detalle->genero) === 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block font-bold mb-2">Talla Camisa *</label>
                        <input type="text" name="talla_camisa" id="talla_camisa" class="w-full p-2 border rounded" 
                               value="{{ old('talla_camisa', $detalle->talla_camisa) }}" 
                               placeholder="Ej: XL" required>
                    </div>
                    <div>
                        <label class="block font-bold mb-2">Talla Pantalón *</label>
                        <input type="text" name="talla_pantalon" id="talla_pantalon" class="w-full p-2 border rounded" 
                               value="{{ old('talla_pantalon', $detalle->talla_pantalon) }}" 
                               placeholder="Ej: 34" required>
                    </div>
                    <div>
                        <label class="block font-bold mb-2">Talla Zapatos *</label>
                        <input type="text" name="talla_zapatos" id="talla_zapatos" class="w-full p-2 border rounded" 
                               value="{{ old('talla_zapatos', $detalle->talla_zapatos) }}" 
                               placeholder="Ej: 42" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block font-bold mb-2">Cantidad de Uniformes *</label>
                    <input type="number" name="cantidad_uniformes" id="cantidad_uniformes" class="w-full p-2 border rounded" 
                           value="{{ old('cantidad_uniformes', $detalle->cantidad_uniformes ?? 2) }}" 
                           min="1" required>
                </div>

                <div class="mb-4">
                    <label class="block font-bold mb-2">Observaciones</label>
                    <textarea name="observaciones" class="w-full p-2 border rounded" rows="3">{{ old('observaciones', $detalle->observaciones) }}</textarea>
                    <small class="text-gray-600">Ej: Preferencias especiales, alergias, etc.</small>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        💾 Guardar Tallas
                    </button>
                    <a href="{{ route('solicitudes.show', $solicitud->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Cancelar
                    </a>
                </div>
            </form>

        </div>

        @if ($kitEstandar)
        <script>
            document.getElementById('usarKitBtn').addEventListener('click', function(e) {
                e.preventDefault();
                
                // Pre-llenar el formulario con las tallas del kit estándar
                document.getElementById('genero').value = '{{ $kitEstandar->genero }}';
                document.getElementById('talla_camisa').value = '{{ $kitEstandar->talla_camisa }}';
                document.getElementById('talla_pantalon').value = '{{ $kitEstandar->talla_pantalon }}';
                document.getElementById('talla_zapatos').value = '{{ $kitEstandar->talla_zapatos }}';
                document.getElementById('cantidad_uniformes').value = {{ $kitEstandar->cantidad_uniformes ?? 2 }};
                
                // Scroll al formulario
                document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
                
                // Mostrar notificación
                alert('✓ Tallas recomendadas aplicadas. Revisa los datos y guarda si es correcto.');
            });
        </script>
        @endif

    </div>
</x-app-layout>
