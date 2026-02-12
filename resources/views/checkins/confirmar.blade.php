<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Entrega de Activos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
</head>
<body class="bg-gradient-to-b from-blue-50 to-blue-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-blue-900">🎓 Confirmación de Entrega</h1>
            <p class="text-gray-600 mt-2">Onboarding - Nuevo Empleado</p>
        </div>

        <!-- Main Content -->
        <div class="max-w-2xl mx-auto">
            @if ($checkin)
                <!-- Información del Empleado -->
                <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Información Personal</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-600 text-sm">Empleado</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $checkin->procesoIngreso->nombre_completo }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Cargo</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $checkin->procesoIngreso->cargo->nombre }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Área</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $checkin->procesoIngreso->area->nombre }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Jefe Inmediato</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $checkin->procesoIngreso->jefe->name }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Activos a Confirmar -->
                <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">📦 Activos Entregados</h2>
                    
                    @if ($checkin->activos_entregados)
                        <div class="space-y-4">
                            @foreach ($checkin->activos_entregados as $index => $activo)
                                <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-blue-400 transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-800">{{ $activo['item'] }}</p>
                                            @if ($activo['especificaciones'])
                                                <p class="text-sm text-gray-600 mt-1">{{ $activo['especificaciones'] }}</p>
                                            @endif
                                        </div>
                                        <input type="checkbox" class="activo-checkbox w-5 h-5 text-blue-600 rounded cursor-pointer"
                                               data-index="{{ $index }}" {{ $activo['entregado'] ? 'checked' : '' }}>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-6 mb-6">
                            <div class="flex justify-between mb-2">
                                <p class="font-semibold text-gray-700">Progreso</p>
                                <span class="text-blue-600 font-bold"><span id="activos-completos">{{ $checkin->obtenerPorcentajeCompletado() }}</span>%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-3">
                                <div class="bg-blue-600 h-3 rounded-full transition-all duration-300"
                                     id="progress-bar"
                                     style="width: {{ $checkin->obtenerPorcentajeCompletado() }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Formulario de Confirmación -->
                <form id="formulario-confirmacion" class="bg-white rounded-lg shadow-lg p-8 mb-6">
                    @csrf
                    
                    <!-- Nombre y Cédula -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">👤 Datos de Confirmación</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Nombre Completo *</label>
                                <input type="text" id="nombre_persona" name="nombre_persona" required
                                       placeholder="Juan Pérez González"
                                       class="w-full px-4 py-2 border-2 border-gray-300 rounded focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Cédula/ID *</label>
                                <input type="text" id="cedula_persona" name="cedula_persona" required
                                       placeholder="1234567890"
                                       class="w-full px-4 py-2 border-2 border-gray-300 rounded focus:border-blue-500 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Canvas para Firma -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">✍️ Firma Digital</h3>
                        <p class="text-gray-600 text-sm mb-3">Dibuje su firma en el espacio siguiente. Use mouse o pantalla táctil.</p>
                        <div class="border-2 border-gray-300 rounded bg-white" style="touch-action: none;">
                            <canvas id="signature-canvas" class="w-full" height="200"></canvas>
                        </div>
                        <div class="flex gap-2 mt-3">
                            <button type="button" id="btn-limpiar-firma" 
                                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                🔄 Limpiar Firma
                            </button>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Observaciones (Opcional)</label>
                        <textarea name="observaciones" rows="3" placeholder="Agregue observaciones si es necesario..."
                                  class="w-full px-4 py-2 border-2 border-gray-300 rounded focus:border-blue-500 focus:outline-none"></textarea>
                    </div>

                    <!-- Aviso Legal -->
                    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <p class="text-sm text-gray-700">
                            <strong>⚠️ Declaración:</strong> Al firmar, confirmo que he recibido todos los activos listados anteriormente en perfecto estado.
                            Esta acción es considerada un acuerdo vinculante.
                        </p>
                    </div>

                    <!-- Checkbox de Aceptación -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" id="aceptar-terminos" name="aceptar_terminos" required
                                   class="w-5 h-5 text-blue-600 rounded cursor-pointer">
                            <span class="ml-3 text-gray-700">
                                Confirmo que he recibido todos los activos y acepto los términos
                            </span>
                        </label>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex gap-4">
                        <button type="submit" id="btn-confirmar"
                                class="flex-1 bg-green-600 text-white font-bold py-3 rounded hover:bg-green-700 transition">
                            ✓ Confirmar Recepción
                        </button>
                        <button type="button" onclick="window.history.back()"
                                class="flex-1 bg-gray-400 text-white font-bold py-3 rounded hover:bg-gray-500 transition">
                            ✕ Cancelar
                        </button>
                    </div>
                </form>

            @else
                <!-- Mensaje de Error -->
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                    <p class="font-bold">❌ Error</p>
                    <p>No se encontró el código de verificación. Por favor, verifique el enlace enviado a su correo.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Configurar canvas de firma
        const canvas = document.getElementById('signature-canvas');
        const signaturePad = new SignaturePad(canvas);

        // Ajustar tamaño del canvas
        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width;
            canvas.height = rect.height;
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        // Limpiar firma
        document.getElementById('btn-limpiar-firma').addEventListener('click', function() {
            signaturePad.clear();
        });

        // Actualizar progreso cuando se marca checkbox
        document.querySelectorAll('.activo-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const totalCheckboxes = document.querySelectorAll('.activo-checkbox').length;
                const checkedCheckboxes = document.querySelectorAll('.activo-checkbox:checked').length;
                const porcentaje = Math.round((checkedCheckboxes / totalCheckboxes) * 100);
                
                document.getElementById('activos-completos').textContent = porcentaje;
                document.getElementById('progress-bar').style.width = porcentaje + '%';
            });
        });

        // Manejar envío del formulario
        document.getElementById('formulario-confirmacion').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validaciones
            if (signaturePad.isEmpty()) {
                alert('Por favor, firme en el espacio designado.');
                return;
            }

            if (!document.getElementById('aceptar-terminos').checked) {
                alert('Debe aceptar los términos antes de confirmar.');
                return;
            }

            const btn = document.getElementById('btn-confirmar');
            btn.disabled = true;
            btn.textContent = '⏳ Procesando...';

            // Recopilar datos
            const formData = new FormData(this);
            
            // Agregar activos entregados checados
            const activos = [];
            document.querySelectorAll('.activo-checkbox').forEach(checkbox => {
                activos.push({
                    index: parseInt(checkbox.dataset.index),
                    entregado: checkbox.checked
                });
            });
            formData.append('activos_confirmados', JSON.stringify(activos));
            
            // Agregar firma
            formData.append('firma_digital', signaturePad.toDataURL());

            // Enviar datos
            try {
                const response = await fetch('/checkin/{{ $checkin->codigo_verificacion }}/procesar', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = `/checkin/{{ $checkin->codigo_verificacion }}/confirmado`;
                } else {
                    alert(data.message || 'Error al procesar la confirmación');
                    btn.disabled = false;
                    btn.textContent = '✓ Confirmar Recepción';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al enviar la confirmación');
                btn.disabled = false;
                btn.textContent = '✓ Confirmar Recepción';
            }
        });
    </script>
</body>
</html>
