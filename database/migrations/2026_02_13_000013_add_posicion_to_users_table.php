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
        Schema::table('users', function (Blueprint $table) {
            // Agregar nuevos campos si no existen
            if (!Schema::hasColumn('users', 'posicion_id')) {
                $table->foreignId('posicion_id')->nullable()
                      ->after('id')
                      ->constrained('posiciones')->nullOnDelete();
            }
            
            if (!Schema::hasColumn('users', 'numero_documento')) {
                $table->string('numero_documento', 20)->nullable()->unique()->after('email');
            }
            
            if (!Schema::hasColumn('users', 'telefono')) {
                $table->string('telefono', 20)->nullable()->after('numero_documento');
            }
            
            if (!Schema::hasColumn('users', 'direccion')) {
                $table->text('direccion')->nullable()->after('telefono');
            }
            
            if (!Schema::hasColumn('users', 'fecha_ingreso')) {
                $table->date('fecha_ingreso')->nullable()->after('direccion');
            }
            
            if (!Schema::hasColumn('users', 'fecha_salida')) {
                $table->date('fecha_salida')->nullable()->after('fecha_ingreso');
            }
            
            // Nota: area_id y cargo_id serán removidos manualmente después de migrar datos
            // porque dependen de posiciones
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Posicion::class);
            
            $table->dropColumn([
                'posicion_id',
                'numero_documento',
                'telefono',
                'direccion',
                'fecha_ingreso',
                'fecha_salida'
            ]);
        });
    }
};
