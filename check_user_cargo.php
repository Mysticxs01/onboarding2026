<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$usuarios = User::where('activo', true)->get();

echo "Usuarios con cargo_id y area_id:\n";
echo "=================================\n";

foreach ($usuarios as $user) {
    echo "Usuario: {$user->name}\n";
    echo "  - cargo_id: {$user->cargo_id}\n";
    echo "  - area_id: {$user->area_id}\n";
    echo "  - posicion_id: {$user->posicion_id}\n";
    echo "\n";
}

echo "Total usuarios activos: " . User::where('activo', true)->count() . "\n";
