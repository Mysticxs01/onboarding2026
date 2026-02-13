# 📋 RESUMEN DE CAMBIOS - Refactorización del Módulo Solicitudes

**Fecha:** February 14, 2026  
**Versión:** 2.0 - Módulo de Solicitudes Refactorizado  
**Estado:** ✅ COMPLETADO

---

## 🎯 Objetivo Principal

Refactorizar completamente el módulo de Solicitudes para que cada tipo de solicitud (Tecnología, Dotación, Servicios Generales, Formación, Bienes) tenga una interfaz única y diferenciada, con validaciones condicionales específicas y gestión de datos consolidada.

---

## ✨ Cambios Implementados

### 1. **Base de Datos - Migraciones Nuevas**

**Archivo:** `database/migrations/2026_02_14_create_missing_solicitudes_tables.php`

#### Agregadas a tabla `detalles_tecnologia`:
- `necesita_computador` (boolean)
- `gama_computador` (enum: 'Básica', 'Media', 'Premium')
- `credenciales_plataformas` (text)

#### Agregadas a tabla `detalles_uniformes`:
- `necesita_dotacion` (boolean)
- `justificacion_no_dotacion` (text)
- `talla_camiseta` (string)

#### Nueva tabla: `detalles_bienes`
```sql
CREATE TABLE detalles_bienes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    solicitud_id BIGINT FK (solicitudes),
    bienes_requeridos JSON,
    observaciones TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

#### Nueva tabla: `solicitud_curso` (many-to-many)
```sql
CREATE TABLE solicitud_curso (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    solicitud_id BIGINT FK (solicitudes),
    curso_id BIGINT FK (cursos),
    UNIQUE(solicitud_id, curso_id)
)
```

#### Agregada a tabla `solicitudes`:
- `puesto_trabajo_id` (FK → puestos_trabajo)

---

### 2. **Modelos Laravel - Nuevos y Actualizados**

#### **Modelo Nuevo: DetalleBienes**
```php
// app/Models/DetalleBienes.php
protected $casts = ['bienes_requeridos' => 'array'];
public function solicitud() { return $this->belongsTo(Solicitud::class); }
```

#### **Modelo Solicitud - Relaciones Nuevas**
```php
public function detalleBienes() { return $this->hasOne(DetalleBienes::class); }
public function puestoTrabajo() { return $this->belongsTo(PuestoTrabajo::class); }
public function cursos() { return $this->belongsToMany(Curso::class, 'solicitud_curso'); }
```

#### **Modelo Curso - Relación Nueva**
```php
public function solicitudes() { return $this->belongsToMany(Solicitud::class, 'solicitud_curso'); }
```

---

### 3. **Vistas Blade - 5 Nuevas por Tipo de Solicitud**

#### **solicitudes/tipo-tecnologia.blade.php** (226 líneas)
**Características:**
- Radio button: "¿Necesita computador?" (Sí/No)
- Si SÍ: Dropdown con gama (Básica/Media/Premium)
- Textarea para credenciales_plataformas (máx 2000 caracteres)
- Estados guardados mostrados en card verde
- Botón 'Editar' para cambiar

**Validación:**
```php
'necesita_computador' => 'required|boolean',
'gama_computador' => 'nullable|required_if:necesita_computador,1',
'credenciales_plataformas' => 'nullable|required_if:necesita_computador,1|string|max:2000',
```

#### **solicitudes/tipo-dotacion.blade.php** (237 líneas)
**Características:**
- Radio button: "¿Necesita dotación?" (Sí/No)
- Si SÍ: Select género, inputs talla_pantalon, talla_camiseta (mostrados en seccion-tallas)
- Si NO: Textarea justificacion_no_dotacion (mostrado en seccion-justificacion)
- Condicional onchange para mostrar/ocultar secciones

**Validación:**
```php
'necesita_dotacion' => 'required|boolean',
'genero' => 'nullable|required_if:necesita_dotacion,1',
'talla_pantalon' => 'nullable|required_if:necesita_dotacion,1',
'talla_camiseta' => 'nullable|required_if:necesita_dotacion,1',
'justificacion_no_dotacion' => 'nullable|required_if:necesita_dotacion,0',
```

#### **solicitudes/tipo-servicios-generales.blade.php** (205 líneas)
**Características:**
- Dropdown dropdown pre-filtrado a puestos con estado='Disponible'
- Ordena por piso y número de puesto
- Muestra: número, sección, piso
- Auto-asigna puesto al estado 'Asignado'
- Constrained: solo puestos disponibles (no módulo separado)

**Validación:**
```php
'puesto_trabajo_id' => 'required|exists:puestos_trabajo,id',
```

#### **solicitudes/tipo-formacion.blade.php** (264 líneas)
**Características:**
- Checkbox grid para todos los 31 cursos (ordenados alfabéticamente)
- Cada curso muestra: nombre, duración (horas), modalidad, categoría
- Grid 2 columnas en mobile, 3 en desktop
- Max-height scroll para lista larga
- Solo Jefe RRHH puede editar
- Many-to-many relationship con cursos

**Validación:**
```php
'curso_ids' => 'nullable|array',
'curso_ids.*' => 'exists:cursos,id',
```

#### **solicitudes/tipo-bienes.blade.php** (273 líneas)
**Características:**
- Checkbox grid para 10 items: silla, escritorio, papelera, organizador, cuadernos, lapiceros, post_it, archivador, mouse_pad, cable_cargador
- Cada item con emoji icon (🪑, 🖥️, 🗑️, etc.)
- Textarea opcional para observaciones_bienes
- Grid 2 columnas mobile, 3 desktop
- Almacena como JSON array

**Validación:**
```php
'bienes' => 'nullable|array',
'observaciones_bienes' => 'nullable|string|max:1000',
```

---

### 4. **Controlador - SolicitudController.php**

#### **Método `index()` - REFACTORIZADO**
```php
// Según rol - Filtra solicitudes visibles

