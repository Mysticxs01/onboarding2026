<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Posicion;
use App\Models\Cargo;
use App\Models\Area;

echo "Asignando posiciones ocupadas a usuarios...\n";
echo "==========================================\n\n";

$usuarios = User::where('activo', true)->get();

$contador = 0;

foreach ($usuarios as $usuario) {
    if ($usuario->area_id) {
        $area = Area::find($usuario->area_id);
        
        if ($area) {
            // Obtener un cargo de esa área (el primero disponible)
            $cargo = Cargo::where('area_id', $usuario->area_id)->first();
            
            if ($cargo) {
                // Crear posición ocupada para este usuario
                $posicion = Posicion::create([
                    'cargo_id' => $cargo->id,
                    'area_id' => $usuario->area_id,
                    'usuario_id' => $usuario->id,
                    'sucursal' => 'Sede Principal',
                    'estado' => 'Ocupada',
                    'fecha_disponible_desde' => $usuario->created_at,
                    'observaciones' => "Posición ocupada por {$usuario->name}",
                ]);
                
                // Asignar la posición al usuario
                $usuario->update(['posicion_id' => $posicion->id]);
                
                echo "[✓] Usuario: {$usuario->name}\n";
                echo "    - Área: {$area->nombre}\n";
                echo "    - Cargo: {$cargo->nombre}\n";
                echo "    - Posición ID: {$posicion->id}\n\n";
                
                $contador++;
            }
        }
    }
}

echo "==========================================\n";
echo "✅ {$contador} posiciones ocupadas asignadas\n";
echo "==========================================\n";

// Mostrar resumen final
$posiciones = Posicion::selectRaw('estado, COUNT(*) as total')
    ->groupBy('estado')
    ->get();

echo "\nResumen Final de Posiciones:\n";
echo "============================\n";
foreach ($posiciones as $p) {
    echo "{$p->estado}: {$p->total}\n";
}

echo "\nTotal: " . Posicion::count() . " posiciones\n";
