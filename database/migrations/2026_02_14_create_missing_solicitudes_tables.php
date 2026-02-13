<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar campos a detalles_tecnologia si no existen
        if (Schema::hasTable('detalles_tecnologia')) {
            Schema::table('detalles_tecnologia', function (Blueprint $table) {
                if (!Schema::hasColumn('detalles_tecnologia', 'necesita_computador')) {
                    $table->boolean('necesita_computador')->default(false)->after('solicitud_id');
                }
                if (!Schema::hasColumn('detalles_tecnologia', 'gama_computador')) {
                    $table->enum('gama_computador', ['Básica', 'Media', 'Premium'])->nullable()->after('necesita_computador');
                }
                if (!Schema::hasColumn('detalles_tecnologia', 'credenciales_plataformas')) {
                    $table->text('credenciales_plataformas')->nullable()->after('gama_computador');
                }
            });
        }

        // Agregar campos a detalles_uniformes si no existen
        if (Schema::hasTable('detalles_uniformes')) {
            Schema::table('detalles_uniformes', function (Blueprint $table) {
                if (!Schema::hasColumn('detalles_uniformes', 'necesita_dotacion')) {
                    $table->boolean('necesita_dotacion')->default(false)->after('solicitud_id');
                }
                if (!Schema::hasColumn('detalles_uniformes', 'justificacion_no_dotacion')) {
                    $table->text('justificacion_no_dotacion')->nullable()->after('necesita_dotacion');
                }
                if (!Schema::hasColumn('detalles_uniformes', 'talla_camiseta')) {
                    $table->string('talla_camiseta')->nullable()->after('talla_camisa');
                }
            });
        }

        // Crear tabla detalles_bienes
        Schema::create('detalles_bienes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')
                  ->constrained('solicitudes')
                  ->onDelete('cascade');
            
            $table->json('bienes_requeridos')->nullable(); // Array de items seleccionados
            $table->text('observaciones')->nullable();
            
            $table->timestamps();
        });

        // Crear tabla pivot para solicitud-curso (many-to-many)
        Schema::create('solicitud_curso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')
                  ->constrained('solicitudes')
                  ->onDelete('cascade');
            
            $table->foreignId('curso_id')
                  ->constrained('cursos')
                  ->onDelete('cascade');
            
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['solicitud_id', 'curso_id']);
        });

        // Agregar puesto_trabajo_id a solicitudes
        if (Schema::hasTable('solicitudes')) {
            Schema::table('solicitudes', function (Blueprint $table) {
                if (!Schema::hasColumn('solicitudes', 'puesto_trabajo_id')) {
                    $table->foreignId('puesto_trabajo_id')
                          ->nullable()
                          ->after('area_id')
                          ->constrained('puestos')
                          ->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud_curso');
        Schema::dropIfExists('detalles_bienes');
        
        if (Schema::hasTable('solicitudes')) {
            Schema::table('solicitudes', function (Blueprint $table) {
                if (Schema::hasColumn('solicitudes', 'puesto_trabajo_id')) {
                    $table->dropForeign(['puesto_trabajo_id']);
                    $table->dropColumn('puesto_trabajo_id');
                }
            });
        }

        if (Schema::hasTable('detalles_uniformes')) {
            Schema::table('detalles_uniformes', function (Blueprint $table) {
                if (Schema::hasColumn('detalles_uniformes', 'talla_camiseta')) {
                    $table->dropColumn('talla_camiseta');
                }
                if (Schema::hasColumn('detalles_uniformes', 'justificacion_no_dotacion')) {
                    $table->dropColumn('justificacion_no_dotacion');
                }
                if (Schema::hasColumn('detalles_uniformes', 'necesita_dotacion')) {
                    $table->dropColumn('necesita_dotacion');
                }
            });
        }

        if (Schema::hasTable('detalles_tecnologia')) {
            Schema::table('detalles_tecnologia', function (Blueprint $table) {
                if (Schema::hasColumn('detalles_tecnologia', 'credenciales_plataformas')) {
                    $table->dropColumn('credenciales_plataformas');
                }
                if (Schema::hasColumn('detalles_tecnologia', 'gama_computador')) {
                    $table->dropColumn('gama_computador');
                }
                if (Schema::hasColumn('detalles_tecnologia', 'necesita_computador')) {
                    $table->dropColumn('necesita_computador');
                }
            });
        }
    }
};
