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
        Schema::create('posiciones', function (Blueprint $table) {
            $table->id();
            
            // Relaciones principales
            $table->foreignId('cargo_id')->constrained('cargos');
            $table->foreignId('area_id')->constrained('areas');
            $table->foreignId('usuario_id')->nullable()->constrained('users');
            
            // Ubicación física
            $table->foreignId('puesto_trabajo_id')->nullable()->constrained('puestos_trabajo');
            $table->string('sucursal')->nullable()->comment('Nombre de sucursal o ubicación');
            
            // Estado
            $table->enum('estado', ['Libre', 'Ocupada', 'Bloqueada'])->default('Libre');
            $table->text('razon_bloqueo')->nullable();
            
            // Historial
            $table->dateTime('fecha_disponible_desde')->useCurrent();
            $table->dateTime('fecha_disponible_hasta')->nullable();
            $table->text('observaciones')->nullable();
            
            // Metadatos
            $table->timestamps();
            $table->softDeletes(); // Para mantener historial
            
            // Índices para búsquedas comunes
            $table->index('cargo_id');
            $table->index('area_id');
            $table->index('usuario_id');
            $table->index('estado');
            $table->index(['cargo_id', 'estado']);
            $table->index(['area_id', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posiciones');
    }
};
