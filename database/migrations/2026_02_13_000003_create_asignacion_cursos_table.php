<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignacion_cursos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('proceso_ingreso_id')
                  ->constrained('procesos_ingresos')
                  ->onDelete('cascade');
            
            $table->foreignId('curso_id')
                  ->constrained('cursos')
                  ->onDelete('cascade');
            
            $table->date('fecha_asignacion');
            $table->date('fecha_limite')->nullable();
            $table->date('fecha_completacion')->nullable();
            
            $table->enum('estado', [
                'Asignado',
                'En Progreso',
                'Completado',
                'Vencido',
                'Cancelado'
            ])->default('Asignado');
            
            $table->integer('calificacion')->nullable();
            $table->string('certificado_url')->nullable();
            
            $table->foreignId('asignado_por_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            $table->foreignId('responsable_validacion_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            $table->text('observaciones')->nullable();
            
            $table->timestamps();
            
            $table->index('proceso_ingreso_id');
            $table->index('curso_id');
            $table->index('estado');
            $table->unique(['proceso_ingreso_id', 'curso_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignacion_cursos');
    }
};
