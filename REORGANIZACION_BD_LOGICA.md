# Reorganización de la Estructura de BD: Usuarios y Cargos

## 📊 ANÁLISIS DEL PROBLEMA ACTUAL

### Estado Actual (Inconsistencia)

1. **Tabla `cargos`** (54 cargos totales):
   - ✅ Bien estructurada
   - Incluye todos los puestos de trabajo de la empresa
   - Ejemplo: "Gerente de TI", "Desarrollador Full Stack", "Coordinador de Formación"
   - **Propósito original**: Catálogo maestro de todos los puestos disponibles

2. **Tabla `users`** (11 usuarios actuales):
   - ❌ Tiene NULL en `cargo_id` para todos los usuarios
   - Contiene nombres de cargo EN LOS CAMPOS `name`:
     - "Jefe de RRHH"
     - "Coordinador de Formación"
     - "Jefe de Tecnología"
     - "Operador Tecnología"
     - etc.
   - **PROBLEMA**: Los cargos están hardcoded en el nombre, no usando la relación con la tabla `cargos`

### Conflicto de Propósitos

| Tabla | Propósito Original | Propósito Real Actual |
|-------|------|------|
| `cargos` | Catálogo de todos los puestos disponibles | Solo para puestos con VACANTES |
| `users` | Asignar cargo_id a cada usuario | El cargo viene en el nombre, no en la relación |

---

## 🎯 SOLUCIÓN PROPUESTA

### Lógica a Implementar

La tabla `cargos` debería **reservarse SOLO para puestos con vacantes disponibles** (aquellos donde todavía pueden ingresar nuevos empleados).

La tabla `users` debería contener **todos los jefes y coordinadores** capaces de resolver solicitudes de nuevo ingreso, cada uno vinculado correctamente a su cargo.

### Estructura Nueva

#### 1. **Tabla `cargos`** (modificada)
```sql
-- Solo mantiene cargos con vacantes disponibles
-- Agregar campo para rastrear disponibilidad
ALTER TABLE cargos ADD COLUMN (
  vacantes_disponibles SMALLINT DEFAULT 0,
  activo BOOLEAN DEFAULT 1
);
```

**Datos esperados en `cargos`**:
- Solo los ~20-25 puestos que efectivamente tienen vacantes
- Ejemplo: "Desarrollador Full Stack", "Analista de Crédito", etc.
- **Excluir**: Puestos como "Jefe de RRHH", "Gerente TI" (estos son para users)

---

#### 2. **Tabla `users`** (modificada)
```sql
-- Debe vincular correctamente a cargo_id
-- Los `name` deben ser NOMBRES DE PERSONAS, no cargos
-- Agregar campo para roles/responsabilidades
ALTER TABLE users ADD COLUMN (
  rol_onboarding VARCHAR(255) DEFAULT NULL,  -- jefe_area, coordinador, revisor
  puede_aprobar_solicitudes BOOLEAN DEFAULT 0
);
```

**Datos esperados en `users`**:
- User 1: name="Juan Pérez", cargo_id=46 (Gerente Talento Humano), rol_onboarding="jefe_area", puede_aprobar=true
- User 2: name="María García", cargo_id=49 (Coordinador de Formación), rol_onboarding="coordinador", puede_aprobar=true
- User 3: name="Carlos López", cargo_id=39 (Coordinador de Infraestructura), rol_onboarding="coordinador", puede_aprobar=true

---

### Tabla Nueva Recomendada: `maestro_cargos`

Para mantener un catálogo COMPLETO de todos los puestos (sin depender de vacantes):

```sql
CREATE TABLE maestro_cargos (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(255) NOT NULL UNIQUE,
  area_id BIGINT UNSIGNED NOT NULL,
  descripcion TEXT,
  nivel_jerarquico INT,
  es_puesto_entrada BOOLEAN DEFAULT 0,  -- true si acepta nuevos empleados
  activo BOOLEAN DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (area_id) REFERENCES areas(id)
);
```

**Propósito**: Referencia histórica y completa de TODOS los puestos de la empresa.

---

## 🔄 PLAN DE MIGRACIÓN

### FASE 1: Preparación
1. **Crear tabla `maestro_cargos`** con todos los 54 cargos actuales
2. **Verificar integridad** de datos

### FASE 2: Actualización de `users`
1. Extraer cargo del campo `name` actual
2. Buscar/crear el cargo en tabla `cargos` si no existe
3. Actualizar `cargo_id` en cada usuario
4. Actualizar `name` a nombre real (si se dispone)
5. Llenar campo `rol_onboarding` según responsabilidad

### FASE 3: Limpieza de `cargos`
1. Identificar qué cargos son "puestos de entrada" (con vacantes)
2. Eliminar del `cargos` aquellos que no aplican (jefes, gerentes)
3. Actualizar `vacantes_disponibles` con la cantidad real

### FASE 4: Validación
1. Verificar que cada usuario tenga cargo_id válido
2. Verificar que `can_approve_requests` esté correctamente asignado
3. Pruebas con solicitudes de nuevo ingreso

---

## 📋 MAPEO DE USUARIOS ACTUALES A CARGOS

```
User ID | Name Actual | Cargo Inferido | Area | Rol Onboarding
--------|-------------|-----------|------|------------------
1       | Administrador | Gerente Talento Humano? | 20 | ADMIN
2       | Jefe de RRHH | Gerente Talento Humano | 20 | jefe_area
3       | Coordinador de Formación | Coordinador de Formación y Capacitación | 22 | coordinador
4       | Root Admin | Gerente Talento Humano? | 1 | ADMIN
5       | Administrador | Gerente Administración? | 1 | ADMIN
6       | Jefe de Tecnología | Gerente de TI | 2/16 | jefe_area
7       | Operador Tecnología | Técnico de Soporte Nivel 1 | 2/19 | operador
8       | Operador Dotación | Asistente? | 1 | operador
9       | Operador Servicios | Asistente? | 3 | operador
10      | Operador Formación | Coordinador de Formación? | 4/22 | operador
11      | Operador Bienes | Asistente? | 5 | operador
```

---

## ✅ BENEFICIOS DE ESTA REORGANIZACIÓN

1. **Integridad referencial**: Cada usuario vinculado a un cargo real
2. **Escalabilidad**: Fácil agregar más jefes/coordinadores sin modificar SQL
3. **Auditoría**: Rastreo claro de quién aprobó qué y en qué rol
4. **Separación de responsabilidades**: 
   - `maestro_cargos` = catálogo completo histórico
   - `cargos` = vacantes activas
   - `users` = empleados actuales con roles

5. **Reportería mejorada**: Saber exactamente qué jefes/coordinadores pueden resolver cada tipo de solicitud

---

## 🚀 PRÓXIMOS PASOS

1. Revisión y aprobación de esta estructura
2. Crear migration de Laravel
3. Ejecutar script de sincronización de datos
4. Validar que no haya datos huérfanos
5. Actualizar controladores y vistas para usar las nuevas relaciones
