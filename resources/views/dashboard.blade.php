<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight" style="color: #1B365D;">
                {{ __('Dashboard') }} - Bienvenido {{ Auth::user()->name }}
            </h2>
            <span class="text-white px-3 py-1 rounded text-sm font-semibold" style="background-color: #1B365D;">
                {{ Auth::user()->getRoleNames()->first() ?? 'Usuario' }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Estadísticas Rápidas -->
            @if (Auth::user()->hasRole(['Root', 'Admin']))
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="p-6 rounded-lg shadow" style="background-color: #F8F9FA; border-left: 4px solid #1B365D;">
                        <p style="color: #1B365D;" class="text-sm font-semibold">Procesos de Ingreso</p>
                        <p class="text-3xl font-bold mt-2" style="color: #1B365D;">
                            {{ \App\Models\ProcesoIngreso::count() }}
                        </p>
                    </div>
                    <div class="p-6 rounded-lg shadow" style="background-color: #F8F9FA; border-left: 4px solid #28A745;">
                        <p style="color: #28A745;" class="text-sm font-semibold">Solicitudes Pendientes</p>
                        <p class="text-3xl font-bold mt-2" style="color: #28A745;">
                            {{ \App\Models\Solicitud::where('estado', 'Pendiente')->count() }}
                        </p>
                    </div>
                    <div class="p-6 rounded-lg shadow" style="background-color: #F8F9FA; border-left: 4px solid #C59D42;">
                        <p style="color: #C59D42;" class="text-sm font-semibold">Check-ins Pendientes</p>
                        <p class="text-3xl font-bold mt-2" style="color: #C59D42;">
                            {{ \App\Models\Checkin::where('estado_checkin', 'Pendiente')->count() }}
                        </p>
                    </div>
                    <div class="p-6 rounded-lg shadow" style="background-color: #F8F9FA; border-left: 4px solid #28A745; opacity: 0.8;">
                        <p style="color: #1B365D;" class="text-sm font-semibold">Usuarios Registrados</p>
                        <p class="text-3xl font-bold mt-2" style="color: #1B365D;">
                            {{ \App\Models\User::count() }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Panel Principal -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6" style="border-left: 4px solid #1B365D;">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6" style="color: #1B365D;">
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
                        <h2 class="text-lg font-bold mb-4 pb-2" style="color: #1B365D; border-bottom: 2px solid #C59D42;">
                            📋 Procesos de Ingreso
                        </h2>
                        <div class="flex gap-4 flex-wrap items-start">
                            <div class="flex gap-3 flex-wrap">
                                @if (Auth::user()->hasRole(['Root', 'Admin']))
                                    <a href="{{ route('procesos-ingreso.create') }}" class="btn-secondary">
                                        ➕ Crear Nuevo Proceso
                                    </a>
                                @endif
                                <a href="{{ route('procesos-ingreso.index') }}" class="btn-primary">
                                    👁️ Ver Procesos
                                </a>
                                @if (Auth::user()->hasRole(['Root', 'Admin']))
                                    <a href="{{ route('procesos-ingreso.historico') }}" class="btn-accent">
                                        📜 Histórico
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Solicitudes por Área -->
                    <div class="mb-8">
                        <h2 class="text-lg font-bold mb-4 pb-2" style="color: #1B365D; border-bottom: 2px solid #28A745;">
                            🎯 Solicitudes por Área
                        </h2>
                        <div class="flex gap-4 flex-wrap items-start">
                            <div class="flex gap-3 flex-wrap">
                                <a href="{{ route('solicitudes.index') }}" class="btn-primary">
                                    📊 Ver Solicitudes
                                </a>
                            </div>
                            <div>
                                @if (Auth::user()->hasRole('Jefe'))
                                    <p style="color: #1B365D;" class="text-sm italic">
                                        📝 Valida y especifica requerimientos técnicos de tu área
                                    </p>
                                @elseif (Auth::user()->hasRole('Operador') || Auth::user()->getRoleNames()->contains('Operador'))
                                    <p style="color: #1B365D;" class="text-sm italic">
                                        ✅ Completa las solicitudes de {{ Auth::user()->area->nombre ?? 'tu área' }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Check-in de Activos -->
                    <div class="mb-8">
                        <h2 class="text-lg font-bold mb-4 pb-2" style="color: #1B365D; border-bottom: 2px solid #C59D42;">
                            ✅ Check-in de Activos
                        </h2>
                        <div class="flex gap-4 flex-wrap items-start">
                            <div class="flex gap-3 flex-wrap">
                                <a href="{{ route('checkins.index') }}" class="btn-secondary">
                                    📦 Ver Check-ins
                                </a>
                            </div>
                            @if (Auth::user()->hasRole(['Root', 'Admin']))
                                <div>
                                    <p style="color: #1B365D;" class="text-sm italic">
                                        🔍 Monitorea la entrega de activos a empleados
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sección: Administración (solo Admin/Root) -->
                    @if (Auth::user()->hasRole(['Root', 'Admin']))
                        <div class="mb-8">
                            <h2 class="text-lg font-bold mb-4 pb-2" style="color: #1B365D; border-bottom: 2px solid #1B365D;">
                                ⚙️ Administración
                            </h2>
                            <div class="flex gap-4 flex-wrap items-start">
                                <div class="flex gap-3 flex-wrap">
                                    <a href="{{ route('procesos-ingreso.index') }}" class="btn-accent">
                                        🗺️ Gestionar Procesos
                                    </a>
                                </div>
                                <div>
                                    <p style="color: #1B365D;" class="text-sm italic">
                                        🔧 Selecciona un proceso para asignar puestos de trabajo
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Documentación -->
                    <div class="border-l-4 p-4 rounded mt-8" style="background-color: #F8F9FA; border-left-color: #1B365D;">
                        <p style="color: #1B365D;" class="text-sm">
                            <strong>ℹ️ Información:</strong> Este sistema gestiona todo el proceso de onboarding de nuevos empleados.
                            Cuando creas un nuevo proceso de ingreso, se generan automáticamente solicitudes para todas las áreas
                            correspondientes basadas en el cargo del empleado.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Atajos Rápidos por Rol -->
            @if (Auth::user()->hasRole('Jefe'))
                <div class="border-l-4 rounded-lg p-6 shadow mb-6" style="background-color: #F8F9FA; border-left-color: #28A745;">
                    <h3 class="text-lg font-bold mb-4" style="color: #28A745;">🚀 Acciones Rápidas - Jefe</h3>
                    <ul class="space-y-2" style="color: #1B365D;">
                        <li>✓ Especifica requerimientos técnicos (TI, uniformes, etc.)</li>
                        <li>✓ Valida que se complete todo antes de la fecha límite</li>
                        <li>✓ Supervisa check-in de activos al empleado</li>
                    </ul>
                </div>
            @elseif (Auth::user()->hasRole('Operador') || Auth::user()->getRoleNames()->contains('Operador'))
                <div class="border-l-4 rounded-lg p-6 shadow" style="background-color: #F8F9FA; border-left-color: #28A745;">
                    <h3 class="text-lg font-bold mb-4" style="color: #28A745;">🚀 Acciones Rápidas - Operador</h3>
                    <ul class="space-y-2" style="color: #1B365D;">
                        <li>✓ Completa todas las solicitudes de {{ Auth::user()->area->nombre ?? 'tu área' }}</li>
                        <li>✓ Marca cada ítem como completado</li>
                        <li>✓ Prepara todo antes de la fecha límite</li>
                    </ul>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
