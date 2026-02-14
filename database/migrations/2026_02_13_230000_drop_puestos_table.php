<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('puestos');
    }

    public function down(): void
    {
        Schema::create('puestos', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->integer('fila');
            $table->integer('columna');
            $table->enum('estado', ['Disponible', 'Ocupado'])->default('Disponible');
            $table->foreignId('proceso_ingreso_id')->nullable()
                ->constrained('procesos_ingresos')
                ->onDelete('set null');
            $table->timestamps();
        });
    }
};
