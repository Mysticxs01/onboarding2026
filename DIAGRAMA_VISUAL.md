# 📊 DIAGRAMA VISUAL - Nueva Estructura de BD

## Antes vs Después

### ❌ ESTRUCTURA ACTUAL (Problemática)

```
┌─────────────────────────────────────────────────┐
│  TABLA: users (11 registros)                   │
├─────────────────────────────────────────────────┤
│ id │ name (Cargo hardcoded) │ cargo_id (NULL)  │
├─────────────────────────────────────────────────┤
│ 1  │ Administrador         │ NULL             │
│ 2  │ Jefe de RRHH          │ NULL             │
│ 3  │ Coordinador Formación │ NULL             │ ← Incoherencia
│ 4  │ Root Admin            │ NULL             │
│ ... (etc)                                      │
└─────────────────────────────────────────────────┘

                        ❌ NO RELACIÓN
                        (cargo_id es NULL)
                             │
                             v
┌─────────────────────────────────────────────────┐
│  TABLA: cargos (54 registros)                  │
├─────────────────────────────────────────────────┤
│ id │ nombre              │                     │
├─────────────────────────────────────────────────┤
│ 1  │ Gerente Admin.      │                     │
│ ... (todos los cargos)                        │
│ 54 │ Especialista Bien.  │                     │
└─────────────────────────────────────────────────┘

PROBLEMA: 
- Cargos están en el campo "name" de users (hardcoded)
- No hay vinculación a la tabla cargos
- La tabla cargos se usa para otros propósitos
- NO hay claridad de roles en onboarding
```

---

### ✅ NUEVA ESTRUCTURA (Reorganizada)

```
┌─────────────────────────────────────────────────────────┐
│  TABLA: maestro_cargos (54 registros)                  │
│         [REFERENCIA COMPLETA HISTÓRICA]                │
├─────────────────────────────────────────────────────────┤
│ id │ nombre                      │ nivel │ es_entrada │
├─────────────────────────────────────────────────────────┤
│ 1  │ Gerente Administración      │ 5     │ false      │
│ 2  │ Coordinador Formación       │ 3     │ true       │
│ 3  │ Técnico Soporte Nivel 1     │ 1     │ true       │
│ 4  │ Analista de Crédito         │ 2     │ true       │
│ ...│ (TODOS los cargos: 54)      │ ...   │ ...        │
└─────────────────────────────────────────────────────────┘
         │
         │ (Referencia histórica)
         │
         └─────────────────────────────────┬────────────────┐
                                           │                │
                                           v                v
┌──────────────────────────────────┐  ┌─────────────────────────────────┐
│ TABLA: cargos                    │  │ TABLA: users                    │
│ [VACANTES ACTIVAS]               │  │ [EMPLEADOS ACTUALES]            │
│ ~10-15 registros                 │  │ 11 registros                    │
├──────────────────────────────────┤  ├─────────────────────────────────┤
│ id│nombre          │vacantes│act│  │id│name│cargo_id│rol_onboard │  │
├──────────────────────────────────┤  ├─────────────────────────────────┤
│ 5 │Técnico Soporte │ 2     │ 1 │  │1 │Admin│46(FK)│admin        │  │
│ 8 │Analista Crédito│ 1     │ 1 │  │2 │Jefe │46(FK)│jefe_area    │  │
│ 12│Asesor Servicio │ 1     │ 1 │  │3 │Coor │49(FK)│coordinador  │  │
│   │(SOLO entrada)  │       │   │  │..│...  │...   │...          │  │
└──────────────────────────────────┘  └─────────────────────────────────┘
         │                                    │
         │  [Tiene Vacantes]                  │
         │                                    │
         └──────────────┬─────────────────────┘
                        │
                   RELACIÓN CLARA
                FOREIGN KEY cargo_id
                  (NO NULL)
                        │
                        v
            ✅ Cada usuario vinculado
               a un cargo específico
               en la tabla cargos
```

