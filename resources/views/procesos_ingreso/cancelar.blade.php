<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">Cancelar Proceso de Ingreso</h2>
            <a href="{{ route('procesos-ingreso.show', $proceso->id) }}" class="text-blue-600 hover:text-blue-800">Volver</a>
        </div>
    </x-slot>

    <div class="p-6 max-w-2xl">

        <div class="bg-white p-6 rounded shadow border-l-4 border-red-600">
            
            <h3 class="text-lg font-bold mb-4 text-red-600">⚠️ Confirmación de Cancelación</h3>

            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded">
                <p class="mb-2"><strong>Empleado:</strong> {{ $proceso->nombre_completo }}</p>
                <p class="mb-2"><strong>Código:</strong> {{ $proceso->codigo }}</p>
                <p class="mb-2"><strong>Cargo:</strong> {{ $proceso->cargo->nombre }}</p>
                <p><strong>Fecha de Ingreso:</strong> {{ $proceso->fecha_ingreso }}</p>
            </div>

            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded">
                <p class="font-bold text-red-700 mb-2">Al cancelar este proceso:</p>
                <ul class="list-disc pl-5 text-red-700">
                    <li>Se cambiarán el estado de todas las solicitudes pendientes a "Canceladas"</li>
                    <li>Se liberará el puesto de trabajo asignado</li>
                    <li>Esta acción no poddrá ser revertida</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('procesos-ingreso.cancelar', $proceso->id) }}">
                @csrf

                <div class="mb-4">
                    <label class="block font-bold mb-2">Motivo de la Cancelación (Opcional)</label>
                    <textarea 
                        name="motivo" 
                        class="w-full p-2 border rounded" 
                        rows="4"
                        placeholder="Indique el motivo de la cancelación...">{{ old('motivo') }}</textarea>
                </div>

                <p class="text-sm text-gray-600 mb-4">
                    <input type="checkbox" id="confirm" required class="mr-2">
                    <label for="confirm" class="text-red-700 font-bold">
                        Confirmo que deseo cancelar este proceso de ingreso
                    </label>
                </p>

                <div class="flex gap-2">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        ❌ Cancelar Proceso
                    </button>
                    <a href="{{ route('procesos-ingreso.show', $proceso->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Volver sin cancelar
                    </a>
                </div>
            </form>

        </div>

    </div>
</x-app-layout>
