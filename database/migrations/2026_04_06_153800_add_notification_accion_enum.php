<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modificar la columna accion del ENUM para agregar los nuevos valores
        // MySql: Necesitamos alterar el tipo ENUM
        DB::statement("ALTER TABLE auditoria_onboarding MODIFY COLUMN accion ENUM('create','update','delete','view','export','anular','notificacion_enviada','notificacion_fallida','notificacion_reintentada')");
    }

    public function down(): void
    {
        // Revertir al ENUM anterior
        DB::statement("ALTER TABLE auditoria_onboarding MODIFY COLUMN accion ENUM('create','update','delete','view','export','anular')");
    }
};
