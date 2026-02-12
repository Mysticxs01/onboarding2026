<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl">💻 Asignación de Tecnología</h2>
                <p class="text-sm text-gray-600 mt-1">
                    Credenciales, hardware y accesos digitales
                </p>
            </div>
            <a href="{{ route('solicitudes.index') }}" class="text-blue-600 hover:text-blue-800 whitespace-nowrap">← Volver a Solicitudes</a>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6">

        {{-- Mensajes --}}
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        {{-- Información del Empleado --}}
        <div class="bg-white p-6 rounded shadow mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-600 font-semibold text-sm">Empleado</p>
                    <p class="text-lg">{{ $solicitud->proceso->nombre_completo }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold text-sm">Cargo</p>
                    <p class="text-lg">{{ $solicitud->proceso->cargo->nombre }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold text-sm">Correo Corporativo</p>
                    <p class="text-lg">{{ $solicitud->proceso->email }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Formulario (Izquierda) --}}
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('tecnologia.guardar', $solicitud->id) }}" class="space-y-6">
                    @csrf

                    {{-- Kit Recomendado --}}
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <h3 class="font-bold text-blue-900 mb-2">💡 Kit Estándar</h3>
                        <button type="button" onclick="cargarKitEstandar()" class="text-sm text-blue-700 hover:text-blue-900 font-semibold">
                            ↻ Cargar kit automático para {{ $solicitud->proceso->cargo->nombre }}
                        </button>
                    </div>

                    {{-- Hardware --}}
                    <div class="bg-white p-6 rounded shadow">
                        <h3 class="font-bold text-lg mb-4">Hardware</h3>

                        <div class="space-y-4">
                            @php
                                $hardwareKits = [
                                    'Computadora' => ['Laptop', 'Desktop', 'Tablet'],
                                    'Accesorios' => ['Mouse', 'Teclado', 'Monitor', 'Docking Station'],
                                    'Periféricos' => ['Impresora', 'Escáner', 'Teléfono IP'],
                                ];
                            @endphp

                            @foreach($hardwareKits as $categoria => $items)
                                <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded">
                                    <h4 class="font-semibold text-blue-900 mb-3">{{ $categoria }}</h4>
                                    <div class="space-y-2">
                                        @foreach($items as $item)
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" 
                                                       name="hardware[]" 
                                                       value="{{ $item }}"
                                                       class="rounded">
                                                <span class="text-gray-700">{{ $item }}</span>
                                                <span class="text-xs text-gray-500">✓ Asignado</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Credenciales y Acceso --}}
                    <div class="bg-white p-6 rounded shadow">
                        <h3 class="font-bold text-lg mb-4">Credenciales y Acceso</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nombre de Usuario (AD)
                                </label>
                                <input type="text" 
                                       name="usuario_ad"
                                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                                       placeholder="ejemplo.apellido"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Correo Corporativo
                                </label>
                                <input type="email" 
                                       name="correo_corporativo"
                                       value="{{ $solicitud->proceso->email }}"
                                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Contraseña Inicial (Generada)
                                </label>
                                <div class="flex gap-2">
                                    <input type="text" 
                                           name="password"
                                           id="password-input"
                                           class="flex-1 px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                                           readonly
                                           placeholder="Se generará automáticamente">
                                    <button type="button" onclick="generarPassword()" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 font-semibold">
                                        🔄 Generar
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Niveles de Acceso
                                </label>
                                <div class="space-y-2">
                                    @php
                                        $accesos = [
                                            'Correo Electrónico',
                                            'Intranet',
                                            'Sistemas ERP',
                                            'Dropbox/OneDrive',
                                            'VPN',
                                            'Software Especializado',
                                            'Datos Sensibles'
                                        ];
                                    @endphp
                                    @foreach($accesos as $acceso)
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="accesos[]" 
                                                   value="{{ $acceso }}"
                                                   class="rounded">
                                            <span class="text-gray-700">{{ $acceso }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Software --}}
                    <div class="bg-white p-6 rounded shadow">
                        <h3 class="font-bold text-lg mb-4">Software a Instalar</h3>

                        <div class="space-y-2">
                            @php
                                $softwares = [
                                    'Microsoft Office Suite',
                                    'Adobe Creative Cloud',
                                    'Antivirus Corporativo',
                                    'VPN Client',
                                    'Git / GitHub Desktop',
                                    'Visual Studio Code',
                                    'Java Development Kit',
                                    'Python',
                                    'Node.js',
                                    'Docker Desktop',
                                ];
                            @endphp
                            @foreach($softwares as $software)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" 
                                           name="software[]" 
                                           value="{{ $software }}"
                                           class="rounded">
                                    <span class="text-gray-700">{{ $software }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-3 rounded hover:bg-blue-700 font-semibold">
                            ✓ Guardar Asignación de TI
                        </button>
                        <a href="{{ route('solicitudes.show', $solicitud->id) }}" class="flex-1 bg-gray-300 text-gray-800 px-4 py-3 rounded hover:bg-gray-400 font-semibold text-center">
                            Cancelar
                        </a>
                    </div>

                </form>
            </div>

            {{-- Panel Derecho --}}
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded shadow sticky top-20 space-y-4">
                    <h3 class="font-bold text-lg">🖥️ Resumen IT</h3>

                    {{-- Contadores --}}
                    <div class="space-y-2">
                        <div class="p-3 bg-blue-50 rounded">
                            <p class="text-gray-600 text-sm">Hardware</p>
                            <p class="text-2xl font-bold text-blue-600" id="hw-count">0</p>
                        </div>

                        <div class="p-3 bg-purple-50 rounded">
                            <p class="text-gray-600 text-sm">Accesos asignados</p>
                            <p class="text-2xl font-bold text-purple-600" id="acceso-count">0</p>
                        </div>

                        <div class="p-3 bg-green-50 rounded">
                            <p class="text-gray-600 text-sm">Software</p>
                            <p class="text-2xl font-bold text-green-600" id="soft-count">0</p>
                        </div>
                    </div>

                    <hr>

                    {{-- Información --}}
                    <div class="bg-amber-50 p-3 rounded">
                        <p class="text-xs font-semibold text-amber-900 mb-1">⚠️ Importante</p>
                        <ul class="text-xs text-amber-800 space-y-1">
                            <li>• Crear usuario AD antes de asignar</li>
                            <li>• Enviar credenciales por canal seguro</li>
                            <li>• Cambiar contraseña en primer login</li>
                            <li>• Documentar todos los accesos</li>
                        </ul>
                    </div>

                    <hr>

                    {{-- Estados --}}
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Checklist</p>
                        <ul class="text-xs space-y-2">
                            <li class="flex items-center gap-2">
                                <input type="checkbox" class="rounded">
                                <span>Usuario creado en AD</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <input type="checkbox" class="rounded">
                                <span>Credenciales enviadas</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <input type="checkbox" class="rounded">
                                <span>Hardware entregado</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <input type="checkbox" class="rounded">
                                <span>Software instalado</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <script>
        function generarPassword() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
            let password = '';
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('password-input').value = password;
        }

        function cargarKitEstandar() {
            alert('Cargar kit estándar para {{ $solicitud->proceso->cargo->nombre }}');
        }

        function updateCounts() {
            const hw = document.querySelectorAll('[name="hardware[]"]:checked').length;
            const acceso = document.querySelectorAll('[name="accesos[]"]:checked').length;
            const soft = document.querySelectorAll('[name="software[]"]:checked').length;
            
            document.getElementById('hw-count').textContent = hw;
            document.getElementById('acceso-count').textContent = acceso;
            document.getElementById('soft-count').textContent = soft;
        }

        document.addEventListener('change', updateCounts);
    </script>
</x-app-layout>