if ($user->hasRole('Root')) {
    // Ve TODAS las solicitudes
}
elseif ($user->hasRole('Jefe RRHH')) {
    // Ve TODAS las solicitudes (gestiona onboarding completo)
}
elseif ($user->hasRole('Jefe Tecnología')) {
    // Ve SOLO tipo='Tecnología'
}
elseif ($user->hasRole('Jefe Dotación')) {
    // Ve SOLO tipo='Dotación'
}
elseif ($user->hasRole('Jefe Servicios Generales')) {
    // Ve SOLO tipo='Servicios Generales'
}
elseif ($user->hasRole('Jefe Bienes y Servicios')) {
    // Ve SOLO tipo='Bienes'
}
```

#### **Método `show()` - ACTUALIZADO**
```php
// Redirige a vista específica según tipo
$tipoView = match($solicitude->tipo) {
    'Tecnología' => 'tipo-tecnologia',
    'Dotación' => 'tipo-dotacion',
    'Servicios Generales' => 'tipo-servicios-generales',
    'Formación' => 'tipo-formacion',
    'Bienes' => 'tipo-bienes',
};

return view("solicitudes.{$tipoView}", compact('solicitude'));
```

#### **5 Nuevos Métodos para Guardar**

1. **`guardarTecnologia()`**
   - Valida necesita_computador, gama (condicional)
   - Crea/actualiza DetalleTecnologia

2. **`guardarDotacion()`**
   - Valida condicional (genero/talla si SÍ, justificación si NO)
   - Crea/actualiza DetalleUniforme

3. **`guardarServiciosGenerales()`**
   - Valida puesto_trabajo_id existe
   - Actualiza Solicitud.puesto_trabajo_id
   - Cambia puesto.estado a 'Asignado'

4. **`guardarFormacion()`**
   - Detach cursos anteriores
   - Attach nuevos curso_ids
   - Maneja null array

5. **`guardarBienes()`**
   - Crea/actualiza DetalleBienes
   - Almacena bienes_requeridos como JSON
   - Guarda observaciones

#### **Método `verificarPermiso()` - ACTUALIZADO**
```php
// Verifica si usuario puede ver solicitud según rol y tipo
if ($user->hasRole('Root')) return true;
if ($user->hasRole('Jefe RRHH')) return true;

