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
    Schema::create('procesos_ingresos', function (Blueprint $table) {
        $table->id();

        $table->string('codigo')->unique(); // autogenerado
        $table->string('nombre_completo');
        $table->string('tipo_documento');
        $table->string('documento')->unique();

        $table->foreignId('cargo_id')->constrained();
        $table->foreignId('area_id')->constrained();

        $table->date('fecha_ingreso');

        $table->foreignId('jefe_id')
              ->constrained('users');

        $table->enum('estado', [
            'Pendiente',
            'En Proceso',
            'Finalizado',
            'Cancelado'
        ])->default('Pendiente');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proceso_ingresos');
    }
};
