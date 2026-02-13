# 🚀 GUÍA DE EJECUCIÓN - Reorganización de Usuarios y Cargos

## 📋 RESUMEN EJECUTIVO

Se ha preparado una reorganización completa de la estructura de datos para **alinear la lógica de usuarios y cargos**:

| Elemento | Estado |
|----------|--------|
| ✅ Documento de análisis | Completado |
| ✅ Migración Laravel | Creada |
| ✅ Seeder de sincronización | Creado |
| ✅ Script SQL alternativo | Creado |
| ✅ Modelos actualizados | Completados |

---

## 📍 ARCHIVOS GENERADOS

```
onboarding/
├── REORGANIZACION_BD_LOGICA.md                          ← Análisis detallado
├── database/
│   ├── migrations/
│   │   └── 2026_02_13_000001_reorganizar_usuarios_cargos.php
│   ├── seeders/
│   │   └── ReorganizarUsuariosCargoSeeder.php
│   └── sincronizacion_usuarios_cargos.sql              ← Script SQL manual
├── app/Models/
│   ├── User.php                                         ← Actualizado
│   ├── Cargo.php                                        ← Actualizado
│   └── MaestroCargo.php                                 ← Nuevo modelo
└── GUIA_EJECUCION.md                                   ← Este archivo
```

---

## 🎯 OPCIÓN 1: EJECUCIÓN AUTOMÁTICA (Recomendado)

### Paso 1: Hacer Backup
```bash
# Backup de la base de datos (en terminal)
mysqldump -u root -p onboarding > backups/onboarding_backup_$(date +%Y%m%d).sql
```

### Paso 2: Ejecutar Migración
```bash
# En la carpeta del proyecto
php artisan migrate
```

✅ Esto crea la tabla `maestro_cargos` y agrega campos a `users` y `cargos`.

### Paso 3: Ejecutar Seeder
```bash
php artisan db:seed --class=ReorganizarUsuariosCargoSeeder
```

✅ Esto sincroniza automáticamente todos los datos.

### Paso 4: Verificar Resultados
```bash
php artisan tinker

# En el tinker, ejecutar:
>>> User::with('cargo', 'area')->get();
>>> Cargo::where('activo', true)->get();
>>> \App\Models\MaestroCargo::count();
```

**Resultado esperado:**
- ✅ 11 usuarios con `cargo_id` NO NULL
- ✅ Jefes/coordinadores con `puede_aprobar_solicitudes = 1`
- ✅ 54 registros en `maestro_cargos`
- ✅ ~10-15 cargos activos en tabla `cargos`

---

## 🎯 OPCIÓN 2: EJECUCIÓN MANUAL (Alternativa)

### Paso 1: Ejecutar Migración
```bash
php artisan migrate
```

### Paso 2: Ejecutar Script SQL
1. Abrir phpMyAdmin o tu gestor de BD
2. Seleccionar la base de datos `onboarding`
3. Ir a la pestaña "SQL"
4. Copiar el contenido completo de: `database/sincronizacion_usuarios_cargos.sql`
5. Ejecutar

**O desde línea de comandos:**
```bash
mysql -u root -p onboarding < database/sincronizacion_usuarios_cargos.sql
```

---

## ⚠️ ROLLBACK (Si algo sale mal)

### Opción A: Desde Backup
```bash
mysql -u root -p onboarding < backups/onboarding_backup_YYYYMMDD.sql
```

### Opción B: Revertir Migración
```bash
php artisan migrate:rollback
```

---

## 📊 CAMBIOS REALIZADOS

### 1. Nueva Tabla: `maestro_cargos`
**Propósito**: Referencia histórica COMPLETA de todos los cargos (54 registros)

```sql
-- Ejemplo de registro
id: 1
nombre: Gerente Administración
area_id: 1
es_puesto_entrada: false
nivel_jerarquico: 5
activo: true
```

### 2. Tabla `cargos` (Modificada)
**Propósito**: Solo cargos con VACANTES disponibles

**Nuevos campos agregados:**
- `vacantes_disponibles` (SMALLINT): Cantidad de puestos libres
- `activo` (BOOLEAN): ¿Aceptan nuevos empleados?
- `descripcion` (TEXT): Descripción detallada

```sql
-- Ejemplo
id: 1
nombre: Técnico de Soporte Nivel 1
area_id: 19
vacantes_disponibles: 2
activo: true
```

### 3. Tabla `users` (Actualizada)
**Propósito**: Empleados actuales con asignación correcta de cargo y rol

**Nuevos campos agregados:**
- `rol_onboarding` (ENUM): 'admin', 'jefe_area', 'coordinador', 'revisor', 'operador'
- `puede_aprobar_solicitudes` (BOOLEAN): ¿Puede aprobar solicitudes de nuevo ingreso?
- `jefe_directo_id` (FK → users): Cadena de mando

```sql
-- Ejemplo Usuario 2
id: 2
name: Jefe Talento Humano
email: jefe.rrhh@sinergia.com
cargo_id: 46 (Gerente Talento Humano)
area_id: 20
rol_onboarding: jefe_area
puede_aprobar_solicitudes: 1
jefe_directo_id: NULL
```

---

## 🔍 VALIDACIONES POST-EJECUCIÓN

Ejecutar estas queries para verificar todo está correcto:

