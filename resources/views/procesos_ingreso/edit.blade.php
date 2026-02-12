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
                <label class="block font-bold mb-2">Cargo</label>
                <select name="cargo_id" id="cargo_id" class="w-full p-2 border rounded" required>
                    <option value="">Seleccione cargo</option>
                    @foreach ($cargos as $cargo)
                        <option value="{{ $cargo->id }}" data-area="{{ $cargo->area_id }}" {{ old('cargo_id', $proceso->cargo_id) == $cargo->id ? 'selected' : '' }}>
                            {{ $cargo->nombre }} - {{ $cargo->area->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2">Jefe Inmediato</label>
                <select name="jefe_id" id="jefe_id" class="w-full p-2 border rounded" required>
                    <option value="">Seleccione jefe</option>
                    @foreach ($jefes as $jefe)
                        <option value="{{ $jefe->id }}" {{ old('jefe_id', $proceso->jefe_id) == $jefe->id ? 'selected' : '' }}>
                            {{ $jefe->name }}
                        </option>
                    @endforeach
                </select>
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
        document.getElementById('cargo_id').addEventListener('change', function() {
            const areaId = this.options[this.selectedIndex].dataset.area;
            const jefeSelect = document.getElementById('jefe_id');
            
            jefeSelect.innerHTML = '<option value="">Seleccione jefe</option>';

            if(areaId) {
                fetch(`/areas/${areaId}/jefes`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(jefe => {
                            const option = document.createElement('option');
                            option.value = jefe.id;
                            option.textContent = jefe.name;
                            jefeSelect.appendChild(option);
                        });
                    });
            }
        });
    </script>
</x-app-layout>
