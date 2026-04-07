<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProcesoIngreso;
use App\Models\Solicitud;
use App\Models\Area;
use App\Models\User;
use App\Models\AuditoriaOnboarding;

echo "=== DEBUG NOTIFICACIONES ===\n\n";

// 1. Obtener último proceso
$proceso = ProcesoIngreso::latest()->first();
if (!$proceso) {
    echo "❌ No hay procesos creados\n";
    exit;
}

echo "✅ Proceso encontrado:\n";
echo "   ID: " . $proceso->id . "\n";
echo "   Código: " . $proceso->codigo . "\n";
echo "   Empleado: " . $proceso->nombre_completo . "\n";
echo "   Área ID: " . $proceso->area_id . "\n\n";

// 2. Ver solicitudes
$solicitudes = $proceso->solicitudes()->get();
echo "📋 Solicitudes (" . $solicitudes->count() . "):\n";
if ($solicitudes->count() === 0) {
    echo "   ❌ NO HAY SOLICITUDES - Este es probablemente el problema!\n";
} else {
    foreach ($solicitudes as $sol) {
        echo "   - Area: " . $sol->area?->nombre . " | Tipo: " . $sol->tipo . "\n";
    }
}
echo "\n";

// 3. Ver usuarios en TODAS las áreas de solicitudes
echo "👥 Usuarios por Área (donde hay solicitudes):\n\n";

$areasConSolicitudes = [];
foreach ($solicitudes as $sol) {
    if (!isset($areasConSolicitudes[$sol->area_id])) {
        $areasConSolicitudes[$sol->area_id] = $sol->area;
    }
}

$todosBienConfigurados = true;
foreach ($areasConSolicitudes as $areaId => $area) {
    $usuarios = User::where('area_id', $areaId)
        ->where('activo', true)
        ->get();
    
    echo "   📍 " . $area->nombre . ": ";
    if ($usuarios->count() === 0) {
        echo "❌ SIN USUARIOS (no se enviarán notificaciones)\n";
        $todosBienConfigurados = false;
    } else {
        echo "✅ " . $usuarios->count() . " usuarios\n";
        foreach ($usuarios as $user) {
            echo "      - " . $user->name . " (" . $user->email . ")\n";
        }
    }
}

echo "\n";
if ($todosBienConfigurados) {
    echo "✅ ESTÁ TODO BIEN CONFIGURADO\n";
} else {
    echo "⚠️  FALTAN USUARIOS EN ALGUNAS ÁREAS - Las notificaciones no se enviarán a esas áreas\n";
}
echo "\n";

// 4. Ver registros de auditoría
$auditorias = AuditoriaOnboarding::where('entidad', 'ProcesoIngreso')
    ->where('entidad_id', $proceso->id)
    ->get();

echo "📊 Registros de Auditoría (" . $auditorias->count() . "):\n";
if ($auditorias->count() === 0) {
    echo "   ❌ NO HAY REGISTROS - El Job no registró nada\n";
} else {
    foreach ($auditorias as $audit) {
        echo "   - Acción: " . $audit->accion . " | Usuario: " . $audit->usuario?->name . "\n";
        if ($audit->valores_nuevos) {
            echo "     Detalles: " . json_encode($audit->valores_nuevos) . "\n";
        }
    }
}

echo "\n=== FIN DEBUG ===\n";
