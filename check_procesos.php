<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "📋 Procesos más recientes:\n";
echo str_repeat('-', 80) . "\n";

$procesos = DB::table('procesos_ingresos')
    ->orderBy('created_at', 'desc')
    ->limit(3)
    ->get();

foreach ($procesos as $p) {
    echo "ID: {$p->id} | Código: {$p->codigo} | Creado: {$p->created_at}\n";
    
    // Contar solicitudes
    $solicitudes = DB::table('solicitudes')->where('proceso_ingreso_id', $p->id)->count();
    echo "  Solicitudes: $solicitudes\n";
    
    // Revisar auditoría para este proceso
    $auditorias = DB::table('auditoria_onboarding')
        ->where('accion', 'like', 'notificacion%')
        ->where('entidad_id', $p->id)
        ->count();
    echo "  Registros de notificación: $auditorias\n\n";
}
?>
