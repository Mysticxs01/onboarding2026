<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">Especificar Requerimientos de Tecnología</h2>
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
                    <h3 class="font-bold text-lg mb-3 text-green-700">💡 Recomendación Inteligente</h3>
                    
                    @if ($estadisticas['total_solicitudes'] > 0)
                        <p class="text-sm text-gray-600 mb-3">
                            Basado en {{ $estadisticas['total_solicitudes'] }} 
                            {{ $estadisticas['total_solicitudes'] == 1 ? 'ingreso anterior' : 'ingresos anteriores' }}
                            del cargo <strong>{{ $solicitud->proceso->cargo->nombre }}</strong>:
                        </p>

                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                            @if ($estadisticas['tipo_computador_sugerido'])
                                <div>
                                    <p class="font-semibold text-gray-700">Tipo de Computador:</p>
                                    <p class="text-green-700">{{ $estadisticas['tipo_computador_sugerido'] }}</p>
                                </div>
                            @endif
                            
                            @if ($estadisticas['marca_sugerida'])
                                <div>
                                    <p class="font-semibold text-gray-700">Marca Recomendada:</p>
                                    <p class="text-green-700">{{ $estadisticas['marca_sugerida'] }}</p>
                                </div>
                            @endif
                        </div>

                        @if (count($estadisticas['especificaciones_recientes']) > 0)
                            <div class="mb-3">
                                <p class="font-semibold text-gray-700 text-sm">Especificaciones Recientes:</p>
                                <ul class="text-sm text-gray-600 list-disc pl-5 mt-1">
                                    @foreach ($estadisticas['especificaciones_recientes'] as $esp)
                                        <li>{{ $esp }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (count($estadisticas['software_frecuente']) > 0)
                            <div class="mb-3">
                                <p class="font-semibold text-gray-700 text-sm">Software Frecuentemente Solicitado:</p>
                                <p class="text-gray-600 text-sm">{{ implode(', ', array_slice($estadisticas['software_frecuente'], 0, 3)) }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                            <label class="flex items-center">
                                <span class="text-gray-600">Monitor Adicional: </span>
                                <span class="ml-2 {{ $estadisticas['monitor_adicional_comun'] ? 'text-green-700 font-semibold' : 'text-gray-500' }}">
                                    {{ $estadisticas['monitor_adicional_comun'] ? '✓ Sí (común)' : '✗ No (inusual)' }}
                                </span>
                            </label>
                            <label class="flex items-center">
                                <span class="text-gray-600">Mouse/Teclado: </span>
                                <span class="ml-2 {{ $estadisticas['mouse_keyboard_comun'] ? 'text-green-700 font-semibold' : 'text-gray-500' }}">
                                    {{ $estadisticas['mouse_keyboard_comun'] ? '✓ Sí (común)' : '✗ No (inusual)' }}
                                </span>
                            </label>
                        </div>
                    @endif

                    @if ($kitEstandar)
                        <button type="button" id="usarKitBtn" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm font-semibold">
                            ✓ Usar Kit Recomendado
                        </button>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('solicitudes.guardar-ti', $solicitud->id) }}">
                @csrf

                <div class="mb-4">
                    <label class="block font-bold mb-2">Tipo de Computador *</label>
                    <select name="tipo_computador" id="tipo_computador" class="w-full p-2 border rounded" required>
                        <option value="">Seleccione tipo</option>
                        <option value="Portátil" {{ old('tipo_computador', $detalle->tipo_computador) === 'Portátil' ? 'selected' : '' }}>Portátil</option>
                        <option value="Escritorio" {{ old('tipo_computador', $detalle->tipo_computador) === 'Escritorio' ? 'selected' : '' }}>Escritorio</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-bold mb-2">Marca y Modelo *</label>
                    <input type="text" name="marca_computador" id="marca_computador" class="w-full p-2 border rounded" 
                           value="{{ old('marca_computador', $detalle->marca_computador) }}" 
                           placeholder="Ej: Dell Latitude 5420" required>
                </div>

                <div class="mb-4">
                    <label class="block font-bold mb-2">Especificaciones *</label>
                    <textarea name="especificaciones" id="especificaciones" class="w-full p-2 border rounded" rows="3" required>{{ old('especificaciones', $detalle->especificaciones) }}</textarea>
                    <small class="text-gray-600">Ej: RAM 16GB, Procesador Intel i7, SSD 512GB, Windows 11</small>
                </div>

                <div class="mb-4">
                    <label class="block font-bold mb-2">Software Requerido *</label>
                    <textarea name="software_requerido" id="software_requerido" class="w-full p-2 border rounded" rows="3" required>{{ old('software_requerido', $detalle->software_requerido) }}</textarea>
                    <small class="text-gray-600">Ej: Microsoft Office, Zoom, VPN, Antivirus, etc.</small>
                </div>

                <div class="mb-4">
                    <label class="block font-bold mb-2">Accesorios Adicionales</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="monitor_adicional" id="monitor_adicional" value="1" 
                                   {{ old('monitor_adicional', $detalle->monitor_adicional) ? 'checked' : '' }} class="mr-2">
                            Monitor Adicional
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="mouse_teclado" id="mouse_teclado" value="1" 
                                   {{ old('mouse_teclado', $detalle->mouse_teclado ?? true) ? 'checked' : '' }} class="mr-2">
                            Mouse y Teclado
                        </label>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        💾 Guardar Requerimientos
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
                
                // Pre-llenar el formulario con los datos del kit estándar
                document.getElementById('tipo_computador').value = '{{ $kitEstandar->tipo_computador }}';
                document.getElementById('marca_computador').value = '{{ $kitEstandar->marca_computador }}';
                document.getElementById('especificaciones').value = '{{ addslashes($kitEstandar->especificaciones) }}';
                document.getElementById('software_requerido').value = '{{ addslashes($kitEstandar->software_requerido) }}';
                document.getElementById('monitor_adicional').checked = {{ $kitEstandar->monitor_adicional ? 'true' : 'false' }};
                document.getElementById('mouse_teclado').checked = {{ $kitEstandar->mouse_teclado ? 'true' : 'false' }};
                
                // Scroll al formulario
                document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
                
                // Mostrar notificación
                alert('✓ Kit recomendado aplicado. Revisa los datos y guarda si es correcto.');
            });
        </script>
        @endif

    </div>
</x-app-layout>
