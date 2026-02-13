<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('historico_posiciones', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('posicion_id')->constrained('posiciones')->cascadeOnDelete();
            $table->foreignId('usuario_anterior_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('usuario_nuevo_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Información del cambio
            $table->enum('tipo_movimiento', [
                'Creacion',
                'Asignacion',
                'Liberacion',
                'Cambio Usuario',
                'Bloqueo',
                'Desbloqueo',
                'Cambio Ubicacion'
            ]);
            
            $table->text('razon')->nullable();
            $table->string('realizado_por')->nullable()->comment('Usuario o sistema que realizó el cambio');
            
            // Datos anteriores y nuevos
            $table->json('datos_anteriores')->nullable();
            $table->json('datos_nuevos')->nullable();
            
            // Metadatos
            $table->timestamps();
            
            // Índices
            $table->index('posicion_id');
            $table->index('usuario_anterior_id');
            $table->index('usuario_nuevo_id');
            $table->index('tipo_movimiento');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_posiciones');
    }
};
