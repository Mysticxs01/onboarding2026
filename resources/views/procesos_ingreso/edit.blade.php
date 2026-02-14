<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">Editar Proceso {{ $proceso->codigo }}</h2>
            <a href="{{ route('procesos-ingreso.show', $proceso->id) }}" class="text-blue-600 hover:text-blue-800">Volver</a>
        </div>
    </x-slot>

    <div class="p-6 max-w-2xl">

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

        <form method="POST" action="{{ route('procesos-ingreso.update', $proceso->id) }}" class="bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-bold mb-2">Nombre Completo</label>
                <input type="text" name="nombre_completo" value="{{ old('nombre_completo', $proceso->nombre_completo) }}" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2">Tipo de Documento</label>
                <input type="text" name="tipo_documento" value="{{ old('tipo_documento', $proceso->tipo_documento) }}" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2">Documento (Solo lectura)</label>
                <input type="text" value="{{ $proceso->documento }}" class="w-full p-2 border rounded bg-gray-100" disabled>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2">Gerencia</label>
                <select id="gerencia_id" class="w-full p-2 border rounded" required>
                    <option value="">Seleccione gerencia</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2">Area</label>
                <select id="area_id" class="w-full p-2 border rounded" required>
                    <option value="">Seleccione area</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2">Cargo</label>
                <select name="cargo_id" id="cargo_id" class="w-full p-2 border rounded" required>
                    <option value="">Seleccione cargo</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2">Jefe Inmediato (cargo)</label>
                <input id="jefe_cargo" class="w-full p-2 border rounded bg-gray-100" readonly>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    💾 Guardar Cambios
                </button>
                <a href="{{ route('procesos-ingreso.show', $proceso->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Cancelar
                </a>
            </div>
        </form>

    </div>

    <script>
        const gerencias = @json($gerencias);
        const oldGerenciaId = "{{ old('gerencia_id', optional(optional($proceso->cargo)->area)->gerencia_id) }}";
        const oldAreaId = "{{ old('area_id', optional($proceso->cargo)->area_id) }}";
        const oldCargoId = "{{ old('cargo_id', $proceso->cargo_id) }}";

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
                if (oldGerenciaId && String(gerencia.id) === String(oldGerenciaId)) {
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
                if (oldAreaId && String(area.id) === String(oldAreaId)) {
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
                option.textContent = cargo.activo ? cargo.nombre : `${cargo.nombre} (Inactivo)`;
                option.dataset.jefeNombre = cargo.jefe_inmediato ? cargo.jefe_inmediato.nombre : '';
                if (oldCargoId && String(cargo.id) === String(oldCargoId)) {
                    option.selected = true;
                }
                if (!cargo.activo && String(cargo.id) !== String(oldCargoId)) {
                    option.disabled = true;
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

        populateGerencias();
        if (gerenciaSelect.value) {
            populateAreas(gerenciaSelect.value);
        }
        if (gerenciaSelect.value && areaSelect.value) {
            populateCargos(gerenciaSelect.value, areaSelect.value);
        }
        updateJefeCargo();
    </script>
</x-app-layout>
