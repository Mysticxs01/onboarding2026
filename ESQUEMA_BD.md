# 📋 ESQUEMA DE BASE DE DATOS - Sinergia Onboarding

## Estructura Final Limpia y Normalizada

---

## 1. CAPA ORGANIZACIONAL

### Tabla: gerencias
```sql
CREATE TABLE gerencias (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(255) UNIQUE NOT NULL,
  codigo VARCHAR(10) UNIQUE,
  descripcion TEXT,
  activo BOOLEAN DEFAULT 1,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  deleted_at TIMESTAMP NULL
);

-- 6 Registros originales:
1. Gerencia Administración (GA)
2. Gerencia Comercial (GC)
3. Gerencia Riesgo y Crédito (GRC)
4. Gerencia Financiera (GF)
5. Gerencia TI (GTI)
6. Gerencia Talento Humano (GTH)
```

### Tabla: areas
```sql
CREATE TABLE areas (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  gerencia_id BIGINT NOT NULL (FK → gerencias.id CASCADE),
  nombre VARCHAR(255) NOT NULL,
  descripcion TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  deleted_at TIMESTAMP NULL
);

-- 18 Registros (según organigrama):
GA: Servicios Generales, Mantenimiento
GC: Ventas y Captación, Gestión de Canales, Marketing y Producto, Servicio al Cliente
GRC: Análisis de Crédito, Riesgo Operativo
GF: Tesorería, Contabilidad, Planeación
GTI: Infraestructura y Redes, Desarrollo de Software, Soporte Técnico
GTH: Selección y Reclutamiento, Formación y Capacitación, Nómina, Clima Organizacional
```

### Tabla: cargos
```sql
CREATE TABLE cargos (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  area_id BIGINT NOT NULL (FK → areas.id),
  nombre VARCHAR(255) NOT NULL,
  descripcion TEXT,
  nivel_jerarquico INT,
  vacantes_disponibles INT DEFAULT 0,
  activo BOOLEAN DEFAULT 1,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  deleted_at TIMESTAMP NULL
);

-- 54 Registros (posiciones reales de la cooperativa)
Ejemplos:
- Gerente Administrativo
- Coordinador de Servicios Corporativos
- Auxiliar de Servicios Generales
- Jefe de Infraestructura y Mantenimiento
- Gerente Comercial
- Ejecutivo de Captación y Colocación
... (54 total)
```

### Tabla: users
```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255) NOT NULL,
  
  -- ESTRUCTURA ORGANIZACIONAL
  area_id BIGINT (FK → areas.id),
  cargo_id BIGINT (FK → cargos.id),
  jefe_directo_id BIGINT NULL (FK → users.id - SELF),
  
  -- ONBOARDING
  rol_onboarding VARCHAR(50),
  puede_aprobar_solicitudes BOOLEAN DEFAULT 0,
  activo BOOLEAN DEFAULT 1,
  
  -- SISTEMA
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  deleted_at TIMESTAMP NULL
);

-- ~50 Empleados (datos reales de Sinergia)
```

---

## 2. CAPA ONBOARDING

### Tabla: procesos_ingresos
```sql
CREATE TABLE procesos_ingresos (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  codigo VARCHAR(50) UNIQUE NOT NULL,  -- Autogenerado
  usuario_id BIGINT (FK → users.id),
  nombre_empleado VARCHAR(255) NOT NULL,
  documento VARCHAR(50) NOT NULL,
  tipo_identificacion VARCHAR(50),
  
  cargo_id BIGINT NOT NULL (FK → cargos.id),
  area_id BIGINT NOT NULL (FK → areas.id),
  jefe_inmediato_id BIGINT (FK → users.id),
  
  fecha_ingreso DATE NOT NULL,
  estado ENUM('activo', 'cancelado', 'completado') DEFAULT 'activo',
  
  observaciones TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  deleted_at TIMESTAMP NULL
);

-- Relación 1:1 con usuario
```

