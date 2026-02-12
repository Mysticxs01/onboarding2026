# Documentación - Módulo de Solicitudes por Área

## 📋 Descripción General

Se ha implementado un sistema completo de gestión de solicitudes de incorporación dividido en **5 áreas operacionales** independientes, cada una con funcionalidades especializadas para la gestión del onboarding de nuevos empleados.

---

## 🏢 Áreas Implementadas

### 1. **Servicios Generales** 🏢
**Objetivo:** Asignación de puesto de trabajo e identificación de empleados

**Funcionalidades:**
- Plano interactivo de puestos de trabajo con mapa visual
- Visualización de disponibilidad de puestos (color verde = disponible, rojo = ocupado)
- Asignación de puesto a empleado
- Generación automática de carnet de identificación
- Liberación de puestos cuando sea necesario

**Vistas:**
- `resources/views/areas/servicios-generales.blade.php` - Plano interactivo

**Controlador:**
- `app/Http/Controllers/SolicitudServiciosGeneralesController.php`

**Rutas:**
```
GET /servicios-generales/solicitudes/{solicitud}/plano
POST /servicios-generales/solicitudes/{solicitud}/asignar-puesto
POST /servicios-generales/solicitudes/{solicitud}/generar-carnet
```

---

### 2. **Dotación** 👕
**Objetivo:** Gestión de EPP (Elementos de Protección Personal) y uniformes

**Funcionalidades:**
- Asignación de kit estándar por cargo
- Selección de tallas y colores
- Seguimiento de entregas
- Compatibilidad con uniforme diario, especial y de gala
- Tipos de EPP: Casco, Chaleco, Guantes, Zapatos, Gafas, Tapabocas, Cinturón

**Vistas:**
- `resources/views/areas/dotacion.blade.php` - Formulario de asignación

**Controlador:**
- `app/Http/Controllers/SolicitudDotacionController.php`

**Rutas:**
```
GET /dotacion/solicitudes/{solicitud}/formulario
POST /dotacion/solicitudes/{solicitud}/guardar
POST /dotacion/elementos-proteccion/{elemento}/marcar-entregado
```

---

### 3. **Formación** 📚
**Objetivo:** Creación y seguimiento de planes de capacitación

**Funcionalidades:**
- Diseño de planes personalizados por cargo
- Módulos de formación configurable (Inducción, Seguridad, Políticas, etc.)
- Estados del plan: Diseño → Programado → En Ejecución → Completado/Cancelado
- Envío automático de plan por email al empleado
- Tracking de módulos completados

**Vistas:**
- `resources/views/areas/formacion.blade.php` - Editor de planes

**Controlador:**
- `app/Http/Controllers/SolicitudFormacionController.php`

**Rutas:**
```
GET /formacion/solicitudes/{solicitud}/formulario
POST /formacion/solicitudes/{solicitud}/guardar
POST /formacion/planes/{plan}/completar-modulo
```

---

### 4. **Bienes y Servicios** 🛋️
**Objetivo:** Asignación de muebles y artículos de oficina

**Funcionalidades:**
- Kit estándar de inmobiliario por cargo
- Seguimiento de estado: Pendiente → En Almacén → En Tránsito → Entregado
- Artículos de papelería checklist
- Generación de reporte de entrega
- Rastreo por quien entregó y fecha

**Vistas:**
- `resources/views/areas/bienes.blade.php` - Formulario de asignación

**Controlador:**
- `app/Http/Controllers/SolicitudBienesController.php`

**Rutas:**
```
GET /bienes/solicitudes/{solicitud}/formulario
POST /bienes/solicitudes/{solicitud}/guardar
PATCH /bienes/items/{item}/estado
```

---

### 5. **Tecnología** 💻
**Objetivo:** Gestión de hardware, software y accesos digitales

**Funcionalidades:**
- Creación de usuario AD (Active Directory)
- Asignación de correo corporativo
- Generación de contraseña segura
- Kit de hardware estándar por cargo
- Configuración de accesos: Correo, Intranet, ERP, VPN, etc.
- Selección de software a instalar
- Validación de credenciales
- Checklist de implementación

**Vistas:**
- `resources/views/areas/tecnologia.blade.php` - Formulario de asignación

**Controlador:**
- `app/Http/Controllers/SolicitudTecnologiaController.php`

**Rutas:**
```
GET /tecnologia/solicitudes/{solicitud}/formulario
POST /tecnologia/solicitudes/{solicitud}/guardar
POST /tecnologia/detalles/{detalle}/hardware-entregado
GET /tecnologia/detalles/{detalle}/checklist
```

---

## 🗄️ Base de Datos

### Nuevas Tablas Creadas (Migración: 2026_02_08_100000)

#### 1. **puestos_trabajo**
```sql
- id
- numero_puesto
- piso
- seccion
- ubicacion_x, ubicacion_y (para mapa)
- equipamiento (JSON)
- estado: Disponible | Asignado | Mantenimiento | Bloqueado
- timestamps
```

#### 2. **solicitudes_servicios_generales**
```sql
- id
- solicitud_id
- puesto_trabajo_id
- carnet_generado
- numero_carnet
- fecha_carnetizacion
- timestamps
```

#### 3. **planes_capacitacion**
```sql
- id
- solicitud_id
- cargo_id
- modulos (JSON array)
- duracion_horas
- responsable_capacitacion
- estado: Diseño | Programado | En Ejecución | Completado | Cancelado
- email_enviado
- fecha_email_enviado
- timestamps
```

