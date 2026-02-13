@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Centro de Reportes</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Dashboard Ejecutivo --}}
        <a href="{{ route('reportes.dashboard') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 p-4 rounded-lg">
                    <svg class="w-8 h-8 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zm8-6h4a1 1 0 011 1v12a1 1 0 01-1 1h-4a1 1 0 01-1-1V5a1 1 0 011-1z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-blue-900">Dashboard Ejecutivo</h3>
                    <p class="text-sm text-gray-600">Métricas principales</p>
                </div>
            </div>
        </a>

        {{-- Cumplimiento por Área --}}
        <a href="{{ route('reportes.cumplimiento-por-area') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-4 rounded-lg">
                    <svg class="w-8 h-8 text-green-900" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-blue-900">Cumplimiento</h3>
                    <p class="text-sm text-gray-600">Por área</p>
                </div>
            </div>
        </a>

        {{-- Formación por Curso --}}
        <a href="{{ route('reportes.formacion-por-curso') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 p-4 rounded-lg">
                    <svg class="w-8 h-8 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.5 1.5H3a1 1 0 00-1 1v15a1 1 0 001 1h10a1 1 0 001-1v-13l-3.5-3zM13 4v2h2M5 8h8M5 11h8M5 14h5"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-blue-900">Formación</h3>
                    <p class="text-sm text-gray-600">Por cursos</p>
                </div>
            </div>
        </a>

        {{-- Asignaciones Pendientes --}}
        <a href="{{ route('reportes.asignaciones-pendientes') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
            <div class="flex items-center gap-4">
                <div class="bg-yellow-100 p-4 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-blue-900">Pendientes</h3>
                    <p class="text-sm text-gray-600">Asignaciones</p>
                </div>
            </div>
        </a>

        {{-- Retrasos --}}
        <a href="{{ route('reportes.retrasos-formacion') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
            <div class="flex items-center gap-4">
                <div class="bg-red-100 p-4 rounded-lg">
                    <svg class="w-8 h-8 text-red-900" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-blue-900">Retrasos</h3>
                    <p class="text-sm text-gray-600">Vencidas</p>
                </div>
            </div>
        </a>

        {{-- Costos --}}
        <a href="{{ route('reportes.costos-formacion') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
            <div class="flex items-center gap-4">
                <div class="bg-purple-100 p-4 rounded-lg">
                    <svg class="w-8 h-8 text-purple-900" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.718-1.46A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L11 9.414V13H8.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-blue-900">Costos</h3>
                    <p class="text-sm text-gray-600">Formación</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
