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

            {{-- Cargo --}}
            <select id="cargo_id" name="cargo_id" class="w-full mb-2 p-2 border rounded" required>
                <option value="">Seleccione cargo</option>
                @foreach ($cargos as $cargo)
                    <option value="{{ $cargo->id }}" data-area="{{ $cargo->area_id }}" {{ old('cargo_id') == $cargo->id ? 'selected' : '' }}>
                        {{ $cargo->nombre }} - {{ $cargo->area->nombre }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}" class="w-full mb-2 p-2 border rounded" required>

            {{-- Jefe --}}
            <select id="jefe_id" name="jefe_id" class="w-full mb-4 p-2 border rounded" required>
                <option value="">Seleccione jefe</option>
                @foreach ($jefes as $jefe)
                    <option value="{{ $jefe->id }}" data-area="{{ $jefe->area_id }}" {{ old('jefe_id') == $jefe->id ? 'selected' : '' }}>
                        {{ $jefe->name }}
                    </option>
                @endforeach
            </select>

            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Crear proceso
            </button>
        </form>
    </div>

    {{-- Script para cargar jefes dinámicamente según área --}}
    <script>
        document.getElementById('cargo_id').addEventListener('change', function() {
            const areaId = this.options[this.selectedIndex].dataset.area;
            const jefeSelect = document.getElementById('jefe_id');
            
            // Mostrar estado de carga
            jefeSelect.innerHTML = '<option value="">Cargando jefes...</option>';

            if(areaId) {
                fetch(`/areas/${areaId}/jefes`)
                    .then(response => response.json())
                    .then(data => {
                        jefeSelect.innerHTML = '<option value="">Seleccione jefe</option>';
                        data.forEach(jefe => {
                            const option = document.createElement('option');
                            option.value = jefe.id;
                            option.textContent = jefe.name;
                            jefeSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error al cargar jefes:', error);
                        jefeSelect.innerHTML = '<option value="">Error al cargar jefes</option>';
                    });
            } else {
                jefeSelect.innerHTML = '<option value="">Seleccione jefe</option>';
            }
        });
    </script>
</x-app-layout>
