<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight" style="color: #1B365D;">
                🏢 Solicitud de Servicios Generales #{{ $solicitude->id }}
            </h2>
            <a href="{{ route('solicitudes.index') }}" class="btn-outline-primary">← Volver</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                    <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Panel Izquierdo -->
                <div class="lg:col-span-1 space-y-4">
                    @if ($solicitude->proceso)
                        <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #1B365D;">
                            <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">👤 Empleado</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-600 uppercase">Nombre</p>
                                    <p class="font-semibold text-sm" style="color: #1B365D;">{{ $solicitude->proceso->nombre_completo }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 uppercase">Cargo</p>
                                    <p class="font-semibold text-sm" style="color: #1B365D;">{{ $solicitude->proceso->cargo->nombre }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 uppercase">Área</p>
                                    <p class="font-semibold text-sm" style="color: #28A745;">{{ $solicitude->proceso->area->nombre }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #C59D42;">
                        <h3 class="text-lg font-bold mb-4" style="color: #C59D42;">📊 Estado</h3>
                        <p class="text-2xl font-bold text-center" style="color: #C59D42;">{{ $solicitude->estado }}</p>
                        <p class="text-sm text-gray-600 text-center mt-3">{{ $solicitude->fecha_limite?->format('d/m/Y') ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Panel Central y Derecho -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #1B365D;">
                        <h3 class="text-xl font-bold mb-6" style="color: #1B365D;">🏢 Asignación de Puesto Físico</h3>

                        @if($solicitude->puestoTrabajo)
                            <!-- Mostrar Puesto Asignado -->
                            <div class="p-4 bg-green-50 rounded border-l-4 border-green-500 mb-6">
                                <h4 class="font-bold text-green-900 mb-3">✅ Puesto Asignado</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-600 uppercase text-xs mb-1">Número de Puesto</p>
                                        <p class="font-semibold" style="color: #1B365D;">{{ $solicitude->puestoTrabajo->numero_puesto }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 uppercase text-xs mb-1">Sección</p>
                                        <p class="font-semibold" style="color: #1B365D;">{{ $solicitude->puestoTrabajo->seccion ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 uppercase text-xs mb-1">Piso</p>
                                        <p class="font-semibold" style="color: #1B365D;">{{ $solicitude->puestoTrabajo->piso ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 uppercase text-xs mb-1">Estado del Puesto</p>
                                        <span class="inline-block px-3 py-1 rounded text-xs font-semibold"
                                              style="background-color: #E8F5E9; color: #28A745;">
                                            ✅ {{ $solicitude->puestoTrabajo->estado }}
                                        </span>
                                    </div>
                                </div>

                                @if(Auth::user()->hasRole('Root') || Auth::user()->getRoleNames()->contains(fn($role) => strpos($role, 'Servicios') !== false))
                                    <div class="mt-4">
                                        <button onclick="document.getElementById('formulario-puesto').style.display = 'block'" class="btn-secondary">
                                            ✏️ Cambiar Puesto
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-600 mb-6">No se ha asignado puesto físico. Selecciona uno a continuación:</p>
                        @endif

                        <!-- Plano Interactivo -->
                        <div id="plano-interactivo">
                            <label class="block text-sm font-bold mb-4" style="color: #1B365D;">
                                🗺️ Plano Interactivo de Puestos
                            </label>

                            <!-- Controles de Filtrado -->
                            <div class="mb-4 flex gap-2">
                                <select id="filtro-piso" class="border rounded px-3 py-2 text-sm">
                                    <option value="">Todos los Pisos</option>
                                </select>
                                <select id="filtro-seccion" class="border rounded px-3 py-2 text-sm">
                                    <option value="">Todas las Secciones</option>
                                </select>
                            </div>

                            <!-- Leyenda de Estados -->
                            <div class="mb-4 p-3 bg-gray-50 rounded flex flex-wrap gap-4 text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="w-4 h-4 rounded" style="background-color: #28A745;"></span>
                                    <span>Disponible</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-4 h-4 rounded" style="background-color: #FFC107;"></span>
                                    <span>Reservado</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-4 h-4 rounded" style="background-color: #E74C3C;"></span>
                                    <span>Asignado</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-4 h-4 rounded" style="background-color: #95A5A6;"></span>
                                    <span>Mantenimiento</span>
                                </div>
                            </div>

                            <!-- Canvas del Plano -->
                            <div class="border rounded-lg p-6 bg-gray-50 overflow-auto" style="min-height: 400px;">
                                <canvas id="canvas-plano" width="800" height="400"></canvas>
                            </div>

                            <!-- Input Oculto para Puesto Seleccionado -->
                            <input type="hidden" id="puesto-seleccionado" name="puesto_trabajo_id">

                            <!-- Información del Puesto Seleccionado -->
                            <div id="info-puesto-seleccionado" class="mt-4 p-4 bg-blue-50 rounded border-l-4 border-blue-500" style="display: none;">
                                <h4 class="font-bold text-blue-900 mb-2">✅ Puesto Seleccionado</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-600 uppercase text-xs">Número</p>
                                        <p class="font-semibold" id="info-numero-puesto"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 uppercase text-xs">Piso</p>
                                        <p class="font-semibold" id="info-piso-puesto"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 uppercase text-xs">Sección</p>
                                        <p class="font-semibold" id="info-seccion-puesto"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="flex gap-4 pt-4 border-t">
                                <button type="button" id="btn-confirmar-puesto" class="btn-primary" style="display: none;">
                                    ✅ Confirmar Selección
                                </button>
                                <button type="button" id="btn-limpiar-seleccion" class="btn-outline-primary" style="display: none;">
                                    🔄 Deshacer Selección
                                </button>
                            </div>
                        </div>

                        <!-- Formulario Oculto (backup) -->
                        <div id="formulario-puesto-backup" style="display: none;">
                            <form action="{{ route('solicitudes.guardar-servicios-generales', $solicitude->id) }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" id="puesto_trabajo_id_backup" name="puesto_trabajo_id">
                                <button type="submit" class="btn-primary">✅ Asignar Puesto</button>
                            </form>
                        </div>
                    </div>

                    <!-- Cambiar Estado -->
                    @if(Auth::user()->getRoleNames()->contains(fn($role) => strpos($role, 'Servicios') !== false || strpos($role, 'Admin') !== false) || Auth::user()->hasRole('Root'))
                        <div class="bg-white rounded-lg shadow p-6 mt-6">
                            <h3 class="text-lg font-bold mb-4" style="color: #1B365D;">🔧 Cambiar Estado</h3>
                            
                            <form action="{{ route('solicitudes.cambiar-estado', $solicitude->id) }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: #1B365D;">Nuevo Estado</label>
                                    <select name="estado" class="w-full border rounded px-4 py-2" style="border-color: #1B365D;">
                                        <option value="Pendiente" {{ $solicitude->estado === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="En Proceso" {{ $solicitude->estado === 'En Proceso' ? 'selected' : '' }}>En Proceso</option>
                                        <option value="Finalizada" {{ $solicitude->estado === 'Finalizada' ? 'selected' : '' }}>Finalizada</option>
                                    </select>
                                </div>

                                <button type="submit" class="w-full btn-secondary">
                                    🔄 Actualizar Estado
                                </button>
                            </form>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('canvas-plano');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let puestosData = [];
    let puestoSeleccionado = null;
    const solicitudId = {{ $solicitude->id }};
    const puestoActualId = {{ $solicitude->puesto_trabajo_id ?? 'null' }};

    // Cargar datos de puestos
    async function cargarPuestos() {
        try {
            const response = await fetch('{{ route("api.puestos.plano") }}');
            const data = await response.json();
            puestosData = data.puestos;
            
            // Llenar selectores de filtro
            const pisos = [...new Set(puestosData.map(p => p.piso))].sort((a, b) => a - b);
            const secciones = [...new Set(puestosData.map(p => p.seccion).filter(Boolean))].sort();
            
            const filtroPiso = document.getElementById('filtro-piso');
            const filtroSeccion = document.getElementById('filtro-seccion');
            
            pisos.forEach(piso => {
                const option = document.createElement('option');
                option.value = `piso-${piso}`;
                option.textContent = `Piso ${piso}`;
                filtroPiso.appendChild(option);
            });
            
            secciones.forEach(seccion => {
                const option = document.createElement('option');
                option.value = seccion;
                option.textContent = `Sección ${seccion}`;
                filtroSeccion.appendChild(option);
            });
            
            dibujarPlano();
        } catch (error) {
            console.error('Error cargando puestos:', error);
            ctx.fillStyle = '#E74C3C';
            ctx.font = '16px Arial';
            ctx.fillText('Error cargando el plano', 50, 50);
        }
    }

    // Dibujar plano
    function dibujarPlano() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Fondo
        ctx.fillStyle = '#F5F5F5';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        const padding = 30;
        const puestoAncho = 60;
        const puestoAlto = 50;
        const espacioX = 20;
        const espacioY = 20;
        
        let filtroActivoPiso = null;
        let filtroActivoSeccion = null;
        
        const filtroPiso = document.getElementById('filtro-piso').value;
        const filtroSeccion = document.getElementById('filtro-seccion').value;
        
        if (filtroPiso) filtroActivoPiso = parseInt(filtroPiso.split('-')[1]);
        if (filtroSeccion) filtroActivoSeccion = filtroSeccion;
        
        let posX = padding;
        let posY = padding;
        let maxHeight = 0;

        puestosData.forEach((puesto, index) => {
            // Aplicar filtros
            if (filtroActivoPiso && puesto.piso !== filtroActivoPiso) return;
            if (filtroActivoSeccion && puesto.seccion !== filtroActivoSeccion) return;

            // Salto de línea
            if (posX + puestoAncho + espacioX > canvas.width - padding) {
                posX = padding;
                posY += puestoAlto + espacioY;
            }

            // Determinar color según estado
            let color = '#28A745'; // Disponible
            if (puesto.estado === 'Asignado') color = '#E74C3C';
            else if (puesto.estado === 'En Mantenimiento') color = '#95A5A6';
            else if (puesto.estado === 'Bloqueado') color = '#34495E';
            else if (puesto.estado === 'Reservado') color = '#FFC107';

            // Dibujar puesto
            ctx.fillStyle = puestoSeleccionado && puestoSeleccionado.id === puesto.id ? '#3498DB' : color;
            ctx.fillRect(posX, posY, puestoAncho, puestoAlto);

            // Borde
            ctx.strokeStyle = '#333';
            ctx.lineWidth = 2;
            ctx.strokeRect(posX, posY, puestoAncho, puestoAlto);

            // Texto
            ctx.fillStyle = '#FFF';
            ctx.font = 'bold 14px Arial';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(puesto.numero_puesto, posX + puestoAncho / 2, posY + puestoAlto / 2);

            // Guardar posición para click
            puesto.canvasX = posX;
            puesto.canvasY = posY;
            puesto.canvasAncho = puestoAncho;
            puesto.canvasAlto = puestoAlto;

            posX += puestoAncho + espacioX;
            maxHeight = Math.max(maxHeight, posY + puestoAlto);
        });

        // Configurar evento de click
        canvas.addEventListener('click', handleCanvasClick);
    }

    function handleCanvasClick(e) {
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        puestosData.forEach(puesto => {
            if (puesto.canvasX && 
                x >= puesto.canvasX && 
                x <= puesto.canvasX + puesto.canvasAncho &&
                y >= puesto.canvasY && 
                y <= puesto.canvasY + puesto.canvasAlto) {
                
                // Solo permitir seleccionar disponibles
                if (puesto.estado === 'Disponible') {
                    puestoSeleccionado = puesto;
                    document.getElementById('puesto-seleccionado').value = puesto.id;
                    
                    // Mostrar información
                    document.getElementById('info-numero-puesto').textContent = puesto.numero_puesto;
                    document.getElementById('info-piso-puesto').textContent = `Piso ${puesto.piso}`;
                    document.getElementById('info-seccion-puesto').textContent = puesto.seccion || 'General';
                    document.getElementById('info-puesto-seleccionado').style.display = 'block';
                    
                    // Mostrar botones
                    document.getElementById('btn-confirmar-puesto').style.display = 'inline-block';
                    document.getElementById('btn-limpiar-seleccion').style.display = 'inline-block';
                    
                    dibujarPlano();
                }
            }
        });
    }

    // Botones
    document.getElementById('btn-confirmar-puesto').addEventListener('click', async function() {
        if (!puestoSeleccionado) {
            alert('Por favor selecciona un puesto');
            return;
        }

        try {
            const response = await fetch(`{{ route('api.puestos.reservar', ['id' => 'ID']) }}`.replace('ID', puestoSeleccionado.id), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    solicitud_id: solicitudId
                })
            });

            const data = await response.json();
            
            if (data.success) {
                // Mostrar éxito y recargar
                alert('✅ ' + data.message);
                window.location.reload();
            } else {
                alert('❌ ' + data.message);
                dibujarPlano();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al guardar la selección');
        }
    });

    document.getElementById('btn-limpiar-seleccion').addEventListener('click', function() {
        puestoSeleccionado = null;
        document.getElementById('puesto-seleccionado').value = '';
        document.getElementById('info-puesto-seleccionado').style.display = 'none';
        document.getElementById('btn-confirmar-puesto').style.display = 'none';
        document.getElementById('btn-limpiar-seleccion').style.display = 'none';
        dibujarPlano();
    });

    document.getElementById('filtro-piso').addEventListener('change', dibujarPlano);
    document.getElementById('filtro-seccion').addEventListener('change', dibujarPlano);

    // Cargar puestos al iniciar
    cargarPuestos();
});
</script>
