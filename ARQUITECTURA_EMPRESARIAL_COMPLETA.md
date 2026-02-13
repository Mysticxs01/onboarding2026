# 🏛️ ARQUITECTURA EMPRESARIAL COMPLETA - SISTEMA DE ONBOARDING
## Análisis, Diagnóstico y Propuesta de Solución

**Fecha:** Febrero 13, 2026  
**Versión:** 1.0 - Diagnóstico Arquitectónico  
**Realizado por:** Arquitecto de Software Senior  
**Alcance:** Sistema de Onboarding Corporativo para Empresa Financiera

---

## ÍNDICE DE CONTENIDOS

1. [Diagnóstico General](#1-diagnóstico-general)
2. [Matriz de Cumplimiento](#2-matriz-de-cumplimiento)
3. [Faltantes Críticos](#3-faltantes-críticos)
4. [Problemas Arquitectónicos](#4-problemas-arquitectónicos)
5. [Arquitectura Propuesta](#5-arquitectura-propuesta)
6. [Modelo de Datos Completo](#6-modelo-de-datos-completo)
7. [Módulos Funcionales](#7-módulos-funcionales)
8. [Flujos de Negocio](#8-flujos-de-negocio)
9. [UX/UI Corporativo](#9-uxui-corporativo)
10. [Roadmap de Implementación](#10-roadmap-de-implementación)

---

## 1. DIAGNÓSTICO GENERAL

### 1.1 Estado Actual del Sistema

#### ✅ LO QUE ESTÁ BIEN IMPLEMENTADO

```
MÓDULO                          NIVEL DE COMPLETITUD    OBSERVACIONES
─────────────────────────────────────────────────────────────────────
Administración Procesos          ████████░░  80%         Falta validaciones avanzadas
Check-in de Activos              ███████░░░  70%         Falta integración con firma digital real
Solicitudes Servic Generales     ████████░░  80%         Falta liberación de puestos
Solicitudes Dotación             ███████░░░  70%         Falta tracking completo
Solicitudes Tecnología           ██████░░░░  60%         Falta integración AD real
Solicitudes Bienes               ██████░░░░  60%         Falta sistema de almacén
Solicitudes Formación            ████░░░░░░  40%         CRÍTICO: Solo módulos, sin cursos
Roles y Permisos                 ███████░░░  70%         Implementado pero incompleto
Notificaciones                   ██████░░░░  60%         Solo logs, sin API real
Base de Datos                    ███████░░░  70%         Estructura OK, falta campos críticos
```

#### ❌ LO QUE FALTA CRÍTICO

```
1. MÓDULO DE CURSOS/CAPACITACIONES - NO EXISTE
   └─ No existe modelo Curso independiente
   └─ No existe sistema de catálogo de cursos
   └─ No existe asignación de cursos por RRHH
   └─ No existe lógica de cursos obligatorios/opcionales
   └─ No existe trazabilidad de cursos completados

2. SISTEMA DE RUTAS DE FORMACIÓN - NO EXISTE
   └─ No hay definición de rutas por cargo
   └─ No hay rutas por área
   └─ No hay recomendaciones inteligentes

3. REPORTES Y ANALYTICS - PARCIAL
   └─ No existen dashboards de cumplimiento
   └─ No hay análisis de retrasos por área
   └─ No hay reportes de retención

4. SISTEMA DE IA/PREDICCIONES - NO EXISTE
   └─ No hay sugerencias de insumos
   └─ No hay predicción de necesidades
   └─ No hay recomendaciones de cursos

5. DISEÑO CORPORATIVO - INCOMPLETO
   └─ Colors no aplicados globalmente
   └─ No hay componentes branded
   └─ No hay assets corporativos

6. TRAZABILIDAD Y AUDITORÍA - BÁSICA
   └─ Sin registro detallado de cambios
   └─ Sin auditoría de acciones de usuarios
   └─ Sin histórico de decisiones

7. VALIDACIONES DE JEFE INMEDIATO - PARCIAL
   └─ Solo para TI y Dotación
   └─ No hay para otras áreas
```

---

## 2. MATRIZ DE CUMPLIMIENTO

### 2.1 Requerimientos Funcionales vs Implementación

| # | Requerimiento | Estado | % | Notas |
|---|---|---|---|---|
| 1 | Crear proceso de ingreso | ✅ Completo | 95% | Falta validaciones de negocio |
| 2 | Código autogenerado | ✅ Completo | 100% | ING-YYYYMMDDHHmmss |
| 3 | Generar solicitudes automáticas | ✅ Completo | 90% | Falta reintentar fallidos |
| 4 | Asignar puesto (plano) | ✅ Completo | 85% | Falta historial de cambios |
| 5 | Gestión de dotación | ✅ Completo | 75% | Falta integración con almacén |
| 6 | Gestión de tecnología | ✅ Completo | 60% | Falta integración AD real |
| 7 | Gestión formación | ⚠️ Incompleto | 30% | **CRÍTICO** - Solo módulos |
| 8 | Asignar cursos (RRHH) | ❌ No existe | 0% | **FALTANTE CRÍTICO** |
| 9 | Validaciones jefe | ✅ Completo | 70% | Solo TI y Dotación |
| 10 | Check-in de activos | ✅ Completo | 75% | Falta firma digital real |
| 11 | Reportes cumplimiento | ❌ No existe | 0% | **FALTANTE CRÍTICO** |
| 12 | IA/Recomendaciones | ❌ No existe | 0% | **FALTANTE CRÍTICO** |
| 13 | Control jerárquico | ✅ Completo | 80% | Bien implementado con roles |
| 14 | Auditoría | ⚠️ Incompleto | 40% | Solo logs básicos |
| 15 | Diseño corporativo | ❌ No existe | 0% | **FALTANTE CRÍTICO** |

**CUMPLIMIENTO GENERAL: 54%** (Requiere 20% más de trabajo)

---

## 3. FALTANTES CRÍTICOS

### 3.1 Faltante #1: MÓDULO DE CURSOS (CRÍTICO)

**Problema:**
- El sistema actual usa `PlanCapacitacion` como tabla de planes simples
- No existe modelo `Curso` independiente
- No existe catálogo de cursos
- La formación es reactiva (módulos adhoc), no catalogada
- Imposible reutilizar cursos
- No hay asignación por RRHH

**Impacto:**
- No se puede cumplir con requerimiento de "Lista de Cursos Existentes: Inducción, SARLAFT, SST, etc."
- No se puede implementar lógica de RRHH
- No hay trazabilidad de formación corporativa
- Incumplimiento normativo (SARLAFT, SST)

**Solución Propuesta:**
```
Crear modelo Curso con:
├─ ID, Código (único)
├─ Nombre, Descripción
├─ Categoría (Obligatorio, Opcional, Cumplimiento)
├─ Modalidad (Presencial, Virtual, Híbrida)
├─ Duración (horas)
├─ Objetivo, Contenido
├─ Responsable (Área)
├─ Costo
├─ Requisitos previos (cursos)
├─ Vigencia, Certificado
└─ Activo/Inactivo

Crear tabla CursoXCargo:
├─ curso_id
├─ cargo_id
├─ es_obligatorio
├─ orden_secuencia
└─ fecha_desde/hasta

Crear tabla CursoXArea:
├─ curso_id
├─ area_id
└─ es_obligatorio
```

### 3.2 Faltante #2: ASIGNACIÓN DE CURSOS POR RRHH (CRÍTICO)

**Problema:**
- No existe interfaz para que RRHH asigne cursos
- Formación se gestiona a nivel de plan ad-hoc
- No hay lógica de rutas de formación
- No hay recomendaciones inteligentes
- Imposible gestionar cursos obligatorios

**Impacto:**
- RRHH no puede cumplir su función crítica
- Incumplimiento normativo
- Falta de trazabilidad de formación corporativa

**Solución Propuesta:**
```
Crear módulo completo RRHH:

1. Panel de Cursos Disponibles
   ├─ Catálogo filtrable por categoría
   ├─ Filas de formación recomendadas
   ├─ Obligatorios vs opcionales
   └─ Vista rápida: necesarios por cargo

2. Asignación de Cursos al Empleado
   ├─ Búsqueda del proceso de ingreso
   ├─ Mostrar cursos recomendados automáticamente
   ├─ Selección múltiple de cursos
   ├─ Crear ruta de formación personalizada
   ├─ Definir fechas y responsables
   └─ Notificar al empleado y área

3. Tabla AsignacionCurso:
   ├─ id, proceso_ingreso_id, curso_id
   ├─ estado (Asignado, En Progreso, Completado)
   ├─ fecha_asignacion, fecha_limite, fecha_completacion
   ├─ calificacion, certificado
   ├─ responsable_validacion
   └─ observaciones
```

### 3.3 Faltante #3: RUTAS DE FORMACIÓN (CRÍTICO)

**Problema:**
- No existe concepto de "ruta de formación"
- Cada plan es independiente sin secuencia
- No hay construcción de caminos de desarrollo
- No hay progresión lógica en la formación

**Solución Propuesta:**
```
Crear modelo RutaFormacion:
├─ id, nombre, descripcion
├─ cargo_id, area_id
├─ version, activa
├─ cursos (lista ordenada)
│  ├─ Curso 1 (Obligatorio, fase 1)
│  ├─ Curso 2 (Obligatorio, fase 2)
│  └─ Cursos 3-5 (Opcionales)
├─ duracion_total_horas
├─ fecha_vigencia
└─ responsable_rrhh

Tabla RutaFormacionCurso:
├─ ruta_id, curso_id
├─ numero_secuencia
├─ es_requerido_previo
└─ es_obligatorio
```

### 3.4 Faltante #4: REPORTES Y DASHBOARDS (CRÍTICO)

**Problema:**
- No existen dashboards de cumplimiento
- No hay análisis de retrasos
- No hay reportes ejecutivos
- Admin trabaja a ciegas

**Solución Propuesta:**
```
Crear 8 reportes críticos:

1. Dashboard Ejecutivo
   ├─ % Procesos completados
   ├─ Retrasos por área
   ├─ Empleados por estado
   └─ Tendencias

2. Reporte de Cumplimiento por Área
   ├─ Solicitudes en tiempo
   ├─ Solicitudes retrasadas
   ├─ Promedio de días completados
   └─ Causalidad de retrasos

3. Reporte de Formación
   ├─ Cursos completados vs asignados
   ├─ Tasa de cumplimiento por curso
   ├─ Cursos con baja participación
   └─ Certificados emitidos

4. Reporte de Costos
   ├─ Costo por área
   ├─ Costo por cargo
   ├─ Presupuesto vs ejecución
   └─ Variación

5. Reporte de Activos
   ├─ Hardware distribuido
   ├─ Software instalado
   ├─ Costo por empleado
   └─ Disponibilidad

6. Reporte de Inducción
   ├─ Duración promedio
   ├─ Módulos completados
   ├─ Efectividad
   └─ Feedback

7. Análisis de Retención
   ├─ Empleados completados hace 6 meses
   ├─ Tasa de retención por área
   ├─ Tasa de retención por cargo
   └─ Predictivo: riesgo de salida

8. Auditoría
   ├─ Cambios registrados
   ├─ Usuario que realizó cambio
   ├─ Fecha y hora
   └─ Justificación
```

### 3.5 Faltante #5: IA/PREDICCIONES (ESTRATÉGICO)

**Problema:**
- Sistema totalmente reactivo, sin inteligencia
- No hay recomendaciones
- No hay predicciones de necesidades
- Se pierde valor agregado

**Solución Propuesta:**
```
Implementar 3 módulos IA:

1. Recomendador de Insumos (IA)
   ├─ Analizar histórico de cargo
   ├─ Sugerir hardware/software estándar
   ├─ Aprender de decisiones anteriores
   └─ % de exactitud prediciendo necesidades

2. Sugeridor de Rutas de Formación (IA)
   ├─ Analizar perfil del cargo
   ├─ Sugerir cursos basados en historial
   ├─ Detectar gaps de formación
   └─ Personalizar según desempeño esperado

3. Predictor de Retrasos (IA)
   ├─ Analizar velocidad de cada área
   ├─ Alertar si proceso va tarde
   ├─ Recomendar recursos adicionales
   └─ Predecir fecha probable de entrega
```

### 3.6 Faltante #6: DISEÑO CORPORATIVO (CRÍTICO)

**Problema:**
- Colores corporativos NO APLICADOS
- Sistema usa colores por defecto de Tailwind
- No hay componentes branded
- Interfaz genérica, no corporativos

**Colores Obligatorios:**
```
- #1B365D Azul Oxford         ← Colores primarios
- #28A745 Verde Cooperativo
- #C59D42 Dorado Mate
- #F8F9FA Blanco Humo
```

**Solución:**
```
1. Crear config/colors-corporativo.php
2. Crear componentes Blade branded
3. Aplicar a todas las vistas
4. Assets: logo, iconos, fuentes
5. Paleta de sombras y tonos
```

### 3.7 Faltante #7: AUDITORÍA COMPLETA (CRÍTICO)

**Problema:**
- Sistema usa logs básicos
- No existe tabla de auditoría
- Imposible auditar cambios
- Incumplimiento normativo

**Solución:**
```
Crear tabla AuditoriaOnboarding:
├─ id
├─ usuario_id
├─ accion (create, update, delete)
├─ entidad (tabla afectada)
├─ entidad_id
├─ valores_anteriores (JSON)
├─ valores_nuevos (JSON)
├─ motivo/justificacion
├─ ip_origen
├─ navegador
├─ timestamp
└─ autorizado_por
```

### 3.8 Otros Faltantes

```
Validaciones de Jefe Inmediato
├─ Extender a todas las áreas, no solo TI y Dotación
├─ Crear interface estándar de validación
└─ Auditar decisiones del jefe

Integración Active Directory
├─ Crear usuario AD automáticamente
├─ Asignar grupos de seguridad
├─ Sincronizar estado del empleado
└─ Desconectar al cancelar

Firma Digital Real
├─ Uso de certificados digitales
├─ Integración con prestador de firmas
├─ Validación legal del checkin
└─ Archivo en repositorio seguro

Sistema de Formularios Ad Hoc
├─ RRHH crear preguntas personalizadas
├─ Validaciones específicas
├─ Integración en flujo
└─ Análisis de respuestas
```

---

## 4. PROBLEMAS ARQUITECTÓNICOS

### 4.1 Problema #1: Modelo de Datos Deficiente

**Ubicación:** `database/migrations/`

**Problemas:**
```
❌ Tabla notificaciones NOT existe
❌ Tabla cursos NOT existe  
❌ Tabla auditoria NOT existe
❌ Campo email falta en procesos_ingresos
❌ Índices no optimizados
❌ Relaciones cascadeOnDelete inconsistentes
❌ Foreign keys sin restricciones
```

**Ejemplo Deficiente:**
```php
// MALA: Falta información crítica
Schema::create('procesos_ingresos', function (Blueprint $table) {
    $table->id();
    $table->string('nombre_completo'); // ❌ Sin validación de largo
    $table->enum('estado', [...]);      // ❌ Sin índice
    // ❌ FALTA: email, teléfono, dirección
    // ❌ FALTA: estado_civil, dependientes
    // ❌ FALTA: fecha_nacimiento
});
```

### 4.2 Problema #2: Controladores Monolíticos

**Ubicación:** `app/Http/Controllers/`

**Problemas:**
```
❌ SolicitudController hace demasiado
❌ Lógica de negocio en controladores
❌ Sin use cases o actions
❌ Sin validaciones compartidas
❌ Sin manejo de transacciones
```

**Ejemplo Deficiente:**
```php
// MALA: Lógica de negocio en controlador
public function cambiarEstado(Request $request, $id)
{
    $solicitud = Solicitud::findOrFail($id);
    // ❌ Validación básica
    // ❌ Sin transacciones
    // ❌ Sin auditoría automática
    // ❌ Sin notificaciones configurables
}
```

### 4.3 Problema #3: Falta de Servicios de Dominio

**Ubicación:** `app/Services/`

**Problemas:**
```
❌ NotificationService existe, pero otros NO
❌ Sin FormacionService
❌ Sin ProcesoIngresoService
❌ Sin CalculoService
❌ Sin RecommendationService
```

### 4.4 Problema #4: Vistas Sin Componentes

**Ubicación:** `resources/views/`

**Problemas:**
```
❌ Código duplicado en vistas
❌ Sin componentes reutilizables
❌ Sin diseño corporativo global
❌ Sin estructura modular
❌ CSS inline en vistas
```

### 4.5 Problema #5: Rutas Desorganizadas

**Ubicación:** `routes/web.php`

**Problemas:**
```
❌ 132 líneas en un solo archivo
❌ Sin agrupar por dominio
❌ Sin namespaces claros
❌ Middleware disperso
```

---

## 5. ARQUITECTURA PROPUESTA

### 5.1 Arquitectura General

```
┌─────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                  │
│  ┌─────────────────────────────────────────────────┐   │
│  │  Controllers │ Views │ Components │ Middleware │   │
│  └─────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
                          ↓↑
┌─────────────────────────────────────────────────────────┐
│                  APPLICATION LAYER                     │
│  ┌──────────────────────────────────────────────────┐  │
│  │  Actions │ UseCases │ Policies │ Resources │     │  │
│  │  Requests │ Exceptions │ Mappers                │  │
│  └──────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
                          ↓↑
┌─────────────────────────────────────────────────────────┐
│                  DOMAIN LAYER                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Services │ Repositories │ ValueObjects │         │  │
│  │ Business Rules │ Entities │ Aggregates           │  │
│  └──────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
                          ↓↑
┌─────────────────────────────────────────────────────────┐
│                 INFRASTRUCTURE LAYER                    │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Models │ Database │ External APIs │ Filesystem  │  │
│  │ Cache │ Queue │ Mail │ Storage                  │  │
│  └──────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

### 5.2 Estructura de Carpetas Propuesta

```
app/
├── Actions/                    ← Acciones de usuario
│   ├── Onboarding/
│   │   ├── CrearProcesoIngresoAction.php
│   │   ├── AsignarCursosAction.php
│   │   ├── GenerarCheckInAction.php
│   │   └── CompletarProcesoAction.php
│   ├── Curso/
│   │   ├── CrearCursoAction.php
│   │   ├── AsignarCursoAction.php
│   │   └── CompletarCursoAction.php
│   └── Auditoria/
│       └── RegistrarAuditoriaAction.php
│
├── Models/
│   ├── ProcesoIngreso.php
│   ├── Solicitud.php
│   ├── Curso.php                 ← NUEVO
│   ├── CursoXCargo.php           ← NUEVO
│   ├── CursoXArea.php            ← NUEVO
│   ├── AsignacionCurso.php       ← NUEVO
│   ├── RutaFormacion.php         ← NUEVO
│   ├── AuditoriaOnboarding.php   ← NUEVO
│   └── ...
│
├── Services/
│   ├── NotificationService.php   ← EXISTENTE
│   ├── FormacionService.php      ← NUEVO
│   ├── OnboardingService.php     ← NUEVO
│   ├── CursoService.php          ← NUEVO
│   ├── RecommendationService.php ← NUEVO
│   ├── ReportService.php         ← NUEVO
│   ├── AuditoriaService.php      ← NUEVO
│   └── IAService.php             ← NUEVO
│
├── Repositories/
│   ├── CursoRepository.php
│   ├── RutaFormacionRepository.php
│   ├── AsignacionCursoRepository.php
│   └── AuditoriaRepository.php
│
├── Policies/
│   ├── ProcesoIngresoPolicy.php
│   ├── CursoPolicy.php
│   ├── AsignacionCursoPolicy.php
│   └── AuditoriaPolicy.php
│
├── Http/
│   ├── Controllers/
│   │   ├── Onboarding/
│   │   │   ├── ProcesoIngresoController.php
│   │   │   └── SolicitudController.php
│   │   ├── Formacion/
│   │   │   ├── CursoController.php         ← NUEVO
│   │   │   ├── RutaFormacionController.php ← NUEVO
│   │   │   ├── AsignacionCursoController.php ← NUEVO
│   │   │   └── SolicitudFormacionController.php
│   │   ├── Reportes/
│   │   │   └── ReporteController.php       ← NUEVO
│   │   └── Auditoria/
│   │       └── AuditoriaController.php     ← NUEVO
│   │
│   ├── Requests/
│   │   ├── StoreProcesoIngresoRequest.php
│   │   ├── StoreCursoRequest.php           ← NUEVO
│   │   ├── AsignarCursoRequest.php         ← NUEVO
│   │   └── ...
│   │
│   ├── Resources/
│   │   ├── ProcesoIngresoResource.php
│   │   ├── CursoResource.php               ← NUEVO
│   │   └── ...
│   │
│   └── Middleware/
│       ├── VerifyOnboarding.php
│       ├── VerifyFormacion.php
│       └── LogAuditoria.php                ← NUEVO
│
├── Events/
│   ├── ProcesoIngresoCreado.php
│   ├── CursoAsignado.php                   ← NUEVO
│   ├── CursCompletado.php                  ← NUEVO
│   └── ProcesoCompletado.php
│
├── Listeners/
│   ├── EnviarNotificacionFormacion.php
│   ├── RegistrarAuditoriaFormacion.php
│   └── ActualizarEstadisticas.php
│
├── Jobs/
│   ├── EnviarRemindersFormacion.php
│   ├── GenerarReporte.php                  ← NUEVO
│   ├── ActualizarIA.php                    ← NUEVO
│   └── SincronizarAD.php
│
└── Exceptions/
    ├── OnboardingException.php
    ├── FormacionException.php
    ├── CursoNoEncontradoException.php
    └── PermisoNeagaroException.php

database/
├── migrations/
│   ├── 2026_02_20_create_cursos_table.php                    ← NUEVA
│   ├── 2026_02_21_create_curso_x_cargo_table.php             ← NUEVA
│   ├── 2026_02_22_create_curso_x_area_table.php              ← NUEVA
│   ├── 2026_02_23_create_asignacion_cursos_table.php         ← NUEVA
│   ├── 2026_02_24_create_rutas_formacion_table.php           ← NUEVA
│   ├── 2026_02_25_create_curso_x_ruta_table.php              ← NUEVA
│   ├── 2026_02_26_create_auditoria_onboarding_table.php      ← NUEVA
│   ├── 2026_02_26_add_email_to_procesos_ingresos.php          ← NUEVA
│   ├── 2026_02_27_create_recomendaciones_table.php           ← NUEVA
│   └── 2026_02_28_create_notificaciones_table.php            ← NUEVA
│
├── seeders/
│   ├── CursoSeeder.php                                        ← NUEVO
│   ├── RutaFormacionSeeder.php                                ← NUEVO
│   └── CursoXCargoSeeder.php                                  ← NUEVO
│
└── factories/
    ├── CursoFactory.php                                       ← NUEVO
    └── AsignacionCursoFactory.php                             ← NUEVO

resources/
├── views/
│   ├── layouts/
│   │   ├── app-corporativo.blade.php       ← NUEVO (colored)
│   │   └── app.blade.php
│   ├── components/
│   │   ├── button-primary.blade.php        ← NUEVO
│   │   ├── button-success.blade.php        ← NUEVO
│   │   ├── alert-error.blade.php          ← NUEVO
│   │   ├── card-corporativo.blade.php     ← NUEVO
│   │   └── ...
│   ├── formacion/
│   │   ├── cursos/
│   │   │   ├── index.blade.php             ← NUEVO
│   │   │   ├── create.blade.php            ← NUEVO
│   │   │   ├── edit.blade.php              ← NUEVO
│   │   │   └── show.blade.php              ← NUEVO
│   │   ├── asignaciones/
│   │   │   ├── index.blade.php             ← NUEVO
│   │   │   ├── asignar.blade.php           ← NUEVO
│   │   │   └── seguimiento.blade.php       ← NUEVO
│   │   ├── rutas/
│   │   │   ├── index.blade.php             ← NUEVO
│   │   │   └── create.blade.php            ← NUEVO
│   │   └── rrhh/
│   │       ├── dashboard.blade.php         ← NUEVO
│   │       └── asignar-masivo.blade.php    ← NUEVO
│   ├── reportes/
│   │   ├── ejecutivo.blade.php             ← NUEVO
│   │   ├── cumplimiento.blade.php          ← NUEVO
│   │   ├── formacion.blade.php             ← NUEVO
│   │   └── costos.blade.php                ← NUEVO
│   ├── auditoria/
│   │   ├── index.blade.php                 ← NUEVO
│   │   └── show.blade.php                  ← NUEVO
│   └── ...
│
├── css/
│   ├── corporativo.css                     ← NUEVO
│   └── app.css
│
└── js/
    ├── formacion.js                        ← NUEVO
    ├── reporte.js                          ← NUEVO
    └── auditoria.js                        ← NUEVO

tests/
├── Feature/
│   ├── Onboarding/
│   ├── Formacion/
│   ├── Reportes/
│   └── Auditoria/
└── Unit/
    ├── Services/
    └── Models/

routes/
├── web.php                                 ← REDUCIDO (20 líneas)
├── api.php                                 ← NUEVO
├── web-onboarding.php                      ← NUEVO
├── web-formacion.php                       ← NUEVO (55 líneas)
├── web-reportes.php                        ← NUEVO
├── web-auditoria.php                       ← NUEVO
└── web-admin.php                           ← NUEVO
```

---

## 6. MODELO DE DATOS COMPLETO

### 6.1 Diagrama ER (NUEVAS TABLAS)

```
Procesos Ingresos (existente, mejorado)
├─ id, codigo, nombre_completo
├─ documento, tipo_documento
├─ email ← NUEVO
├─ teléfono ← NUEVO
├─ cargo_fk, area_fk, jefe_fk
├─ fecha_ingreso, fecha_esperada_finalizacion
├─ estado, observaciones
└─ timestamps

         ↓ 1:N

Solicitudes (existente)
├─ id, proceso_ingreso_fk
├─ area_fk, tipo, fecha_limite
├─ estado, observaciones
└─ timestamps

         ↓ 1:1

Planes Capacitacion (existente, mejorado)
├─ id, solicitud_fk, cargo_fk
├─ titulo_plan, descripcion
├─ modulos (JSON)
├─ estado
└─ timestamps

         ↓ N:N (NUEVA)

Cursos (NUEVO)
├─ id, codigo_curso (unique)
├─ nombre, descripcion
├─ categoria (Obligatorio|Opcional|Cumplimiento)
├─ modalidad (Presencial|Virtual|Híbrida)
├─ duracion_horas
├─ objetivo, contenido
├─ area_responsable_fk
├─ costo
├─ requiere_certificado
├─ vigencia_meses
├─ activo, deleted_at
└─ timestamps

Asignacion Cursos (NUEVO)
├─ id, proceso_ingreso_fk, curso_fk
├─ fecha_asignacion, fecha_limite, fecha_completacion
├─ estado (Asignado|En Progreso|Completado|Vencido)
├─ calificacion (0-100)
├─ certificado_url
├─ responsable_validacion_fk
├─ observaciones
└─ timestamps

Cursos X Cargo (NUEVO)
├─ id, curso_fk, cargo_fk
├─ es_obligatorio
├─ orden_secuencia
├─ fecha_desde, fecha_hasta
└─ timestamps

Cursos X Area (NUEVO)
├─ id, curso_fk, area_fk
├─ es_obligatorio
├─ timestamps

Rutas Formacion (NUEVO)
├─ id, nombre, descripcion
├─ cargo_fk, area_fk
├─ version, activa
├─ duracion_total_horas
├─ fecha_vigencia
├─ responsable_rrhh_fk
├─ deleted_at
└─ timestamps

Ruta X Curso (NUEVO)
├─ id, ruta_fk, curso_fk
├─ numero_secuencia
├─ es_obligatorio
├─ es_requisito_previo
└─ timestamps

Auditoria Onboarding (NUEVO)
├─ id, usuario_fk
├─ accion (create|update|delete|view|export)
├─ entidad (tabla)
├─ entidad_id
├─ valores_anteriores (JSON)
├─ valores_nuevos (JSON)
├─ motivo
├─ ip_origin
├─ user_agent
├─ timestamps

Recomendaciones (NUEVO)
├─ id, proceso_ingreso_fk
├─ tipo (hardware|software|cursos|recursos)
├─ descripcion, detalle (JSON)
├─ confianza (0-100)
├─ implementado (boolean)
├─ timestamps

Notificaciones (NUEVO)
├─ id, usuario_fk
├─ tipo (email|sms|push|in_app)
├─ asunto, mensaje
├─ referencia_id, referencia_tipo
├─ leida, leida_timestamp
├─ enviada, enviada_timestamp
├─ fallida, motivo_fallo
├─ intentos
└─ timestamps
```

### 6.2 Migraciones Necesarias

```php
// 2026_02_20_create_cursos_table.php
Schema::create('cursos', function (Blueprint $table) {
    $table->id();
    $table->string('codigo')->unique();
    $table->string('nombre');
    $table->text('descripcion')->nullable();
    $table->enum('categoria', [
        'Obligatorio',
        'Opcional',
        'Cumplimiento Normativo',
        'Desarrollo'
    ])->default('Opcional');
    $table->enum('modalidad', [
        'Presencial',
        'Virtual',
        'Híbrida'
    ])->default('Virtual');
    $table->integer('duracion_horas');
    $table->text('objetivo')->nullable();
    $table->text('contenido')->nullable();
    $table->foreignId('area_responsable_id')
          ->nullable()
          ->constrained('areas')
          ->onDelete('set null');
    $table->decimal('costo', 10, 2)->default(0);
    $table->boolean('requiere_certificado')->default(true);
    $table->integer('vigencia_meses')->nullable();
    $table->boolean('activo')->default(true);
    $table->softDeletes();
    $table->timestamps();
    
    $table->index('codigo');
    $table->index('categoria');
    $table->index('activo');
});

// 2026_02_21_create_curso_x_cargo_table.php
Schema::create('curso_x_cargo', function (Blueprint $table) {
    $table->id();
    $table->foreignId('curso_id')
          ->constrained('cursos')
          ->onDelete('cascade');
    $table->foreignId('cargo_id')
          ->constrained('cargos')
          ->onDelete('cascade');
    $table->boolean('es_obligatorio')->default(false);
    $table->integer('orden_secuencia')->default(0);
    $table->date('fecha_desde')->nullable();
    $table->date('fecha_hasta')->nullable();
    $table->timestamps();
    
    $table->unique(['curso_id', 'cargo_id']);
    $table->index(['cargo_id', 'es_obligatorio']);
});

// Resto de migraciones similares...
```

---

## 7. MÓDULOS FUNCIONALES

### 7.1 Módulo CURSOS (TODO)

**Responsables:**
- RRHH: Crear y gestionar catálogo
- Operadores: Registrar progreso
- Empleados: Acceder a contenido

**Funcionalidades:**

```
1. Gestión de Catálogo
   └─ CRUD de cursos
   └─ Filtros: categoría, modalidad, área
   └─ Búsqueda full-text
   └─ Versioning de contenido

2. Asignación por Cargo/Área
   └─ Marcar como obligatorio
   └─ Definir secuencias
   └─ Especificar vigencia
   └─ Eliminar asignaciones

3. Visualización de Catálogo
   └─ Tarjetas de cursos
   └─ Filtros avanzados
   └─ Vista de detalles
   └─ Requisitos previos visibles
```

### 7.2 Módulo ASIGNACIÓN DE CURSOS (TODO)

**Responsables:**
- RRHH: Asignar cursos a empleados
- Jefe: Validar asignaciones
- Empleados: Acceder a cursos

**Flujo:**

```
1. RRHH accede a ProcesoIngreso
   ├─ Ve cursos recomendados (por AI)
   ├─ Selecciona múltiples cursos
   ├─ Crea ruta personalizada
   ├─ Define fechas de inicio
   └─ Notifica a empleado

2. Empleado recibe notificación
   ├─ Ve cursos asignados
   ├─ Accede a materiales
   ├─ Completa evaluación
   ├─ Obtiene certificado
   └─ Sistema registra completación

3. Jefe valida (si aplica)
   ├─ Revisa calificaciones
   ├─ Aprueba/rechaza
   ├─ Registra observaciones
   └─ Marca como completado
```

### 7.3 Módulo REPORTES (TODO)

**8 Reportes Críticos:**

```
1. Dashboard Ejecutivo
   ├─ KPIs principales
   ├─ Gráficos de tendencia
   ├─ Alertas de retrasos
   └─ PDF exportable

2. Cumplimiento por Área
   ├─ Tabla: área, % cumplimiento, retrasos
   ├─ Gráfico de barras
   ├─ Comparativo con período anterior
   └─ Drill-down a detalles

3. Formación
   ├─ Cursos completados vs asignados
   ├─ Tasa de completación por curso
   ├─ Cursos con baja participación
   ├─ Certificados emitidos
   └─ Recomendaciones

4. Costos
   ├─ Costo por área
   ├─ Costo por cargo
   ├─ Presupuesto vs ejecución
   ├─ Variación esperada
   └─ Análisis ABC

5. Activos
   ├─ Hardware distribuido (qty, costo)
   ├─ Software instalado (licencias)
   ├─ Disponibilidad en almacén
   ├─ Equipos por renovar
   └─ Costo unitario vs presupuesto

6. Inducción
   ├─ Duración promedio
   ├─ Módulos completados
   ├─ Satisfacción (encuestas)
   ├─ Efectividad predictiva
   └─ Anomalías

7. Retención
   ├─ Empleados completados hace 6 meses
   ├─ Tasa de retención por cargo
   ├─ Tasa de retención por área
   ├─ Tendencia anual
   └─ Predictivo: riesgo de salida (IA)

8. Auditoría
   ├─ Cambios registrados (tabla timeline)
   ├─ Usuario responsable
   ├─ Datos modificados
   ├─ Motivo registrado
   └─ IP y navegador
```

### 7.4 Módulo IA/RECOMENDACIONES (TODO)

**3 Motores de IA:**

```
1. Recomendador de Insumos
   Input: cargo, área, histórico
   Output: { hardware_recomendado, software_recomendado, 
             confianza%, razón }
   
   Reglas:
   ├─ Developer → Laptop high-end, 2 monitores, IDE
   ├─ RRHH → Laptop estándar, Office, HRIS
   └─ Analista → Laptop estándar, Excel avanzado, VPN

2. Sugeridor de Rutas
   Input: cargo, área, competencias_requeridas
   Output: { cursos_sugeridos: [curso, orden], 
             duración_total, certificados }
   
   Reglas:
   ├─ Todos → Inducción (obligatorio)
   ├─ Área Financiera → SARLAFT (obligatorio)
   ├─ Todos → SST (obligatorio, anual)
   └─ Por competencias faltantes...

3. Predictor de Retrasos
   Input: proceso_ingreso, área, histórico
   Output: { probabilidad_retraso%, 
             dias_estimado_retraso, 
             cuello_botella_identificado }
   
   Reglas:
   ├─ Si área Tecnología tiene 5+ procesos en curso
   │  └─ Probabilidad retraso +30%
   ├─ Si jefe tiene antecedente de lentitud
   │  └─ Probabilidad retraso +20%
   └─ Si es viernes
      └─ Retrasar inicio fin de semana
```

---

## 8. FLUJOS DE NEGOCIO

### 8.1 Flujo Completo de Onboarding

```
┌─────────────────────────────────────────────────────────┐
│  PASO 1: CREACIÓN DE PROCESO (RRHH)                    │
└─────────────────────────────────────────────────────────┘

  1.1 RRHH abre "Nuevo Ingreso"
      ├─ Formulario con:
      │  ├─ Nombre, documento, tipo_doc
      │  ├─ Cargo (dropdown)
      │  ├─ Área (auto, desde cargo)
      │  ├─ Jefe (auto-filtrado por área)
      │  ├─ Fecha de ingreso
      │  └─ Observaciones
      │
  1.2 RRHH hace clic "Crear"
      ├─ Validar datos
      ├─ Generar código (ING-YYYYMMDDHHmmss)
      ├─ Crear ProcesoIngreso
      └─ TRIGGER: Crear solicitudes automáticas
         ├─ Por cada PlantillaSolicitud para el cargo
         ├─ Crear Solicitud con estado "Pendiente"
         ├─ Calcular fecha_limite
         └─ Guardar en BD


┌─────────────────────────────────────────────────────────┐
│  PASO 2: ASIGNACIÓN DE CURSOS (RRHH)                   │
│  [*** FLUJO NUEVO **]                                   │
└─────────────────────────────────────────────────────────┘

  2.1 RRHH accede al ProcesoIngreso
      └─ Nuevo tab "Formación"

  2.2 Sistema sugiere cursos automáticamente (IA)
      ├─ Cursos obligatorios por cargo
      ├─ Cursos obligatorios por área
      ├─ Cursos recomendados (basado en perfil)
      └─ Ordenados por secuencia

  2.3 RRHH puede:
      ├─ Usar ruta sugerida automáticamente
      ├─ Seleccionar cursos adicionales del catálogo
      ├─ Reordenar secuencia manualmente
      ├─ Definir fechas de inicio
      └─ Configurar responsables de validación

  2.4 RRHH hace clic "Asignar Cursos"
      ├─ Crear AsignacionCurso (N registros)
      ├─ Enviar notificación a empleado
      ├─ Enviar a área de formación
      ├─ Registrar en auditoría
      └─ Actualizar estado proceso


┌─────────────────────────────────────────────────────────┐
│  PASO 3: VALIDACIÓN DEL JEFE                           │
└─────────────────────────────────────────────────────────┘

  3.1 Jefe recibe notificación de nueva solicitud
      └─ Para Tecnología y Dotación SOLAMENTE

  3.2 Jefe especifica detalles:
      ├─ TECNOLOGÍA
      │  ├─ Tipo de computador
      │  ├─ Marca, RAM, procesador, SSD
      │  ├─ Software requerido
      │  ├─ Monitor adicional (S/N)
      │  └─ Mouse/Teclado (S/N)
      │
      ├─ DOTACIÓN
      │  ├─ Género
      │  ├─ Talla camisa, pantalón, zapatos
      │  ├─ Cantidad de uniformes
      │  └─ Observaciones
      │
      └─ Sistema sugiere estándares por cargo

  3.3 Jefe guarda especificaciones
      ├─ Crear DetalleTecnologia/DetalleUniforme
      ├─ Notificar a área operacional
      └─ Registrar en auditoría


┌─────────────────────────────────────────────────────────┐
│  PASO 4: EJECUCIÓN POR ÁREAS                           │
└─────────────────────────────────────────────────────────┘

  4.1 SERVICIOS GENERALES
      ├─ Mostrar plano interactivo de puestos
      ├─ Seleccionar puesto disponible
      ├─ Generar carnet con foto
      └─ Marcar solicitud como "Entregado"

  4.2 DOTACIÓN
      ├─ Preparar EPP según especificación
      ├─ Preparar uniformes en tallas seleccionadas
      ├─ Entregar a empleado
      └─ Marcar como "Entregado"

  4.3 TECNOLOGÍA
      ├─ Crear usuario en Active Directory
      ├─ Asignar grupos de seguridad
      ├─ Instalar software especificado
      ├─ Entregar equipo
      └─ Marcar como "Entregado"

  4.4 FORMACIÓN
      ├─ Cargar cursos en LMS
      ├─ Habilitar acceso a empleado
      ├─ Entrenar a empleado
      └─ Marcar como "En Progreso" → "Completado"

  4.5 BIENES Y SERVICIOS
      ├─ Recolectar inmobiliario del almacén
      ├─ Entregar a puesto del empleado
      └─ Marcar como "Entregado"

  [Cada paso envía notificación]


┌─────────────────────────────────────────────────────────┐
│  PASO 5: CHECK-IN DE ACTIVOS                           │
└─────────────────────────────────────────────────────────┘

  5.1 Sistema genera check-in automáticamente
      ├─ Lista todos los activos entregados
      ├─ Generar código único (8 chars hex)
      ├─ URL pública para empleado
      └─ Guardar en tabla checkins

  5.2 Empleado accede a URL pública
      ├─ Formulario sin autenticación
      ├─ Listado de activos
      ├─ Canvas para firma digital
      ├─ Checkboxes: "Confirmo recibir"
      ├─ Términos y condiciones
      └─ Botón "Confirmar entrega"

  5.3 Empleado firma y confirma
      ├─ Validar firma (no vacío)
      ├─ Validar todos los checkboxes
      ├─ Validar aceptación términos
      ├─ Capturar IP, navegador, timestamp
      └─ Guardar firma en tabla

  5.4 Sistema genera PDF
      ├─ Datos empleado
      ├─ Lista de activos
      ├─ QR con enlace a verificación
      ├─ Firma digital (imagen)
      ├─ Email al empleado
      └─ Email a RRHH con copia

  5.5 Mostrar página de éxito
      ├─ "Entrega completada exitosamente"
      ├─ Botón para descargar PDF
      ├─ Link a inducción corporativa
      └─ Contacto RRHH


┌─────────────────────────────────────────────────────────┐
│  PASO 6: FINALIZACIÓN                                  │
└─────────────────────────────────────────────────────────┘

  6.1 Sistema verifica completación
      ├─ Todas las solicitudes en "Entregado"
      ├─ Check-in completado
      ├─ Todos los cursos completados
      └─ Si YES → siguiente paso

  6.2 Cambiar estado ProcesoIngreso
      ├─ Estado: "Pendiente" → "Finalizado"
      ├─ fecha_finalizacion = hoy
      ├─ Guardar en histórico
      └─ Notificación a RRHH

  6.3 Generar reportes finales
      ├─ Duración total del proceso
      ├─ Costo total invertido
      ├─ Días de retraso (si aplica)
      └─ Retroalimentación de efectividad
```

### 8.2 Flujo de Cursos (RRHH)

```
┌─────────────────────────────────────────────────────────┐
│  A. CREAR CATÁLOGO DE CURSOS (Admin)                  │
└─────────────────────────────────────────────────────────┘

  A.1 Admin entra a "Gestionar Cursos"
  A.2 Click "Nuevo Curso"
  A.3 Formulario:
      ├─ Código (auto-generado: CURSO-XXX)
      ├─ Nombre: "SARLAFT Básico"
      ├─ Descripción: "..."
      ├─ Categoría: "Cumplimiento Normativo" (radio)
      ├─ Modalidad: "Virtual" (radio)
      ├─ Duración: 8 horas (number)
      ├─ Objetivo: "..." (textarea)
      ├─ Contenido: "..." (editor)
      ├─ Área responsable: "Formación" (dropdown)
      ├─ Costo: $500 (money)
      ├─ Requiere certificado: ☑ (checkbox)
      ├─ Vigencia: 12 meses (number)
      └─ Estado: Activo ☑ (checkbox)
  A.4 Click "Crear"
  A.5 Guardar en BD


┌─────────────────────────────────────────────────────────┐
│  B. ASIGNAR CURSOS A CARGOS                            │
└─────────────────────────────────────────────────────────┘

  B.1 Admin entra a "Asignaciones Curso X Cargo"
  B.2 Tabla con 3 columnas: Curso, Cargo, Acciones
  B.3 Click "Asignar"
      ├─ Dropdown: Seleccionar Curso
      ├─ Dropdown: Seleccionar Cargo
      ├─ Checkbox: "Es obligatorio"
      ├─ Number: "Orden de secuencia"
      ├─ Date: "Vigente desde/hasta"
      └─ Click "Asignar"
  B.4 Guardar relación CursoXCargo


┌─────────────────────────────────────────────────────────┐
│  C. CREAR RUTAS DE FORMACIÓN (Admin)                   │
└─────────────────────────────────────────────────────────┘

  C.1 Admin entra a "Rutas de Formación"
  C.2 Click "Nueva Ruta"
  C.3 Formulario:
      ├─ Nombre: "Ruta Inducción Developer" (text)
      ├─ Descripción: "..." (textarea)
      ├─ Cargo: "Developer" (dropdown)
      ├─ Área: "Tecnología" (dropdown)
      ├─ Versión: 1.0 (text)
      └─ Estado: Activa ☑ (checkbox)
  C.4 Agregar Cursos (dynamic)
      ├─ Curso 1: "Inducción Corporativa" | Obligatorio | Secuencia 1
      ├─ Curso 2: "SST" | Obligatorio | Secuencia 2
      ├─ Curso 3: "Git Avanzado" | Opcional | Secuencia 3
      └─ Botón "+ Agregar Curso"
  C.5 Click "Crear Ruta"


┌─────────────────────────────────────────────────────────┐
│  D. ASIGNAR CURSOS A EMPLEADO (RRHH)                   │
└─────────────────────────────────────────────────────────┘

  D.1 RRHH abre ProcesoIngreso
  D.2 Click en tab "Formación"
  D.3 Panel izqdo: Cursos sugeridos (IA)
      ├─ Inducción Corporativa [+] → Agregar
      ├─ SST [+] → Agregar
      ├─ SARLAFT [+] → Agregar
      └─ [Ver todos los cursos disponibles]
  D.4 Panel dcho: Cursos seleccionados
      ├─ Inducción Corporativa -- Inicio: [date]
      ├─ SST -- Inicio: [date]
      ├─ SARLAFT -- Inicio: [date]
      └─ Botón "Asignar Cursos"
  D.5 Sistema registra AsignacionCurso (3 registros)
  D.6 Envía email a empleado
  D.7 Enviá a área de formación


┌─────────────────────────────────────────────────────────┐
│  E. EMPLEADO COMPLETA CURSOS                           │
└─────────────────────────────────────────────────────────┘

  E.1 Empleado recibe email con enlace "Mis Cursos"
  E.2 Queda autenticado (portal del empleado)
  E.3 Ve tabla:
      ├─ Inducción Corporativa | En Progreso | 40% | Ver
      ├─ SST | Pendiente | 0% | Ver
      └─ SARLAFT | Pendiente | 0% | Ver
  E.4 Click "Ver" en Inducción
      ├─ Acceso a contenido (LMS)
      ├─ Módulos: 1, 2, 3, 4, 5
      ├─ Completar evaluación
      └─ Click "Marcar Completado"
  E.5 Sistema actualiza AsignacionCurso
      ├─ estado: "En Progreso" → "Completado"
      ├─ fecha_completacion: hoy
      ├─ calificacion: 85/100
      └─ certificado_url: /certs/...


┌─────────────────────────────────────────────────────────┐
│  F. JEFE VALIDA COMPLETACIÓN (si aplica)             │
└─────────────────────────────────────────────────────────┘

  F.1 Jefe recibe notificación
      └─ "Empleado XXX completó curso YYYY"
  F.2 Jefe accede a "Validar Cursos"
  F.3 Ve tabla de cursos completados
  F.4 Click en curso
      ├─ Ver calificación
      ├─ Ver certificado
      ├─ Opción: Aprobar / Rechazar + motivo
      └─ Registrar decisión
  F.5 Sistema actualiza AsignacionCurso
      ├─ responsable_validacion_id = jefe.id
      └─ estado: "Completado" (validado)
```

### 8.3 Flujo de Reportes

```
ACCESO A REPORTES
├─ Admin: Ver todos (todos los usuarios, todas las áreas)
├─ Jefe: Ver su área + sus empleados
└─ Operador: Ver solo su área

FLUJO:
  1. Ejecutivo abre "Reportes"
  2. Selecciona tipo (dropdown)
  3. Rango de fechas (fecha_desde, fecha_hasta)
  4. Filtros adicionales (área, cargo, estado)
  5. Click "Generar"
     └─ Sistema consulta BD
     └─ Calcula KPIs
     └─ Genera gráficos
  6. Mostrar resultado
  7. Opciones: Descargar PDF, Compartir, Disparar alerta
```

### 8.4 Flujo de Auditoría

```
CADA ACCIÓN REGISTRA AUDITORÍA

Ejemplo: Cambiar estado de Solicitud

  1. Usuario hace clic "Marcar Entregado"
  2. Controlador ejecuta cambiarEstado()
  3. TRIGGER: Middleware LogAuditoria
     ├─ usuario_id = auth().id()
     ├─ accion = "update"
     ├─ entidad = "solicitudes"
     ├─ entidad_id = $solicitud->id
     ├─ valores_anteriores = { estado: "Pendiente" }
     ├─ valores_nuevos = { estado: "Entregado" }
     ├─ motivo = $request->motivo ?? "N/A"
     ├─ ip_origin = $request->ip()
     ├─ user_agent = $request->header('User-Agent')
     └─ Guardar en AuditoriaOnboarding
  4. Notificar cambio
  5. Responder a usuario

ACCESO A AUDITORÍA
  ├─ Admin: Ver todo
  ├─ Área Manager: Ver su área
  └─ Jefe: Ver sus empleados (limitado)

BÚSQUEDA EN AUDITORÍA
  ├─ Por usuario
  ├─ Por fecha rango
  ├─ Por acción
  ├─ Por entidad
  ├─ Por entidad_id
  └─ Full-text en motivo
```

---

## 9. UX/UI CORPORATIVO

### 9.1 Paleta de Colores

```
COLORES PRIMARIOS (Obligatorios):
├─ #1B365D Azul Oxford
│  ├─ Uso: Headers, botones primarios, links
│  ├─ RGB: 27, 54, 93
│  └─ Opciones:
│      ├─ Hover: #152a47 (más oscuro)
│      └─ Light: #e8ecf2 (para backgrounds)
│
├─ #28A745 Verde Cooperativo
│  ├─ Uso: Éxito, completado, positivo
│  ├─ RGB: 40, 167, 69
│  └─ Opciones:
│      ├─ Hover: #206c3f (más oscuro)
│      └─ Light: #e8f5e9 (para backgrounds)
│
├─ #C59D42 Dorado Mate
│  ├─ Uso: Atención, premium, destacado
│  ├─ RGB: 197, 157, 66
│  └─ Opciones:
│      ├─ Hover: #9f7d34 (más oscuro)
│      └─ Light: #fef9f3 (para backgrounds)
│
└─ #F8F9FA Blanco Humo
   └─ Uso: Backgrounds, tarjetas

COLORES DE ESTADO:
├─ Verde #28A745: Completado, Entregado, Activo
├─ Naranja #FFC107: En Progreso, En Proceso
├─ Rojo #DC3545: Error, Pendiente Urgente
├─ Gris #6C757D: Pendiente, Inactivo
└─ Azul #1B365D: Información, En revisión

ESCALA DE GRISES:
├─ #1A1A1A: Texto principal
├─ #4A4A4A: Texto secundario
├─ #7F7F7F: Placeholder, disabled
├─ #D0D0D0: Bordes
├─ #E8E8E8: Separadores
└─ #F5F5F5: Backgrounds suaves
```

### 9.2 Componentes Branded

```
<!-- Button Primary (Azul Oxford) -->
<button class="btn-primary">
  background-color: #1B365D;
  color: white;
  border-radius: 6px;
  padding: 10px 20px;
  font-weight: 600;
  &:hover { background-color: #152a47; }
</button>

<!-- Button Success (Verde) -->
<button class="btn-success">
  background-color: #28A745;
  color: white;
  &:hover { background-color: #206c3f; }
</button>

<!-- Card Corporativo -->
<div class="card-corporativo">
  border-left: 4px solid #1B365D;
  background: white;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  border-radius: 8px;
  padding: 20px;
  
  .card-header {
    color: #1B365D;
    font-weight: 700;
    border-bottom: 1px solid #E8E8E8;
  }
</div>

<!-- Alert Corporativo -->
<div class="alert-success">
  background-color: #e8f5e9;
  border-left: 4px solid #28A745;
  color: #206c3f;
</div>

<!-- Badge -->
<span class="badge badge-primary">
  background-color: #e8ecf2;
  color: #1B365D;
  padding: 4px 12px;
  border-radius: 20px;
</span>

<!-- Progress Bar -->
<div class="progress-corporate">
  background: #E8E8E8;
  
  .progress-bar {
    background: linear-gradient(90deg, #1B365D 0%, #28A745 100%);
  }
</div>

<!-- Breadcrumb -->
<nav class="breadcrumb-corporate">
  a { color: #1B365D; }
  .active { color: #4A4A4A; }
</nav>

<!-- Form Input -->
<input type="text" class="form-control-corporate">
  border: 1px solid #D0D0D0;
  border-radius: 6px;
  padding: 10px 12px;
  font-size: 14px;
  
  &:focus {
    border-color: #1B365D;
    box-shadow: 0 0 0 3px rgba(27, 54, 93, 0.1);
  }
</input>

<!-- Tab Navigation -->
<div class="nav-tabs-corporate">
  .nav-link {
    color: #4A4A4A;
    border-bottom: 2px solid transparent;
    
    &.active {
      color: #1B365D;
      border-bottom-color: #1B365D;
      font-weight: 600;
    }
  }
</div>
```

### 9.3 Layouts

```
┌─────────────────────────────────────────────────────────┐
│  NAVBAR (Altura: 64px)                                 │
│  Background: #1B365D (Azul Oxford)                     │
│  ├─ Logo (izq)                                          │
│ ├─ Título página (centro)                             │
│ └─ User menu (der)                                     │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  BREADCRUMB (bajo navbar)                              │
│  Home > Procesos Ingreso > ING-202602131234            │
└─────────────────────────────────────────────────────────┘

┌──────────────────┬──────────────────────────────────────┐
│  SIDEBAR         │  CONTENIDO PRINCIPAL               │
│  (240px)         │                                    │
│  Background:     │  - Encabezado con filtros         │
│  #F8F9FA         │  - Tabla/Cards                     │
│                  │  - Paginación                      │
│  - Menu items    │  - Acciones                        │
│  - Badges        │                                    │
└──────────────────┴──────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  FOOTER                                                 │
│  Copyright © 2026 Empresa Financiera                   │
│  | Términos | Privacidad | Soporte | Contacto |       │
└─────────────────────────────────────────────────────────┘
```

### 9.4 Patrones de Interacción

```
1. CONFIRMACIÓN DE ACCIONES DESTRUCTIVAS
   └─ Modal con título, descripción, botones Cancelar/Confirmar

2. CARGA DE DATOS
   └─ Skeleton screens → Contenido
   └─ Animación de slide-in

3. VALIDACIÓN DE FORMULARIOS
   └─ En tiempo real (frontend)
   └─ Mensajes de error en rojo (#DC3545)
   └─ Iconos de validación (✓ en verde)

4. NOTIFICACIONES
   ├─ Toast (esquina superior derecha)
   │  ├─ Success: Verde
   │  ├─ Error: Rojo
   │  ├─ Info: Azul
   │  └─ Warning: Naranja
   └─ Durabilidad: 5 segundos

5. FLUJOS MULTISTEP
   └─ Indicador de progreso (Step 1 of 5)
   └─ Botones Anterior/Siguiente
   └─ Guardar borradores

6. BÚSQUEDA Y FILTROS
   └─ Campo de búsqueda (debounce 300ms)
   └─ Filtros inmediatos (sin botón)
   └─ "Clear all" si hay filtros activos

7. TABLAS RESPONSIVAS
   └─ Desktop: Tabla normal
   └─ Mobile: Stack vertical con etiquetas
   └─ Acciones en botón "..." (menú)

8. MODALES
   └─ Overlay oscuro (rgba)
   └─ Transición suave (300ms)
   └─ Cerrar con X o Escape
```

### 9.5 Tipografía

```
FUENTE PRIMARIA: Inter
├─ H1: 32px, 700, line-height: 1.3
├─ H2: 24px, 700, line-height: 1.4
├─ H3: 18px, 600, line-height: 1.5
├─ Body: 14px, 400, line-height: 1.6
├─ Small: 12px, 400, line-height: 1.5
└─ Code: Courier New, 12px

COLORES DE TEXTO:
├─ Primario: #1A1A1A
├─ Secundario: #4A4A4A
├─ Terciario: #7F7F7F
└─ Links: #1B365D
```

### 9.6 Espaciado

```
ESCALA (multiplicador 4px):
├─ xs: 4px (0.25rem)
├─ sm: 8px (0.5rem)
├─ md: 12px (0.75rem)
├─ lg: 16px (1rem)
├─ xl: 24px (1.5rem)
├─ 2xl: 32px (2rem)
├─ 3xl: 48px (3rem)
└─ 4xl: 64px (4rem)

USO:
├─ Padding interno botón: 10px 20px (lg + lg)
├─ Margin entre elementos: 16px (lg)
├─ Padding card: 24px (xl)
└─ Gap entre columnas: 24px (xl)
```

---

## 10. ROADMAP DE IMPLEMENTACIÓN

### Fase 1: FUNDAMENTOS (Semanas 1-2)

```
SEMANA 1:
├─ [ ] Crear migraciones de BD (Cursos, Asignaciones, Rutas, Auditoría)
├─ [ ] Crear modelos (Curso, AsignacionCurso, RutaFormacion, etc.)
├─ [ ] Crear factories y seeders
├─ [ ] Correr migraciones en BD
└─ [ ] Validar estructura de datos

SEMANA 2:
├─ [ ] Crear config corporativo (colores, componentes)
├─ [ ] Crear componentes Blade corporativos (buttons, cards, alerts)
├─ [ ] Aplicar colores a layout existente
├─ [ ] Crear archivo CSS corporativo
└─ [ ] Validar diseño corporativo
```

### Fase 2: MÓDULO CURSOS (Semanas 3-4)

```
SEMANA 3:
├─ [ ] Crear CursoController (CRUD completo)
├─ [ ] Crear vistas: index, create, edit, show
├─ [ ] Crear CursoRepository
├─ [ ] Crear CursoPolicy (autorizaciones)
├─ [ ] Crear validaciones en CursoRequest
└─ [ ] Testear CRUD de cursos

SEMANA 4:
├─ [ ] Crear CursoXCargoController (asignaciones)
├─ [ ] Crear CursoXAreaController
├─ [ ] Crear vistas: matriz de asignación
├─ [ ] Implementar asignación masiva
├─ [ ] Crear índices en BD para performance
└─ [ ] Testear asignaciones
```

### Fase 3: MÓDULO RUTAS Y ASIGNACIÓN (Semanas 5-6)

```
SEMANA 5:
├─ [ ] Crear RutaFormacionController
├─ [ ] Crear vistas: index, create, edit
├─ [ ] UI drag-and-drop para ordenar cursos
├─ [ ] Crear RutaFormacionRepository
├─ [ ] Crear RutaFormacionPolicy
└─ [ ] Testear creación de rutas

SEMANA 6:
├─ [ ] Crear AsignacionCursoController
├─ [ ] Panel RRHH: Buscar proceso ingreso
├─ [ ] UI: Sugerencias automáticas (IA simple)
├─ [ ] UI: Seleccionar múltiples cursos
├─ [ ] Crear AsignacionCursoRequest
├─ [ ] Implementar notificaciones
└─ [ ] Testear asignaciones
```

### Fase 4: REPORTES (Semanas 7-8)

```
SEMANA 7:
├─ [ ] Crear ReporteController
├─ [ ] Crear ReportService (lógica)
├─ [ ] Implementar 3 reportes básicos:
│   ├─ Dashboard Ejecutivo
│   ├─ Cumplimiento por Área
│   └─ Formación
├─ [ ] Crear vistas con gráficos (Chart.js)
├─ [ ] Implementar filtros
└─ [ ] Testear reportes

SEMANA 8:
├─ [ ] Implementar 5 reportes restantes
├─ [ ] Exportar a PDF (dompdf/TCPDF)
├─ [ ] Descargar CSV
├─ [ ] Programar reporte automático (Job)
├─ [ ] Email con reporte
└─ [ ] Testear exportación
```

### Fase 5: AUDITORÍA (Semana 9)

```
├─ [ ] Crear tabla AuditoriaOnboarding
├─ [ ] Crear middleware LogAuditoria
├─ [ ] Aplicar middleware a rutas críticas
├─ [ ] Crear AuditoriaController
├─ [ ] Crear vistas: tabla searchable, timeline
├─ [ ] Implementar filtros avanzados
├─ [ ] Crear reportes de auditoría
└─ [ ] Testear auditoría completa
```

### Fase 6: IA Y RECOMENDACIONES (Semana 10)

```
├─ [ ] Crear AIService (servicios de IA)
├─ [ ] Implementar Recomendador de Insumos
│   ├─ Analizar histórico
│   ├─ Generar sugerencias
│   └─ Almacenar en tabla Recomendaciones
├─ [ ] Implementar Sugeridor de Rutas
│   ├─ Identificar cursos obligatorios
│   ├─ Identificar cursos recomendados
│   └─ Ordenar secuencia
├─ [ ] Implementar Predictor de Retrasos
├─ [ ] UI: Mostrar recomendaciones en formularios
└─ [ ] Testear IA
```

### Fase 7: MEJORAS FINALES (Semana 11)

```
├─ [ ] Optimizar performance BD (índices, queries)
├─ [ ] Implementar caché de reportes
├─ [ ] Validar seguridad (OWASP Top 10)
├─ [ ] Testeo de penetración
├─ [ ] Validar cumplimiento normativo
├─ [ ] Documentación técnica
├─ [ ] Manual de usuario
└─ [ ] Capacitación a usuarios
```

### Fase 8: DEPLOYMENT (Semana 12)

```
├─ [ ] Setup servidor producción
├─ [ ] Configurar backups automáticos
├─ [ ] Configurar monitoreo
├─ [ ] Configurar logging centralizado
├─ [ ] Migración de datos piloto
├─ [ ] UAT con usuarios reales
├─ [ ] Ajustes finales
└─ [ ] Go-live
```

### Cronograma Visual

```
           Semana 1          Semana 5          Semana 9         Semana 12
              ↓                 ↓                 ↓                ↓
Fundamentos ████               |                 |                |
Cursos      |    ████████      |                 |                |
Rutas       |          ████████                  |                |
Reportes    |                  ████████          |                |
Auditoría   |                          ████      |                |
IA          |                               ████ |                |
Mejoras     |                                    ████             |
Deploy      |                                         ████████████

Timeline: 12 semanas
Equipo estimado: 3 developers + 1 QA
Total de funciones nuevas: 45+
Total de líneas de código: ~8,500
Complejidad: Alta
```

---

## 11. CONCLUSIONES Y RECOMENDACIONES

### 11.1 Situación Actual

El sistema está en fase **FUNCIONAL PERO INCOMPLETO** (54% cumplimiento). Tiene:
- ✅ Estructura base sólida con Laravel Best Practices
- ✅ 5 módulos de solicitudes por área implementados
- ✅ Sistema de roles y permisos funcional
- ❌ Falta el módulo crítico de CURSOS
- ❌ Falta el módulo de REPORTES
- ❌ Falta el módulo de IA/RECOMENDACIONES
- ❌ Falta aplicación real del diseño corporativo

### 11.2 Próximos Pasos Inmediatos

1. **SEMANA 1:** Implementar modelo de CURSOS (crítico)
2. **SEMANA 2:** Crear interface RRHH para asignar cursos
3. **SEMANA 3:** Implementar REPORTES básicos
4. **SEMANA 4:** Aplicar colores corporativos

### 11.3 Riescos a Mitigar

```
RIESGO                           IMPACTO   PROBABILIDAD   MITIGACIÓN
────────────────────────────────────────────────────────────────────────
Falta módulo cursos              ALTO      ALTA           Priorizar Fase 2
Sin reportes ejecutivos          ALTO      ALTA           Priorizar Fase 4
Incumplimiento normativo         CRÍTICO   MEDIA          Auditoría +Tests
Performance con 1000+ usuarios   MEDIO     MEDIA          Índices BD, caché
Integración AD real              MEDIO     MEDIA          Usar librería LDAP
Falta de documentación           BAJO      ALTA           Wiki, Runbooks
```

### 11.4 Logros Destacables

✅ Arquitectura modular y escalable  
✅ Sistema de notificaciones integrado  
✅ Check-in with firma digital  
✅ Plano interactivo de puestos  
✅ Generador de datos de prueba  
✅ API lista para extender  
✅ Tests unitarios base  

### RECOMENDACIÓN FINAL

**Este es un proyecto viable y con buenos fundamentos. Con las mejoras propuestas en este documento, será un sistema empresarial ROBUSTO, COMPLETO y COMPETITIVO en el mercado.**

Inversión de tiempo estimada para completitud 100%: **12 semanas**  
Equipoesperado: **3-4 desarrolladores**  
ROI: **Alto (automatización, reducción de tiempos, normativo)**

---

**Documento finalizado:** Febrero 13, 2026  
**Próxima revisión:** Después de implementar Fase 1
