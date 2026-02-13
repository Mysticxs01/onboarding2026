# 🏛️ RESUMEN EJECUTIVO - AUDITORÍA ARQUITECTÓNICA DDD

**Fecha:** Febrero 2026  
**Estado:** CRÍTICO - Requiere refactorización inmediata  
**Audiencia:** Arquitecto de Software Senior  

---

## 1️⃣ DIAGNÓSTICO CRÍTICO

### PROBLEMA FUNDAMENTAL

```
El sistema actual VIOLA los principios DDD al crear MÚLTIPLES
"fuentes de verdad" para conceptos que debería haber ÚNOS.

Resultado: Desincronización de datos, inconsistencia semántica,
           viabilidad comprometida para escalado.
```

### HALLAZGOS PRINCIPALES

| Hallazgo | Severidad | Descripción |
|----------|-----------|-------------|
| **5-7 modelos de "cargo"** | 🔴 CRÍTICO | `cargos`, `puestos`, `puestos_trabajo`, `maestro_cargos`, `posiciones` - Duplicidad semántica |
| **Falta jerarquía organizacional** | 🔴 CRÍTICO | Areas existen pero sin relación a Gerencias (falta nivel superior) |
| **Tabla `posiciones` incoherente** | 🔴 CRÍTICO | Duplica asignación cargo/usuario que ya existe en `users.cargo_id` |
| **RBAC confundido con cargos** | 🟠 GRAVE | Conceptos separados (permisos vs posiciones) tratados como uno solo |
| **Solicitudes duplicadas** | 🟠 GRAVE | `solicitudes` + `solicitudes_servicios_generales` = estructura paralela |
| **maestro_cargos innecesario** | 🟠 GRAVE | Creado erróneamente en migración - redundante de `cargos` |
| **5+ controladores redundantes** | 🟡 ALTO | Cada tipo solicitud tiene controlador propio (consolidable en 1) |

---

## 2️⃣ ANÁLISIS DETALLADO POR ENTIDAD

### ❌ ENTIDADES A ELIMINAR (4)

```
1. maestro_cargos
   ├─ Tipo: Tabla duplicada
   ├─ Razón: Copia innecesaria de "cargos"
   ├─ Impacto: Desincronización
   └─ Acción: DROP TABLE maestro_cargos

2. posiciones
   ├─ Tipo: Tabla de asignación defectuosa
   ├─ Razón: Duplica relación users.cargo_id
   ├─ Impacto: Dos "fuentes de verdad" for cargo assignment
   └─ Acción: DROP TABLE posiciones

3. solicitudes_servicios_generales
   ├─ Tipo: Estructura paralela
   ├─ Razón: Debería ser users.solicitudes con tipo='servicios_generales'
   ├─ Impacto: Código duplicado, lógica fragmentada
   └─ Acción: DROP TABLE, migrar datos a solicitudes

4. historico_posicion (si existe)
   ├─ Tipo: Relacional innecesaria
   ├─ Razón: No tiene uso activo
   ├─ Impacto: Deuda técnica
   └─ Acción: DROP TABLE
```

### 🔄 ENTIDADES A REFACTORIZAR (2)

```
1. solicitudes
   ├─ Problema: Múltiples herencias (solicitudes_servicios_generales)
   ├─ Solución: Campo "tipo" ENUM + JSON flexible para detalles
   ├─ Resultado: Un modelo, múltiples contextos
   └─ Modelos a consolidar: SolicitudBienes, SolicitudDotacion, etc → TODOS en UNA tabla

2. roles (de Spatie)
   ├─ Problema: Confundido con cargos corporativos
   ├─ Solución: Usar SOLO para RBAC (admin, jefe_area, operador)
   ├─ Separación clara: Cargo ≠ Rol
   └─ Resultado: RBAC limpio, independiente de estructura org
```

### ✅ ENTIDADES VÁLIDAS (21)

```
CORE ESTRUCTURAL (4):
✅ gerencias          [NUEVA - crear] - Nivel superior
✅ areas              [ACTUALIZAR] - Agregar gerencia_id
✅ cargos             [MANTENER] - Única fuente de cargos
✅ users              [MANTENER] - Personas reales

ONBOARDING (7):
✅ procesos_ingresos  - Flujo de ingreso
✅ solicitudes        [REFACT] - Unificar tipos
✅ plantilla_solicitudes
✅ curso_x_cargo
✅ asignacion_cursos
✅ ruta_formacion
✅ ruta_x_curso

RECURSOS (6):
✅ detalles_tecnologia
✅ detalles_uniformes
✅ elementos_proteccion
✅ items_inmobiliario
✅ checkins
✅ puestos_trabajo    [RESOURCE] - Infraestructura física

AUDITORÍA (2):
✅ auditoria_onboarding
✅ reporte_cumplimiento

RBAC (3):
✅ roles, permissions, model_has_roles
```