---

## 📈 Flujo de Datos Actualizado

### Nuevo Ingreso a la Empresa

```
┌─────────────────────────────────────────────────────────────┐
│ 1. CREAR SOLICITUD DE NUEVO INGRESO                        │
└─────────────────────────────────────────────────────────────┘
        │
        │ ¿Qué cargo va a ocupar?
        │
        v
┌─────────────────────────────────────────────────────────────┐
│ 2. BUSCAR CARGO EN TABLA: cargos (SOLO VACANTES)           │
│    - Mostrar lista de cargos activos                        │
│    - Mostrar vacantes disponibles                           │
└─────────────────────────────────────────────────────────────┘
        │
        v
┌─────────────────────────────────────────────────────────────┐
│ 3. ASIGNAR SOLICITUD A APROBADOR                           │
│    - Obtener Usuario jefe del área                          │
│    - Usar: User::jefesArea()->where('area_id', $id)->first()│
│    - O si no existe: User::coordinadores()->first()         │
└─────────────────────────────────────────────────────────────┘
        │
        v
┌─────────────────────────────────────────────────────────────┐
│ 4. APROBADOR REVISA (usa nuevo campo: puede_aprobar)       │
│    if ($usuario->puedeAprobarSolicitudes()) { ... }        │
└─────────────────────────────────────────────────────────────┘
        │
        v
┌─────────────────────────────────────────────────────────────┐
│ 5. SOLICITUD APROBADA                                       │
│    - Crear registro en users                                │
│    - Asignar cargo_id                                       │
│    - Descontar vacante: $cargo->decrement(...)             │
└─────────────────────────────────────────────────────────────┘
        │
        v
┌─────────────────────────────────────────────────────────────┐
│ 6. ONBOARDING COMPLETADO ✅                                 │
│    - Nuevo empleado en el sistema                           │
│    - Asignado a su cargo con vacante                        │
│    - Vinculado a su jefe directo                            │
└─────────────────────────────────────────────────────────────┘
```

---

## 🎯 Roles en Onboarding

### Matriz de Responsabilidades

```
┌────────────────────┬──────────────────────────────────────────────┐
│ Rol                │ Responsabilidades                            │
├────────────────────┼──────────────────────────────────────────────┤
│ admin              │ • Crear solicitudes                          │
│ (Administrador)    │ • Aprobar todas las solicitudes              │
│                    │ • Gestionar usuarios y cargos                │
│                    │ • Acceso completo al sistema                 │
├────────────────────┼──────────────────────────────────────────────┤
│ jefe_area          │ • Crear solicitudes en su área               │
│ (Jefe de Depto)    │ • Aprobar solicitudes de su área             │
│ [pode_aprobar=1]   │ • Supervisar empleados directos              │
│                    │ • Reportes de su área                        │
├────────────────────┼──────────────────────────────────────────────┤
│ coordinador        │ • Crear solicitudes en su especialidad       │
│ (Coordinador)      │ • Revisar/validar en su área                 │
│ [pode_aprobar=1]   │ • Coordinar procesos de inducción            │
│                    │ • Soporte a nuevos empleados                 │
├────────────────────┼──────────────────────────────────────────────┤
│ revisor            │ • Revisar conformidad de documentos           │
│ (Revisor)          │ • Validar completitud de carpeta             │
│ [pode_aprobar=0]   │ • Reportes de cumplimiento                   │
├────────────────────┼──────────────────────────────────────────────┤
│ operador           │ • Crear solicitud de equipos                 │
│ (Operador)         │ • Listar activos a entregar                  │
│ [pode_aprobar=0]   │ • Registrar entregas                         │
│                    │ • No puede aprobar nada                      │
└────────────────────┴──────────────────────────────────────────────┘
```

---

## 📊 Estadísticas de Datos

