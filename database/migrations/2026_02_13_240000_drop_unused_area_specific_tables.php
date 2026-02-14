<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Elimina tablas que fueron creadas en migraciones anteriores pero nunca implementadas.
     * Estas tablas fueron reemplazadas por detalles_tecnologia, detalles_uniformes, y detalles_bienes.
     */
    public function up(): void
    {
        // Desactivar validación de FK temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Eliminar tablas obsoletas que nunca se usaron
        Schema::dropIfExists('elementos_proteccion');
        Schema::dropIfExists('items_inmobiliario');
        Schema::dropIfExists('planes_capacitacion');
        
        // Reactivar validación de FK
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recrear las tablas si se hace rollback (por compatibilidad)
        Schema::create('elementos_proteccion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
            $table->string('tipo_elemento');
            $table->text('descripcion')->nullable();
            $table->integer('cantidad')->default(1);
            $table->string('talla')->nullable();
            $table->string('color')->nullable();
            $table->enum('estado', ['Pendiente de Entrega', 'Disponible para Entregar', 'Entregado', 'Sin Talla Disponible', 'Artículo Descontinuado'])->default('Pendiente de Entrega');
            $table->boolean('entregado')->default(false);
            $table->datetime('fecha_entrega')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('items_inmobiliario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
            $table->string('tipo_item');
            $table->text('descripcion')->nullable();
            $table->integer('cantidad')->default(1);
            $table->enum('estado', ['Pendiente', 'Disponible', 'Asignado', 'Entregado', 'No Disponible'])->default('Pendiente');
            $table->text('observaciones')->nullable();
            $table->string('entregado_por')->nullable();
            $table->datetime('fecha_entrega')->nullable();
            $table->timestamps();
        });

        Schema::create('planes_capacitacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
            $table->foreignId('cargo_id')->constrained('cargos')->onDelete('cascade');
            $table->string('titulo_plan');
            $table->text('descripcion')->nullable();
            $table->integer('duracion_horas')->default(40);
            $table->datetime('fecha_inicio_estimada')->nullable();
            $table->datetime('fecha_fin_estimada')->nullable();
            $table->json('modulos')->nullable();
            $table->string('responsable_capacitacion')->nullable();
            $table->enum('estado', ['Diseño', 'Programado', 'En Ejecución', 'Completado', 'Cancelado'])->default('Diseño');
            $table->boolean('email_enviado')->default(false);
            $table->datetime('fecha_email_enviado')->nullable();
            $table->timestamps();
        });
    }
};
