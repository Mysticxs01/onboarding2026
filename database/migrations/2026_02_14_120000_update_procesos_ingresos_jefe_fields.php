<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('procesos_ingresos', 'jefe_id')) {
            Schema::table('procesos_ingresos', function (Blueprint $table) {
                $table->dropForeign(['jefe_id']);
            });

            DB::statement('ALTER TABLE procesos_ingresos MODIFY jefe_id BIGINT UNSIGNED NULL');

            Schema::table('procesos_ingresos', function (Blueprint $table) {
                $table->foreign('jefe_id')->references('id')->on('users')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('procesos_ingresos', 'jefe_cargo_id')) {
            Schema::table('procesos_ingresos', function (Blueprint $table) {
                $table->foreignId('jefe_cargo_id')->nullable()->after('jefe_id')
                    ->constrained('cargos')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('procesos_ingresos', 'jefe_cargo_id')) {
            Schema::table('procesos_ingresos', function (Blueprint $table) {
                $table->dropForeign(['jefe_cargo_id']);
                $table->dropColumn('jefe_cargo_id');
            });
        }

        if (Schema::hasColumn('procesos_ingresos', 'jefe_id')) {
            Schema::table('procesos_ingresos', function (Blueprint $table) {
                $table->dropForeign(['jefe_id']);
            });

            DB::statement('ALTER TABLE procesos_ingresos MODIFY jefe_id BIGINT UNSIGNED NOT NULL');

            Schema::table('procesos_ingresos', function (Blueprint $table) {
                $table->foreign('jefe_id')->references('id')->on('users');
            });
        }
    }
};
