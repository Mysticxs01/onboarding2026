<!-- EJEMPLOS DE USO - MÓDULO DE SOLICITUDES REFACTORIZADO -->

# 📚 EJEMPLOS DE USO - Sistema de Solicitudes Refactorizado

## 1️⃣ Flujo Completo de un Nuevo Empleado

### PASO 1: Crear ProcesoIngreso (Solo Jefe RRHH/Root)

**URL:** `POST /procesos-ingreso`

```javascript
{
    "nombre_completo": "Juan Carlos López",
    "tipo_documento": "Cédula",
    "documento": "1001234567",
    "cargo_id": 5,              // Ejecutivo de Ventas (oblig. está en área VENTAS)
    "fecha_ingreso": "2026-03-15",
    "jefe_id": 2                // Jefe inmediato (debe estar en área Ventas)
}
```

**Resultado Automático:**
```
✅ Proceso ING-20260214235901 creado
✅ 5 Solicitudes generadas automáticamente:
   - Solicitud #1: tipo='Tecnología', estado='Pendiente'
   - Solicitud #2: tipo='Dotación', estado='Pendiente'
   - Solicitud #3: tipo='Servicios Generales', estado='Pendiente'
   - Solicitud #4: tipo='Formación', estado='Pendiente'
   - Solicitud #5: tipo='Bienes', estado='Pendiente'
```

---

### PASO 2: Jefe Tecnología Completa su Solicitud

**URL:** `GET /solicitudes/1` (ver solicitud tipo Tecnología)

**Visto por Jefe Tecnología:**
```
┌─────────────────────────────────────────────────┐
│ 💻 Solicitud de Tecnología #1                   │
├─────────────────────────────────────────────────┤
│ Empleado: Juan Carlos López                     │
│ Cargo: Ejecutivo de Ventas                      │
│ Área: Ventas                                    │
│                                                  │
│ 🤔 ¿Necesita Computador?  [⊙ SÍ] [ ] NO       │
│                                                  │
│ Cuando SÍ se selecciona:                        │
│ Gama del Computador: [Básica ▼]                │
│                      - Básica                   │
│                      - Media                    │
│                      - Premium                  │
│                                                  │
│ Credenciales y Plataformas:                    │
│ ┌──────────────────────────────────────┐       │
│ │ Usuario: jclopez                      │       │
│ │ Contraseña: xxxxxx                    │       │
│ │ Microsoft Teams: team.com             │       │
│ │ Salesforce: sales.user.com            │       │
│ └──────────────────────────────────────┘       │
│                                                  │
│ [✅ Guardar]     [❌ Cancelar]                 │
└─────────────────────────────────────────────────┘
```

**POST /solicitudes/1/guardar-tecnologia**
```javascript
{
    "necesita_computador": 1,
    "gama_computador": "Media",
    "credenciales_plataformas": "Usuario: jclopez\nPassword: xxxxxx\nTeams: team.com\nSalesforce: sfdc.com"
}
```

**Validación:**
```
✅ necesita_computador = 1       [VÁLIDO]
✅ gama_computador = 'Media'     [VÁLIDO - requerido si computador=1]
✅ credenciales < 2000 chars    [VÁLIDO]

Result: ✅ Guardado exitosamente
```

---

### PASO 3: Jefe Dotación Completa su Solicitud

**URL:** `GET /solicitudes/2` (ver solicitud tipo Dotación)

**Visto por Jefe Dotación:**
```
┌─────────────────────────────────────────────────┐
│ 👔 Solicitud de Dotación #2                     │
├─────────────────────────────────────────────────┤
│                                                  │
│ 🤔 ¿Necesita Dotación?  [⊙ SÍ] [ ] NO         │
│                                                  │
│ SECCIÓN A: Si SÍ (mostrado/hidden por JS)      │
│ ┌─────────────────────────────────────┐        │
│ │ Género: [Masculino ▼]                │       │
│ │           - Masculino                │       │
│ │           - Femenino                 │       │
│ │                                      │       │
│ │ Talla Pantalón: [M ▼]               │       │
│ │ Talla Camiseta: [M ▼]               │       │
│ └─────────────────────────────────────┘        │
│                                                  │
│ SECCIÓN B: Si NO (mostrado/hidden por JS)      │
│ ┌─────────────────────────────────────┐        │
│ │ Justificación:                       │       │
│ │ [Textarea 1000 chars max]            │       │
│ │                                      │       │
│ │ El empleado tiene su propia          │       │
│ │ dotación de empresa anterior.        │       │
│ └─────────────────────────────────────┘        │
│                                                  │
│ [✅ Guardar]     [❌ Cancelar]                 │
└─────────────────────────────────────────────────┘
```

