<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria_onboarding', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('usuario_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            $table->enum('accion', [
                'create',
                'update',
                'delete',
                'view',
                'export',
                'anular'
            ]);
            
            $table->string('entidad');
            $table->unsignedBigInteger('entidad_id');
            
            $table->json('valores_anteriores')->nullable();
            $table->json('valores_nuevos')->nullable();
            
            $table->text('motivo')->nullable();
            
            $table->string('ip_origin')->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            $table->index('usuario_id');
            $table->index(['entidad', 'entidad_id']);
            $table->index('accion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_onboarding');
    }
};
