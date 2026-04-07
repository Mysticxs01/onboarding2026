<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "📊 Estado de Jobs en la Cola:\n";
echo str_repeat('-', 80) . "\n";

$jobs = DB::table('jobs')->get();
$failedJobs = DB::table('failed_jobs')->get();

echo "Jobs en cola: " . $jobs->count() . "\n";
echo "Jobs fallidos: " . $failedJobs->count() . "\n\n";

if ($failedJobs->isNotEmpty()) {
    echo "📋 Últimos jobs fallidos:\n";
    foreach ($failedJobs->take(3) as $job) {
        echo "  Exception: " . substr($job->exception, 0, 100) . "...\n";
    }
}

// Verificar auditoría
$auditorias = DB::table('auditoria_onboarding')
    ->where('accion', 'like', 'notificacion%')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

echo "\n📧 Últimas acciones de notificación en auditoría:\n";
echo str_repeat('-', 80) . "\n";
foreach ($auditorias as $a) {
    $datos = json_decode($a->detalles, true);
    echo "Acción: {$a->accion}\n";
    echo "Timestamp: {$a->created_at}\n";
    if (isset($datos['error'])) {
        echo "ERROR: {$datos['error']}\n";
    } elseif (isset($datos['destinatario'])) {
        echo "TO: {$datos['destinatario']}\n";
    }
    echo "\n";
}

if ($auditorias->isEmpty()) {
    echo "⏳ Aún no hay registros... El Job podría estar procesándose.\n";
}
?>
