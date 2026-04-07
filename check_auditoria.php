<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "📊 Verificando registros de auditoría:\n";
echo str_repeat('-', 80) . "\n";

// Obtener estructura de la tabla
$columns = DB::select("DESCRIBE auditoria_onboarding");
echo "Columnas de auditoria_onboarding:\n";
foreach ($columns as $col) {
    echo "  - {$col->Field} ({$col->Type})\n";
}

echo "\n";

// Obtener últimos registros
$auditorias = DB::table('auditoria_onboarding')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

echo "Últimos 5 registros:\n";
foreach ($auditorias as $a) {
    echo "\nID: {$a->id}\n";
    echo "Acción: {$a->accion}\n";
    echo "Entidad: {$a->entidad}\n";
    echo "Entidad ID: {$a->entidad_id}\n";
    echo "Valores Nuevos: " . substr($a->valores_nuevos ?? 'null', 0, 80) . "\n";
}

if ($auditorias->isEmpty()) {
    echo "⏳ No hay registros aún.\n";
}
?>