---

## 3️⃣ MODELO ARQUITECTÓNICO CORRECTO (DDD)

### ESTRUCTURA JERÁRQUICA CORRECTA

```
┌─────────────────────────────────────────────────┐
│         GERENCIA (Nivel 1 - 7 records)          │
│  (Gerencia General, Administración, Comercial)  │
│                     │                           │
│                     ▼                           │
│  ÁREA (Nivel 2 - 24 records)                    │
│  (desde gerencias - relación 1:N)               │
│                     │                           │
│                     ▼                           │
│  CARGO (Nivel 3 - 54 records)                   │
│  (desde áreas - relación 1:N)                   │
│                     │                           │
│                     ▼                           │
│  USER (Nivel 4 - 11 usuarios)                   │
│  (personas reales ocupando cargos)              │
│                     │                           │
│                     ▼                           │
│      ROLES (RBAC independiente)                 │
│  (permisos: admin, jefe_area, etc)              │
└─────────────────────────────────────────────────┘
```

### DIAGRAMA RELACIONAL (Versión correcta)

```
gerencias (7)
  id (PK)
  nombre UNIQUE
  codigo UNIQUE
       │
       ├─→ areas (24)
       │     id (PK)
       │     gerencia_id (FK)
       │     nombre
       │          │
       │          ├─→ cargos (54)
       │          │     id (PK)
       │          │     area_id (FK)
       │          │     nombre
       │          │          │
       │          │          ├─→ users (11)
       │          │          │     id (PK)
       │          │          │     cargo_id (FK)
       │          │          │     area_id (FK)
       │          │          │          │
       │          │          │          └─→ roles (RBAC)
       │          │          │                id, nombre
       │          │          │                (admin, jefe_area)
       │          │          │
       │          │          └─→ curso_x_cargo
       │          │
       │          └─→ solicitudes
       │                id (PK)
       │                usuario_id (FK)
       │                tipo: ENUM
       │                estado: ENUM
       │                detalles: JSON
```

---

## 4️⃣ CONSOLIDACIÓN DE CONTROLADORES

### ANTES (Fragmentado)

```
Controllers/
├─ SolicitudController.php
├─ SolicitudBienesController.php
├─ SolicitudDotacionController.php
├─ SolicitudFormacionController.php
├─ SolicitudServiciosGeneralesController.php
├─ SolicitudTecnologiaController.php
└─ ... (6 controladores para lo mismo)
```

**Problema:** Código altamente duplicado, lógica fragmentada

### DESPUÉS (Consolidado)

```
Controllers/
├─ SolicitudController.php
│  └─ Maneja TODOS los tipos via parámetro "tipo"
├─ ProcesoIngresoController.php
├─ CursoController.php
└─ ... (Controladores específicos del dominio)
```

**Beneficio:** DRY, lógica centralizada, mantenimiento fácil

---

## 5️⃣ ARCHIVOS A ELIMINAR INMEDIATAMENTE

### Modelos (4 archivos)
```bash
❌ app/Models/MaestroCargo.php
❌ app/Models/Posicion.php
❌ app/Models/SolicitudServiciosGenerales.php
❌ app/Models/HistoricoPosicion.php (si existe)
```

### Controladores (5 archivos)
```bash
❌ app/Http/Controllers/SolicitudBienesController.php
❌ app/Http/Controllers/SolicitudDotacionController.php
❌ app/Http/Controllers/SolicitudFormacionController.php
❌ app/Http/Controllers/SolicitudServiciosGeneralesController.php
❌ app/Http/Controllers/SolicitudTecnologiaController.php
```

### Migraciones (2 archivos)
```bash
❌ database/migrations/2026_02_13_000001_reorganizar_usuarios_cargos.php
❌ database/migrations/2026_02_13_000009_create_posiciones_table.php
```

**Total:** 11 archivos innecesarios

---

## 6️⃣ CAMBIOS REQUERIDOS - PRIORIDAD

### 🔴 P0 - INMEDIATO (Hoy)

1. **Eliminar tablas base de datos** (DROP TABLE)
   - maestro_cargos
   - posiciones
   - solicitudes_servicios_generales
   - historico_posicion