if ($user->hasRole('Jefe Tecnología') && $solicitud->tipo === 'Tecnología') return true;
if ($user->hasRole('Jefe Dotación') && $solicitud->tipo === 'Dotación') return true;
// ... y así para otros jefes

abort(403); // Si no tiene permiso
```

#### **Método `cambiarEstado()` - REFACTORIZADO**
```php
// Solo jefes específicos + Root/Jefe RRHH pueden cambiar estado
$tipoRolMap = [
    'Tecnología' => 'Jefe Tecnología',
    'Dotación' => 'Jefe Dotación',
    // etc...
];

// Verifica que el usuario tenga el rol correspondiente al tipo
```

**Cambio de lógica importante:**
- Si estado = 'Finalizada' Y todas las solicitudes están 'Finalizada'
- Entonces: Marca proceso como 'Finalizado'
- Y: Flash message indicando que puede generarse check-in consolidado

#### **Método Nuevo: `checkinConsolidado()`**
```php
public function checkinConsolidado($procesoId)
{
    // Verifica permisos
    // Verifica que TODAS las solicitudes estén 'Finalizada'
    // Carga todas las solicitudes y sus detalles
    // Retorna vista con datos consolidados
}
```

---

### 5. **Rutas - routes/web.php**

#### **Nuevas rutas POST para guardar:**
```php
POST /solicitudes/{id}/guardar-tecnologia
POST /solicitudes/{id}/guardar-dotacion
POST /solicitudes/{id}/guardar-servicios-generales
POST /solicitudes/{id}/guardar-formacion
POST /solicitudes/{id}/guardar-bienes
```

#### **Nueva ruta para check-in consolidado:**
```php
GET /procesos-ingreso/{id}/checkin-consolidado
```

---

### 6. **Vista Check-in Consolidado**

**Archivo:** `resources/views/solicitudes/checkin-consolidado.blade.php` (280+ líneas)

**Características:**
- Encabezado con datos del proceso (código, empleado, fecha ingreso)
- Sección por cada tipo de solicitud (si existe)
- Cada sección muestra:
  - Resumen visual con íconos
  - Datos específicos almacenados
  - Estado actual (✅ Finalizada)
- Resumen final con 5 tarjetas (Tecnología, Dotación, Servicios, Formación, Bienes)
- Botones: Ver Proceso Completo, Imprimir Check-in
- Responsive design (2 col mobile, 3+ col desktop)

---

### 7. **Controlador ProcesoIngresoController.php**

#### **Método `create()` - Restringido**
```php
// Solo Root y Jefe RRHH pueden crear nuevos procesos
if (!auth()->user()->hasRole(['Root', 'Jefe RRHH'])) {
    abort(403, 'Solo el Jefe de RRHH puede crear nuevos procesos de ingreso.');
}
```

#### **Método `store()` - Restringido**
```php
// Misma validación que create()
```

---

### 8. **Vistas Actualizadas**

#### **dashboard.blade.php**
- Cambió condición de botón "Crear Nuevo Proceso" de `['Root', 'Admin']` a `['Root', 'Jefe RRHH']`

#### **procesos_ingreso/show.blade.php**
- Agregó botón "Ver Check-in Consolidado" cuando progreso === 100%
- Link a `route('solicitudes.checkin-consolidado', $proceso->id)`

---

## 🔐 Modelo de Permisos Implementado

### Roles del Sistema:
1. **Root** - Acceso total a todo
2. **Jefe RRHH** - Gestiona todo el onboarding (crea procesos, ve todas las solicitudes, asigna cursos)
3. **Jefe Tecnología** - Solo ve/edita solicitudes de tipo 'Tecnología'
4. **Jefe Dotación** - Solo ve/edita solicitudes de tipo 'Dotación'
5. **Jefe Servicios Generales** - Solo ve/edita solicitudes de tipo 'Servicios Generales'
6. **Jefe Bienes y Servicios** - Solo ve/edita solicitudes de tipo 'Bienes'

### Matriz de Permisos:

| Acción | Root | Jefe RRHH | Jefe Tec | Jefe Dot | Jefe SG | Jefe Bien |
|--------|------|-----------|----------|----------|---------|-----------|
| Ver todas solicitudes | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Ver solo su tipo | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Editar su tipo | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Cambiar estado | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Crear ProcesoIngreso | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Ver Check-in Consolidado | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 📊 Flujo de Datos

```
ProcesoIngreso creado (Solo Jefe RRHH/Root)
    ↓
