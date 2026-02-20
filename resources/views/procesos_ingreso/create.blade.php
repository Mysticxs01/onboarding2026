<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Nuevo Proceso de Ingreso</h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">

        {{-- Mostrar errores de validación --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('procesos-ingreso.store') }}">
            @csrf

            {{-- ==================== DATOS BÁSICOS ==================== --}}
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">📋 Datos Básicos del Empleado</h3>
                
                <input name="nombre_completo" placeholder="Nombre completo" value="{{ old('nombre_completo') }}" class="w-full mb-3 p-2 border rounded" required>

                <div class="grid grid-cols-2 gap-3 mb-3">
                    <input name="tipo_documento" placeholder="Tipo documento" value="{{ old('tipo_documento') }}" class="w-full p-2 border rounded" required>
                    <input name="documento" placeholder="Documento" value="{{ old('documento') }}" class="w-full p-2 border rounded" required>
                </div>

                {{-- Gerencia --}}
                <select id="gerencia_id" class="w-full mb-3 p-2 border rounded" required>
                    <option value="">Seleccione gerencia</option>
                </select>

                {{-- Area --}}
                <select id="area_id" class="w-full mb-3 p-2 border rounded" required>
                    <option value="">Seleccione area</option>
                </select>

                {{-- Cargo --}}
                <select id="cargo_id" name="cargo_id" class="w-full mb-3 p-2 border rounded" required>
                    <option value="">Seleccione cargo</option>
                </select>

                <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}" class="w-full mb-3 p-2 border rounded" required>

                {{-- Jefe directo (derivado del cargo) --}}
                <input id="jefe_cargo" class="w-full p-2 border rounded bg-gray-100" placeholder="Jefe directo (cargo)" readonly>
            </div>

            {{-- ==================== DOTACIÓN (UNIFORMES) ==================== --}}
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-bold mb-4" style="color: #28A745;">👕 Especificaciones de Dotación</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">¿Necesita dotación?</label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="necesita_dotacion" value="1" class="mr-2" 
                                   onchange="document.getElementById('seccion-tallas').style.display = 'block'; document.getElementById('seccion-justificacion-dotacion').style.display = 'none'"
                                   {{ old('necesita_dotacion') == '1' ? 'checked' : '' }}>
                            <span class="text-sm">✅ Sí</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="necesita_dotacion" value="0" class="mr-2"
                                   onchange="document.getElementById('seccion-tallas').style.display = 'none'; document.getElementById('seccion-justificacion-dotacion').style.display = 'block'"
                                   {{ old('necesita_dotacion') == '0' ? 'checked' : '' }}>
                            <span class="text-sm">❌ No</span>
                        </label>
                    </div>
                </div>

                {{-- Sección de tallas (visible si necesita dotación) --}}
                <div id="seccion-tallas" style="display: {{ old('necesita_dotacion') == '1' ? 'block' : 'none' }};" class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">Género</label>
                        <select name="genero" class="w-full border rounded p-2">
                            <option value="">Selecciona género...</option>
                            <option value="Masculino" {{ old('genero') === 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero') === 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ old('genero') === 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">👖 Talla Pantalón</label>
                            <input type="text" name="talla_pantalon" class="w-full border rounded p-2" 
                                   placeholder="Ej: 32, M, XL" value="{{ old('talla_pantalon') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">👕 Talla Camiseta</label>
                            <input type="text" name="talla_camiseta" class="w-full border rounded p-2" 
                                   placeholder="Ej: M, L, XL" value="{{ old('talla_camiseta') }}">
                        </div>
                    </div>
                </div>

                {{-- Justificación si NO necesita dotación --}}
                <div id="seccion-justificacion-dotacion" style="display: {{ old('necesita_dotacion') == '0' ? 'block' : 'none' }};">
                    <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">Justificación (¿Por qué no necesita dotación?)</label>
                    <textarea name="justificacion_no_dotacion" rows="3" class="w-full border rounded p-2" 
                              placeholder="Explica la razón por la que no requiere dotación uniforme">{{ old('justificacion_no_dotacion') }}</textarea>
                </div>
            </div>

            {{-- ==================== TECNOLOGÍA ==================== --}}
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-bold mb-4" style="color: #428FFF;">💻 Especificaciones de Tecnología</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">¿Necesita computador?</label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="necesita_computador" value="1" class="mr-2"
                                   onchange="document.getElementById('seccion-gama-computador').style.display = 'block'"
                                   {{ old('necesita_computador') == '1' ? 'checked' : '' }}>
                            <span class="text-sm">✅ Sí</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="necesita_computador" value="0" class="mr-2"
                                   onchange="document.getElementById('seccion-gama-computador').style.display = 'none'"
                                   {{ old('necesita_computador') == '0' ? 'checked' : '' }}>
                            <span class="text-sm">❌ No</span>
                        </label>
                    </div>
                </div>

                {{-- Gama del computador (visible si necesita computador) --}}
                <div id="seccion-gama-computador" style="display: {{ old('necesita_computador') == '1' ? 'block' : 'none' }};" class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">📊 Gama del Computador</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label class="flex items-start p-3 border rounded cursor-pointer hover:bg-blue-50">
                                <input type="radio" name="gama_computador" value="Básica" class="mt-1 mr-2"
                                       {{ old('gama_computador') === 'Básica' ? 'checked' : '' }}>
                                <div>
                                    <div class="font-semibold text-sm" style="color: #1B365D;">Básica</div>
                                    <div class="text-xs text-gray-600">Office, navegación</div>
                                </div>
                            </label>
                            <label class="flex items-start p-3 border rounded cursor-pointer hover:bg-blue-50">
                                <input type="radio" name="gama_computador" value="Media" class="mt-1 mr-2"
                                       {{ old('gama_computador') === 'Media' ? 'checked' : '' }}>
                                <div>
                                    <div class="font-semibold text-sm" style="color: #1B365D;">Media</div>
                                    <div class="text-xs text-gray-600">Programación, diseño ligero</div>
                                </div>
                            </label>
                            <label class="flex items-start p-3 border rounded cursor-pointer hover:bg-blue-50">
                                <input type="radio" name="gama_computador" value="Premium" class="mt-1 mr-2"
                                       {{ old('gama_computador') === 'Premium' ? 'checked' : '' }}>
                                <div>
                                    <div class="font-semibold text-sm" style="color: #1B365D;">Premium</div>
                                    <div class="text-xs text-gray-600">Diseño CAD, edición</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">🔑 Credenciales y Plataformas Requeridas</label>
                        <textarea name="credenciales_plataformas" rows="4" class="w-full border rounded p-2" 
                                  placeholder="Ejemplo:&#10;- Email corporativo: usuario@sinergia.coop&#10;- Sistema Core: Credencial autogenerada&#10;- Slack: workspace sinergia-coop&#10;- Bases de datos: acceso a BD_Operativa">{{ old('credenciales_plataformas') }}</textarea>
                        <p class="text-xs text-gray-600 mt-1">Especifica qué plataformas, sistemas y credenciales necesita el empleado</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold">
                ✅ Crear Proceso de Ingreso
            </button>
        </form>
    </div>

    {{-- Script para selector jerarquico --}}
    <script>
        const gerencias = @json($gerencias);
        console.log('Gerencias cargadas:', gerencias); // Debug
        let oldGerenciaId = "{{ old('gerencia_id') }}";
        let oldAreaId = "{{ old('area_id') }}";
        const oldCargoId = "{{ old('cargo_id') }}";

        const gerenciaSelect = document.getElementById('gerencia_id');
        const areaSelect = document.getElementById('area_id');
        const cargoSelect = document.getElementById('cargo_id');
        const jefeCargoInput = document.getElementById('jefe_cargo');

        const resetSelect = (select, placeholder) => {
            select.innerHTML = '';
            const option = document.createElement('option');
            option.value = '';
            option.textContent = placeholder;
            select.appendChild(option);
        };

        const populateGerencias = () => {
            resetSelect(gerenciaSelect, 'Seleccione gerencia');
            gerencias.forEach((gerencia) => {
                const option = document.createElement('option');
                option.value = gerencia.id;
                option.textContent = gerencia.nombre;
                if (oldGerenciaId && String(gerencia.id) === oldGerenciaId) {
                    option.selected = true;
                }
                gerenciaSelect.appendChild(option);
            });
        };

        const populateAreas = (gerenciaId) => {
            resetSelect(areaSelect, 'Seleccione area');
            resetSelect(cargoSelect, 'Seleccione cargo');
            jefeCargoInput.value = '';

            const gerencia = gerencias.find((item) => String(item.id) === String(gerenciaId));
            if (!gerencia) {
                return;
            }

            gerencia.areas.forEach((area) => {
                const option = document.createElement('option');
                option.value = area.id;
                option.textContent = area.nombre;
                if (oldAreaId && String(area.id) === oldAreaId) {
                    option.selected = true;
                }
                areaSelect.appendChild(option);
            });
        };

        const populateCargos = (gerenciaId, areaId) => {
            resetSelect(cargoSelect, 'Seleccione cargo');
            jefeCargoInput.value = '';

            const gerencia = gerencias.find((item) => String(item.id) === String(gerenciaId));
            const area = gerencia?.areas.find((item) => String(item.id) === String(areaId));
            if (!area) {
                return;
            }

            area.cargos.forEach((cargo) => {
                const option = document.createElement('option');
                option.value = cargo.id;
                option.textContent = cargo.nombre;
                option.dataset.jefeNombre = cargo.jefe_inmediato ? cargo.jefe_inmediato.nombre : '';
                if (oldCargoId && String(cargo.id) === oldCargoId) {
                    option.selected = true;
                }
                cargoSelect.appendChild(option);
            });
        };

        const updateJefeCargo = () => {
            const selected = cargoSelect.options[cargoSelect.selectedIndex];
            jefeCargoInput.value = selected?.dataset?.jefeNombre || 'Sin jefe asignado';
        };

        gerenciaSelect.addEventListener('change', (event) => {
            populateAreas(event.target.value);
            if (event.target.value) {
                populateCargos(event.target.value, areaSelect.value);
            }
        });

        areaSelect.addEventListener('change', (event) => {
            populateCargos(gerenciaSelect.value, event.target.value);
        });

        cargoSelect.addEventListener('change', updateJefeCargo);

        if (!oldGerenciaId && oldCargoId) {
            gerencias.forEach((gerencia) => {
                gerencia.areas.forEach((area) => {
                    area.cargos.forEach((cargo) => {
                        if (String(cargo.id) === String(oldCargoId)) {
                            oldGerenciaId = String(gerencia.id);
                            oldAreaId = String(area.id);
                        }
                    });
                });
            });
        }

        populateGerencias();
        if (oldGerenciaId) {
            gerenciaSelect.value = oldGerenciaId;
            populateAreas(oldGerenciaId);
        }
        if (oldGerenciaId && oldAreaId) {
            areaSelect.value = oldAreaId;
            populateCargos(oldGerenciaId, oldAreaId);
        }
        updateJefeCargo();
    </script>
</x-app-layout>
