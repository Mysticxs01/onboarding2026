<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('detalles_tecnologia')) {
            Schema::create('detalles_tecnologia', function (Blueprint $table) {
                $table->id();
                $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
                $table->foreignId('proceso_ingreso_id')->constrained('procesos_ingresos')->onDelete('cascade');

                $table->enum('tipo_computador', ['Portatil', 'Escritorio'])->nullable();
                $table->string('marca_computador')->nullable();
                $table->string('especificaciones')->nullable();
                $table->text('software_requerido')->nullable();
                $table->boolean('monitor_adicional')->default(false);
                $table->boolean('mouse_teclado')->default(true);

                $table->timestamps();
            });
        }

        if (!Schema::hasTable('detalles_uniformes')) {
            Schema::create('detalles_uniformes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
                $table->foreignId('proceso_ingreso_id')->constrained('procesos_ingresos')->onDelete('cascade');

                $table->string('talla_camisa')->nullable();
                $table->string('talla_pantalon')->nullable();
                $table->string('talla_zapatos')->nullable();
                $table->string('genero')->nullable();
                $table->integer('cantidad_uniformes')->default(2);
                $table->text('observaciones')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_uniformes');
        Schema::dropIfExists('detalles_tecnologia');
    }
};