**POST /solicitudes/2/guardar-dotacion**

**Caso A: Requiere Dotación**
```javascript
{
    "necesita_dotacion": 1,
    "genero": "Masculino",
    "talla_pantalon": "M",
    "talla_camiseta": "L"
}
```

**Caso B: NO requiere Dotación**
```javascript
{
    "necesita_dotacion": 0,
    "justificacion_no_dotacion": "El empleado tiene dotación de su empresa anterior y solicitó no renovarla"
}
```

**Validación - Caso A:**
```
✅ necesita_dotacion = 1                    [VÁLIDO]
✅ genero es requerido                      [VÁLIDO]
✅ talla_pantalon es requerido              [VÁLIDO]
✅ talla_camiseta es requerido              [VÁLIDO]

Result: ✅ Guardado exitosamente
```

**Validación - Caso B:**
```
✅ necesita_dotacion = 0                         [VÁLIDO]
✅ justificacion_no_dotacion es requerido       [VÁLIDO]
✅ genero, talla_* pueden ser null              [VÁLIDO]

Result: ✅ Guardado exitosamente
```

---

### PASO 4: Jefe Servicios Generales Asigna Puesto

**URL:** `GET /solicitudes/3` (ver solicitud tipo Servicios Generales)

**Visto por Jefe Servicios Generales:**
```
┌──────────────────────────────────────────────────┐
│ 🏢 Solicitud de Servicios Generales #3           │
├──────────────────────────────────────────────────┤
│                                                   │
│ Selecciona Puesto de Trabajo Disponible:        │
│ ┌─────────────────────────────┐                │
│ │ A12 - Oficina (Piso 2)  ▼   │               │
│ │ B01 - Oficina (Piso 1)      │               │
│ │ B02 - Oficina (Piso 1)      │               │
│ │ B04 - Oficina (Piso 1)      │               │
│ │ C01 - Oficina (Piso 3)      │               │
│ └─────────────────────────────┘                │
│                                                   │
│ ℹ️ Solo puestos con estado='Disponible'         │
│ ℹ️ La asignación se realiza aquí sin módulo     │
│    separado                                      │
│                                                   │
│ [✅ Asignar Puesto]   [❌ Cancelar]            │
└──────────────────────────────────────────────────┘
```

**POST /solicitudes/3/guardar-servicios-generales**
```javascript
{
    "puesto_trabajo_id": 12  // Puesto A12
}
```

**Validación:**
```
✅ puesto_trabajo_id existe en puestos_trabajo   [VÁLIDO]
✅ estado del puesto = 'Disponible'              [VÁLIDO]

Result: ✅ Puesto asignado
        ✅ Puesto A12 marcado como 'Asignado'
```

---

### PASO 5: Jefe RRHH Asigna Plan de Formación

**URL:** `GET /solicitudes/4` (ver solicitud tipo Formación)

**Visto por Jefe RRHH:**
```
┌────────────────────────────────────────────────────┐
│ 📚 Plan de Formación #4                            │
├────────────────────────────────────────────────────┤
│                                                     │
│ Selecciona Cursos para el Plan de Formación:     │
│ (31 cursos disponibles - scroll)                  │
│                                                     │
│ ┌─────────────────────────────────────────────┐  │
│ │ ☑️ Inducción General                        │  │
│ │    📖 Obligatorio | ⏱️ 8h | 🎓 Presencial │  │
│ │                                             │  │
│ │ ☑️ Código de Conducta                       │  │
│ │    📖 Obligatorio | ⏱️ 2h | 🎓 e-Learning │  │
│ │                                             │  │
│ │ ☑️ Productos Financieros                    │  │
│ │    📖 Obligatorio | ⏱️ 16h | 🎓 Híbrido   │  │
│ │                                             │  │
│ │ ☐ Servicio al Cliente                       │  │
│ │    📖 Complementario | ⏱️ 4h | 🎓 e-Learning│  │
│ │                                             │  │
│ │ [... más cursos ...]                        │  │
│ └─────────────────────────────────────────────┘  │
│                                                     │
│ [✅ Guardar Plan]   [❌ Cancelar]                │
└────────────────────────────────────────────────────┘
```

