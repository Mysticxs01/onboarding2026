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
        Schema::create('checkin_accesos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('area_id')->nullable()->constrained('areas')->onDelete('set null');
            $table->date('fecha_acceso');
            $table->time('hora_acceso');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('dispositivo_tipo')->nullable(); // Mobile, Tablet, Escritorio
            $table->string('navegador')->nullable(); // Chrome, Firefox, Safari, etc
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->text('nota')->nullable();
            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index('usuario_id');
            $table->index('area_id');
            $table->index('fecha_acceso');
            $table->index(['usuario_id', 'fecha_acceso']);
            $table->index(['area_id', 'fecha_acceso']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkin_accesos');
    }
};
