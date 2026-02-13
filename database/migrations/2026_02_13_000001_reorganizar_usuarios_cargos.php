<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Reorganización de la estructura de usuarios y cargos:
     * - Crear tabla maestro_cargos con TODOS los cargos
     * - Actualizar tabla cargos para solo vacantes
     * - Actualizar tabla users con relación correcta a cargo_id
     */
    public function up(): void
    {
        // 1. Crear tabla maestro_cargos (referencia completa de todos los cargos)
        Schema::create('maestro_cargos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique()->comment('Nombre del cargo');
            $table->foreignId('area_id')->constrained('areas')->onDelete('restrict')->comment('Área a la que pertenece');
            $table->text('descripcion')->nullable()->comment('Descripción del cargo');
            $table->integer('nivel_jerarquico')->nullable()->comment('Nivel en la estructura (1=entrada, 5=gerencia)');
            $table->boolean('es_puesto_entrada')->default(false)->comment('¿Acepta nuevos empleados?');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index('area_id');
            $table->index('es_puesto_entrada');
            $table->index('nivel_jerarquico');
        });

        // 2. Actualizar tabla cargos (solo para puestos con vacantes)
        Schema::table('cargos', function (Blueprint $table) {
            // Agregar campos para controlar vacantes
            if (!Schema::hasColumn('cargos', 'vacantes_disponibles')) {
                $table->smallInteger('vacantes_disponibles')->default(0)->after('area_id')
                    ->comment('Cantidad de vacantes disponibles para este cargo');
            }
            
            if (!Schema::hasColumn('cargos', 'activo')) {
                $table->boolean('activo')->default(true)->after('vacantes_disponibles')
                    ->comment('¿Cargo activo para nuevos ingresos?');
            }
            
            if (!Schema::hasColumn('cargos', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('activo')
                    ->comment('Descripción detallada del cargo');
            }
        });

        // 3. Actualizar tabla users con campos adicionales
        Schema::table('users', function (Blueprint $table) {
            // Agregar campos para identificar responsables de onboarding
            if (!Schema::hasColumn('users', 'rol_onboarding')) {
                $table->enum('rol_onboarding', ['admin', 'jefe_area', 'coordinador', 'revisor', 'operador'])
                    ->nullable()
                    ->after('cargo_id')
                    ->comment('Rol en el proceso de onboarding');
            }
            
            if (!Schema::hasColumn('users', 'puede_aprobar_solicitudes')) {
                $table->boolean('puede_aprobar_solicitudes')->default(false)->after('rol_onboarding')
                    ->comment('¿Puede aprobar solicitudes de nuevo ingreso?');
            }

            if (!Schema::hasColumn('users', 'jefe_directo_id')) {
                $table->foreignId('jefe_directo_id')
                    ->nullable()
                    ->constrained('users')
                    ->onDelete('set null')
                    ->after('puede_aprobar_solicitudes')
                    ->comment('Usuario que es el jefe directo');
            }

            // Index para búsquedas frecuentes
            $table->index('rol_onboarding');
            $table->index('puede_aprobar_solicitudes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir cambios en users
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['rol_onboarding']);
            $table->dropIndex(['puede_aprobar_solicitudes']);
            $table->dropForeignKeyIfExists(['jefe_directo_id']);
            $table->dropColumn(['rol_onboarding', 'puede_aprobar_solicitudes', 'jefe_directo_id']);
        });

        // Revertir cambios en cargos
        Schema::table('cargos', function (Blueprint $table) {
            if (Schema::hasColumn('cargos', 'vacantes_disponibles')) {
                $table->dropColumn('vacantes_disponibles');
            }
            if (Schema::hasColumn('cargos', 'activo')) {
                $table->dropColumn('activo');
            }
            if (Schema::hasColumn('cargos', 'descripcion')) {
                $table->dropColumn('descripcion');
            }
        });

        // Eliminar tabla maestro_cargos
        Schema::dropIfExists('maestro_cargos');
    }
};
