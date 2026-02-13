<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            
            $table->string('codigo')->unique();
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            
            $table->enum('categoria', [
                'Obligatorio',
                'Opcional',
                'Cumplimiento Normativo',
                'Desarrollo',
                'Liderazgo'
            ])->default('Opcional');
            
            $table->enum('modalidad', [
                'Presencial',
                'Virtual',
                'Híbrida'
            ])->default('Virtual');
            
            $table->integer('duracion_horas');
            $table->text('objetivo')->nullable();
            $table->longText('contenido')->nullable();
            
            $table->foreignId('area_responsable_id')
                  ->nullable()
                  ->constrained('areas')
                  ->onDelete('set null');
            
            $table->decimal('costo', 10, 2)->default(0);
            $table->boolean('requiere_certificado')->default(true);
            $table->integer('vigencia_meses')->nullable();
            
            $table->boolean('activo')->default(true);
            $table->softDeletes();
            
            $table->timestamps();
            
            $table->index('codigo');
            $table->index('categoria');
            $table->index(['activo', 'categoria']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
