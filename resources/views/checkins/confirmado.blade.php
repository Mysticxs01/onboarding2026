<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Confirmación Exitosa!</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-green-50 to-green-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md mx-auto text-center px-4">
        <!-- Success Icon -->
        <div class="mb-6">
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100">
                <svg class="h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <!-- Title and Message -->
        <h1 class="text-4xl font-bold text-green-900 mb-4">¡Éxito!</h1>
        <p class="text-xl text-green-800 mb-6">
            Hemos recibido su confirmación de entrega de activos.
        </p>

        <!-- Employee Info -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6 text-left">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Información Confirmada</h2>
            <div class="space-y-3 text-gray-700">
                <p>
                    <strong>Empleado:</strong><br>
                    {{ $checkin->procesoIngreso->nombre_completo }}
                </p>
                <p>
                    <strong>Cargo:</strong><br>
                    {{ $checkin->procesoIngreso->cargo->nombre }}
                </p>
                <p>
                    <strong>Área:</strong><br>
                    {{ $checkin->procesoIngreso->area->nombre }}
                </p>
                <p>
                    <strong>Fecha de Confirmación:</strong><br>
                    {{ $checkin->fecha_confirmacion->format('d \d\e F \d\e Y \a \l\a\s H:i') }}
                </p>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-6 text-left">
            <p class="text-sm text-blue-700">
                <strong>Próximos pasos:</strong> Su jefe inmediato recibirá una confirmación de que todos los activos han sido entregados correctamente. 
                Esto completa el proceso de onboarding.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <a href="{{ route('checkins.pdf', $checkin->id) }}" 
               class="flex-1 bg-red-600 text-white font-bold py-3 rounded hover:bg-red-700 transition">
                📄 Descargar Acta
            </a>
            <a href="/" 
               class="flex-1 bg-blue-600 text-white font-bold py-3 rounded hover:bg-blue-700 transition">
                🏠 Ir a Inicio
            </a>
        </div>

        <!-- Footer Message -->
        <p class="text-sm text-gray-600 mt-8">
            Código de verificación: <span class="font-mono text-sm bg-gray-200 px-2 py-1 rounded">{{ $checkin->codigo_verificacion }}</span>
        </p>
    </div>
</body>
</html>
