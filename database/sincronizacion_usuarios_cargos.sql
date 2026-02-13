-- ============================================================================
-- SCRIPT DE SINCRONIZACIÓN MANUAL - USUARIOS Y CARGOS
-- ============================================================================
-- Este script es alternativo al seeder de Laravel
-- Usar si prefiere ejecutar directamente en SQL
-- 
-- PASOS:
-- 1. Hacer backup de la base de datos
-- 2. Ejecutar las migraciones: php artisan migrate
-- 3. Ejecutar: php artisan db:seed --class=ReorganizarUsuariosCargoSeeder
-- O ejecutar este script directamente en el gestor de BD

-- ============================================================================
-- PASO 1: VERIFICAR INTEGRIDAD INICIAL
-- ============================================================================

-- Ver estado actual de users
SELECT id, name, cargo_id, area_id FROM users ORDER BY id;

-- Ver estado actual de cargos
SELECT id, nombre, area_id, activo FROM cargos ORDER BY id;


-- ============================================================================
-- PASO 2: POBLANDO maestro_cargos CON TODOS LOS CARGOS EXISTENTES
-- ============================================================================

INSERT INTO maestro_cargos (nombre, area_id, descripcion, es_puesto_entrada, activo, created_at, updated_at)
SELECT DISTINCT 
    c.nombre,
    c.area_id,
    c.descripcion,
    1 as es_puesto_entrada,
    1 as activo,
    NOW(),
    NOW()
FROM cargos c
ON DUPLICATE KEY UPDATE updated_at = NOW();

SELECT COUNT(*) as total_maestro_cargos FROM maestro_cargos;


-- ============================================================================
-- PASO 3: CREAR CARGOS FALTANTES (si no existen)
-- ============================================================================

-- Verificar e insertarlos si faltan
INSERT IGNORE INTO cargos (nombre, area_id, created_at, updated_at)
VALUES 
    ('Asistente Administrativo', 1, NOW(), NOW()),
    ('Supervisor de Servicios Generales', 2, NOW(), NOW()),
    ('Asistente de Inventario', 1, NOW(), NOW());


-- ============================================================================
-- PASO 4: ACTUALIZAR TABLA USERS CON DATOS CORRECTOS
-- ============================================================================

-- User 1: Administrador Sistema -> Gerente Talento Humano
UPDATE users 
SET 
    name = 'Administrador Sistema',
    cargo_id = (SELECT id FROM cargos WHERE nombre = 'Gerente Talento Humano' LIMIT 1),
    rol_onboarding = 'admin',
    puede_aprobar_solicitudes = 1
WHERE id = 1;

-- User 2: Jefe de RRHH -> Gerente Talento Humano
UPDATE users 
SET 
    name = 'Jefe Talento Humano',
    cargo_id = (SELECT id FROM cargos WHERE nombre = 'Gerente Talento Humano' LIMIT 1),
    rol_onboarding = 'jefe_area',
    puede_aprobar_solicitudes = 1
WHERE id = 2;

-- User 3: Coordinador de Formación
UPDATE users 
SET 
    name = 'Coordinador Formación',
    cargo_id = (SELECT id FROM cargos WHERE nombre = 'Coordinador de Formación y Capacitación' LIMIT 1),
    rol_onboarding = 'coordinador',
    puede_aprobar_solicitudes = 1
WHERE id = 3;

-- User 4: Root Admin
UPDATE users 
SET 
    name = 'Root Administrator',
    cargo_id = (SELECT id FROM cargos WHERE nombre = 'Gerente Talento Humano' LIMIT 1),
    rol_onboarding = 'admin',
    puede_aprobar_solicitudes = 1
WHERE id = 4;

-- User 5: Admin Onboarding
UPDATE users 
SET 
    name = 'Admin Onboarding',
    cargo_id = (SELECT id FROM cargos WHERE nombre = 'Gerente Talento Humano' LIMIT 1),
    rol_onboarding = 'admin',
    puede_aprobar_solicitudes = 1
WHERE id = 5;

-- User 6: Jefe de TI -> Gerente de TI
UPDATE users 
SET 
    name = 'Jefe Tecnología',
    cargo_id = (SELECT id FROM cargos WHERE nombre = 'Gerente de TI' LIMIT 1),
    rol_onboarding = 'jefe_area',
    puede_aprobar_solicitudes = 1
WHERE id = 6;

-- User 7: Operador Tecnología -> Técnico de Soporte
UPDATE users 
SET 
    name = 'Operador Soporte TI',
    cargo_id = (SELECT id FROM cargos WHERE nombre = 'Técnico de Soporte Nivel 1' LIMIT 1),
    rol_onboarding = 'operador',
    puede_aprobar_solicitudes = 0
WHERE id = 7;