**POST /solicitudes/4/guardar-formacion**
```javascript
{
    "curso_ids": [1, 2, 3, 5, 7]  // IDs de cursos seleccionados
}
```

**Validación:**
```
✅ curso_ids es array                            [VÁLIDO]
✅ Todos los IDs existen en tabla cursos        [VÁLIDO]
✅ Puede estar vacío (plan mínimo de formación) [VÁLIDO]

Result: ✅ Cursos asignados (5 total)
```

---

### PASO 6: Jefe Bienes Selecciona Items

**URL:** `GET /solicitudes/5` (ver solicitud tipo Bienes)

**Visto por Jefe Bienes y Servicios:**
```
┌──────────────────────────────────────────────────┐
│ 🛋️ Solicitud de Bienes y Servicios #5           │
├──────────────────────────────────────────────────┤
│                                                   │
│ Selecciona los Bienes Requeridos:               │
│                                                   │
│ ┌─────────────────────────────────────────────┐ │
│ │ ☑️ 🪑 Silla       │ ☐ 🗑️ Papelera         │ │
│ │ ☑️ 🖥️ Escritorio  │ ☐ 📦 Organizador       │ │
│ │ ☑️ 📓 Cuadernos   │ ☑️ ✏️ Lapiceros         │ │
│ │ ☐ 📌 Post-it     │ ☐ 📂 Archivador       │ │
│ │ ☐ 🖱️ Mouse pad   │ ☐ 🔌 Cable cargador  │ │
│ └─────────────────────────────────────────────┘ │
│                                                   │
│ Observaciones (Opcional):                        │
│ ┌─────────────────────────────────────────────┐ │
│ │ Silla ergonómica preferida, altura ajustable │ │
│ │ Escritorio de 1.5m mínimo                    │ │
│ └─────────────────────────────────────────────┘ │
│                                                   │
│ [✅ Guardar]   [❌ Cancelar]                    │
└──────────────────────────────────────────────────┘
```

**POST /solicitudes/5/guardar-bienes**
```javascript
{
    "bienes": [
        "silla",
        "escritorio",
        "cuadernos",
        "lapiceros"
    ],
    "observaciones_bienes": "Silla ergonómica preferida, altura ajustable. Escritorio de 1.5m mínimo"
}
```

**Guardado en DB:**
```json
{
    "bienes_requeridos": ["silla", "escritorio", "cuadernos", "lapiceros"],
    "observaciones": "Silla ergonómica preferida, altura ajustable. Escritorio de 1.5m mínimo"
}
```

---

## 2️⃣ Cambio de Estado a "Finalizada"

### Cada Jefe marca su solicitud como "Finalizada"

**POST /solicitudes/{id}/cambiar-estado**

```javascript
{
    "estado": "Finalizada",
    "observaciones": "Completado exitosamente"
}
```

**Lógica en Backend:**
```
1. Jefe Tecnología marca Solicitud #1 → "Finalizada"
2. Jefe Dotación marca Solicitud #2 → "Finalizada"
3. Jefe SG marca Solicitud #3 → "Finalizada"
4. Jefe RRHH marca Solicitud #4 → "Finalizada"
5. Jefe Bienes marca Solicitud #5 → "Finalizada"

Cuando TODAS están "Finalizada":
├─ Sistema detecta: proceso.solicitudes().where('estado', '!=', 'Finalizada').doesntExist()
├─ Actualiza: proceso.estado = "Finalizado"
├─ Flash: "¡Proceso de onboarding completado! Ahora puede generar el check-in consolidado."
└─ Dashboard: Muestra botón "Ver Check-in Consolidado" (solo si progreso === 100%)
```

