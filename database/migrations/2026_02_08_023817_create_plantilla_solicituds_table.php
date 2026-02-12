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
    Schema::create('plantilla_solicitudes', function (Blueprint $table) {
        $table->id();

        $table->foreignId('cargo_id')->constrained();
        $table->foreignId('area_id')->constrained();

        $table->string('tipo_solicitud');
        $table->integer('dias_maximos'); // SLA

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantilla_solicitudes');
    }
};
