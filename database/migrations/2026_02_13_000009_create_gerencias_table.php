<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gerencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('codigo')->unique()->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['activo', 'codigo']);
        });

        // Insertar las 6 gerencias de la empresa
        \Illuminate\Support\Facades\DB::table('gerencias')->insert([
            [
                'nombre' => 'Gerencia Administración',
                'codigo' => 'GA',
                'descripcion' => 'Soporte físico de las oficinas y sucursales',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Gerencia Comercial',
                'codigo' => 'GC',
                'descripcion' => 'Captación de clientes y gestión de asesores',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Gerencia Riesgo y Crédito',
                'codigo' => 'GRC',
                'descripcion' => 'Análisis de capacidad de pago y prevención de riesgo',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Gerencia Financiera',
                'codigo' => 'GF',
                'descripcion' => 'Gestión de liquidez y reportes regulatorios',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Gerencia TI',
                'codigo' => 'GTI',
                'descripcion' => 'Mantenimiento de software y seguridad de datos',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Gerencia Talento Humano',
                'codigo' => 'GTH',
                'descripcion' => 'Atracción, retención y desarrollo del personal',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('gerencias');
    }
};
