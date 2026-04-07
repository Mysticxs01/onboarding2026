<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "📧 DETALLES DE NOTIFICACIONES ENVIADAS (Proceso 14)\n";
echo str_repeat('=', 80) . "\n\n";

$auditorias = DB::table('auditoria_onboarding')
    ->where('entidad_id', 14)
    ->where('accion', 'like', 'notificacion%')
    ->orderBy('created_at', 'asc')
    ->get();

$enviadas = 0;
$fallidas = 0;
$sinUsuarios = 0;

foreach ($auditorias as $a) {
    $datos = json_decode($a->valores_nuevos, true);
    
    if ($a->accion === 'notificacion_enviada') {
        $enviadas++;
        echo "✅ ENVIADA\n";
        if (isset($datos['area_id'])) {
            $nombreArea = DB::table('areas')->find($datos['area_id'])?->nombre ?? 'Desconocida';
            echo "   Área: $nombreArea\n";
        }
        if (isset($datos['destinatario'])) {
            echo "   To: {$datos['destinatario']}\n";
        }
    } elseif ($a->accion === 'notificacion_fallida') {
        $fallidas++;
        echo "❌ FALLIDA\n";
        if (isset($datos['area_nombre'])) {
            echo "   Área: {$datos['area_nombre']}\n";
            echo "   Motivo: No hay usuarios activos\n";
            $sinUsuarios++;
        } elseif (isset($datos['error'])) {
            echo "   Error: " . substr($datos['error'], 0, 60) . "...\n";
        }
    }
    echo "\n";
}

echo str_repeat('=', 80) . "\n";
echo "RESUMEN:\n";
echo "✅ Enviadas: $enviadas\n";
echo "❌ Fallidas (Total): $fallidas\n";
echo "   - Sin usuarios activos: $sinUsuarios\n";
echo "   - Con error: " . ($fallidas - $sinUsuarios) . "\n";
?>