### ✅ Validación 1: Usuarios sin cargo
```sql
SELECT * FROM users WHERE cargo_id IS NULL;
-- Resultado esperado: 0 registros
```

### ✅ Validación 2: Aprobadores correctos
```sql
SELECT u.id, u.name, c.nombre as cargo, u.rol_onboarding
FROM users u
JOIN cargos c ON u.cargo_id = c.id
WHERE u.puede_aprobar_solicitudes = 1;

-- Resultado esperado: 5-6 usuarios (jefes/coordinadores)
```

### ✅ Validación 3: Maestro cargos poblado
```sql
SELECT COUNT(*) FROM maestro_cargos;
-- Resultado esperado: 54
```

### ✅ Validación 4: Cargos con vacantes
```sql
SELECT COUNT(*) FROM cargos WHERE activo = 1 AND vacantes_disponibles > 0;
-- Resultado esperado: ~10-15 cargos
```

---

## 💻 USO EN LA APLICACIÓN

### A. Obtener Aprobadores de Solicitudes
```php
// En un controlador
$aprobadores = User::aprobadores()->get();

// O customizado por área
$jefesArea = User::jefesArea()
    ->where('area_id', $areaId)
    ->get();

// O coordinadores solamente
$coordinadores = User::coordinadores()->get();
```

### B. Asignar Solicitantes a Aprobadores
```php
// Por rol
if ($usuario->rol_onboarding === 'jefe_area') {
    // Puede aprobar solicitudes de su área
}

if ($usuario->rol_onboarding === 'coordinador') {
    // Puede revisar según su especialidad
}
```

### C. Obtener Cargos Disponibles
```php
// Cargos con vacantes
$cargosCon Vacantes = Cargo::conVacantes()->get();

// O verificar si un cargo específico tiene vacantes
$cargo = Cargo::find($cargoId);
if ($cargo->tieneVacantes()) {
    // Desplegar como opción
}
```

### D. Ver Cadena de Mando
```php
// Obtener jefe directo de un usuario
$jefe = $usuario->jefe;

// Obtener todos los subordinados directos
$subordinados = $usuario->subordinados;

// Obtener todos los supervisados (directo + indirecto)
$supervision = $usuario->obtenerSupervisionados();
```

---

## 📈 BENEFICIOS INMEDIATOS

1. ✅ **Integridad referencial**: No hay usuarios sin cargo
2. ✅ **Lógica clara**: Cargos de entrada vs jefes/coordinadores
3. ✅ **Auditoría mejorada**: Saber quién puede aprobar qué
4. ✅ **Escalabilidad**: Agregar nuevos jefes sin modificar SQL
5. ✅ **Reportería**: Análisis de cargos, vacantes, cadena de mando

---

## ⚡ PROXIMOS PASOS RECOMENDADOS

### 1. Actualizar Controladores
Buscar referencias a `$user->name` para cargos y cambiar a:
```php
$user->getNombreCargoCompleto()  // Obtiene el cargo del usuario
$user->rol_onboarding             // Obtiene el rol en onboarding
```

### 2. Actualizar Vistas
En formularios de solicitudes, mostrar aprobadores dinámicamente:
```php
<select name="aprobador_id">
    @foreach(User::aprobadores()->get() as $user)
        <option value="{{ $user->id }}">
            {{ $user->name }} - {{ $user->getNombreCargoCompleto() }}
        </option>
    @endforeach
</select>
```

### 3. Crear Reportes
Nuevos datos disponibles para reportería:
- Cantidad de vacantes por cargo
- Empleados por cargo
- Aprobadores por área
- Cadena de mando completa

---

## 🆘 SOLUCIÓN DE PROBLEMAS

### Problema: "SQLSTATE[HY000]: General error: 1030"
**Solución**: Asegurar que la carpeta `database` tenga permisos de escritura
```bash
chmod 755 database/
```

### Problema: Seeder no encuentra los cargos
**Solución**: Ejecutar primero `php artisan migrate`

### Problema: Usuarios con cargo_id NULL después del seeder
**Solución**: Ejecutar script SQL manualmente o revisar que los nombres de cargocoincidan exactamente

### Problema: Rollback no funciona
**Solución**: 
```bash
php artisan migrate:rollback --step=1
# O si es necesario, ejecutar el backup
mysql -u root -p onboarding < backups/onboarding_backup.sql
```

---

## 📞 SOPORTE

Para preguntas sobre la implementación:
1. Revisar [REORGANIZACION_BD_LOGICA.md](./REORGANIZACION_BD_LOGICA.md)
2. Revisar comentarios en la migración
3. Revisar comentarios en el seeder

---

## ✅ CHECKLIST FINAL

- [ ] Backup realizado
- [ ] Migración ejecutada: `php artisan migrate`
- [ ] Seeder ejecutado: `php artisan db:seed --class=ReorganizarUsuariosCargoSeeder`
- [ ] Validación 1: `SELECT * FROM users WHERE cargo_id IS NULL` (0 resultados)
- [ ] Validación 2: `SELECT COUNT(*) FROM maestro_cargos` (54 resultados)
- [ ] Validación 3: Aprobadores verificados en Tinker
- [ ] Validación 4: Cargos con vacantes mostrados correctamente
- [ ] Controladores actualizados (si aplica)
- [ ] Vistas actualizadas (si aplica)
- [ ] Pruebas en desarrollo realizadas ✅

---

**¡Reorganización completada exitosamente!** 🎉
