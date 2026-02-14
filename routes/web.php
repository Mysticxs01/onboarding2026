<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProcesoIngresoController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\SolicitudServiciosGeneralesController;
use App\Http\Controllers\SolicitudDotacionController;
use App\Http\Controllers\SolicitudFormacionController;
use App\Http\Controllers\SolicitudBienesController;
use App\Http\Controllers\SolicitudTecnologiaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Grupo de rutas protegidas por auth
Route::middleware('auth')->group(function () {

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // PROCESOS DE INGRESO - usamos resource para index, create, store, etc.
    Route::resource('procesos-ingreso', ProcesoIngresoController::class);

    // Rutas adicionales para gestión de procesos
    Route::get('/procesos-ingreso/{id}/cambiar-fecha', [ProcesoIngresoController::class, 'cambiarFecha'])->name('procesos-ingreso.cambiar-fecha');
    Route::post('/procesos-ingreso/{id}/actualizar-fecha', [ProcesoIngresoController::class, 'actualizarFecha'])->name('procesos-ingreso.actualizar-fecha');
    Route::get('/procesos-ingreso/{id}/cancelar', [ProcesoIngresoController::class, 'mostrarCancelacion'])->name('procesos-ingreso.mostrar-cancelacion');
    Route::post('/procesos-ingreso/{id}/cancelar', [ProcesoIngresoController::class, 'cancelar'])->name('procesos-ingreso.cancelar');
    
    // Histórico de ingresos
    Route::get('/procesos-ingreso-historico', [ProcesoIngresoController::class, 'historico'])->name('procesos-ingreso.historico');
;

    // API para obtener puestos (deprecated)

    // SOLICITUDES - Módulo completo de solicitudes por área
    Route::resource('solicitudes', SolicitudController::class);
    
    // Guardar detalles de cada tipo de solicitud (vistas específicas)
    Route::post('/solicitudes/{id}/guardar-tecnologia', [SolicitudController::class, 'guardarTecnologia'])->name('solicitudes.guardar-tecnologia');
    Route::post('/solicitudes/{id}/guardar-dotacion', [SolicitudController::class, 'guardarDotacion'])->name('solicitudes.guardar-dotacion');
    Route::post('/solicitudes/{id}/guardar-servicios-generales', [SolicitudController::class, 'guardarServiciosGenerales'])->name('solicitudes.guardar-servicios-generales');
    Route::post('/solicitudes/{id}/guardar-formacion', [SolicitudController::class, 'guardarFormacion'])->name('solicitudes.guardar-formacion');
    Route::post('/solicitudes/{id}/guardar-bienes', [SolicitudController::class, 'guardarBienes'])->name('solicitudes.guardar-bienes');
    
    // Cambiar estado de solicitudes
    Route::post('/solicitudes/{id}/cambiar-estado', [SolicitudController::class, 'cambiarEstado'])->name('solicitudes.cambiar-estado');
    
    // Check-in consolidado (cuando todas las solicitudes están finalizadas)
    Route::get('/procesos-ingreso/{id}/checkin-consolidado', [SolicitudController::class, 'checkinConsolidado'])->name('solicitudes.checkin-consolidado');
    
    // Detalles técnicos - Jefe especifica requerimientos (RUTAS ANTIGUAS - MANTENER POR COMPATIBILIDAD)
    Route::get('/solicitudes/{id}/especificar-ti', [SolicitudController::class, 'especificarTI'])->name('solicitudes.especificar-ti');
    Route::post('/solicitudes/{id}/guardar-ti', [SolicitudController::class, 'guardarTI'])->name('solicitudes.guardar-ti');
    Route::get('/solicitudes/{id}/especificar-tallas', [SolicitudController::class, 'especificarTallas'])->name('solicitudes.especificar-tallas');
    Route::post('/solicitudes/{id}/guardar-tallas', [SolicitudController::class, 'guardarTallas'])->name('solicitudes.guardar-tallas');

    // CHECK-IN - Módulo de seguimiento y recepción de activos
    Route::resource('checkins', CheckinController::class)->only(['index', 'show']);
    Route::post('/procesos-ingreso/{id}/generar-checkin', [CheckinController::class, 'generar'])->name('checkins.generar');
    Route::get('/checkins/{id}/pdf', [CheckinController::class, 'generarPDF'])->name('checkins.pdf');
    Route::get('/checkins/{id}/confirmado', [CheckinController::class, 'confirmado'])->name('checkins.confirmado');

    // ===== MÓDULO DE SOLICITUDES POR ÁREA =====
    
    // SERVICIOS GENERALES - Plano interactivo y asignación de puestos
    Route::prefix('servicios-generales')->name('servicios-generales.')->group(function () {
        Route::get('/solicitudes/{solicitud}/plano', [SolicitudServiciosGeneralesController::class, 'mostrarPlano'])->name('plano');
        Route::post('/solicitudes/{solicitud}/asignar-puesto', [SolicitudServiciosGeneralesController::class, 'asignarPuesto'])->name('asignar-puesto');
        Route::post('/solicitudes/{solicitud}/generar-carnet', [SolicitudServiciosGeneralesController::class, 'generarCarnet'])->name('generar-carnet');
        Route::get('/solicitudes/{solicitud}/detalles', [SolicitudServiciosGeneralesController::class, 'verDetalles'])->name('detalles');
        Route::post('/solicitudes/{solicitud}/liberar-puesto', [SolicitudServiciosGeneralesController::class, 'liberarPuesto'])->name('liberar-puesto');
    });

    // DOTACIÓN - EPP y Uniformes
    Route::prefix('dotacion')->name('dotacion.')->group(function () {
        Route::get('/solicitudes/{solicitud}/formulario', [SolicitudDotacionController::class, 'mostrarFormulario'])->name('formulario');
        Route::post('/solicitudes/{solicitud}/guardar', [SolicitudDotacionController::class, 'guardar'])->name('guardar');
        Route::get('/solicitudes/{solicitud}/kit-estandar', [SolicitudDotacionController::class, 'cargarKitEstandar'])->name('kit-estandar');
        Route::post('/elementos-proteccion/{elemento}/marcar-entregado', [SolicitudDotacionController::class, 'marcarEntregado'])->name('marcar-entregado');
        Route::get('/solicitudes/{solicitud}/resumen', [SolicitudDotacionController::class, 'verResumen'])->name('resumen');
    });

    // FORMACIÓN - Planes de capacitación
    Route::prefix('formacion')->name('formacion.')->group(function () {
        Route::get('/solicitudes/{solicitud}/formulario', [SolicitudFormacionController::class, 'mostrarFormulario'])->name('formulario');
        Route::post('/solicitudes/{solicitud}/guardar', [SolicitudFormacionController::class, 'guardar'])->name('guardar');
        Route::get('/solicitudes/{solicitud}/plan-estandar', [SolicitudFormacionController::class, 'cargarPlanEstandar'])->name('plan-estandar');
        Route::patch('/planes/{plan}/estado', [SolicitudFormacionController::class, 'actualizarEstado'])->name('actualizar-estado');
        Route::get('/solicitudes/{solicitud}/resumen', [SolicitudFormacionController::class, 'verResumen'])->name('resumen');
        Route::post('/planes/{plan}/completar-modulo', [SolicitudFormacionController::class, 'completarModulo'])->name('completar-modulo');
    });

    // BIENES Y SERVICIOS - Inmobiliario
    Route::prefix('bienes')->name('bienes.')->group(function () {
        Route::get('/solicitudes/{solicitud}/formulario', [SolicitudBienesController::class, 'mostrarFormulario'])->name('formulario');
        Route::post('/solicitudes/{solicitud}/guardar', [SolicitudBienesController::class, 'guardar'])->name('guardar');
        Route::get('/solicitudes/{solicitud}/kit-estandar', [SolicitudBienesController::class, 'cargarKitEstandar'])->name('kit-estandar');
        Route::patch('/items/{item}/estado', [SolicitudBienesController::class, 'actualizarEstadoItem'])->name('actualizar-estado');
        Route::post('/items/{item}/marcar-entregado', [SolicitudBienesController::class, 'marcarEntregado'])->name('marcar-entregado');
        Route::get('/solicitudes/{solicitud}/resumen', [SolicitudBienesController::class, 'verResumen'])->name('resumen');
        Route::get('/solicitudes/{solicitud}/reporte', [SolicitudBienesController::class, 'generarReporte'])->name('reporte');
    });

    // TECNOLOGÍA - Hardware, software y credenciales
    Route::prefix('tecnologia')->name('tecnologia.')->group(function () {
        Route::get('/solicitudes/{solicitud}/formulario', [SolicitudTecnologiaController::class, 'mostrarFormulario'])->name('formulario');
        Route::post('/solicitudes/{solicitud}/guardar', [SolicitudTecnologiaController::class, 'guardar'])->name('guardar');
        Route::get('/solicitudes/{solicitud}/kit-estandar', [SolicitudTecnologiaController::class, 'cargarKitEstandar'])->name('kit-estandar');
        Route::post('/detalles/{detalle}/hardware-entregado', [SolicitudTecnologiaController::class, 'marcarHardwareEntregado'])->name('hardware-entregado');
        Route::patch('/detalles/{detalle}/estado', [SolicitudTecnologiaController::class, 'actualizarEstado'])->name('actualizar-estado');
        Route::get('/solicitudes/{solicitud}/detalles', [SolicitudTecnologiaController::class, 'verDetalles'])->name('detalles');
        Route::get('/detalles/{detalle}/checklist', [SolicitudTecnologiaController::class, 'generarChecklist'])->name('checklist');
        Route::post('/detalles/{detalle}/validar-credenciales', [SolicitudTecnologiaController::class, 'validarCredenciales'])->name('validar-credenciales');
    });

    // Ruta para obtener jefes por área (AJAX)
    Route::get('/areas/{area}/jefes', [ProcesoIngresoController::class, 'getJefesByArea']);

});

// Rutas públicas - Check-in y confirmación de activos (sin autenticación)
Route::get('/checkin/{codigo}', [CheckinController::class, 'confirmar'])->name('checkin.confirmar');
Route::post('/checkin/{codigo}/procesar', [CheckinController::class, 'procesarConfirmacion'])->name('checkin.procesar');
Route::get('/checkin/{codigo}/confirmado', [CheckinController::class, 'confirmado'])->name('checkin.confirmado');
Route::get('/checkin/{codigo}/estado', [CheckinController::class, 'verificarEstado'])->name('checkin.estado');

// MÓDULO DE FORMACIÓN - Cursos, Asignaciones, Rutas, Reportes y Auditoría
require __DIR__.'/web-formacion.php';

require __DIR__.'/auth.php';
