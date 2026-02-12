<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">Asignar Puesto de Trabajo - {{ $proceso->nombre_completo }}</h2>
            <a href="{{ route('procesos-ingreso.show', $proceso->id) }}" class="text-blue-600 hover:text-blue-800">Volver</a>
        </div>
    </x-slot>

    <div class="p-6 max-w-6xl">

        {{-- Información del empleado --}}
        <div class="bg-blue-50 border border-blue-200 rounded p-4 mb-6">
            <p class="text-sm">
                <strong>Empleado:</strong> {{ $proceso->nombre_completo }}<br>
                <strong>Cargo:</strong> {{ $proceso->cargo->nombre }}<br>
                <strong>Área:</strong> {{ $proceso->area->nombre }}
            </p>
        </div>

        {{-- Leyenda --}}
        <div class="flex gap-6 mb-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-green-500 rounded border-2 border-green-600 cursor-pointer flex items-center justify-center text-white font-bold">✓</div>
                <span>Disponible</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-red-500 rounded border-2 border-red-600 cursor-not-allowed flex items-center justify-center text-white font-bold">✗</div>
                <span>Ocupado</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-500 rounded border-4 border-blue-700 flex items-center justify-center text-white font-bold">★</div>
                <span>Seleccionado</span>
            </div>
        </div>

        {{-- Plano de puestos --}}
        <div class="bg-white p-8 rounded shadow mb-6 border-2 border-gray-300">
            <h3 class="text-lg font-bold mb-6 text-center">Plano de Oficina</h3>
            
            <div id="plano" class="grid gap-4" style="grid-template-columns: repeat(auto-fit, minmax(50px, 1fr)); max-width: 600px;">
                <!-- Los puestos se cargarán aquí con JavaScript -->
            </div>
        </div>

        {{-- Puesto seleccionado --}}
        <div class="bg-white p-6 rounded shadow" id="seleccionado-container" style="display: none;">
            <h3 class="text-lg font-bold mb-4">Puesto Seleccionado</h3>
            <p class="mb-4">
                <strong>Número de Puesto:</strong> <span id="puesto-numero"></span>
            </p>
            <form id="form-asignar" method="POST" action="{{ route('procesos-ingreso.asignar-puesto', $proceso->id) }}">
                @csrf
                <input type="hidden" name="puesto_id" id="puesto-id">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    ✓ Asignar este Puesto
                </button>
            </form>
        </div>

    </div>

    <script>
        let puestoSeleccionado = null;
        const plano = document.getElementById('plano');
        const procesoId = {{ $proceso->id }};

        // Cargar puestos
        async function cargarPuestos() {
            try {
                const response = await fetch('/api/puestos');
                const puestos = await response.json();
                
                plano.innerHTML = '';
                
                puestos.forEach(puesto => {
                    const div = document.createElement('div');
                    div.className = 'w-12 h-12 rounded border-2 flex items-center justify-center font-bold text-sm cursor-pointer transition-all hover:scale-110';
                    
                    if (puesto.estado === 'Disponible') {
                        div.className += ' bg-green-500 border-green-600 text-white';
                        div.style.cursor = 'pointer';
                    } else {
                        div.className += ' bg-red-500 border-red-600 text-white';
                        div.style.cursor = 'not-allowed';
                    }
                    
                    div.textContent = puesto.numero;
                    div.dataset.id = puesto.id;
                    div.dataset.numero = puesto.numero;
                    div.dataset.estado = puesto.estado;
                    
                    if (puesto.estado === 'Disponible') {
                        div.addEventListener('click', () => seleccionarPuesto(puesto.id, puesto.numero, div));
                    }
                    
                    plano.appendChild(div);
                });
            } catch (error) {
                console.error('Error al cargar puestos:', error);
            }
        }

        function seleccionarPuesto(id, numero, element) {
            // Remover selección anterior
            document.querySelectorAll('#plano > div').forEach(el => {
                if (el.style.borderWidth === '4px') {
                    el.style.borderWidth = '2px';
                    el.style.borderColor = el.dataset.estado === 'Disponible' ? '#16a34a' : '#dc2626';
                }
            });
            
            // Seleccionar nuevo
            puestoSeleccionado = { id, numero };
            element.style.borderWidth = '4px';
            element.style.borderColor = '#1e40af';
            
            // Mostrar información
            document.getElementById('puesto-numero').textContent = numero;
            document.getElementById('puesto-id').value = id;
            document.getElementById('seleccionado-container').style.display = 'block';
        }

        // Cargar puestos al iniciar
        cargarPuestos();

        // Manejar envío del formulario
        document.getElementById('form-asignar').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            try {
                const response = await fetch(`/procesos-ingreso/${procesoId}/asignar-puesto`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        puesto_id: document.getElementById('puesto-id').value
                    })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    alert('✓ Puesto asignado correctamente');
                    window.location.href = '/procesos-ingreso/{{ $proceso->id }}';
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al asignar puesto');
            }
        });
    </script>
</x-app-layout>
