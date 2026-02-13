<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procesos_ingresos', function (Blueprint $table) {
            // Agregar campos faltantes si no existen
            if (!Schema::hasColumn('procesos_ingresos', 'email')) {
                $table->string('email')->nullable()->after('documento');
            }
            if (!Schema::hasColumn('procesos_ingresos', 'telefono')) {
                $table->string('telefono')->nullable()->after('email');
            }
            if (!Schema::hasColumn('procesos_ingresos', 'fecha_esperada_finalizacion')) {
                $table->date('fecha_esperada_finalizacion')->nullable()->after('fecha_ingreso');
            }
        });
    }

    public function down(): void
    {
        Schema::table('procesos_ingresos', function (Blueprint $table) {
            $table->dropColumn(['email', 'telefono', 'fecha_esperada_finalizacion']);
        });
    }
};
