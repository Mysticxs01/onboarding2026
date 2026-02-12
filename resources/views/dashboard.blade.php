<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }} - Bienvenido {{ Auth::user()->name }}
            </h2>
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm font-semibold">
                {{ Auth::user()->getRoleNames()->first() ?? 'Usuario' }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Estadísticas Rápidas -->
            @if (Auth::user()->hasRole(['Root', 'Admin']))
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-blue-50 p-6 rounded-lg shadow">
                        <p class="text-gray-600 text-sm">Procesos de Ingreso</p>
                        <p class="text-3xl font-bold text-blue-600">
                            {{ \App\Models\ProcesoIngreso::count() }}
                        </p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg shadow">
                        <p class="text-gray-600 text-sm">Solicitudes Pendientes</p>
                        <p class="text-3xl font-bold text-green-600">
                            {{ \App\Models\Solicitud::where('estado', 'Pendiente')->count() }}
                        </p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-lg shadow">
                        <p class="text-gray-600 text-sm">Check-ins Pendientes</p>
                        <p class="text-3xl font-bold text-yellow-600">
                            {{ \App\Models\Checkin::where('estado_checkin', 'Pendiente')->count() }}
                        </p>
                    </div>
                    <div class="bg-purple-50 p-6 rounded-lg shadow">
                        <p class="text-gray-600 text-sm">Usuarios Registrados</p>
                        <p class="text-3xl font-bold text-purple-600">
                            {{ \App\Models\User::count() }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Panel Principal -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">
                        @if (Auth::user()->hasRole('Root'))
                            🔐 Panel de Control Raíz
                        @elseif (Auth::user()->hasRole('Admin'))
                            📊 Panel Administrativo
                        @elseif (Auth::user()->hasRole('Jefe'))
                            👔 Panel de Jefe
                        @else
                            📋 Panel de Operador
                        @endif
                    </h1>

                    <!-- Sección: Procesos de Ingreso -->
                    <div class="mb-8">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b-2 border-blue-500 pb-2">
                            📋 Procesos de Ingreso
                        </h2>
                        <div class="flex gap-3 flex-wrap">
                            <a href="{{ route('procesos-ingreso.create') }}"
                               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow transition">
                                ➕ Crear Nuevo Proceso
                            </a>
                            <a href="{{ route('procesos-ingreso.index') }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">
                                👁️ Ver Procesos
                            </a>
                            @if (Auth::user()->hasRole(['Root', 'Admin']))
                                <a href="{{ route('procesos-ingreso.historico') }}"
                                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow transition">
                                    📜 Histórico
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Sección: Solicitudes por Área -->
                    <div class="mb-8">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b-2 border-green-500 pb-2">
                            🎯 Solicitudes por Área
                        </h2>
                        <div class="flex gap-3 flex-wrap">
                            <a href="{{ route('solicitudes.index') }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">
                                📊 Ver Solicitudes
                            </a>
                            @if (Auth::user()->hasRole('Jefe'))
                                <p class="text-gray-600 text-sm mt-2 italic">
                                    📝 Valida y especifica requerimientos técnicos de tu área
                                </p>
                            @elseif (Auth::user()->hasRole('Operador') || Auth::user()->getRoleNames()->contains('Operador'))
                                <p class="text-gray-600 text-sm mt-2 italic">
                                    ✅ Completa las solicitudes de {{ Auth::user()->area->nombre ?? 'tu área' }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Sección: Check-in de Activos -->
                    <div class="mb-8">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b-2 border-yellow-500 pb-2">
                            ✅ Check-in de Activos
                        </h2>
                        <div class="flex gap-3 flex-wrap">
                            <a href="{{ route('checkins.index') }}"
                               class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded shadow transition">
                                📦 Ver Check-ins
                            </a>
                            @if (Auth::user()->hasRole(['Root', 'Admin']))
                                <p class="text-gray-600 text-sm mt-2 italic">
                                    🔍 Monitorea la entrega de activos a empleados
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Sección: Administración (solo Admin/Root) -->
                    @if (Auth::user()->hasRole(['Root', 'Admin']))
                        <div class="mb-8">
                            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b-2 border-purple-500 pb-2">
                                ⚙️ Administración
                            </h2>
                            <div class="flex gap-3 flex-wrap">
                                <a href="{{ route('procesos-ingreso.index') }}"
                                   class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded shadow transition">
                                    🗺️ Gestionar Procesos
                                </a>
                                <p class="text-gray-600 text-sm mt-2 italic">
                                    🔧 Selecciona un proceso para asignar puestos de trabajo
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Documentación -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mt-8">
                        <p class="text-sm text-gray-700">
                            <strong>ℹ️ Información:</strong> Este sistema gestiona todo el proceso de onboarding de nuevos empleados.
                            Cuando creas un nuevo proceso de ingreso, se generan automáticamente solicitudes para todas las áreas
                            correspondientes basadas en el cargo del empleado.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Atajos Rápidos por Rol -->
            @if (Auth::user()->hasRole('Jefe'))
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6 shadow mb-6">
                    <h3 class="text-lg font-bold text-indigo-900 mb-4">🚀 Acciones Rápidas - Jefe</h3>
                    <ul class="space-y-2 text-indigo-700">
                        <li>✓ Especifica requerimientos técnicos (TI, uniformes, etc.)</li>
                        <li>✓ Valida que se complete todo antes de la fecha límite</li>
                        <li>✓ Supervisa check-in de activos al empleado</li>
                    </ul>
                </div>
            @elseif (Auth::user()->hasRole('Operador') || Auth::user()->getRoleNames()->contains('Operador'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 shadow">
                    <h3 class="text-lg font-bold text-green-900 mb-4">🚀 Acciones Rápidas - Operador</h3>
                    <ul class="space-y-2 text-green-700">
                        <li>✓ Completa todas las solicitudes de {{ Auth::user()->area->nombre ?? 'tu área' }}</li>
                        <li>✓ Marca cada ítem como completado</li>
                        <li>✓ Prepara todo antes de la fecha límite</li>
                    </ul>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
