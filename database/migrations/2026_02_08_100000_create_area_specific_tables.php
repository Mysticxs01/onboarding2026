<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('puestos_trabajo', function (Blueprint $table) {
            $table->id();
            $table->string('numero_puesto')->unique();
            $table->integer('piso')->default(1);
            $table->string('seccion')->nullable();
            $table->integer('capacidad')->default(1);
            $table->enum('estado', ['Disponible', 'Asignado', 'En Mantenimiento', 'Bloqueado'])->default('Disponible');
            $table->integer('ubicacion_x')->nullable();
            $table->integer('ubicacion_y')->nullable();
            $table->text('descripcion')->nullable();
            $table->json('equipamiento')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('solicitudes_servicios_generales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
            $table->foreignId('puesto_trabajo_id')->nullable()->constrained('puestos_trabajo')->onDelete('set null');
            $table->boolean('carnet_generado')->default(false);
            $table->string('numero_carnet')->nullable();
            $table->datetime('fecha_carnetizacion')->nullable();
            $table->text('observaciones')->nullable();
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
    }

    public function down(): void
    {
        Schema::dropIfExists('elementos_proteccion');
        Schema::dropIfExists('items_inmobiliario');
        Schema::dropIfExists('planes_capacitacion');
        Schema::dropIfExists('solicitudes_servicios_generales');
        Schema::dropIfExists('puestos_trabajo');
    }
};