### Tabla: solicitudes
```sql
CREATE TABLE solicitudes (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  proceso_ingreso_id BIGINT (FK → procesos_ingresos.id),
  usuario_id BIGINT NOT NULL (FK → users.id),
  
  -- TIPO DE SOLICITUD
  tipo ENUM(
    'dotacion',           -- Uniformes + EPP
    'tecnologia',         -- Credenciales + Hardware
    'servicios_generales', -- Puesto + Carnetización
    'formacion',          -- Inducción + Plan capacitación
    'bienes'              -- Inmobiliario + Insumos
  ) NOT NULL,
  
  -- ESTADO
  estado ENUM('pendiente', 'en_proceso', 'entregado', 'rechazado') 
         DEFAULT 'pendiente',
  
  descripcion TEXT,
  detalles JSON,        -- Datos específicos por tipo
  
  -- APROBACIÓN
  asignado_a_id BIGINT (FK → users.id - operador),
  aprobado_por_id BIGINT (FK → users.id - admin/jefe),
  fecha_aprobacion TIMESTAMP NULL,
  observaciones TEXT,
  
  fecha_vencimiento DATE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

-- Relación N:1 con ProcesoIngreso (1 proceso → N solicitudes)
```

---

## 3. CAPA FORMACIÓN

### Tabla: cursos
```sql
CREATE TABLE cursos (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(255) NOT NULL UNIQUE,
  objetivo TEXT NOT NULL,
  modalidad ENUM('presencial', 'virtual') NOT NULL,
  horas INT,
  descripcion TEXT,
  activo BOOLEAN DEFAULT 1,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

-- 31 Cursos (catálogo de formación)
1. Inducción a la Cultura Cooperativa (Presencial)
2. Portafolio de Productos y Servicios (Virtual)
3. Prevención de Lavado de Activos (Virtual)
... (31 total)
```

### Tabla: curso_x_cargo
```sql
CREATE TABLE curso_x_cargo (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  curso_id BIGINT NOT NULL (FK → cursos.id),
  cargo_id BIGINT NOT NULL (FK → cargos.id),
  es_obligatorio BOOLEAN DEFAULT 0,
  orden INT,
  created_at TIMESTAMP
);

-- Relación muchos a muchos
-- Define qué cursos deben tomar qué cargos
```

### Tabla: asignacion_cursos
```sql
CREATE TABLE asignacion_cursos (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  usuario_id BIGINT NOT NULL (FK → users.id),
  curso_id BIGINT NOT NULL (FK → cursos.id),
  proceso_ingreso_id BIGINT (FK → procesos_ingresos.id),
  
  estado ENUM('asignado', 'en_curso', 'completado', 'no_iniciado')
         DEFAULT 'asignado',
  
  fecha_asignacion DATE NOT NULL,
  fecha_inicio DATE NULL,
  fecha_finalizacion DATE NULL,
  calificacion INT NULL,
  
  observaciones TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

-- Jefe RRHH selecciona cursos de asignacion_cursos
```

### Tabla: ruta_formacion
```sql
CREATE TABLE ruta_formacion (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  usuario_id BIGINT NOT NULL (FK → users.id),
  
  nombre VARCHAR(255),
  descripcion TEXT,
  
  fecha_inicio DATE,
  fecha_fin_prevista DATE NULL,
  estado ENUM('activa', 'completada', 'suspendida') DEFAULT 'activa',
  
  progreso_porcentaje INT DEFAULT 0,
  
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

-- Plan de desarrollo individual
-- Agrupa múltiples asignacion_cursos en una ruta
```

### Tabla: ruta_x_curso
```sql
CREATE TABLE ruta_x_curso (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  ruta_formacion_id BIGINT NOT NULL (FK → ruta_formacion.id),
  asignacion_curso_id BIGINT NOT NULL (FK → asignacion_cursos.id),
  
  orden INT,
  es_prerrequisito BOOLEAN DEFAULT 0,
  
  created_at TIMESTAMP
);

-- Relación: Una ruta contiene muchas asignaciones de cursos
```

---

## 4. CAPA RECURSOS

