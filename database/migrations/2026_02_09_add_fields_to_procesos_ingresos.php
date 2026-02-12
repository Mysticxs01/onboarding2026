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
        Schema::table('procesos_ingresos', function (Blueprint $table) {
            // Para mantener un histórico de observaciones
            $table->text('observaciones')->nullable()->after('estado');
            $table->dateTime('fecha_cancelacion')->nullable()->after('observaciones');
            $table->dateTime('fecha_finalizacion')->nullable()->after('fecha_cancelacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procesos_ingresos', function (Blueprint $table) {
            $table->dropColumn(['observaciones', 'fecha_cancelacion', 'fecha_finalizacion']);
        });
    }
};