#### 4. **items_inmobiliario**
```sql
- id
- solicitud_id
- tipo_item
- descripcion
- cantidad
- estado: Pendiente | En Almacén | En Tránsito | Entregado
- entregado_por
- fecha_entrega
- observaciones
- timestamps
```

#### 5. **elementos_proteccion**
```sql
- id
- solicitud_id
- tipo
- cantidad
- talla
- color
- entregado
- fecha_entrega
- timestamps
```

### Relaciones de Modelos

```
Solicitud (hub central)
├── solicitudServiciosGenerales() → SolicitudServiciosGenerales
├── planCapacitacion() → PlanCapacitacion
├── itemsInmobiliario() → ItemInmobiliario
├── elementosProteccion() → ElementoProteccion
└── detalleTecnologia() → DetalleTecnologia
```

---

## 🎨 Características Principales

### **Vistas Responsivas**
- Diseño mobile-first con Tailwind CSS
- Desktop: Grid de 5 tarjetas (una por área)
- Mobile: Stack vertical con botones de acción
- Paneles laterales pegadizos (sticky) para información

### **Kits Estándar**
Cada área tiene kits preconfigurados por tipo de cargo:

**Área Dotación (por Cargo):**
- Operario: Casco, Chaleco, Guantes, Zapatos
- Administrativo: Tapabocas, Guantes

**Área Bienes (por Cargo):**
- Gerente: Escritorio Ejecutivo, Silla Ejecutiva, Estantería, Lámpara
- Operario: Silla Base, Escritorio Simple

**Área Tecnología (por Cargo):**
- Administrativo: Laptop, Monitor, Mouse, Teclado
- Developer: Laptop High-end, 2x Monitor, Docking, Teclado Mecánico

### **Inteligencia en la UI**
- Botones contextuales (crear vs editar)
- Contadores en tiempo real
- Estados visuales con emojis
- Validación de datos antes de guardar

---

## 🚀 Instalación y Uso

### 1. **Ejecutar Migraciones** ✅
```bash
php artisan migrate
```

### 2. **Compilar Assets** ✅
```bash
npm run build
```

### 3. **Acceder a Solicitudes**
Desde el dashboard, navegaremos a Solicitudes. En cada solicitud veremos:
- El dashboard central con 5 tarjetas (una por área)
- Cada tarjeta enlaza al formulario específico del área
- Indicadores visuales de completitud

### 4. **Flujo de Uso Típico**
```
1. Crear Solicitud (SolicitudController)
   ↓
2. Ver Dashboard de Áreas (areas-dashboard.blade.php)
   ├─ Asignar Puesto → Servicios Generales
   ├─ Asignar EPP → Dotación
   ├─ Crear Plan → Formación
   ├─ Asignar Inmobiliario → Bienes
   └─ Crear Usuario → Tecnología
   ↓
3. Marcar Items como Entregados
   ↓
4. Completar Solicitud
```

---

## 📊 Modelos Creados

### **PuestoTrabajo**
- Métodos: `estaDisponible()`, `empleadoActual()`
- Relaciones: `hasMany(SolicitudServiciosGenerales)`

### **SolicitudServiciosGenerales**
- Campos: `puesto_trabajo_id`, `carnet_generado`, `numero_carnet`
- Relaciones: `belongsTo(Solicitud)`, `belongsTo(PuestoTrabajo)`

### **PlanCapacitacion**
- Métodos: `obtenerPlanPorCargo()`, `crearPlanPorDefecto()`
- Campos JSON: `modulos` (array de {nombre, descripcion, duracion_horas, responsable})

### **ItemInmobiliario**
- Métodos: `obtenerKitEstandarPorCargo()`, `obtenerTipos()`, `obtenerEstados()`
- Tipos predefinidos: Silla, Escritorio, Estantería, Lámpara, Papelería

### **ElementoProteccion**
- Métodos: `obtenerKitEstandarPorCargo()`, `obtenerTipos()`, `obtenerTallas()`, `obtenerColores()`
- Tipos: 8 tipos de EPP, 7 tallas, 8 colores

---

## 🔄 Integración con NotificationService

El sistema usa la clase `NotificationService` existente para enviar notificaciones de planes de capacitación:

```php
// En SolicitudFormacionController
$this->notificationService->enviar(
    destinatario: $empleado->email,
    asunto: "Tu Plan de Capacitación ha sido creado",
    mensaje: $mensajePlan,
    tipo: 'capacitacion'
);
```

---

## 📝 Notas Importantes

1. **Kits Hardcoded**: Los kits estándar están actualmente hardcodeados en los modelos. Pueden migrarse a base de datos en futuras versiones.

2. **Permisos**: Todos los controladores incluyen `$this->authorize('view'|'update')` para verificar permisos basados en roles.

3. **Validaciones**: Se valida entrada de datos en todos los formularios POST/PATCH.

4. **Estados**: Cada área usa enumeraciones para restringir valores de estado válidos.

5. **Responsive**: Todas las vistas están optimizadas para mobile con Tailwind CSS.

---

## 🔮 Futuras Mejoras

- [ ] Panel de administración para configurar kits desde UI
- [ ] Reporte PDF de asignaciones por área
- [ ] Notificaciones push en tiempo real
- [ ] Integración con Active Directory (AD) para validación de credenciales
- [ ] Dashboard de analytics (% de completitud por área)
- [ ] Exportar reporte de incorporación en Excel
- [ ] Auditoría de cambios (quién asignó qué y cuándo)
- [ ] Plantillas de planes por cargo

---

## 📞 Soporte

Para consultas o problemas, revisar los logs en `storage/logs/laravel.log`

Fecha de Implementación: **Febrero 8, 2026**