---

## 3️⃣ Ver Check-in Consolidado

### Cuando todas las solicitudes están "Finalizada"

**URL:** `GET /procesos-ingreso/{procesId}/checkin-consolidado`

**Respuesta Visual (Vista Consolidada):**
```
╔════════════════════════════════════════════════════════════════════╗
║                 ✅ CHECK-IN CONSOLIDADO DE ONBOARDING             ║
╠════════════════════════════════════════════════════════════════════╣
║                                                                     ║
║  CÓDIGO: ING-20260214235901                                       ║
║  EMPLEADO: Juan Carlos López                                      ║
║  FECHA INGRESO: 15/03/2026                                        ║
║                                                                     ║
║  CARGO: Ejecutivo de Ventas  │  ÁREA: Ventas  │  JEFE: María    ║
║  ESTADO: ✅ Onboarding Completado                                 ║
║                                                                     ║
╠════════════════════════════════════════════════════════════════════╣
║ 💻 TECNOLOGÍA                                                      ║
╠════════════════════════════════════════════════════════════════════╣
║                                                                     ║
║  ¿Necesita Computador? ✅ SÍ                                      ║
║  Gama: Media                                                       ║
║  Estado: ✅ Finalizada                                            ║
║                                                                     ║
║  Credenciales y Plataformas:                                      ║
║  ┌────────────────────────────────────────────────────────────┐   ║
║  │ Usuario: jclopez                                            │   ║
║  │ Contraseña: xxxxxx                                          │   ║
║  │ Microsoft Teams: team.com                                   │   ║
║  │ Salesforce: sales.user.com                                  │   ║
║  └────────────────────────────────────────────────────────────┘   ║
║                                                                     ║
╠════════════════════════════════════════════════════════════════════╣
║ 👔 DOTACIÓN                                                        ║
╠════════════════════════════════════════════════════════════════════╣
║                                                                     ║
║  ¿Necesita Dotación? ✅ SÍ                                        ║
║  Género: Masculino                                                ║
║  Talla Pantalón: M                                                ║
║  Talla Camiseta: L                                                ║
║  Estado: ✅ Finalizada                                            ║
║                                                                     ║
╠════════════════════════════════════════════════════════════════════╣
║ 🏢 SERVICIOS GENERALES                                             ║
╠════════════════════════════════════════════════════════════════════╣
║                                                                     ║
║  Número de Puesto: A12                                            ║
║  Sección: Oficina                                                 ║
║  Piso: 2                                                          ║
║  Estado del Puesto: ✅ Asignado                                   ║
║  Estado Solicitud: ✅ Finalizada                                  ║
║                                                                     ║
╠════════════════════════════════════════════════════════════════════╣
║ 📚 PLAN DE FORMACIÓN                                               ║
╠════════════════════════════════════════════════════════════════════╣
║                                                                     ║
║  Cursos Asignados (5 total):                                      ║
║  ✅ Inducción General          (8h, Presencial)                   ║
║  ✅ Código de Conducta         (2h, e-Learning)                   ║
║  ✅ Productos Financieros      (16h, Híbrido)                     ║
║  ✅ Servicio al Cliente        (4h, e-Learning)                   ║
║  ✅ Técnicas de Venta          (6h, Presencial)                   ║
║                                                                     ║
║  Estado: ✅ Finalizada                                            ║
║                                                                     ║
╠════════════════════════════════════════════════════════════════════╣
║ 🛋️ BIENES Y SERVICIOS                                              ║
╠════════════════════════════════════════════════════════════════════╣
║                                                                     ║
║  Bienes Requeridos:                                               ║
║  ✅ 🪑 Silla        ✅ ✏️ Lapiceros    ☐ 📌 Post-it              ║
║  ✅ 🖥️ Escritorio   ✅ 📓 Cuadernos    ☐ 🖱️ Mouse pad            ║
║                                                                     ║
║  Observaciones:                                                    ║
║  Silla ergonómica preferida, altura ajustable.                   ║
║  Escritorio de 1.5m mínimo                                       ║
║                                                                     ║
║  Estado: ✅ Finalizada                                            ║
║                                                                     ║
╠════════════════════════════════════════════════════════════════════╣
║ 📊 RESUMEN FINAL                                                   ║
╠════════════════════════════════════════════════════════════════════╣
║                                                                     ║
║  ✅ Tecnología      ✅ Dotación        ✅ Servicios Generales      ║
║  ✅ Formación       ✅ Bienes                                      ║
║                                                                     ║
║  [👁️ Ver Proceso Completo]  [🖨️ Imprimir Check-in]              ║
║                                                                     ║
╚════════════════════════════════════════════════════════════════════╝
```

