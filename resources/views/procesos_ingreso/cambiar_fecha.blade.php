<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">Cambiar Fecha de Ingreso</h2>
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

        <div class="bg-white p-6 rounded shadow">
            
            <p class="mb-4 text-gray-600">
                <strong>Empleado:</strong> {{ $proceso->nombre_completo }}<br>
                <strong>Fecha Actual:</strong> {{ $proceso->fecha_ingreso }}<br>
                <strong>Nota:</strong> Solo puede postergar la fecha, no adelantarla. Las fechas límite de solicitudes se ajustarán automáticamente.
            </p>

            <form method="POST" action="{{ route('procesos-ingreso.actualizar-fecha', $proceso->id) }}">
                @csrf

                <div class="mb-4">
                    <label class="block font-bold mb-2">Nueva Fecha de Ingreso</label>
                    <input 
                        type="date" 
                        name="nueva_fecha" 
                        value="{{ old('nueva_fecha', $proceso->fecha_ingreso) }}"
                        min="{{ $proceso->fecha_ingreso }}"
                        class="w-full p-2 border rounded" 
                        required>
                    <small class="text-gray-600">Mínimo: {{ $proceso->fecha_ingreso }}</small>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        📅 Actualizar Fecha
                    </button>
                    <a href="{{ route('procesos-ingreso.show', $proceso->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Cancelar
                    </a>
                </div>
            </form>

            {{-- Información sobre solicitudes --}}
            <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded">
                <h3 class="font-bold mb-2">Solicitudes que serán ajustadas:</h3>
                <ul class="list-disc pl-5">
                    @foreach ($proceso->solicitudes as $solicitud)
                        @if ($solicitud->estado !== 'Finalizada')
                            <li>
                                <strong>{{ $solicitud->area->nombre }}</strong> - {{ $solicitud->tipo }}
                                <br><small>Fecha límite actual: {{ $solicitud->fecha_limite }}</small>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

        </div>

    </div>
</x-app-layout>
