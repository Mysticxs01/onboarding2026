<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Desactivar validación de FK temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Eliminar tablas innecesarias/duplicadas
        Schema::dropIfExists('historico_posiciones');
        Schema::dropIfExists('posiciones');
        Schema::dropIfExists('maestro_cargos');
        Schema::dropIfExists('solicitudes_servicios_generales');
        Schema::dropIfExists('historico_posicion');
        
        // Reactivar validación de FK
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Migrations irreversibles - no revertir en PROD
    }
};
