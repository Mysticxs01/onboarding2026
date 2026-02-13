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
        Schema::table('cargos', function (Blueprint $table) {
            // Agregar campos si no existen
            if (!Schema::hasColumn('cargos', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('nombre');
            }
            
            if (!Schema::hasColumn('cargos', 'jefe_inmediato_cargo_id')) {
                $table->foreignId('jefe_inmediato_cargo_id')->nullable()
                      ->after('area_id')
                      ->constrained('cargos')->nullOnDelete();
            }
            
            if (!Schema::hasColumn('cargos', 'gerencia_id')) {
                $table->foreignId('gerencia_id')->nullable()
                      ->after('jefe_inmediato_cargo_id')
                      ->constrained('areas')->nullOnDelete();
            }
            
            if (!Schema::hasColumn('cargos', 'salario_minimo')) {
                $table->decimal('salario_minimo', 12, 2)->nullable()->after('gerencia_id');
            }
            
            if (!Schema::hasColumn('cargos', 'salario_maximo')) {
                $table->decimal('salario_maximo', 12, 2)->nullable()->after('salario_minimo');
            }
            
            if (!Schema::hasColumn('cargos', 'requerimientos_minimos')) {
                $table->text('requerimientos_minimos')->nullable()->after('salario_maximo');
            }
            
            if (!Schema::hasColumn('cargos', 'activo')) {
                $table->boolean('activo')->default(true)->after('requerimientos_minimos');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cargos', function (Blueprint $table) {
            $table->dropColumn([
                'descripcion',
                'jefe_inmediato_cargo_id',
                'gerencia_id',
                'salario_minimo',
                'salario_maximo',
                'requerimientos_minimos',
                'activo'
            ]);
        });
    }
};
