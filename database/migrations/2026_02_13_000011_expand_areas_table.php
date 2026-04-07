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
        Schema::table('areas', function (Blueprint $table) {
            // Agregar campos si no existen
            if (!Schema::hasColumn('areas', 'gerencia_id')) {
                $table->foreignId('gerencia_id')->nullable()
                      ->after('nombre')
                      ->constrained('gerencias')->nullOnDelete();
            }
            
            if (!Schema::hasColumn('areas', 'jefe_area_cargo_id')) {
                $table->foreignId('jefe_area_cargo_id')->nullable()
                      ->after('gerencia_id')
                      ->constrained('cargos')->nullOnDelete();
            }
            
            if (!Schema::hasColumn('areas', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('jefe_area_cargo_id');
            }
            
            if (!Schema::hasColumn('areas', 'activo')) {
                $table->boolean('activo')->default(true)->after('descripcion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn([
                'gerencia_id',
                'jefe_area_cargo_id',
                'descripcion',
                'activo'
            ]);
        });
    }
};
