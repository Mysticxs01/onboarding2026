<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curso_x_cargo', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('curso_id')
                  ->constrained('cursos')
                  ->onDelete('cascade');
            
            $table->foreignId('cargo_id')
                  ->constrained('cargos')
                  ->onDelete('cascade');
            
            $table->boolean('es_obligatorio')->default(false);
            $table->integer('orden_secuencia')->default(0);
            
            $table->date('fecha_desde')->nullable();
            $table->date('fecha_hasta')->nullable();
            
            $table->timestamps();
            
            $table->unique(['curso_id', 'cargo_id']);
            $table->index(['cargo_id', 'es_obligatorio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_x_cargo');
    }
};
