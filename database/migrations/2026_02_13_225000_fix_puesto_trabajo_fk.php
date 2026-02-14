<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes', 'puesto_trabajo_id')) {
                $table->dropForeign(['puesto_trabajo_id']);
            }
        });

        Schema::table('solicitudes', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes', 'puesto_trabajo_id')) {
                $table->foreign('puesto_trabajo_id')
                    ->references('id')
                    ->on('puestos_trabajo')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes', 'puesto_trabajo_id')) {
                $table->dropForeign(['puesto_trabajo_id']);
                $table->foreign('puesto_trabajo_id')
                    ->references('id')
                    ->on('puestos')
                    ->nullOnDelete();
            }
        });
    }
};
