# ⚡ QUICK REFERENCE - Reorganización Usuarios/Cargos

> Este documento es un resumen ejecutivo de 2 minutos

---

## 🎯 EL PROBLEMA

```
users.cargo_id = NULL para TODOS los usuarios
Las responsabilidades están hardcoded en user.name
No hay claridad de quién puede aprobar solicitudes
```

---

## ✅ LA SOLUCIÓN

```
Nueva tabla: maestro_cargos (54 cargos - referencia histórica)
Tabla cargos: Solo ~15 con vacantes disponibles
Tabla users: Todos vinculados a cargo_id + roles definidos
```

---

## 📊 CAMBIOS PRINCIPALES

### Users Tabla
```sql
ALTER TABLE users ADD (
  rol_onboarding ENUM(...),          -- admin|jefe_area|coordinador|revisor|operador
  puede_aprobar_solicitudes BOOLEAN, -- true/false
  jefe_directo_id BIGINT FK          -- para cadena de mando
);
```

### Cargos Tabla
```sql
ALTER TABLE cargos ADD (
  vacantes_disponibles SMALLINT,  -- cantidad de puestos libres
  activo BOOLEAN,                 -- true = acepta nuevos
  descripcion TEXT                -- detalles del cargo
);
```

### Nueva Maestro_Cargos Tabla
```sql
CREATE TABLE maestro_cargos (
  id, nombre, area_id, 
  descripcion, nivel_jerarquico, 
  es_puesto_entrada, activo
);
-- Contiene todos los 54 cargos para referencia
```

---

## 🚀 CÓMO IMPLEMENTAR (3 PASOS)

### 1️⃣ Hacer Backup
```bash
mysqldump -u root -p onboarding > backup.sql
```

### 2️⃣ Ejecutar Migración
```bash
php artisan migrate
php artisan db:seed --class=ReorganizarUsuariosCargoSeeder
```

### 3️⃣ Validar
```sql
SELECT * FROM users WHERE cargo_id IS NULL;     -- Debe dar 0
SELECT COUNT(*) FROM maestro_cargos;            -- Debe dar 54
SELECT COUNT(*) FROM users WHERE puede_aprobar_solicitudes=1; -- 6
```

---

## 💻 USAR EN CÓDIGO

### Obtener Aprobadores
```php
User::aprobadores()->get();           // Todos que pueden aprobar
User::jefesArea()->get();              // Solo jefes
User::coordinadores()->get();          // Solo coordinadores
```

### Obtener Cargos Disponibles
```php
Cargo::conVacantes()->get();           // Cargos con vacantes
$cargo->tieneVacantes();               // Verificar uno específico
```

### Cadena de Mando
```php
$usuario->jefe;                        // Jefe directo
$usuario->subordinados;                // Reportan a este usuario
$usuario->obtenerSupervisionados();    // Todos supervisados
```

---

## 📋 APROBADORES ACTUAL

```
ID │ Nombre                   │ Rol
───┼──────────────────────────┼─────────────
1  │ Administrador Sistema    │ admin
2  │ Jefe Talento Humano      │ jefe_area    ✅
3  │ Coordinador Formación    │ coordinador  ✅
4  │ Root Administrator       │ admin
5  │ Admin Onboarding         │ admin
6  │ Jefe Tecnología          │ jefe_area    ✅

Total: 6 pueden aprobar
```

---

## 📚 DOCUMENTOS RELACIONADOS

| Documento | Para... |
|-----------|---------|
| [RESUMEN_REORGANIZACION.md](RESUMEN_REORGANIZACION.md) | Idea general |
| [DIAGRAMA_VISUAL.md](DIAGRAMA_VISUAL.md) | Entender visualmente |
| [GUIA_EJECUCION.md](GUIA_EJECUCION.md) | Implementar paso a paso |
| [EJEMPLOS_USO_REORGANIZACION.php](EJEMPLOS_USO_REORGANIZACION.php) | Usar en controladores |
| [README_INDICE.md](README_INDICE.md) | Índice completo |

---

## ⚠️ IMPORTANTE

- ✅ Es **reversible** con `php artisan migrate:rollback`
- ✅ Los **modelos fueron actualizados** automáticamente
- ✅ El **seeder sincroniza datos** correctamente
- ❌ **NO necesita cambios en vistas** (por ahora)
- ⚠️ **SI necesita actualizar controladores** que usan $user->name para cargos

---

## 🆘 TROUBLESHOOTING

| Problema | Solución |
|----------|----------|
| Migración falla | Verificar conexión BD y permisos |
| Seeder no sincroniza | Ejecutar migración primero |
| cargo_id sigue NULL | Ejecutar seeder nuevamente |
| Revertir cambios | `php artisan migrate:rollback` |

---

**¿Dudas?** → Ver [README_INDICE.md](README_INDICE.md) → "Soporte y Dudas"

**Listo para implementar** ✅
