<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Posicion;

$total = Posicion::count();
$libres = Posicion::where('estado', 'Libre')->count();
$ocupadas = Posicion::where('estado', 'Ocupada')->count();
$bloqueadas = Posicion::where('estado', 'Bloqueada')->count();

echo "======================================\n";
echo "VERIFICACIÓN DE POSICIONES\n";
echo "======================================\n";
echo "Total de posiciones: " . $total . "\n";
echo "Posiciones libres: " . $libres . "\n";
echo "Posiciones ocupadas: " . $ocupadas . "\n";
echo "Posiciones bloqueadas: " . $bloqueadas . "\n";
echo "======================================\n";

// Mostrar algunas posiciones libres
echo "\nEjemplos de posiciones libres:\n";
$ejemplos = Posicion::where('estado', 'Libre')
    ->with(['cargo', 'area'])
    ->limit(5)
    ->get();

foreach ($ejemplos as $pos) {
    echo "- Cargo: " . $pos->cargo->nombre . " | Área: " . $pos->area->nombre . "\n";
}

echo "\n✅ Verificación completada exitosamente!\n";
