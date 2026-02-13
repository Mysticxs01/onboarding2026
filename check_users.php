<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$usuarios = User::count();
echo "Total de usuarios: " . $usuarios . "\n";

// Mostrar algunos usuarios
$users = User::limit(5)->get();
foreach ($users as $user) {
    echo "- " . $user->name . "\n";
}
