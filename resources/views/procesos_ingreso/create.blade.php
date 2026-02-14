<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Nuevo Proceso de Ingreso</h2>
    </x-slot>

    <div class="p-6 max-w-xl">

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

            <input name="nombre_completo" placeholder="Nombre completo" value="{{ old('nombre_completo') }}" class="w-full mb-2 p-2 border rounded" required>

            <input name="tipo_documento" placeholder="Tipo documento" value="{{ old('tipo_documento') }}" class="w-full mb-2 p-2 border rounded" required>

            <input name="documento" placeholder="Documento" value="{{ old('documento') }}" class="w-full mb-2 p-2 border rounded" required>

            {{-- Gerencia --}}
            <select id="gerencia_id" class="w-full mb-2 p-2 border rounded" required>
                <option value="">Seleccione gerencia</option>
            </select>

            {{-- Area --}}
            <select id="area_id" class="w-full mb-2 p-2 border rounded" required>
                <option value="">Seleccione area</option>
            </select>

            {{-- Cargo --}}
            <select id="cargo_id" name="cargo_id" class="w-full mb-2 p-2 border rounded" required>
                <option value="">Seleccione cargo</option>
            </select>

            <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}" class="w-full mb-2 p-2 border rounded" required>

            {{-- Jefe directo (derivado del cargo) --}}
            <input id="jefe_cargo" class="w-full mb-4 p-2 border rounded bg-gray-100" placeholder="Jefe directo (cargo)" readonly>

            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Crear proceso
            </button>
        </form>
    </div>

    {{-- Script para selector jerarquico --}}
    <script>
        const gerencias = @json($gerencias);
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
