<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('solicitudes', function (Blueprint $table) {
        $table->id();

        $table->foreignId('proceso_ingreso_id')
            ->constrained('procesos_ingresos')
            ->onDelete('cascade');

        $table->foreignId('area_id')->constrained();

        $table->string('tipo'); // Dotación, Tecnología, etc.

        $table->date('fecha_limite');

        $table->enum('estado', [
            'Pendiente',
            'En Proceso',
            'Finalizada'
        ])->default('Pendiente');

        $table->text('observaciones')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
