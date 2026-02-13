<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruta_x_curso', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('ruta_id')
                  ->constrained('rutas_formacion')
                  ->onDelete('cascade');
            
            $table->foreignId('curso_id')
                  ->constrained('cursos')
                  ->onDelete('cascade');
            
            $table->integer('numero_secuencia')->default(0);
            $table->boolean('es_obligatorio')->default(true);
            $table->boolean('es_requisito_previo')->default(false);
            
            $table->timestamps();
            
            $table->unique(['ruta_id', 'curso_id']);
            $table->index(['ruta_id', 'numero_secuencia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruta_x_curso');
    }
};