### Tabla: detalles_uniformes
```sql
CREATE TABLE detalles_uniformes (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  solicitud_id BIGINT NOT NULL (FK → solicitudes.id),
  usuario_id BIGINT NOT NULL (FK → users.id),
  
  -- PRENDAS
  cantidad_uniformes INT,
  talla_uniforme VARCHAR(10),
  color_uniforme VARCHAR(50),
  
  -- EPP
  cantidad_epp INT,
  tipos_epp JSON,  -- Array de items
  
  estado ENUM('pendiente', 'preparando', 'entregado') DEFAULT 'pendiente',
  fecha_entrega DATE NULL,
  
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Tabla: detalles_tecnologia
```sql
CREATE TABLE detalles_tecnologia (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  solicitud_id BIGINT NOT NULL (FK → solicitudes.id),
  usuario_id BIGINT NOT NULL (FK → users.id),
  
  -- COMPUTADOR
  tipo_computador ENUM('portatil', 'escritorio'),
  marca VARCHAR(100),
  modelo VARCHAR(100),
  serial VARCHAR(100) UNIQUE,
  
  -- SOFTWARE
  software_requerido JSON,  -- Array de licenses
  
  -- ACCESOS
  usuario_sistema VARCHAR(100),
  email_corporativo VARCHAR(255) UNIQUE,
  
  estado ENUM('pendiente', 'preparando', 'entregado') DEFAULT 'pendiente',
  fecha_entrega DATE NULL,
  
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Tabla: elementos_proteccion
```sql
CREATE TABLE elementos_proteccion (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  solicitud_id BIGINT NOT NULL (FK → solicitudes.id),
  usuario_id BIGINT NOT NULL (FK → users.id),
  
  elemento VARCHAR(255),
  cantidad INT,
  serial_o_codigo VARCHAR(100),
  estado ENUM('pendiente', 'entregado') DEFAULT 'pendiente',
  fecha_entrega DATE NULL,
  
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Tabla: items_inmobiliario
```sql
CREATE TABLE items_inmobiliario (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  solicitud_id BIGINT NOT NULL (FK → solicitudes.id),
  usuario_id BIGINT NOT NULL (FK → users.id),
  
  tipo_item VARCHAR(100),  -- silla, escritorio, etc
  marca VARCHAR(100),
  cantidad INT,
  estado ENUM('pendiente', 'entregado') DEFAULT 'pendiente',
  
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Tabla: puestos_trabajo
```sql
CREATE TABLE puestos_trabajo (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  numero_puesto VARCHAR(50) UNIQUE,
  
  -- UBICACIÓN FÍSICA
  piso INT,
  seccion VARCHAR(50),
  ubicacion_x INT,
  ubicacion_y INT,
  
  -- ESTADO
  estado ENUM('disponible', 'asignado', 'mantenimiento', 'bloqueado')
         DEFAULT 'disponible',
  
  capacidad INT,  -- monitor count, etc
  descripcion TEXT,
  equipamiento JSON,
  notas TEXT,
  
  asignado_a_id BIGINT NULL (FK → users.id),
  
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

-- Mapa interactivo de puestos (como asientos de avión)
```

---

## 5. CAPA CONTROL

### Tabla: checkins
```sql
CREATE TABLE checkins (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  proceso_ingreso_id BIGINT NOT NULL (FK → procesos_ingresos.id),
  usuario_id BIGINT NOT NULL (FK → users.id),
  
  -- CONFIRMACIÓN
  confirmado BOOLEAN DEFAULT 0,
  fecha_confirmacion TIMESTAMP NULL,
  codigo_confirmacion VARCHAR(100) UNIQUE,
  
  -- ACTA DE ENTREGA
  numero_acta VARCHAR(100) UNIQUE,
  items_entregados JSON,  -- Array de items
  observaciones TEXT,
  
  -- FIRMA DIGITAL
  firma_digital TEXT,
  fecha_firma TIMESTAMP NULL,
  
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

-- Check-in similar a aerolínea
```

### Tabla: auditoria_onboarding
```sql
CREATE TABLE auditoria_onboarding (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  proceso_ingreso_id BIGINT NOT NULL (FK → procesos_ingresos.id),
  usuario_id BIGINT (FK → users.id),
  
  accion VARCHAR(255) NOT NULL,  -- created, updated, deleted
  descripcion TEXT,
  datos_anteriores JSON,
  datos_nuevos JSON,
  
  realizado_por_id BIGINT (FK → users.id - admin),
  ip_address VARCHAR(45),
  user_agent TEXT,
  
  created_at TIMESTAMP
);

-- Registro de cambios
```

### Tabla: reporte_cumplimiento
```sql
CREATE TABLE reporte_cumplimiento (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  proceso_ingreso_id BIGINT NOT NULL (FK → procesos_ingresos.id),
  
  -- ESTADO GENERAL
  completado BOOLEAN DEFAULT 0,
  fecha_completado TIMESTAMP NULL,
  
  -- CUMPLIMIENTOS POR SECCIÓN
  solicitudes_completadas INT  / solicitudes_total INT,
  cursos_completados INT       / cursos_total INT,
  checkins_completados INT     / checkins_total INT,
  
  -- MÉTRICAS
  dias_para_completar INT,
  eficiencia_porcentaje INT,
  
  retrasos_por_area JSON,  -- {area_id: dias_retraso}
  
  observaciones TEXT,
  generado_por_id BIGINT (FK → users.id - admin),
  
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

-- Reporte final de cumplimiento
```

---

## 6. CAPA AUXILIAR

### Tabla: plantilla_solicitudes
```sql
CREATE TABLE plantilla_solicitudes (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(255) NOT NULL,
  tipo ENUM('dotacion', 'tecnologia', 'servicios_generales', 'formacion', 'bienes'),
  
  descripcion TEXT,
  campos_requeridos JSON,
  
  activa BOOLEAN DEFAULT 1,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

-- Define qué campos necesita cada tipo de solicitud
```

---

## 7. CAPA RBAC (Spatie Permission)

### Tabla: roles
```sql
CREATE TABLE roles (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(125) UNIQUE NOT NULL,
  guard_name VARCHAR(125) DEFAULT 'web',
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

-- Valores:
1. root           (acceso total)
2. admin_rrhh     (gestión procesos ingreso)
3. jefe_inmediato (detalles técnicos)
4. operador_area  (marcar tareas completadas)
```

### Tabla: permissions
```sql
CREATE TABLE permissions (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(125) UNIQUE NOT NULL,
  guard_name VARCHAR(125) DEFAULT 'web',
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

-- Permisos específicos del sistema
```

### Tabla: model_has_roles
```sql
CREATE TABLE model_has_roles (
  role_id BIGINT,
  model_id BIGINT,
  model_type VARCHAR(255),
  PRIMARY KEY (role_id, model_id, model_type),
  FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Relación usuario → roles
-- Ejemplo: usuario 5 tiene rol 2 (admin_rrhh)
```

### Tabla: role_has_permissions
```sql
CREATE TABLE role_has_permissions (
  permission_id BIGINT,
  role_id BIGINT,
  PRIMARY KEY (permission_id, role_id),
  FOREIGN KEY (role_id) REFERENCES roles(id),
  FOREIGN KEY (permission_id) REFERENCES permissions(id)
);

-- Relación rol → permisos
```

---

## 📊 RESUMEN FINAL

| Capa | Tablas | Propósito |
|------|--------|-----------|
| **Organizacional** | 4 (gerencias, areas, cargos, users) | Estructura corporativa |
| **Onboarding** | 2 (procesos_ingresos, solicitudes) | Flujo de ingreso |
| **Formación** | 5 (cursos, curso_x_cargo, asignacion_cursos, ruta_formacion, ruta_x_curso) | Plan de capacitación |
| **Recursos** | 5 (uniformes, tecnologia, proteccion, inmobiliario, puestos_trabajo) | Distribución de activos |
| **Control** | 3 (checkins, auditoria, reporte) | Seguimiento y auditoría |
| **Auxiliar** | 1 (plantilla_solicitudes) | Templates |
| **RBAC** | 4 (roles, permissions, model_has_roles, role_has_permissions) | Autenticación |

**Total: 24 tablas LIMPIAS, sin duplicidades, arquitectura DDD aplicada.**

---

## 🔗 RELACIONES PRINCIPALES

```
gerencias (1) ─── (N) areas
               ├─── (N) cargos ─── (N) users
               │                      ├─── (1) ProcesoIngreso
               │                      │         ├─── (N) solicitudes
               │                      │         ├─── (N) asignacion_cursos
               │                      │         └─── (N) checkins
               │                      │
               │                      └─── (N) asignacion_cursos
               │                           └─── (1) cursos (N:N vía curso_x_cargo)
               │
               └─── RBAC (Separado)
                    users ─── (N:M) roles ─── (N:M) permissions
```

---

**Estado:** Limpio, Normalizado, Listo para Desarrollo  
**Último Update:** Febrero 2026