Generan automáticamente 5 Solicitudes:
    ├─ tipo='Tecnología'
    ├─ tipo='Dotación'
    ├─ tipo='Servicios Generales'
    ├─ tipo='Formación'
    └─ tipo='Bienes'
    ↓
Cada Jefe ve su tipo y completa:
    ├─ Jefe Tecnología → tipo-tecnologia.blade.php
    ├─ Jefe Dotación → tipo-dotacion.blade.php
    ├─ Jefe Servicios → tipo-servicios-generales.blade.php
    ├─ Jefe RRHH → tipo-formacion.blade.php
    └─ Jefe Bienes → tipo-bienes.blade.php
    ↓
Cada Jefe marca su solicitud como "Finalizada"
    ↓
Cuando TODAS están "Finalizada":
    ├─ Proceso auto-marca como "Finalizado"
    ├─ Aparece botón "Ver Check-in Consolidado"
    └─ Se muestra vista con datos consolidados
```

---

## ✅ Validaciones Condicionales

### Tecnología:
- `gama_computador` requerido SOLO si `necesita_computador=1`

### Dotación:
- Si `necesita_dotacion=1`: Requerir `genero`, `talla_pantalon`, `talla_camiseta`
- Si `necesita_dotacion=0`: Requerir `justificacion_no_dotacion`

### Servicios Generales:
- `puesto_trabajo_id` siempre requerido
- Solo puestos con estado='Disponible' disponibles en dropdown

### Formación:
- Array de `curso_ids` (puede ser vacío)
- Cada ID debe existir en tabla `cursos`

### Bienes:
- Array de `bienes` (puede ser vacío de items, pero siempre array)
- Observaciones opcionales

---

## 🎨 Estilos Implementados

Todas las vistas utilizan:
- **Color primario:** #1B365D (azul oscuro - corporativo)
- **Color secundario:** #C59D42 (dorado - acentos)
- **Color éxito:** #28A745 (verde)
- **Color warning:** #FF9800 (naranja)
- **Color danger:** Rojo estándar

Componentes estilizados con Tailwind CSS + colores inline corporativos

---

## 📝 Notas Importantes

### Cambios en Nomenclatura:
- `Bienes y Servicios` → `Bienes` (en algunos lugares todavía aparece el nombre antiguo, se puede actualizar si es necesario)
- `puestos_trabajo` → tabla existente con estados: 'Disponible', 'Asignado', 'En Mantenimiento', 'Bloqueado'
- Cambió de 'Ocupado' a 'Asignado' para estado de puestos

### Compatibilidad Backward:
- Rutas antiguas de `especificar-ti` y `especificar-tallas` aún existen (marcadas como MANTENER POR COMPATIBILIDAD)
- Se pueden eliminar si nadie las usa

### Testing Necesario:
- [ ] Validar condicionales en Tecnología (gama aparece/desaparece)
- [ ] Validar condicionales en Dotación (tallas vs justificación)
- [ ] Verificar que solo puestos 'Disponibles' aparecen
- [ ] Probar cambio de estado a 'Finalizada' en todas las solicitudes
- [ ] Verificar que Check-in consolidado aparece cuando progreso=100%
- [ ] Probar permisos rol-basados (cada Jefe solo ve su tipo)
- [ ] Verificar que solo Jefe RRHH/Root pueden crear procesos

---

## 🚀 Deploy Checklist

- [x] Migración de base de datos ejecutada
- [x] Modelos creados/actualizados
- [x] Vistas creadas
- [x] Controladores refactorizados
- [x] Rutas agregadas
- [x] Permisos configurados
- [ ] Testing en desarrollo
- [ ] QA en staging
- [ ] Deploy a producción

---

## 📞 Contacto

Para preguntas o actualizaciones futuras del módulo, referirse a esta documentación.

**Última actualización:** February 14, 2026
