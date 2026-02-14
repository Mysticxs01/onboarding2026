<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('detalles_tecnologia')) {
            Schema::table('detalles_tecnologia', function (Blueprint $table) {
                if (!Schema::hasColumn('detalles_tecnologia', 'proceso_ingreso_id')) {
                    $table->foreignId('proceso_ingreso_id')->nullable()
                        ->after('solicitud_id')
                        ->constrained('procesos_ingresos')
                        ->nullOnDelete();
                }
                if (!Schema::hasColumn('detalles_tecnologia', 'necesita_computador')) {
                    $table->boolean('necesita_computador')->default(false)->after('proceso_ingreso_id');
                }
                if (!Schema::hasColumn('detalles_tecnologia', 'gama_computador')) {
                    $table->string('gama_computador')->nullable()->after('necesita_computador');
                }
                if (!Schema::hasColumn('detalles_tecnologia', 'credenciales_plataformas')) {
                    $table->text('credenciales_plataformas')->nullable()->after('gama_computador');
                }
            });
        }

        if (Schema::hasTable('detalles_uniformes')) {
            Schema::table('detalles_uniformes', function (Blueprint $table) {
                if (!Schema::hasColumn('detalles_uniformes', 'proceso_ingreso_id')) {
                    $table->foreignId('proceso_ingreso_id')->nullable()
                        ->after('solicitud_id')
                        ->constrained('procesos_ingresos')
                        ->nullOnDelete();
                }
                if (!Schema::hasColumn('detalles_uniformes', 'necesita_dotacion')) {
                    $table->boolean('necesita_dotacion')->default(true)->after('proceso_ingreso_id');
                }
                if (!Schema::hasColumn('detalles_uniformes', 'talla_camiseta')) {
                    $table->string('talla_camiseta')->nullable()->after('talla_pantalon');
                }
                if (!Schema::hasColumn('detalles_uniformes', 'justificacion_no_dotacion')) {
                    $table->text('justificacion_no_dotacion')->nullable()->after('talla_camiseta');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('detalles_tecnologia')) {
            Schema::table('detalles_tecnologia', function (Blueprint $table) {
                $columns = [
                    'necesita_computador',
                    'gama_computador',
                    'credenciales_plataformas',
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('detalles_tecnologia', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('detalles_uniformes')) {
            Schema::table('detalles_uniformes', function (Blueprint $table) {
                $columns = [
                    'necesita_dotacion',
                    'talla_camiseta',
                    'justificacion_no_dotacion',
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('detalles_uniformes', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
