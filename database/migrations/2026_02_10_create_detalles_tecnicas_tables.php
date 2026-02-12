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
        // Tabla para detalles técnicos de TI
        Schema::create('detalles_tecnologia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
            $table->foreignId('proceso_ingreso_id')->constrained('procesos_ingresos')->onDelete('cascade');
            
            $table->enum('tipo_computador', ['Portátil', 'Escritorio'])->nullable();
            $table->string('marca_computador')->nullable();
            $table->string('especificaciones')->nullable(); // RAM, procesador, etc.
            $table->text('software_requerido')->nullable(); // Licencias específicas
            $table->boolean('monitor_adicional')->default(false);
            $table->boolean('mouse_teclado')->default(true);
            
            $table->timestamps();
        });

        // Tabla para detalles de uniformes y tallas
        Schema::create('detalles_uniformes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
            $table->foreignId('proceso_ingreso_id')->constrained('procesos_ingresos')->onDelete('cascade');
            
            $table->string('talla_camisa')->nullable();
            $table->string('talla_pantalon')->nullable();
            $table->string('talla_zapatos')->nullable();
            $table->string('genero')->nullable(); // Masculino, Femenino
            $table->integer('cantidad_uniformes')->default(2);
            $table->text('observaciones')->nullable();
            
            $table->timestamps();
        });

        // Tabla para check-in y aceptación de activos
        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proceso_ingreso_id')->constrained('procesos_ingresos')->onDelete('cascade');
            
            $table->string('codigo_verificacion')->unique(); // Código para firma digital
            $table->text('activos_entregados')->nullable(); // JSON con listado
            $table->string('estado_checkin')->default('Pendiente'); // Pendiente, Completado
            
            $table->dateTime('fecha_generacion');
            $table->dateTime('fecha_confirmacion')->nullable();
            
            $table->string('email_empleado');
            $table->boolean('email_enviado')->default(false);
            $table->timestamp('email_enviado_at')->nullable();
            
            $table->text('firma_digital')->nullable(); // Base64 de la firma
            $table->string('dispositivo_confirmacion')->nullable();
            $table->string('ip_confirmacion')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkins');
        Schema::dropIfExists('detalles_uniformes');
        Schema::dropIfExists('detalles_tecnologia');
    }
};