---

## 4️⃣ Matriz de Acceso - Quién ve QUÉ

### Root (Superusuario)
- ✅ Ve TODAS las solicitudes (todas las áreas y tipos)
- ✅ Puede editar TODAS las solicitudes
- ✅ Puede crear nuevos ProcesoIngreso
- ✅ Acceso a todas las funcionalidades

### Jefe RRHH
- ✅ Ve TODAS las solicitudes
- ✅ Edita tipo 'Formación'
- ✅ Puede crear nuevos ProcesoIngreso
- ✅ Ve Check-in consolidado

### Jefe Tecnología
- ❌ Ve SOLO tipo 'Tecnología'
- ✅ Edita tipo 'Tecnología'
- ❌ NO puede crear ProcesoIngreso
- ✅ Ve Check-in consolidado (como jefe)

### Jefe Dotación
- ❌ Ve SOLO tipo 'Dotación'
- ✅ Edita tipo 'Dotación'
- ❌ NO puede crear ProcesoIngreso
- ✅ Ve Check-in consolidado (como jefe)

### Jefe Servicios Generales
- ❌ Ve SOLO tipo 'Servicios Generales'
- ✅ Edita tipo 'Servicios Generales'
- ❌ NO puede crear ProcesoIngreso
- ✅ Ve Check-in consolidado (como jefe)

### Jefe Bienes y Servicios
- ❌ Ve SOLO tipo 'Bienes'
- ✅ Edita tipo 'Bienes'
- ❌ NO puede crear ProcesoIngreso
- ✅ Ve Check-in consolidado (como jefe)

---

## 5️⃣ Errores Comunes y Soluciones

### ❌ "No tienes permiso para ver esta solicitud"
**Causa:** Usuario intenta ver solicitud de tipo diferente al suyo
**Solución:** Jefe RRHH o Root pueden ver todas; otros jefes ven solo su tipo

### ❌ "La gama de computador es requerida"
**Causa:** Seleccionó "Sí necesita computador" pero no seleccionó gama
**Solución:** Seleccionar Básica, Media o Premium

### ❌ "No se puede asignar este puesto"
**Causa:** El puesto tiene estado != 'Disponible'
**Solución:** Seleccionar otro puesto con estado 'Disponible'

### ❌ "El check-in no puede verse"
**Causa:** No todas las solicitudes están 'Finalizada'
**Solución:** Verificar que las 5 solicitudes tengan estado='Finalizada'

---

## 🔁 Flujo de Permisos - Diagrama

```
Usuario intenta acceder a /solicitudes
    ↓
¿Es Root? → SÍ → Ver TODAS
    ↓ NO
¿Es Jefe RRHH? → SÍ → Ver TODAS
    ↓ NO
¿Es Jefe Tecnología? → SÍ → Ver SOLO tipo='Tecnología'
    ↓ NO
¿Es Jefe Dotación? → SÍ → Ver SOLO tipo='Dotación'
    ↓ NO
¿Es Jefe Servicios Generales? → SÍ → Ver SOLO tipo='Servicios Generales'
    ↓ NO
¿Es Jefe Bienes y Servicios? → SÍ → Ver SOLO tipo='Bienes'
    ↓ NO
Otros roles → Ver solicitudes propias (limitado)
    ↓
abort(403) - No autorizado
```

---

**Última actualización:** February 14, 2026