-- User 8: Operador Dotación
UPDATE users 
SET 
    name = 'Operador Dotación',
    cargo_id = (SELECT id FROM cargos WHERE nombre = 'Asistente Administrativo' LIMIT 1),
    rol_onboarding = 'operador',
    puede_aprobar_solicitudes = 0
WHERE id = 8;

-- User 9: Operador Servicios
UPDATE users 
SET 
    name = 'Operador Servicios',
    cargo_id = (SELECT id FROM cargos WHERE nombre = 'Supervisor de Servicios Generales' LIMIT 1),
    rol_onboarding = 'operador',
    puede_aprobar_solicitudes = 0
WHERE id = 9;

-- User 10: Operador Formación
UPDATE users 
SET 
    name = 'Operador Formación',
    cargo_id = (SELECT id FROM cargos WHERE nombre = 'Coordinador de Formación y Capacitación' LIMIT 1),
    rol_onboarding = 'operador',
    puede_aprobar_solicitudes = 0
WHERE id = 10;

-- User 11: Operador Bienes
UPDATE users 
SET 
    name = 'Operador Bienes',
    cargo_id = (SELECT id FROM cargos WHERE nombre = 'Asistente de Inventario' LIMIT 1),
    rol_onboarding = 'operador',
    puede_aprobar_solicitudes = 0
WHERE id = 11;

-- Verificar actualización
SELECT id, name, cargo_id, rol_onboarding, puede_aprobar_solicitudes FROM users ORDER BY id;


-- ============================================================================
-- PASO 5: CONFIGURAR CARGOS DE ENTRADA (Puestos con Vacantes)
-- ============================================================================

-- Actualizar cargos que ACEPTAN nuevos empleados (vacantes disponibles)
UPDATE cargos SET activo = 1, vacantes_disponibles = 1 WHERE nombre IN (
    'Asistente Administrativo',
    'Técnico de Soporte Nivel 1',
    'Analista de Crédito',
    'Asesor de Servicios',
    'Facilitador de Aprendizaje Interno',
    'Asistente de Inventario',
    'Supervisores de Servicios Generales',
    'Operador de Servicios'
);

-- Actualizar cargos que NO aceptan nuevos empleados (puestos de jefes/gerentes)
UPDATE cargos SET activo = 0 WHERE nombre IN (
    'Gerente Talento Humano',
    'Coordinador de Formación y Capacitación',
    'Gerente de TI',
    'Gerente Administración',
    'Jefe de Riesgo y Crédito',
    'Jefe de Comercial',
    'Jefe de Financiero'
);

-- Verificar
SELECT id, nombre, activo, vacantes_disponibles FROM cargos WHERE activo = 1 ORDER BY nombre;


-- ============================================================================
-- PASO 6: VALIDACIÓN FINAL
-- ============================================================================

-- Usuarios que pueden aprobar solicitudes
SELECT 
    u.id,
    u.name,
    u.cargo_id,
    c.nombre as cargo_nombre,
    u.rol_onboarding,
    u.puede_aprobar_solicitudes
FROM users u
LEFT JOIN cargos c ON u.cargo_id = c.id
WHERE u.puede_aprobar_solicitudes = 1
ORDER BY u.id;

-- Cargos disponibles para nueva entrada
SELECT 
    id,
    nombre,
    area_id,
    vacantes_disponibles,
    (SELECT COUNT(*) FROM users WHERE cargo_id = cargos.id) as empleados_actuales
FROM cargos
WHERE activo = 1 AND vacantes_disponibles > 0
ORDER BY nombre;

-- Reporte de sincronización
SELECT 
    'Total Usuarios' as tipo,
    COUNT(*) as cantidad
FROM users
UNION ALL
SELECT 
    'Usuarios que pueden aprobar',
    COUNT(*)
FROM users
WHERE puede_aprobar_solicitudes = 1
UNION ALL
SELECT 
    'Cargos totales',
    COUNT(*)
FROM cargos
UNION ALL
SELECT 
    'Cargos activos (con vacantes)',
    COUNT(*)
FROM cargos
WHERE activo = 1
UNION ALL
SELECT 
    'Maestro Cargos (referencia)',
    COUNT(*)
FROM maestro_cargos;

-- ============================================================================
-- NOTES FINALES
-- ============================================================================
/*
Este script actualiza la estructura para:

1. maestro_cargos: Catálogo COMPLETO de todos los 54 cargos (histórico)
2. cargos: Solo ~10-15 cargos con vacantes disponibles para nuevos empleados
3. users: 11 usuarios, cada uno vinculado correctamente a un cargo y con rol definido

RESULTADO ESPERADO:
- Todos los usuarios tienen cargo_id NO NULL
- Usuarios jefes/coordinadores tienen puede_aprobar_solicitudes = 1
- Los operadores tienen puede_aprobar_solicitudes = 0
- Tabla cargos contiene solo puestos de entrada
- Tabla maestro_cargos es la referencia completa histórica
*/
