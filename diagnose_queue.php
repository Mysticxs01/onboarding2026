<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 Diagnosis: Por qué no se procesó el Job\n";
echo str_repeat('=', 80) . "\n\n";

// 1. Jobs actuales en cola
echo "1️⃣  Jobs en tabla 'jobs':\n";
$jobs = DB::table('jobs')->get();
echo "   Total: " . $jobs->count() . "\n";
foreach ($jobs as $j) {
    echo "   - Queue: {$j->queue} | Intentos: {$j->attempts}\n";
    echo "     Payload: " . substr($j->payload, 0, 100) . "...\n\n";
}

// 2. Failed jobs
echo "2️⃣  Jobs fallidos:\n";
$failedJobs = DB::table('failed_jobs')->get();
echo "   Total: " . $failedJobs->count() . "\n";
foreach ($failedJobs as $f) {
    echo "   - Queue: {$f->queue}\n";
    echo "     Exception: " . substr($f->exception, 0, 80) . "...\n\n";
}

// 3. Verificar si el Job existe  
echo "3️⃣  Verificando clase del Job:\n";
$jobClass = 'App\\Jobs\\EnviarNotificacionesProcesoJob';
if (class_exists($jobClass)) {
    echo "   ✅ La clase existe\n";
    $reflection = new ReflectionClass($jobClass);
    echo "   Archivo: " . $reflection->getFileName() . "\n";
} else {
    echo "   ❌ LA CLASE NO EXISTE - Esto es el problema!\n";
}

// 4. Ver el config de queue
echo "\n4️⃣  Config de Queue:\n";
echo "   QUEUE_CONNECTION: " . env('QUEUE_CONNECTION') . "\n";
echo "   Config value: " . config('queue.default') . "\n";

?>
