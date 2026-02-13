<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AsignacionCursoController;
use App\Http\Controllers\RutaFormacionController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ReporteController;

// MÓDULO DE FORMACIÓN Y CURSOS
Route::middleware(['auth', 'verified'])->group(function () {

    // Cursos - CRUD Completo
    Route::resource('cursos', CursoController::class);
    Route::post('/cursos/{curso}/asignar-cargo', [CursoController::class, 'asignarACargo'])
        ->name('cursos.asignar-cargo');
    Route::post('/cursos/exportar', [CursoController::class, 'exportar'])
        ->name('cursos.exportar');

    // Asignaciones de Cursos - Panel RRHH
    Route::prefix('asignaciones')->name('asignaciones.')->group(function () {
        Route::get('/', [AsignacionCursoController::class, 'index'])
            ->name('index');
        Route::get('/panel', [AsignacionCursoController::class, 'panel'])
            ->name('panel');
        Route::get('/{procesoIngreso}/asignar', [AsignacionCursoController::class, 'asignar'])
            ->name('asignar');
        Route::post('/{procesoIngreso}/guardar', [AsignacionCursoController::class, 'guardar'])
            ->name('guardar');
        Route::get('/{asignacion}', [AsignacionCursoController::class, 'show'])
            ->name('show');
        Route::get('/{asignacion}/validar', [AsignacionCursoController::class, 'validar'])
            ->name('validar');
        Route::post('/{asignacion}/marcar-completada', [AsignacionCursoController::class, 'marcarCompletada'])
            ->name('marcar-completada');
        Route::post('/{asignacion}/marcar-progreso', [AsignacionCursoController::class, 'marcarEnProgreso'])
            ->name('marcar-progreso');
        Route::post('/{asignacion}/cancelar', [AsignacionCursoController::class, 'cancelar'])
            ->name('cancelar');
    });

    // Rutas de Formación
    Route::resource('rutas', RutaFormacionController::class);
    Route::post('/rutas/{ruta}/agregar-curso', [RutaFormacionController::class, 'agregarCurso'])
        ->name('rutas.agregar-curso');
    Route::post('/rutas/{ruta}/remover-curso', [RutaFormacionController::class, 'removerCurso'])
        ->name('rutas.remover-curso');

    // MÓDULO DE REPORTES
    Route::prefix('reportes')->name('reportes.')->group(function () {
        
        // Dashboard Ejecutivo
        Route::get('/dashboard', [ReporteController::class, 'dashboard'])
            ->name('dashboard');

        // Cumplimiento por Área
        Route::get('/cumplimiento-por-area', [ReporteController::class, 'cumplimientoPorArea'])
            ->name('cumplimiento-por-area');

        // Formación por Curso
        Route::get('/formacion-por-curso', [ReporteController::class, 'formacionPorCurso'])
            ->name('formacion-por-curso');

        // Asignaciones Pendientes
        Route::get('/asignaciones-pendientes', [ReporteController::class, 'asignacionesPendientes'])
            ->name('asignaciones-pendientes');

        // Retrasos en Formación
        Route::get('/retrasos-formacion', [ReporteController::class, 'retrasosFormacion'])
            ->name('retrasos-formacion');

        // Costos de Formación
        Route::get('/costos-formacion', [ReporteController::class, 'costosFormacion'])
            ->name('costos-formacion');

        // Exportar datos
        Route::post('/exportar', [ReporteController::class, 'exportarDatos'])
            ->name('exportar');
    });

    // MÓDULO DE AUDITORÍA
    Route::prefix('auditoria')->name('auditoria.')->group(function () {
        
        Route::get('/', [AuditoriaController::class, 'index'])
            ->name('index');
        
        Route::get('/{auditoria}', [AuditoriaController::class, 'show'])
            ->name('show');
        
        Route::get('/proceso/{proceso}', [AuditoriaController::class, 'porProceso'])
            ->name('por-proceso');
        
        Route::get('/reporte/por-area', [AuditoriaController::class, 'reportePorArea'])
            ->name('reporte-por-area');
        
        Route::post('/exportar', [AuditoriaController::class, 'exportar'])
            ->name('exportar');
    });
});