2. **Eliminar modelos PHP**
   - MaestroCargo.php
   - Posicion.php
   - SolicitudServiciosGenerales.php

3. **Crear migración inversa**
   - Revertir migrations problemáticas

### 🟠 P1 - URGENTE (Días 1-2)

4. **Crear tabla gerencias** (+7 registros)
5. **Agregar FK en areas** (gerencia_id)
6. **Crear Modelo Gerencia**
7. **Validar integridad de datos**

### 🟡 P2 - IMPORTANTE (Días 3-4)

8. **Refactorizar solicitudes**
   - Agregar campo `tipo: ENUM`
   - Agregar campo `detalles: JSON`
   - Unificar lógica

9. **Consolidar controladores**
   - Mantener solo SolicitudController
   - Eliminar otros 5

### 🟢 P3 - IMPORTANTE (Días 5-8)

10. **Validate RBAC separation**
11. **Update documentation**
12. **Testing** completo
13. **Deploy** a producción

---

## 7️⃣ IMPACTO Y BENEFICIOS

### ANTES (Caótico)
```
✗ Múltiples fuentes de verdad
✗ Desincronización de datos
✗ Código duplicado (5 controladores)
✗ Falta jerarquía organizacional
✗ RBAC mezclado con cargos
✗ No escalable
✗ Difícil de mantener
```

### DESPUÉS (DDD Limpio)
```
✓ Una fuente de verdad por concepto
✓ Datos sincronizados
✓ Código DRY centralizado
✓ Jerarquía clara: Gerencia → Area → Cargo → User
✓ RBAC separado e independiente
✓ Escalable y flexible
✓ Fácil de mantener
✓ Listo para crecer
```

---

## 8️⃣ ESTIMACIÓN DE ESFUERZO

| Fase | Descripción | Tiempo | Sprint |
|------|-------------|--------|--------|
| 1 | Preparación/Backup | 2h | 0 |
| 2 | Eliminar tablas/modelos | 4h | 1 |
| 3 | Crear gerencias | 6h | 2 |
| 4 | Validar integridad | 4h | 3 |
| 5 | Unificar solicitudes | 8h | 4 |
| 6 | Consolidar controladores | 6h | 5 |
| 7 | RBAC verification | 4h | 6 |
| 8 | Migraciones finales | 4h | 7 |
| 9 | Documentación | 4h | 8 |
| Testing/Regression | 8h | - |
| **TOTAL** | | **50h** | **8 sprints** |

---

## 9️⃣ RIESGOS Y MITIGACIÓN

| Riesgo | Probabilidad | Impacto | Mitigación |
|--------|--------------|---------|-----------|
| Pérdida de datos | Baja | CRÍTICO | Backup completo ANTES de empezar |
| Inconsistencia durante migration | Media | GRAVE | Scripts de validación post-migración |
| Rollback fallido | Baja | CRÍTICO | Probar rollback antes en staging |
| Downtime en producción | Media | GRAVE | Migration en horario baixo uso |
| Empleados sin "ubicación de cargo" | Media | MEDIO | Mapeo manual gerencia↔area |

---

## 🔟 CHECKLIST DE APROBACIÓN

- [ ] Arquitecto revisa y aprueba plan
- [ ] Backup completo creado
- [ ] Equipo disponible para 8 sprints
- [ ] Testing environment preparado
- [ ] Rollback plan validado
- [ ] Comunicación a stakeholders
- [ ] Fecha de inicio confirmada

---

## 📊 RESUMEN EJECUTIVO DE UNA LÍNEA

```
Sistema requiere refactorización DDD inmediata:
Eliminar 4 tablas duplicadas + 11 archivos + consolidar 5 controladores.
Resultado: Arquitectura limpia, escalable, una fuente de verdad.
Esfuerzo: 50 horas = 8 sprints = ~2 semanas.
```

---

## 📎 DOCUMENTOS RELACIONADOS

1. **AUDITORIA_ARQUITECTONICA.md** → Análisis detallado de cada tabla
2. **PLAN_ACCION_REFACTORIZACION_DDD.md** → Paso a paso ejecutable
3. **ARQUITECTURA_DDD.md** → Documentación post-refactorización (por crear)

---

**APROBADO PARA REFACTORIZACIÓN INMEDIATA**

*Preparado para: Arquitecto de Software Senior*  
*Severidad: CRÍTICO*  
*Urgencia: INMEDIATA*
