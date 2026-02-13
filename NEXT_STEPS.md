# 🚀 PRÓXIMOS PASOS - Desarrollo del Sistema Onboarding

**Estado Actual:** ✅ Base limpia y lista para desarrollo  
**Fecha:** Febrero 2026  
**Prioridad:** Alta

---

## 📋 5 MÓDULOS PARA DESARROLLAR

### 1️⃣ MÓDULO: Administración de Procesos de Ingreso (PRIORITARIO)

**Objetivo:** Crear y gestionar procesos de ingreso de nuevos empleados

**Funcionalidades:**
- ✅ Crear registro de ingreso (código autogenerado)
- ✅ Generar solicitudes automáticas para cada área
- ✅ Editar proceso (excepto si hay tareas finalizadas)
- ✅ Cancelar proceso (si fecha ingreso es futura)
- ✅ Listado con filtros y búsqueda
- ✅ Vista detalle del proceso
- ✅ Histórico de ingresos

**Campos obligatorios:**
```
- Nombre completo
- Documento (tipo + número) 
- Cargo a ocupar (select desde cargos)
- Área asignada (se obtiene del cargo)
- Fecha de ingreso (min. 2 días futura)
- Jefe inmediato (select desde users que puedan serlo)
```

**Flujo:**
```
ProcesoIngreso creado
  ↓
Generar automáticamente:
  ├─ Solicitud para Dotación (uniformes + EPP)
  ├─ Solicitud para Tecnología (equipo + credenciales)
  ├─ Solicitud para Servicios Generales (puesto + carnet)
  ├─ Solicitud para Formación (inducción + plan)
  └─ Solicitud para Bienes (inmobiliario + insumos)
```

**Modelo/Controlador Clave:**
```php
ProcesoIngresoController:
  - create()        // Mostrar formulario
  - store()         // Crear + generar solicitudes
  - edit()          // Editar (validar condiciones)
  - update()        // Actualizar
  - destroy()       // Cancelar
  - show()          // Ver detalle
  - index()         // Listado
```

---

### 2️⃣ MÓDULO: Panel de Solicitudes por Área

**Objetivo:** Cada área gestiona sus solicitudes

**Funcionalidades:**
- ✅ Dashboard por área (filtro automático)
- ✅ Listado de solicitudes pendientes
- ✅ Cambiar estado (Pendiente → En Proceso → Entregado)
- ✅ Mostrar detalles según tipo
- ✅ Cargar archivos/pruebas (si aplica)
- ✅ Notificaciones de nuevas solicitudes

**Solicitudes por tipo:**

**Dotación (Uniformes + EPE):**
```
├─ Jefe talla empleado
├─ Marca de EPE
├─ Cantidad
└─ Marcar entrega
```

**Tecnología:**
```
├─ Jefe selecciona: Portátil/Escritorio
├─ Especifica software requerido
├─ Especifica accesos (email, sistemas)
└─ Marcar entrega
```

**Servicios Generales:**
```
├─ Asignar puesto físico (mapa interactivo)
├─ Generar carnetización
└─ Marcar completado
```

**Formación:**
```
├─ Sistema sugiere cursos por cargo
├─ Jefe RRHH selecciona cursos (checkboxes)
├─ Enviar plan al empleado
└─ Marcar completado cuando cursos terminen
```

**Bienes:**
```
├─ Silla, escritorio, etc
├─ Papelería, insumos
└─ Marcar entrega
```

**Controlador Clave:**
```php
SolicitudController:
  - porArea()       // Filtrar por área del usuario logged
  - cambiarEstado() // Pendiente → En Proceso → Entregado
  - detalles()      // Mostrar según tipo
  - notificar()     // Enviar email
```

---

### 3️⃣ MÓDULO: Asignación de Cursos (MÁS IMPORTANTE)

**Objetivo:** Jefe RRHH selecciona qué cursos asignar al nuevo empleado

**Funcionalidades:**
- ✅ Al crear ProcesoIngreso, mostrar sugerencia de cursos por cargo
- ✅ Selector de cursos (checkboxes, no multiselect dropdown)
- ✅ Mostrar todos los 31 cursos disponibles
- ✅ Pre-marcar cursos "sugeridos" (como kit estándar)
- ✅ Permitir seleccionar adicionales
- ✅ Enviar notificación por email al empleado
- ✅ Crear AsignacionCurso para cada curso seleccionado
- ✅ Seguimiento de progreso

**Estructura Visual:**

```html
<!-- MÓDULO: Asignación de Cursos -->
<div class="cursos-selector">
  
  <!-- 1. Kit Sugerido (según cargo) -->
  <section class="kit-sugerido">
    <h3>Kit de Formación Sugerido</h3>
    <p>Según el cargo "${cargo.nombre}"</p>
    <div class="checkbox-list">
      ☑️ Inducción a la Cultura Cooperativa
      ☑️ Portafolio de Productos y Servicios
      ☑️ Manejo del Core Financiero (Software)
      ... (cursos automáticos)
    </div>
  </section>

  <!-- 2. Cursos Disponibles (para agregar más) -->
  <section class="cursos-adicionales">
    <h3>Cursos Adicionales Disponibles</h3>
    <div class="checkbox-list">
      ☐ Prevención de Lavado de Activos (SARLAFT)
      ☐ Seguridad y Salud en el Trabajo (SST)
      ☐ Brigadas de Primeros Auxilios
      ☐ Prevención de Acoso Laboral
      ... (28 más)
    </div>
  </section>

  <!-- 3. Resumen Selected -->
  <section class="resumen">
    <h3>Cursos Seleccionados: 8/31</h3>
    <ul>
      <li>Inducción a la Cultura Cooperativa</li>
      <li>Portafolio de Productos...</li>
      ...
    </ul>
  </section>

  <!-- 4. Botones -->
  <button>Guardar y Enviar Notificación</button>
</div>
```

