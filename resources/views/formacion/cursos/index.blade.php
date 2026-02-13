@extends('layouts.app')

@section('title', 'Gestión de Cursos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Catálogo de Cursos</h1>
        @can('create', App\Models\Curso::class)
            <a href="{{ route('cursos.create') }}" class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-800">
                + Nuevo Curso
            </a>
        @endcan
    </div>

    {{-- Formulario de Búsqueda --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" placeholder="Buscar curso..." value="{{ request('search') }}" class="border rounded px-3 py-2">
            <select name="categoria" class="border rounded px-3 py-2">
                <option value="">Todas las categorías</option>
                <option value="Obligatorio" {{ request('categoria') === 'Obligatorio' ? 'selected' : '' }}>Obligatorio</option>
                <option value="Opcional" {{ request('categoria') === 'Opcional' ? 'selected' : '' }}>Opcional</option>
                <option value="Cumplimiento Normativo" {{ request('categoria') === 'Cumplimiento Normativo' ? 'selected' : '' }}>Cumplimiento</option>
                <option value="Desarrollo" {{ request('categoria') === 'Desarrollo' ? 'selected' : '' }}>Desarrollo</option>
            </select>
            <select name="modalidad" class="border rounded px-3 py-2">
                <option value="">Todas las modalidades</option>
                <option value="Presencial" {{ request('modalidad') === 'Presencial' ? 'selected' : '' }}>Presencial</option>
                <option value="Virtual" {{ request('modalidad') === 'Virtual' ? 'selected' : '' }}>Virtual</option>
                <option value="Híbrida" {{ request('modalidad') === 'Híbrida' ? 'selected' : '' }}>Híbrida</option>
            </select>
            <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">
                Buscar
            </button>
        </form>
    </div>

    {{-- Tabla de Cursos --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($cursos->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-blue-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-blue-900">Código</th>
                            <th class="px-6 py-3 text-left font-semibold text-blue-900">Nombre</th>
                            <th class="px-6 py-3 text-left font-semibold text-blue-900">Categoría</th>
                            <th class="px-6 py-3 text-left font-semibold text-blue-900">Modalidad</th>
                            <th class="px-6 py-3 text-left font-semibold text-blue-900">Horas</th>
                            <th class="px-6 py-3 text-left font-semibold text-blue-900">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cursos as $curso)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-mono">{{ $curso->codigo }}</td>
                                <td class="px-6 py-4 text-sm">{{ $curso->nombre }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-block bg-blue-100 text-blue-900 px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ $curso->categoria }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $curso->modalidad }}</td>
                                <td class="px-6 py-4 text-sm text-center">{{ $curso->duracion_horas }}h</td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('cursos.show', $curso) }}" class="text-blue-600 hover:underline">Ver</a>
                                    @can('update', $curso)
                                        <a href="{{ route('cursos.edit', $curso) }}" class="text-green-600 hover:underline">Editar</a>
                                    @endcan
                                    @can('delete', $curso)
                                        <form action="{{ route('cursos.destroy', $curso) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="px-6 py-4">
                {{ $cursos->links() }}
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                No se encontraron cursos.
            </div>
        @endif
    </div>
</div>
@endsection
