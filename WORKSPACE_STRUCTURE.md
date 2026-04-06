# Laravel Onboarding System - Workspace Structure & Architecture

> Comprehensive analysis of the employee onboarding platform - focusing on work positions, requests, and multi-department workflows

**Last Updated**: March 2026 | **Framework**: Laravel 11

---

## 📋 Table of Contents
1. [System Overview](#system-overview)
2. [Data Models & Relationships](#data-models--relationships)
3. [Database Schema](#database-schema)
4. [Key Controllers](#key-controllers)
5. [Routing Structure](#routing-structure)
6. [View Architecture](#view-architecture)
7. [Business Logic & Workflows](#business-logic--workflows)

---

## 🎯 System Overview

This is a **comprehensive employee onboarding system** that manages:
- **New hire processes** from start to finish (ProcesoIngreso)
- **Multi-department requests** (Tecnología, Dotación, Servicios Generales, Formación, Bienes y Servicios)
- **Work position assignments** (physical/logical workspaces)
- **Training path management** with course assignments
- **Digital asset acceptance** (check-in with signatures)
- **Audit trails** of all onboarding decisions

The system is **role-based** with specific permissions for: Root, Jefe RRHH, department heads (Jefe Tecnología, Jefe Dotación, etc.)

---

## 🏗️ Data Models & Relationships

### Core Entity Relationship Diagram

```
Gerencia
   ↓
   ├→ Area
   │   ├→ Cargo
   │   │   ├→ User
   │   │   └→ ProcesoIngreso
   │   │       ├→ Solicitud (1→many)
   │   │       │   ├→ DetalleTecnologia (1→1)
   │   │       │   ├→ DetalleUniforme (1→1)
   │   │       │   ├→ DetalleBienes (1→1)
   │   │       │   ├→ PuestoTrabajo (1→1, for Servicios Generales)
   │   │       │   └→ Curso (many→many via solicitud_curso)
   │   │       │
   │   │       ├→ AsignacionCurso (1→many)
   │   │       │   └→ Curso
   │   │       │
   │   │       ├→ Checkin (1→1)
   │   │       │
   │   │       └→ CheckInAcceso (1→many)
   │   │
   │   └→ RutaFormacion
   │       └→ Curso (many→many via ruta_x_curso)
   │
   └→ PuestoTrabajo (managed separately)
```

---

### 1. **ProcesoIngreso** (Hiring Process) - CENTRAL ENTITY

| Aspect | Details |
|--------|---------|
| **Database Table** | `procesos_ingresos` |
| **Purpose** | Tracks the complete lifecycle of a single new hire |
| **Identification** | Auto-generated `codigo` (ING-20260327145530) |

**Key Fields:**
```php
// Employee Information
- nombre_completo, tipo_documento, documento
- email, telefono
- fecha_ingreso (start date)
- observaciones

// Organizational Context
- cargo_id → Cargo (position title)
- area_id → Area (department)
- jefe_id → User (direct manager)
- jefe_cargo_id → Cargo (manager's position)

// Lifecycle
- estado: Pendiente | En Proceso | Finalizado | Cancelado
- fecha_cancelacion, fecha_finalizacion, fecha_esperada_finalizacion
```

**Relationships:**
- `has_many: Solicitud` - All requests for this hire
- `has_many: AsignacionCurso` - All training assignments
- `has_one: Checkin` - Digital acceptance confirmation
- `has_many: CheckInAcceso` - Access logs
- `belongs_to: Cargo, Area, User`

**Key Methods:**
```php
puedeEditar()              // false if any Solicitud is Finalizada
puedeCancelar()            // checks if editable
cambiarFechaIngreso($date) // postpone (no advancing), updates all deadlines
cancelar($motivo)          // releases assigned PuestoTrabajo positions
```

---

### 2. **Solicitud** (Request) - ONE-TO-MANY with ProcesoIngreso

| Aspect | Details |
|--------|---------|
| **Database Table** | `solicitudes` |
| **Purpose** | Specific request from a department for resources/services for this hire |
| **Type Classification** | Enum: Tecnología, Dotación, Servicios Generales, Formación, Bienes y Servicios |

**Core Fields:**
```php
- proceso_ingreso_id  (FK - which hire)
- area_id            (FK - requesting department)
- tipo               (enum - request type)
- fecha_limite       (deadline)
- estado             (Pendiente | En Proceso | Finalizada)
- observaciones
- puesto_trabajo_id  (FK - only for Servicios Generales type)
```

**Relationships:**
- `belongs_to: ProcesoIngreso` - Which hire
- `belongs_to: Area` - Which department initiated request
- `belongs_to: PuestoTrabajo` - Workspace assignment (if Servicios Generales)
- `has_one: DetalleTecnologia` - Tech details (if tipo='Tecnología')
- `has_one: DetalleUniforme` - Sizing details (if tipo='Dotación')
- `has_one: DetalleBienes` - Goods list (if tipo='Bienes y Servicios')
- `belongs_to_many: Curso` - Courses to take (if tipo='Formación')

**Key Methods:**
```php
estaCompleta()        // validates required details for type
marcarEntregado()     // mark as delivered with optional notes
obtenerEstados()      // static: returns state enum options
```

**Type-Specific Detail Models:**

#### **DetalleTecnologia** (if tipo='Tecnología')
```php
Fields:
  - tipo_computador: Portátil | Escritorio
  - marca_computador: string
  - especificaciones: string (RAM, processor, etc.)
  - software_requerido: text (licenses needed)
  - monitor_adicional: boolean
  - mouse_teclado: boolean (default: true)

Methods:
  - obtenerKitEstandar($cargoId)     // suggests based on previous hires
  - obtenerEstadisticasCargo($cargoId) // stats on what similar roles got
```

#### **DetalleUniforme** (if tipo='Dotación')
```php
Fields:
  - talla_camisa, talla_pantalon, talla_zapatos: string
  - genero: Masculino | Femenino | Otro
  - cantidad_uniformes: integer (default: 2)
  - observaciones: text

Methods:
  - obtenerKitEstandar($cargoId)
  - obtenerEstadisticasCargo($cargoId) // size distribution stats
```

#### **DetalleBienes** (if tipo='Bienes y Servicios')
```php
Fields:
  - bienes_requeridos: array (JSON)
  - observaciones: text
```

---

### 3. **PuestoTrabajo** (Work Position) - INDEPENDENT RESOURCE

| Aspect | Details |
|--------|---------|
| **Database Table** | `puestos_trabajo` |
| **Purpose** | Physical/logical work positions/desks that employees occupy |
| **Assignment** | Linked via Solicitud when `tipo='Servicios Generales'` |

**Fields:**
```php
// Identification
- numero_puesto: string unique (format: A1, B2, C1, etc.)
- piso: integer       // building floor
- seccion: string     // section/area on floor

// State Management
- estado: enum ('Disponible', 'Asignado', 'En Mantenimiento', 'Bloqueado')
- capacidad: integer

// Metadata & Visualization
- ubicacion_x, ubicacion_y: integer (map coordinates)
- equipamiento: array (JSON - desk equipment list)
- descripcion: text
- notas: text
```

**Relationships:**
- `has_many: Solicitud` - All assignment requests for this position
  
**Key Methods:**
```php
estaDisponible()   // estado='Disponible' AND no active requests
empleadoActual()   // returns latest non-Cancelado Solicitud
```

**State Flow for PuestoTrabajo:**
```
Disponible → [assigned via Solicitud] → Asignado
   ↓ [maintenance]                           ↓ [cancelar process]
En Mantenimiento                          Disponible
   ↓ [blocked]
Bloqueado
```

---

### 4. **Organizational Hierarchy Models**

#### **Gerencia** (Management Division)
```php
// fields: nombre, codigo, descripcion, activo
relationships:
  - has_many: Area
  - has_many: Cargo
```

#### **Area** (Department)
```php
// fields: nombre, descripcion, activo, gerencia_id, jefe_area_cargo_id
relationships:
  - belongs_to: Gerencia
  - has_many: Cargo
  - has_many: User
  - has_many: Solicitud (as requesting area)
```

#### **Cargo** (Job Title/Position)
```php
// fields: nombre, descripcion, area_id, vacantes_disponibles, activo
relationships:
  - belongs_to: Area
  - has_many: User
  - has_many: RutaFormacion
  - has_many: Cargo (jefeInmediato) - hierarchical relationship
  - belongs_to_many: Curso (via curso_x_cargo)

scopes:
  - activos() - where activo=true
  - conVacantes() - where vacantes_disponibles > 0

methods:
  - tieneVacantes(): bool
  - obtenerCantidadEmpleados(): int
```

---

### 5. **Training & Formación Module**

#### **Curso** (Course)
```php
// fields: codigo, nombre, descripcion, categoria, modalidad
//         duracion_horas, objetivo, contenido
//         area_responsable_id, costo, requiere_certificado
//         vigencia_meses, activo

relationships:
  - belongs_to: Area (area_responsable_id)
  - belongs_to_many: Cargo (via curso_x_cargo)
  - belongs_to_many: RutaFormacion (via ruta_x_curso)
  - belongs_to_many: Solicitud (via solicitud_curso)
  - has_many: AsignacionCurso

scopes:
  - activos()
  - porCategoria($categoria)
  - porModalidad($modalidad)
```

#### **RutaFormacion** (Training Path)
```php
// fields: nombre, descripcion
//         cargo_id, area_id
//         version, activa, duracion_total_horas
//         fecha_vigencia, responsable_rrhh_id

relationships:
  - belongs_to: Cargo, Area, User
  - belongs_to_many: Curso (via ruta_x_curso)
    └─ pivot fields: numero_secuencia, es_obligatorio, es_requisito_previo

scopes:
  - activas()
  - porCargo($cargoId)
  - vigentes() - checks date validity
```

#### **AsignacionCurso** (Course Assignment for hire)
```php
// fields: proceso_ingreso_id (FK)
//         curso_id (FK)
//         fecha_asignacion, fecha_limite, fecha_completacion
//         estado, calificacion, certificado_url
//         asignado_por_id, responsable_validacion_id
//         observaciones

estado values:
  - Asignado      (newly assigned)
  - En Progreso   (in progress)
  - Completado    (finished - may need validation)
  - Vencido       (overdue)

relationships:
  - belongs_to: ProcesoIngreso, Curso, User (asignado_por, responsable_validacion)

scopes:
  - pendientes()
  - enProgreso()
  - completados()
  - vencidos()
  - paraValidar()
```

---

### 6. **Check-in & Acceptance**

#### **Checkin** (Digital Asset Acceptance)
```php
// fields: proceso_ingreso_id (FK - which hire)
//         codigo_verificacion (unique - A3F2B8C1 format)
//         activos_entregados (JSON array)
//         estado_checkin (Pendiente | Completado)
//         fecha_generacion, fecha_confirmacion
//         email_empleado, email_enviado, email_enviado_at
//         firma_digital (base64), dispositivo_confirmacion
//         ip_confirmacion

relationships:
  - belongs_to: ProcesoIngreso

methods:
  - generarCodigoVerificacion(): string (static)
  - obtenerPorcentajeCompletado(): int
  - confirmar($firma, $dispositivo, $ip): bool
```

#### **CheckInAcceso** (Access Tracking)
```php
// fields: usuario_id, area_id
//         fecha_acceso, hora_acceso
//         ip_address, user_agent
//         dispositivo_tipo, navegador
//         latitud, longitud (geolocation)
//         nota

// Indexed on: usuario_id, area_id, fecha_acceso, usuario_id+fecha_acceso, area_id+fecha_acceso
```

---

### 7. **Audit & Compliance**

#### **AuditoriaOnboarding** (Change Tracking)
```php
// fields: usuario_id (who made change)
//         accion (create, update, delete)
//         entidad (which model: ProcesoIngreso, Solicitud, etc.)
//         entidad_id
//         valores_anteriores (JSON - previous values)
//         valores_nuevos (JSON - new values)
//         motivo, ip_origin, user_agent

static methods:
  - registrar($accion, $entidad, $id, $motivo, $old, $new)
  - registrarCreacion($entidad, $id, $valores)
  - registrarActualizacion($entidad, $id, $old, $new)
  - registrarEliminacion($entidad, $id, $motivo)
```

---

### 8. **Supporting Models**

#### **User** (System Users)
```php
// fields: name, email, password
//         area_id, cargo_id
//         rol_onboarding, puede_aprobar_solicitudes
//         jefe_directo_id (for chain of command)

roles (via Spatie):
  - Root
  - Jefe RRHH
  - Jefe Tecnología
  - Jefe Dotación
  - Jefe Servicios Generales
  - Jefe Bienes y Servicios
```

#### **PlantillaSolicitud** (Request Templates)
```php
// fields: cargo_id (FK)
//         area_id (FK - requesting department)
//         tipo_solicitud (which request type auto-creates)
//         dias_maximos (days before start date)

purpose: When ProcesoIngreso created with this Cargo,
         auto-generates one Solicitud per matching Plantilla

relationships:
  - belongs_to: Cargo, Area
```

---

## 📊 Database Schema Overview

### Migration Timeline

| Date | Purpose | Tables Created |
|------|---------|-----------------|
| 2026-02-08 | **Base** | users, areas, cargos, permission tables |
| 2026-02-08 | **Core Onboarding** | procesos_ingresos, solicitudes, plantilla_solicitudes |
| 2026-02-09 | **Positions** | puestos_trabajo |
| 2026-02-10 | **Details** | detalles_tecnologia, detalles_uniformes, checkins |
| 2026-02-13 | **Training** | cursos, curso_x_cargo, asignacion_cursos, rutas_formacion, ruta_x_curso |
| 2026-02-13 | **Audit** | auditoria_onboarding, reporte_cumplimiento |
| 2026-02-14 | **Security** | gerencias, checkin_accesos |

### Key Table Constraints

```sql
-- Foreign Key Relationships
procesos_ingresos.cargo_id      → cargos.id
procesos_ingresos.area_id       → areas.id
procesos_ingresos.jefe_id       → users.id
procesos_ingresos.jefe_cargo_id → cargos.id

solicitudes.proceso_ingreso_id  → procesos_ingresos.id (CASCADE DELETE)
solicitudes.area_id             → areas.id
solicitudes.puesto_trabajo_id   → puestos_trabajo.id

puestos_trabajo.proceso_ingreso_id → procesos_ingresos.id (SET NULL on delete)

detalles_tecnologia.solicitud_id      → solicitudes.id (CASCADE)
detalles_uniformes.solicitud_id       → solicitudes.id (CASCADE)
detalles_bienes.solicitud_id          → solicitudes.id (CASCADE)

asignacion_cursos.proceso_ingreso_id  → procesos_ingresos.id
asignacion_cursos.curso_id            → cursos.id

checkins.proceso_ingreso_id           → procesos_ingresos.id (CASCADE)
checkin_accesos.usuario_id            → users.id (CASCADE)
checkin_accesos.area_id               → areas.id (SET NULL)

-- Many-to-Many
solicitud_curso (solicitud_id, curso_id)
curso_x_cargo (curso_id, cargo_id)
ruta_x_curso (ruta_formacion_id, curso_id) - with pivot fields
```

---

## 🎮 Key Controllers

### 1. **ProcesoIngresoController** - Hiring Process Management

**Primary Methods:**
```php
index()           // List all processes with cargo, area
create()          // Show form (requires Root or Jefe RRHH)
store(Request)    // Create new process
                  // Validates: nombre, documento, cargo, fecha_ingreso
                  // Auto-generates codigo
                  // Creates PlantillaSolicitud-based Solicitudes

show(ProcesoIngreso)   // View full process details
edit(ProcesoIngreso)   // Modify if puedeEditar()
update(Request)        // Update details

cambiarFecha()         // GET - show date change form
actualizarFecha()      // POST - update fecha_ingreso (can only postpone)
                       // Updates all related Solicitud deadlines

mostrarCancelacion()   // GET - show cancellation form
cancelar()             // POST - mark as Cancelado
                       // Releases PuestoTrabajo positions
                       // Marks non-Finalizada Solicitudes

historico()           // List completed/cancelled processes
getJefesByArea()      // AJAX endpoint for dynamic manager lookup
```

**Role Guards:**
- `create/store` - Only Root or Jefe RRHH
- `edit/update` - Only if puedeEditar()
- `cancel` - Only if puedeCancelar()

---

### 2. **SolicitudController** - Request Management

**Primary Methods:**
```php
index()  // List requests with ROLE-BASED FILTERING:
         //   - Root: all requests
         //   - Jefe RRHH: all requests
         //   - Jefe Tecnología: only tipo='Tecnología'
         //   - Jefe Dotación: only tipo='Dotación'
         //   - Jefe Servicios Generales: only tipo='Servicios Generales'
         //   - Others: only their own requests

show(Solicitud)  // View detail → redirects to tipo-specific view

change Request-type-specific create/edit:
  guardarTecnologia($id)        // POST - save DetalleTecnologia
  guardarDotacion($id)          // POST - save DetalleUniforme
  guardarServiciosGenerales($id) // POST - assign PuestoTrabajo
  guardarFormacion($id)         // POST - assign Curso(s)
  guardarBienes($id)           // POST - save DetalleBienes

cambiarEstado($id)     // POST - update Solicitud.estado

checkinConsolidado()   // GET - unified acceptance page when all completed
```

**Type-Specific Views:**
- Solicitud → tipo='Tecnología' → tipo-tecnologia view
- Solicitud → tipo='Dotación' → tipo-dotacion view
- Solicitud → tipo='Servicios Generales' → tipo-servicios-generales view
- Solicitud → tipo='Formación' → tipo-formacion view
- Solicitud → tipo='Bienes y Servicios' → tipo-bienes view

---

### 3. **CheckinController** - Digital Acceptance

**Public Endpoints (No Auth):**
```php
confirmar($codigo)           // GET - show public check-in form
procesarConfirmacion($codigo) // POST - accept assets (needs firma digital geolocation)
confirmado($codigo)          // GET - success confirmation page
verificarEstado($codigo)     // GET - check current status
```

**Authenticated Endpoints:**
```php
index()              // List all check-ins
show($id)           // View check-in details
generar($id)        // POST - generate new Checkin with codigo_verificacion
                    // Compiles all Solicitud activos_entregados
generarPDF($id)     // Output PDF for printing
confirmado($id)     // Success page for authenticated users
```

---

### 4. **CargoController** - Position Management

```php
index()             // List all cargo with relationships
actualizarEstado()  // PATCH - update cargo.estado (Solicitud filtering)
```

---

### 5. **Training Controllers**

- **AsignacionCursoController** - Assign courses, mark complete
- **RutaFormacionController** - Create/edit training paths
- **CursoController** - Manage course catalog
- **AuditoriaController** - View audit logs
- **ReporteController** - Generate compliance reports

---

## 🛣️ Routing Structure

### Main Routes (web.php)

```php
/* PUBLIC ROUTES */
GET  /                                  // Redirect to /login
GET  /checkin/{codigo}                  // Public check-in form
POST /checkin/{codigo}/procesar         // Process public confirmation
GET  /checkin/{codigo}/confirmado       // Success page

/* AUTHENTICATED ROUTES */
GET    /dashboard                       // Dashboard
GET    /profile                         // User profile
PATCH  /profile                         // Update profile

// PROCESO INGRESO - Full resource + custom actions
GET    /procesos-ingreso                // index
GET    /procesos-ingreso/create         // create form
POST   /procesos-ingreso                // store
GET    /procesos-ingreso/{id}           // show
GET    /procesos-ingreso/{id}/edit      // edit form
PATCH  /procesos-ingreso/{id}           // update
DELETE /procesos-ingreso/{id}           // destroy
GET    /procesos-ingreso/{id}/cambiar-fecha
POST   /procesos-ingreso/{id}/actualizar-fecha
GET    /procesos-ingreso/{id}/cancelar
POST   /procesos-ingreso/{id}/cancelar
GET    /procesos-ingreso-historico      // completed processes

// CARGOS MANAGEMENT
GET   /cargos                           // index
PATCH /cargos/{cargo}/estado            // update status

// SOLICITUDES - Full resource + type-specific handlers
GET    /solicitudes                     // index (role-filtered)
GET    /solicitudes/create              // show create form
POST   /solicitudes                     // store
GET    /solicitudes/{id}                // show (redirects to type view)
GET    /solicitudes/{id}/edit           // edit form
PATCH  /solicitudes/{id}                // update
DELETE /solicitudes/{id}                // destroy
POST   /solicitudes/{id}/guardar-tecnologia
POST   /solicitudes/{id}/guardar-dotacion
POST   /solicitudes/{id}/guardar-servicios-generales
POST   /solicitudes/{id}/guardar-formacion
POST   /solicitudes/{id}/guardar-bienes
POST   /solicitudes/{id}/cambiar-estado
GET    /procesos-ingreso/{id}/checkin-consolidado

// CHECK-IN
GET    /checkins                        // index
GET    /checkins/{id}                   // show
POST   /procesos-ingreso/{id}/generar-checkin
GET    /checkins/{id}/pdf               // PDF generation
GET    /checkins/{id}/confirmado        // success page

// AJAX
GET    /areas/{area}/jefes              // Get managers by area

/* FORMACIÓN ROUTES (separate file) */
include web-formacion.php
  - /cursos, /asignacion-cursos, /rutas-formacion, /reportes-formacion
```

---

## 🎨 View Architecture

### Directory Structure

```
resources/views/
├── dashboard.blade.php
│   └── Main dashboard (role-based content)
│
├── procesos_ingreso/
│   ├── index.blade.php          ← List all hiring processes
│   ├── create.blade.php         ← Multi-step form for new hire
│   │                                - Datos básicos (employee info)
│   │                                - Gerencia/Area/Cargo selection
│   │                                - Especificaciones de dotación
│   │                                - Especificaciones de tecnología
│   ├── edit.blade.php           ← Modify process (if puedeEditar)
│   ├── show.blade.php           ← View all details + action buttons
│   ├── cambiar_fecha.blade.php  ← Date postponement form
│   ├── cancelar.blade.php       ← Cancellation confirmation
│   └── historico.blade.php      ← Completed/cancelled process list
│
├── solicitudes/
│   ├── index.blade.php                    ← Filtered request list
│   ├── show.blade.php                     ← Detail view (switches to type-based)
│   ├── tipo-tecnologia.blade.php          ← Tech request form
│   │                                          ├─ Computer type (Portátil/Escritorio)
│   │                                          ├─ Brand, specs, software
│   │                                          ├─ Accessories (monitor, mouse)
│   │                                          └─ Suggestions from kit estándar
│   ├── tipo-dotacion.blade.php            ← Uniform sizing
│   │                                          ├─ Gender selection
│   │                                          ├─ Size inputs (shirt, pants, shoes)
│   │                                          ├─ Quantity
│   │                                          └─ Toggle to skip with justification
│   ├── tipo-servicios-generales.blade.php ← Workspace assignment
│   │                                          ├─ Available position list/map
│   │                                          ├─ Position details selector
│   │                                          └─ Availability checker
│   ├── tipo-formacion.blade.php           ← Course assignment
│   │                                          ├─ Multi-select courses
│   │                                          ├─ Ruta formación suggestions
│   │                                          └─ Due dates
│   ├── tipo-bienes.blade.php              ← Goods/services request
│   │                                          └─ Item list input
│   ├── areas-dashboard.blade.php          ← Department status dashboard
│   │                                          ├─ Checklist per request type
│   │                                          ├─ Progress indicators
│   │                                          └─ Bulk actions
│   └── checkin-consolidado.blade.php      ← Final acceptance step
│                                              ├─ Summary of all Solicitudes
│                                              ├─ Digital signature field
│                                              ├─ Location capture
│                                              └─ Confirmation button
│
├── checkins/
│   ├── index.blade.php   ← List check-ins with status
│   ├── show.blade.php    ← Detail view + PDF download
│   ├── pdf.blade.php     ← Printable check-in document
│   └── confirmado.blade.php ← Success page after public confirmation
│
├── cargos/
│   └── index.blade.php   ← Position/title management
│
├── components/           ← Reusable Blade components
│   ├── modal.blade.php
│   ├── primary-button.blade.php
│   ├── text-input.blade.php
│   ├── input-label.blade.php
│   ├── input-error.blade.php
│   ├── dropdown.blade.php
│   └── ... (standard form components)
│
├── layouts/
│   ├── app.blade.php     ← Main app layout
│   └── guest.blade.php   ← Auth pages layout
│
├── auth/                 ← Authentication views
├── profile/              ← User profile views
├── formacion/            ← Training module views
├── reportes/             ← Report views
└── auditoria/            ← Audit log views
```

### Key Interactive Features in Views

**procesos_ingreso/create.blade.php** - Multi-level dropdowns:
```html
<!-- Gerencia selector → Area selector → Cargo selector → auto-populate Jefe -->
<select id="gerencia_id">
  <option>Select Gerencia</option>
  @foreach($gerencias as $gerencia)
    <option value="{{ $gerencia->id }}">{{ $gerencia->nombre }}</option>
  @endforeach
</select>

<select id="area_id"><!-- populated by JS --></select>
<select id="cargo_id" name="cargo_id"><!-- populated by JS --></select>

<script>
  // AJAX populate area when gerencia changes
  // AJAX populate cargo when area changes
  // Fetch and display jefe_inmediato based on cargo
</script>
```

**solicitudes/tipo-tecn ologia.blade.php** - Smart suggestions:
```html
<!-- Based on DetalleTecnologia::obtenerKitEstandar() for this cargo -->
<div class="suggestion-box">
  <p>Standard equipment for this position:</p>
  <ul>
    <li>Most common computer: {{ $sugeridoTipo }}</li>
    <li>Most common brand: {{ $sugeridoMarca }}</li>
    <li>Common software: {{ $softwareListaSugerido }}</li>
  </ul>
</div>
```

**solicitudes/tipo-uniformes.blade.php** - Conditional visibility:
```html
<div id="need-uniforms">
  <input type="radio" name="necesita_dotacion" value="1" 
         onchange="document.getElementById('sizing').style.display='block'"> Sí
  <input type="radio" name="necesita_dotacion" value="0"
         onchange="show_justification_instead()"> No
</div>

<!-- Size inputs shown if necesita_dotacion=true -->
<div id="sizing" style="display: none;">
  <select name="genero">
    <option>Masculino</option>
    <option>Femenino</option>
    <option>Otro</option>
  </select>
  <input name="talla_camisa" placeholder="Shirt size">
  <input name="talla_pantalon" placeholder="Pants size">
</div>
```

**solicitudes/checkin-consolidado.blade.php** - Complete summary:
```html
<!-- Shows summary of ALL Solicitudes for this hire -->
<div class="checkin-summary">
  @foreach($proceso->solicitudes as $solicitud)
    <section>
      <h3>{{ $solicitud->tipo }}</h3>
      @if($solicitud->tipo === 'Tecnología')
        <p>{{ $solicitud->detalleTecnologia->tipo_computador }} - {{ $solicitud->detalleTecnologia->marca_computador }}</p>
      @elseif($solicitud->tipo === 'Dotación')
        <p>{{ $solicitud->detalleUniforme->talla_camisa }} / {{ $solicitud->detalleUniforme->talla_pantalon }}</p>
      @endif
    </section>
  @endforeach
  
  <!-- Signature capture -->
  <canvas id="signature-pad"></canvas>
  <input type="hidden" id="firma_digital" name="firma_digital">
  
  <!-- Geolocation -->
  <button onclick="captureLocation()">Capture Geolocation</button>
  
  <!-- Confirmation -->
  <button type="submit">I accept all delivered items</button>
</div>
```

---

## 🔄 Business Logic & Workflows

### Workflow 1: Create New Hire (ProcesoIngreso)

```
1. Root/Jefe RRHH initiates new ProcesoIngreso
   ├─ Fill: nombre, documento, cargo, área, start date
   ├─ System auto-generates: codigo (ING-YYYYMMDDHHmmss)
   └─ Auto-identifies: jefe_id (from closest jefe_inmediato in cargo hierarchy)

2. System queries PlantillaSolicitud matching this Cargo
   └─ For each Plantilla:
       ├─ Create Solicitud (tipo, area_id, fecha_limite = start_date - dias_maximos)
       ├─ If tipo='Dotación' + datos provided:
       │  └─ Create DetalleUniforme with sizing
       ├─ If tipo='Tecnología' + datos provided:
       │  └─ Create DetalleTecnologia with gama
       └─ Status: all Solicitudes start as Pendiente

3. Each department (jefe role) sees their Solicitud
   └─ Fills in requirements:
       ├─ Jefe Tecnología → selects specific hardware
       ├─ Jefe Dotación → confirms or changes sizes
       ├─ Jefe Servicios Generales → assigns PuestoTrabajo
       ├─ Jefe Formación → assigns Cursos → creates AsignacionCurso
       └─ Status: Solicitud → En Proceso

4. Department marks as complete (estado=Finalizada)

5. When ALL Solicitudes are Finalizada:
   └─ Employee sees checkin-consolidado page
       ├─ Reviews all accepted items
       ├─ Provides digital signature
       ├─ Gets GPS location & device info
       └─ Creates Checkin record
```

### Workflow 2: Manage Work Positions

```
ProcesoIngreso (Servicios Generales) type:
  ├─ Solicitud created with tipo='Servicios Generales'
  ├─ Jefe Servicios Generales:
  │  ├─ Views available PuestoTrabajo (estaDisponible())
  │  ├─ Selects position
  │  └─ Saves: Solicitud.puesto_trabajo_id = selected_id
  │
  └─ PuestoTrabajo.estado changes: Disponible → Asignado

When ProcesoIngreso cancelled or Servicitud enters certain states:
  └─ Release logic triggers:
      ├─ Find all Solicitud for this ProcesoIngreso with puesto_trabajo_id
      ├─ Update PuestoTrabajo.estado = Disponible
      ├─ Clear Solicitud.puesto_trabajo_id = null
      └─ Position becomes available again
```

### Workflow 3: Training Assignment (Formación)

```
ProcesoIngreso with one or more Solicitud.tipo='Formación':
  ├─ System retrieves RutaFormacion for this Cargo
  ├─ Displays courses in ruta with their sequence
  │
  └─ Jefe RRHH can:
     ├─ Assign all courses from Ruta
     ├─ Assign specific Curso(s) manually
     │
     └─ Creates AsignacionCurso for each:
         ├─ fecha_asignacion = today
         ├─ fecha_limite = assigned_date + days
         ├─ estado = Asignado
         └─ Tracks: responsable_validacion (who approves completion)

During onboarding period:
  └─ User/Manager updates AsignacionCurso:
     ├─ Mark as En Progreso
     ├─ Upload certificate → Completado
     └─ Track calificación (score)
```

### Workflow 4: Postpone Start Date

```
Jefe RRHH can postpone ProcesoIngreso.fecha_ingreso:
  ├─ Can only POSTPONE (not advance)
  ├─ Changes ProcesoIngreso.fecha_ingreso
  │
  └─ System recalculates ALL Solicitud deadlines:
     └─ For each Solicitud where estado ≠ Finalizada:
         └─ Add same number of days to fecha_limite
```

### Workflow 5: Cancel Hiring Process

```
Jefe RRHH initiates cancellation:
  ├─ Check: puedeCancelar() (no Finalizada Solicitudes)
  ├─ Mark ProcesoIngreso.estado = Cancelado
  ├─ Set ProcesoIngreso.fecha_cancelacion = now()
  │
  └─ CRITICAL: Release Resources
     ├─ For each Solicitud:
     │  └─ If puesto_trabajo_id is set:
     │     ├─ Update PuestoTrabajo.estado = Disponible
     │     ├─ Clear Solicitud.puesto_trabajo_id = null
     │     └─ Position available for re-assignment
     │
     └─ Audit log: record cancellation reason
```

### Workflow 6: Digital Check-in (Public)

```
After ALL Solicitudes are Finalizada:
  ├─ CheckinController::generar() creates Checkin
  │  ├─ Generates unique codigo_verificacion
  │  ├─ Compiles all activos_entregados from Solicitudes
  │  ├─ Generates unique public URL
  │  └─ Sends email to employee with check-in link
  │
  └─ Employee receives email → clicks link (no auth needed)
     ├─ /checkin/{codigo} page loads
     ├─ Shows all items to confirm receipt
     ├─ Draws digital signature
     ├─ Browser captures:
     │  ├─ Geolocation (if permitted)
     │  ├─ Device info (User-Agent)
     │  └─ Network IP address
     │
     └─ POST /checkin/{codigo}/procesar:
         ├─ Validates signature
         ├─ Creates Checkin record with all metadata
         ├─ Sends confirmation email
         └─ Redirects to /checkin/{codigo}/confirmado (success page)
```

---

## 🔐 Role-Based Access Control

### Permission Hierarchy

| Role | Capabilities |
|------|--------------|
| **Root** | All operations, all views, can modify any record |
| **Jefe RRHH** | Create/edit ProcesoIngreso, view all Solicitudes, manage training |
| **Jefe Tecnología** | View only Solicitud.tipo='Tecnología', manage tech details |
| **Jefe Dotación** | View only Solicitud.tipo='Dotación', manage sizing |
| **Jefe Servicios Generales** | View only Solicitud.tipo='Servicios Generales', assign positions |
| **Jefe Bienes y Servicios** | View only Solicitud.tipo='Bienes y Servicios' |
| **Employee/Other** | View own ProcesoIngreso and related Solicitudes |

### Controller-Level Filtering Example (SolicitudController):

```php
public function index()
{
    $user = auth()->user();
    
    if ($user->hasRole('Jefe Tecnología')) {
        $solicitudes = Solicitud::where('tipo', 'Tecnología')->paginate(15);
    }
    elseif ($user->hasRole('Jefe Dotación')) {
        $solicitudes = Solicitud::where('tipo', 'Dotación')->paginate(15);
    }
    // ... other roles
}
```

---

## 📊 Key Features & Patterns

### 1. **Smart Equipment Suggestions**

Based on historical data:
```php
// In ProcesoIngresoController or SolicitudController
$kitEstandar = DetalleTecnologia::obtenerKitEstandar($cargo_id);
$estadisticas = DetalleTecnologia::obtenerEstadisticasCargo($cargo_id);

// View shows:
// - Most common computer type for this position
// - Most frequent brands
// - Common software bundles
// - Accessory patterns
```

### 2. **State Validation**

Prevent invalid transitions:
```php
ProcesoIngreso::puedeEditar()   // false if any Solicitud is Finalizada
ProcesoIngreso::puedeCancelar() // false if any Solicitud is Finalizada
PuestoTrabajo::estaDisponible() // estado='Disponible' AND no active assign
```

### 3. **Cascade Operations**

```php
// Delete ProcesoIngreso → all Solicitudes deleted (CASCADE)
// Delete Solicitud → all Detail* records deleted (CASCADE)
// Cancel ProcesoIngreso → release PuestoTrabajo positions
// Postpone start date → update all deadline s proportionally
```

### 4. **Audit Trail**

Track every change:
```php
AuditoriaOnboarding::registrar(
    'update',
    'Solicitud',
    $solicitud->id,
    'Estado cambio a Finalizada',
    ['estado' => 'En Proceso'],
    ['estado' => 'Finalizada']
);
```

### 5. **Multi-Step Onboarding**

Visual progress through departments:
```
[New Hire] 
  ↓ [Complete Datos Básicos]
  ↓ [Tecnología: select computer]
  ↓ [Dotación: confirm sizing]
  ↓ [Servicios Generales: assign workspace]
  ↓ [Formación: assign training]
  ↓ [All departments complete]
  ↓ [Digital Signature + Check-in]
  → [Onboarding Complete]
```

---

## 🚀 Quick Reference - Common Queries

```php
// Get all incomplete hires
$procesosIncompletos = ProcesoIngreso::where('estado', 'Pendiente')->get();

// Find available positions
$posicionesLibres = PuestoTrabajo::where('estado', 'Disponible')->get();

// Get new hire tech requirements
$telework->solicitudes()->where('tipo', 'Tecnología')->with('detalleTecnologia')->first();

// Get training status
$trainings = $proceso->asignacionesCursos()->with('curso')->get();
// Check which are overdue:
$vencidos = $trainings->where('fecha_limite', '<', now())->where('estado', '!=', 'Completado');

// Check-in summary
$checkin = $proceso->checkin;
$completion = $checkin->obtenerPorcentajeCompletado(); // 0-100%

// Audit history
$cambios = AuditoriaOnboarding::where('entidad', 'ProcesoIngreso')
                               ->where('entidad_id', $proceso->id)
                               ->latest()
                               ->get();
```

---

## 📝 Notes & Observations

### Legacy/Inconsistencies
- **PuestoTrabajo references `SolicitudServiciosGenerales`** model (doesn't exist in current codebase)
  - Likely legacy reference; functionality implemented via Solicitud.tipo='Servicios Generales'

### Optimization Opportunities
1. **Pos ition Visualization**: x/y coordinates suggest potential for floor plan UI
2. **Training Completion**: RutaFormacion suggests pre-built training tracks could auto-populate courses
3. **Equipment Standardization**: Statistical methods already built for making default suggestions
4. **Geolocation Tracking**: CheckInAcceso could enable workforce analytics

### Extensibility Points
- Add custom workflow states
- Implement approval chains for department heads
- Build analytics dashboard for onboarding metrics
- Export to HRIS systems
- Multi-language support (currently Spanish)

---

**Generated**: March 27, 2026 | **Project**: Laboratoire Software - Employee Onboarding Platform
