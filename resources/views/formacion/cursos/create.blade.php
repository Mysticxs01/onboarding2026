@extends('layouts.app')

@section('title', 'Crear Curso')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Crear Nuevo Curso</h1>

    <form action="{{ route('cursos.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-blue-900 mb-2">Código</label>
                <input type="text" name="codigo" class="w-full border rounded px-3 py-2" required value="{{ old('codigo') }}">
                @error('codigo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-blue-900 mb-2">Nombre</label>
                <input type="text" name="nombre" class="w-full border rounded px-3 py-2" required value="{{ old('nombre') }}">
                @error('nombre') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-blue-900 mb-2">Descripción</label>
            <textarea name="descripcion" class="w-full border rounded px-3 py-2 h-24">{{ old('descripcion') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-blue-900 mb-2">Categoría</label>
                <select name="categoria" class="w-full border rounded px-3 py-2" required>
                    <option value="">Seleccionar...</option>
                    <option value="Obligatorio" {{ old('categoria') === 'Obligatorio' ? 'selected' : '' }}>Obligatorio</option>
                    <option value="Opcional" {{ old('categoria') === 'Opcional' ? 'selected' : '' }}>Opcional</option>
                    <option value="Cumplimiento Normativo" {{ old('categoria') === 'Cumplimiento Normativo' ? 'selected' : '' }}>Cumplimiento</option>
                    <option value="Desarrollo" {{ old('categoria') === 'Desarrollo' ? 'selected' : '' }}>Desarrollo</option>
                    <option value="Liderazgo" {{ old('categoria') === 'Liderazgo' ? 'selected' : '' }}>Liderazgo</option>
                </select>
                @error('categoria') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-blue-900 mb-2">Modalidad</label>
                <select name="modalidad" class="w-full border rounded px-3 py-2" required>
                    <option value="">Seleccionar...</option>
                    <option value="Presencial" {{ old('modalidad') === 'Presencial' ? 'selected' : '' }}>Presencial</option>
                    <option value="Virtual" {{ old('modalidad') === 'Virtual' ? 'selected' : '' }}>Virtual</option>
                    <option value="Híbrida" {{ old('modalidad') === 'Híbrida' ? 'selected' : '' }}>Híbrida</option>
                </select>
                @error('modalidad') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-semibold text-blue-900 mb-2">Duración (horas)</label>
                <input type="number" name="duracion_horas" class="w-full border rounded px-3 py-2" required value="{{ old('duracion_horas') }}">
            </div>

            <div>
                <label class="block text-sm font-semibold text-blue-900 mb-2">Costo ($)</label>
                <input type="number" step="0.01" name="costo" class="w-full border rounded px-3 py-2" value="{{ old('costo') }}">
            </div>

            <div>
                <label class="block text-sm font-semibold text-blue-900 mb-2">Vigencia (meses)</label>
                <input type="number" name="vigencia_meses" class="w-full border rounded px-3 py-2" value="{{ old('vigencia_meses') }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-blue-900 mb-2">Objetivo</label>
            <textarea name="objetivo" class="w-full border rounded px-3 py-2 h-20">{{ old('objetivo') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-blue-900 mb-2">Contenido</label>
            <textarea name="contenido" class="w-full border rounded px-3 py-2 h-20">{{ old('contenido') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-blue-900 mb-2">Área Responsable</label>
                <select name="area_responsable_id" class="w-full border rounded px-3 py-2">
                    <option value="">Seleccionar...</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ old('area_responsable_id') == $area->id ? 'selected' : '' }}>{{ $area->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-4">
                <label class="flex items-center">
                    <input type="checkbox" name="requiere_certificado" class="mr-2" {{ old('requiere_certificado', true) ? 'checked' : '' }}>
                    <span class="text-sm font-semibold text-blue-900">Requiere Certificado</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="activo" class="mr-2" {{ old('activo', true) ? 'checked' : '' }}>
                    <span class="text-sm font-semibold text-blue-900">Activo</span>
                </label>
            </div>
        </div>

        <div class="flex gap-4 pt-6">
            <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-800">
                Crear Curso
            </button>
            <a href="{{ route('cursos.index') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded hover:bg-gray-400">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
