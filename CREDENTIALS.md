# 🎓 Sistema de Onboarding - Credenciales de Prueba

## Usuarios Disponibles

### 👑 Root Admin (Acceso Total)
- **Email:** root@test.com
- **Contraseña:** 12345678
- **Rol:** Root (acceso a TODO)
- **Área:** Recursos Humanos

### 🔒 Administrador
- **Email:** admin@test.com
- **Contraseña:** 12345678
- **Rol:** Admin
- **Área:** Recursos Humanos

### 👔 Jefe Inmediato
- **Email:** jefe.tecnologia@example.com
- **Contraseña:** password
- **Rol:** Jefe
- **Área:** Tecnología

### 👨‍💼 Operadores por Área

#### Operador de Tecnología
- **Email:** operador.ti@test.com
- **Contraseña:** 12345678
- **Área:** Tecnología

#### Operador de Dotación
- **Email:** operador.dotacion@test.com
- **Contraseña:** 12345678
- **Área:** Recursos Humanos

#### Operador de Servicios Generales
- **Email:** operador.servicios@test.com
- **Contraseña:** 12345678
- **Área:** Servicios Generales

#### Operador de Formación
- **Email:** operador.formacion@test.com
- **Contraseña:** 12345678
- **Área:** Formación y Capacitación

#### Operador de Bienes y Servicios
- **Email:** operador.bienes@test.com
- **Contraseña:** 12345678
- **Área:** Bienes y Servicios

## 📋 Áreas Disponibles

1. **Recursos Humanos** - Responsable de Dotación
2. **Tecnología** - Responsable de equipos y software
3. **Servicios Generales** - Responsable de espacios y puestos
4. **Formación y Capacitación** - Responsable de capacitaciones
5. **Bienes y Servicios** - Responsable de adquisiciones

## 💼 Cargos con Plantillas

1. **Analista de RRHH** (Área: Recursos Humanos)
2. **Desarrollador** (Área: Tecnología)
3. **Técnico de Servicios** (Área: Servicios Generales)
4. **Instructor** (Área: Formación y Capacitación)
5. **Administrador de Inventario** (Área: Bienes y Servicios)

Cada cargo tiene automáticamente 5 solicitudes generadas:
- ✅ Tecnología (5 días antes del ingreso)
- ✅ Dotación (10 días antes del ingreso)
- ✅ Servicios Generales (7 días antes del ingreso)
- ✅ Formación (3 días antes del ingreso)
- ✅ Bienes y Servicios (10 días antes del ingreso)

## 🏗️ Puestos de Trabajo

Se han creado **30 puestos** (5 filas × 6 columnas):
- Fila A: A1, A2, A3, A4, A5, A6
- Fila B: B1, B2, B3, B4, B5, B6
- Fila C: C1, C2, C3, C4, C5, C6
- Fila D: D1, D2, D3, D4, D5, D6
- Fila E: E1, E2, E3, E4, E5, E6

## 🔄 Flujo de Uso

1. **Crear nuevo proceso de ingreso** (Admin/Root)
2. **Se generan automáticamente las solicitudes** basadas en el cargo
3. **Jefe inmediato especifica requerimientos**
   - Detalles técnicos de TI
   - Tallas de uniforme
4. **Operadores completan solicitudes** en sus áreas
5. **Se genera automáticamente el check-in**
6. **Empleado confirma recepción de activos** vía enlace público
7. **Proceso finalizado** ✓

## 📊 Estadísticas Base de Datos

- **Áreas:** 5
- **Cargos:** 5
- **Plantillas de Solicitud:** 25 (5 cargos × 5 tipos)
- **Puestos:** 30
- **Roles:** 9 (Root, Admin, Jefe, 5 Operadores por área)
- **Permisos:** 17
- **Usuarios de Prueba:** 9

## 🚀 Próximas Mejoras

- [ ] Notificaciones por email automáticas
- [ ] Reminders de fechas límite próximas
- [ ] Reportes avanzados por área
- [ ] Dashboard analítico mejorado
- [ ] Integración con sistema de inventario
