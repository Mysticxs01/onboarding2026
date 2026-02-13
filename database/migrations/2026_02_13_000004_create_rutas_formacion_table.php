<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rutas_formacion', function (Blueprint $table) {
            $table->id();
            
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            
            $table->foreignId('cargo_id')
                  ->nullable()
                  ->constrained('cargos')
                  ->onDelete('set null');
            
            $table->foreignId('area_id')
                  ->nullable()
                  ->constrained('areas')
                  ->onDelete('set null');
            
            $table->string('version')->default('1.0');
            $table->boolean('activa')->default(true);
            
            $table->integer('duracion_total_horas')->default(0);
            $table->date('fecha_vigencia')->nullable();
            
            $table->foreignId('responsable_rrhh_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['cargo_id', 'activa']);
            $table->index(['area_id', 'activa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rutas_formacion');
    }
};
