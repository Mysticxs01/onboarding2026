<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$job = DB::table('failed_jobs')->latest('id')->first();
if ($job) {
    echo "=== ÚLTIMO JOB FALLIDO ===\n\n";
    echo "ID: " . $job->id . "\n";
    echo "Tipo: " . $job->queue . "\n\n";
    
    echo "EXCEPCIÓN:\n";
    echo $job->exception . "\n";
} else {
    echo "No hay jobs fallidos\n";
}
