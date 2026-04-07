<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \Illuminate\Support\Facades\DB::table('users')
    ->where('activo', true)
    ->select('id', 'name', 'email', 'email_notificaciones')
    ->get();

echo "Usuarios activos y sus emails de notificación:\n";
echo str_repeat('-', 80) . "\n";
foreach ($users as $user) {
    echo "ID: {$user->id} | Nombre: {$user->name}\n";
    echo "  Email (credenciales): {$user->email}\n";
    echo "  Email (notificaciones): {$user->email_notificaciones}\n";
    echo "\n";
}
echo "Total usuarios activos: " . $users->count() . "\n";
?>
