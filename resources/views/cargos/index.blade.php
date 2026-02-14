<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl">Gestion de Cargos</h2>
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Volver al Dashboard</a>
        </div>
    </x-slot>

    <div class="p-6">
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm">Gerencia</th>
                        <th class="px-4 py-3 text-left text-sm">Area</th>
                        <th class="px-4 py-3 text-left text-sm">Cargo</th>
                        <th class="px-4 py-3 text-left text-sm">Jefe Inmediato</th>
                        <th class="px-4 py-3 text-left text-sm">Estado</th>
                        <th class="px-4 py-3 text-center text-sm">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cargos as $cargo)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $cargo->area?->gerencia?->nombre ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $cargo->area?->nombre ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm font-semibold">{{ $cargo->nombre }}</td>
                            <td class="px-4 py-3 text-sm">{{ $cargo->jefeInmediato?->nombre ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $cargo->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $cargo->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form method="POST" action="{{ route('cargos.estado', $cargo) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="activo" value="{{ $cargo->activo ? 0 : 1 }}">
                                    <button type="submit" class="px-3 py-1 rounded text-sm text-white {{ $cargo->activo ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                        {{ $cargo->activo ? 'Deshabilitar' : 'Habilitar' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay cargos registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