**Lógica Backend:**

```php
// En formulario de ProcesoIngreso o pantalla separada

public function seleccionarCursos(Request $request, ProcesoIngreso $proceso)
{
    // 1. Obtener cargo del proceso
    $cargo = $proceso->cargo;
    
    // 2. Obtener cursos sugeridos para este cargo
    $cursossugeridos = CursoXCargo::where('cargo_id', $cargo->id)
                                   ->with('curso')
                                   ->get();
    
    // 3. Pre-marcar sugeridos en formulario
    // (en vista, marcar checkboxes predefinidos)
    
    // 4. Validar selección
    $cursos_ids = $request->input('cursos', []);
    
    // 5. Crear AsignacionCurso para cada uno
    foreach ($cursos_ids as $curso_id) {
        AsignacionCurso::create([
            'usuario_id' => $proceso->usuario_id,
            'curso_id' => $curso_id,
            'proceso_ingreso_id' => $proceso->id,
            'estado' => 'asignado',
            'fecha_asignacion' => now(),
        ]);
    }
    
    // 6. Enviar email al empleado
    // Mail::send(new PlanFormacionMail($proceso, $cursos));
    
    return redirect()->with('success', 'Cursos asignados');
}
```

**Base de datos:**
```
proceso_ingreso (id=5)
  └─ usuario_id = 15 (empleado nuevo)
     └─ cargo_id = 23 (Ejecutivo Captación)
        └─ cursos sugeridos para cargo 23:
           - Inducción (obligatorio) ☑️
           - Portafolio ☑️
           - Core Financiero ☑️
           
        └─ crear AsignacionCurso para cada:
           asignacion_cursos:
           - usuario_id: 15, curso_id: 1 (Inducción)
           - usuario_id: 15, curso_id: 2 (Portafolio)
           - usuario_id: 15, curso_id: 20 (Core)
```

---

### 4️⃣ MÓDULO: Check-in de Activos

**Objetivo:** Confirmar entrega de todos los recursos

**Funcionalidades:**
- ✅ Generar acta de entrega (PDF)
- ✅ Código único para empleado
- ✅ Empleado ingresa código + confirma recepción
- ✅ Firma digital
- ✅ Historial de confirmaciones

**Flujo:**
```
Todas las solicitudes completadas
  ↓
Generar Acta PDF con:
  - Empleado
  - Cargo
  - Fecha
  - Listado de items entregados
  - Espacio para firma
  ↓
Enviar código al email personal del empleado
  ↓
Empleado ingresa código en sistema
  ↓
Confirma recepción
  ↓
Firma digital (mouse/tablet)
  ↓
Check-in completado
```

---

### 5️⃣ MÓDULO: Reportes y Dashboards

**Objetivo:** Visualizar eficiencia y proporcionar recomendaciones

**Funcionalidades:**
- ✅ Gráficas de cumplimiento por área
- ✅ Empleados en proceso vs completados
- ✅ Retrasos vs plazos
- ✅ Kit estándar por cargo (recomendaciones)
- ✅ Exportar reportes

---

## 🎯 PRIORIDAD DE DESARROLLO

```
1. PRIORITARIO AHORA:
   └─ ProcesoIngresoController (crear + generar solicitudes)
   
2. SEGUNDA SEMANA:
   ├─ SolicitudController (gestión por área)
   └─ Vistas de solicitudes
   
3. TERCERA SEMANA:
   └─ Asignación de cursos con checkboxes
   
4. CUARTA SEMANA:
   └─ Check-in + Reportes
```

---

## 💾 COMANDOS ÚTILES

```bash
# Crear un controlador
php artisan make:controller ProcesoIngresoController

# Crear una migración
php artisan make:migration cambio_tabla_nombre

# Crear vista Blade
# (manually en resources/views/)

# Ejecutar migraciones
php artisan migrate

# Reset BD completa (SOLO DEV)
php artisan migrate:fresh --seed
```

---

## 📖 REFERENCIA RÁPIDA

**Estructura Organizacional:**
- 6 Gerencias
- 18 Áreas
- 54 Cargos
- ~50 Empleados

**Procesos de Ingreso:**
- Generan 5 solicitudes automáticas (una por área)
- Cada solicitud tiene tipo y detalles JSON
- Todos deben completarse antes de check-in

**Cursos:**
- 31 cursos totales
- Modalidad: presencial + virtual
- Jefe RRHH selecciona cuáles asignar
- Se rastrean con AsignacionCurso

**Usuarios:**
- Cada uno tiene: cargo, área, jefe inmediato
- Pueden tener roles RBAC (independiente)
- Pueden ser operadores de área

---

## 🚦 ESTADO ACTUAL

✅ Base de datos normalizada  
✅ Modelos creados  
✅ Relaciones definidas  
✅ Migraciones y seeders  
⏳ **AQUÍ EMPIEZAS TÚ** - Desarrollar vistas y controladores

---

**Adelante con el desarrollo. La arquitectura está lista.** 🎉
