<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reporte_cumplimiento', function (Blueprint $table) {
            $table->id();
            
            $table->date('fecha_reporte');
            $table->foreignId('area_id')
                  ->constrained('areas')
                  ->onDelete('cascade');
            
            $table->integer('total_procesos')->default(0);
            $table->integer('procesos_completados')->default(0);
            $table->integer('procesos_retrasados')->default(0);
            $table->integer('procesos_pendientes')->default(0);
            
            $table->float('porcentaje_cumplimiento')->default(0);
            $table->float('dias_promedio_completacion')->default(0);
            
            $table->timestamps();
            
            $table->index(['area_id', 'fecha_reporte']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reporte_cumplimiento');
    }
};