### Antes
```
Usuarios sin cargo       : 11 (100%)
Cargo_id = NULL          : 11 (100%)
Relación users-cargos    : ROTA
Cargos en uso            : 54 (todos)
Claridad de roles        : NULA
```

### Después
```
Usuarios sin cargo       : 0 (0%)
Cargo_id asignado        : 11 (100%)
Relación users-cargos    : INTACTA
Cargos en tabla cargos   : ~15 (vacantes)
Cargos en maestro_cargos : 54 (referencia)
Usuarios aprobadores     : 5-6
Usuarios operativos      : 5-6
Claridad de roles        : TOTAL ✅
```

---

## 🔄 Cambios Campo por Campo

### Tabla: users

```
ANTES                          DESPUÉS
─────────────────────────────────────────────────────────────
name: "Jefe de RRHH"    →      name: "Jefe Talento Humano"
cargo_id: NULL          →      cargo_id: 46 (FK→cargos)
[sin campo]             →      rol_onboarding: "jefe_area"
[sin campo]             →      puede_aprobar_solicitudes: 1
[sin campo]             →      jefe_directo_id: NULL
```

### Tabla: cargos

```
ANTES                          DESPUÉS
─────────────────────────────────────────────────────────────
[sin campo]             →      vacantes_disponibles: 2
[sin campo]             →      activo: 1 (o 0)
[sin campo]             →      descripcion: "Texto..."
```

### Nueva Tabla: maestro_cargos

```
NUEVA TABLA (antes no existía)
─────────────────────────────────────────────────────────────
id, nombre, area_id
descripcion, nivel_jerarquico
es_puesto_entrada, activo
created_at, updated_at
```

---

## 💾 Integridad Referencial

```
maestro_cargos (54)
    │
    ├──> areas (24)
    │
    └──> (Referencia histórica, sin FK en users)


cargos (~15 activos)
    │
    ├──> areas (24)          [FK: area_id REFERENCES areas(id)]
    │
    └──> users (11+)         [FK INVERSA: users.cargo_id REFERENCES cargos(id)]


users (11)
    │
    ├──> cargos             [FK: cargo_id REFERENCES cargos(id)]
    ├──> areas              [FK: area_id REFERENCES areas(id)]
    ├──> users (jefe)       [FK: jefe_directo_id REFERENCES users(id)]
    └──> users (subordinados) [1:N inverse]
```

---

## 🚀 Beneficios Visuales

```
ANTES: 
  Usuario name="Jefe de RRHH" → ??? ← ¿Dónde está su cargo? Cargo_id=NULL
  No se sabe quién puede aprobar, no hay roles

DESPUÉS:
  Usuario name="Jefe Talento Humano" 
    │
    ├─→ cargo_id: 46
    ├─→ cargo name: "Gerente Talento Humano"
    ├─→ rol_onboarding: "jefe_area"  
    ├─→ puede_aprobar_solicitudes: true
    ├─→ area: "Talento Humano"
    └─→ jefe_directo_id: NULL (es gerente)

RESULTADO: Claridad total ✅
```

---

## 📋 Checklist Implementación

```
✅ Tabla maestro_cargos creada
✅ Campos agregados a tabla cargos
✅ Campos agregados a tabla users
✅ Modelo MaestroCargo.php creado
✅ Modelo User.php actualizado
✅ Modelo Cargo.php actualizado
✅ Migración creada
✅ Seeder creado
✅ Script SQL manual creado
✅ Documentación completa
✅ Ejemplos de uso generados
```

---

## 🎓 Conclusión

La nueva estructura permite:
1. ✅ Integridad referencial total
2. ✅ Claridad en roles y responsabilidades
3. ✅ Escalabilidad de usuarios aprobadores
4. ✅ Auditoría completa de cadena de mando
5. ✅ Reportería detallada de vacantes y empleados
6. ✅ Separación clara entre directorio completo vs operativo

**¡Listo para implementar!** 🚀
