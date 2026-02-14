-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-02-2026 a las 15:36:23
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `onboarding`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `gerencia_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jefe_area_cargo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`id`, `nombre`, `gerencia_id`, `jefe_area_cargo_id`, `descripcion`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Gerencia Administración', NULL, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(2, 'Servicios Generales', 1, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(3, 'Mantenimiento', 1, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(4, 'Gerencia Comercial', NULL, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(5, 'Ventas y Captación', 2, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(6, 'Gestión de Canales', 2, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(7, 'Marketing y Producto', 2, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(8, 'Servicio al Cliente', 2, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(9, 'Gerencia Riesgo y Crédito', NULL, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(10, 'Análisis de Crédito', 3, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(11, 'Riesgo Operativo', 3, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(12, 'Gerencia Financiera', NULL, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(13, 'Tesorería', 4, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(14, 'Contabilidad', 4, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(15, 'Planeación', 4, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(16, 'Gerencia TI', NULL, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(17, 'Infraestructura y Redes', 5, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(18, 'Desarrollo de Software', 5, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(19, 'Soporte Técnico', 5, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(20, 'Gerencia de Talento Humano', NULL, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(21, 'Selección y Reclutamiento', 6, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(22, 'Formación y Capacitación', 6, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(23, 'Nómina', 6, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(24, 'Clima Organizacional', 6, NULL, NULL, 1, '2026-02-13 20:38:12', '2026-02-13 20:38:12'),
(25, 'Gerencia General', 7, NULL, 'Direccion General', 1, '2026-02-14 19:27:39', '2026-02-14 19:27:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_cursos`
--

CREATE TABLE `asignacion_cursos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proceso_ingreso_id` bigint(20) UNSIGNED NOT NULL,
  `curso_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_asignacion` date NOT NULL,
  `fecha_limite` date DEFAULT NULL,
  `fecha_completacion` date DEFAULT NULL,
  `estado` enum('Asignado','En Progreso','Completado','Vencido','Cancelado') NOT NULL DEFAULT 'Asignado',
  `calificacion` int(11) DEFAULT NULL,
  `certificado_url` varchar(255) DEFAULT NULL,
  `asignado_por_id` bigint(20) UNSIGNED DEFAULT NULL,
  `responsable_validacion_id` bigint(20) UNSIGNED DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_onboarding`
--

CREATE TABLE `auditoria_onboarding` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `accion` enum('create','update','delete','view','export','anular') NOT NULL,
  `entidad` varchar(255) NOT NULL,
  `entidad_id` bigint(20) UNSIGNED NOT NULL,
  `valores_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`valores_anteriores`)),
  `valores_nuevos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`valores_nuevos`)),
  `motivo` text DEFAULT NULL,
  `ip_origin` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `area_id` bigint(20) UNSIGNED NOT NULL,
  `jefe_inmediato_cargo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gerencia_id` bigint(20) UNSIGNED DEFAULT NULL,
  `salario_minimo` decimal(12,2) DEFAULT NULL,
  `salario_maximo` decimal(12,2) DEFAULT NULL,
  `requerimientos_minimos` text DEFAULT NULL,
  `vacantes_disponibles` smallint(6) NOT NULL DEFAULT 0 COMMENT 'Cantidad de vacantes disponibles para este cargo',
  `activo` tinyint(1) NOT NULL DEFAULT 1 COMMENT '¿Cargo activo para nuevos ingresos?',
  `descripcion` text DEFAULT NULL COMMENT 'Descripción detallada del cargo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id`, `nombre`, `area_id`, `jefe_inmediato_cargo_id`, `gerencia_id`, `salario_minimo`, `salario_maximo`, `requerimientos_minimos`, `vacantes_disponibles`, `activo`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Gerente Administrativo', 1, 58, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:27:39'),
(2, 'Coordinador de Servicios Corporativos', 2, 1, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(3, 'Auxiliar de Servicios Generales y Cafetería', 2, 2, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(4, 'Asistente de Compras e Inventario', 2, 2, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(5, 'Jefe de Infraestructura y Mantenimiento', 3, 1, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(6, 'Técnico de Mantenimiento Locativo', 3, 5, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(7, 'Técnico en Climatización y Electricidad', 3, 5, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(8, 'Gerente Comercial', 4, 58, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:27:39'),
(9, 'Coordinador de Ventas y Captación', 5, 8, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(10, 'Ejecutivo de Captación y Colocación', 5, 9, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(11, 'Asesor de Crédito Externo', 5, 9, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(12, 'Jefe de Canales', 6, 8, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(13, 'Coordinador de Sucursales', 6, 12, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(14, 'Administrador de Canales Digitales', 6, 12, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(15, 'Coordinador de Marketing y Producto', 7, 8, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(16, 'Analista de Producto', 7, 15, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(17, 'Especialista en Comunicación y Marca', 7, 15, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(18, 'Coordinador de Servicio al Cliente', 8, 8, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(19, 'Oficial de Experiencia al Asociado', 8, 18, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(20, 'Analista de Fidelización y Retención', 8, 18, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(21, 'Gerente de Riesgos', 9, 58, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:27:39'),
(22, 'Coordinador de Análisis y Crédito', 10, 21, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(23, 'Analista de Crédito Senior', 10, 22, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(24, 'Asistente de Verificación y Garantías', 10, 22, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:28'),
(25, 'Analista de Microcrédito y Terreno', 10, 22, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(26, 'Coordinador de Riesgo Operativo', 11, 21, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(27, 'Oficial de Cumplimiento (SARLAFT)', 11, 26, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(28, 'Analista de Riesgo Operacional', 11, 26, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(29, 'Auditor de Procesos Crediticios', 11, 26, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(30, 'Gerente Financiero', 12, 58, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:27:39'),
(31, 'Coordinador de Tesorería', 13, 30, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(32, 'Analista de Tesorería y Pagos', 13, 31, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(33, 'Coordinador de Contabilidad', 14, 30, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(34, 'Analista de Impuestos y Costos', 14, 33, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(35, 'Asistente Contable', 14, 33, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(36, 'Coordinador de Planeación', 15, 30, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(37, 'Analista de Estudios Económicos', 15, 36, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(38, 'Gerente de TI', 16, 58, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:27:39'),
(39, 'Coordinador de Infraestructura y Redes', 17, 38, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(40, 'Administrador de Servidores y Nube', 17, 39, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(41, 'Coordinador de Desarrollo de Software', 18, 38, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(42, 'Desarrollador Full Stack', 18, 41, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(43, 'Analista de QA (Aseguramiento de Calidad)', 18, 41, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(44, 'Coordinador de Soporte Técnico', 19, 38, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(45, 'Técnico de Soporte Nivel 1', 19, 44, NULL, NULL, NULL, NULL, 0, 1, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(46, 'Gerente Talento Humano', 20, 58, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:27:39'),
(47, 'Coordinador de Selección y Reclutamiento', 21, 46, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(48, 'Analista de Atracción de Talento', 21, 47, NULL, NULL, NULL, NULL, 0, 1, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(49, 'Coordinador de Formación y Capacitación', 22, 46, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(50, 'Facilitador de Aprendizaje Interno', 22, 49, NULL, NULL, NULL, NULL, 0, 1, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(51, 'Coordinador de Nómina y Compensación', 23, 46, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(52, 'Analista de Prestaciones y Seguridad Social', 23, 51, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(53, 'Coordinador de Clima Organizacional', 24, 46, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(54, 'Especialista en Bienestar y Cultura', 24, 53, NULL, NULL, NULL, NULL, 0, 0, NULL, '2026-02-13 20:38:12', '2026-02-14 19:12:29'),
(58, 'Gerente General', 25, NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, '2026-02-14 19:27:39', '2026-02-14 19:27:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `checkins`
--

CREATE TABLE `checkins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proceso_ingreso_id` bigint(20) UNSIGNED NOT NULL,
  `codigo_verificacion` varchar(255) NOT NULL,
  `activos_entregados` text DEFAULT NULL,
  `estado_checkin` varchar(255) NOT NULL DEFAULT 'Pendiente',
  `fecha_generacion` datetime NOT NULL,
  `fecha_confirmacion` datetime DEFAULT NULL,
  `email_empleado` varchar(255) NOT NULL,
  `email_enviado` tinyint(1) NOT NULL DEFAULT 0,
  `email_enviado_at` timestamp NULL DEFAULT NULL,
  `firma_digital` text DEFAULT NULL,
  `dispositivo_confirmacion` varchar(255) DEFAULT NULL,
  `ip_confirmacion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `checkins`
--

INSERT INTO `checkins` (`id`, `proceso_ingreso_id`, `codigo_verificacion`, `activos_entregados`, `estado_checkin`, `fecha_generacion`, `fecha_confirmacion`, `email_empleado`, `email_enviado`, `email_enviado_at`, `firma_digital`, `dispositivo_confirmacion`, `ip_confirmacion`, `created_at`, `updated_at`) VALUES
(1, 2, '0036801BB8', '[{\"item\":\"Puesto de Trabajo (1-AAC-2)\",\"especificaciones\":\"Secci\\u00f3n: Atenci\\u00f3n al Cliente, Piso: 1\",\"entregado\":false},{\"item\":\"Plan de Formaci\\u00f3n\",\"especificaciones\":\"Brigadas de Primeros Auxilios, Ciberseguridad para No T\\u00e9cnicos, Comunicaci\\u00f3n Asertiva, Excel Avanzado para Finanzas, Gesti\\u00f3n de Compras y Proveedores\",\"entregado\":false},{\"item\":\"Bienes y Servicios\",\"especificaciones\":\"silla, organizador, cuadernos\",\"entregado\":false}]', 'Pendiente', '2026-02-14 02:17:55', NULL, 'jefe.servicios@sinergia.com', 0, NULL, NULL, NULL, NULL, '2026-02-14 07:17:55', '2026-02-14 07:17:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` enum('Obligatorio','Opcional','Cumplimiento Normativo','Desarrollo','Liderazgo') NOT NULL DEFAULT 'Opcional',
  `modalidad` enum('Presencial','Virtual','Híbrida') NOT NULL DEFAULT 'Virtual',
  `duracion_horas` int(11) NOT NULL,
  `objetivo` text DEFAULT NULL,
  `contenido` longtext DEFAULT NULL,
  `area_responsable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `costo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `requiere_certificado` tinyint(1) NOT NULL DEFAULT 1,
  `vigencia_meses` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `codigo`, `nombre`, `descripcion`, `categoria`, `modalidad`, `duracion_horas`, `objetivo`, `contenido`, `area_responsable_id`, `costo`, `requiere_certificado`, `vigencia_meses`, `activo`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'CUR-001', 'Inducción a la Cultura Cooperativa', 'Conocer la historia, valores y principios del modelo solidario.', 'Obligatorio', 'Presencial', 4, 'Conocer la historia, valores y principios del modelo solidario.', 'Inducción a la Cultura Cooperativa', 22, 50.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(2, 'CUR-002', 'Portafolio de Productos y Servicios', 'Dominar las características de los ahorros y créditos vigentes.', 'Obligatorio', 'Virtual', 8, 'Dominar las características de los ahorros y créditos vigentes.', 'Portafolio de Productos y Servicios', 22, 60.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(3, 'CUR-003', 'Prevención de Lavado de Activos (SARLAFT)', 'Detectar operaciones sospechosas y cumplir con la ley financiera.', 'Cumplimiento Normativo', 'Virtual', 4, 'Detectar operaciones sospechosas y cumplir con la ley financiera.', 'Prevención de Lavado de Activos (SARLAFT)', 22, 75.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(4, 'CUR-004', 'Seguridad y Salud en el Trabajo (SST)', 'Identificar riesgos laborales y protocolos de emergencia.', 'Cumplimiento Normativo', 'Presencial', 4, 'Identificar riesgos laborales y protocolos de emergencia.', 'Seguridad y Salud en el Trabajo (SST)', 22, 40.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(5, 'CUR-005', 'Brigadas de Primeros Auxilios', 'Capacitar al personal en respuesta ante accidentes físicos.', 'Cumplimiento Normativo', 'Presencial', 6, 'Capacitar al personal en respuesta ante accidentes físicos.', 'Brigadas de Primeros Auxilios', 22, 45.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(6, 'CUR-006', 'Prevención de Acoso Laboral', 'Fomentar un ambiente de respeto y convivencia sana.', 'Cumplimiento Normativo', 'Virtual', 3, 'Fomentar un ambiente de respeto y convivencia sana.', 'Prevención de Acoso Laboral', 22, 35.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(7, 'CUR-007', 'Manejo de Extintores y Evacuación', 'Actuar correctamente ante incendios o desastres naturales.', 'Cumplimiento Normativo', 'Presencial', 3, 'Actuar correctamente ante incendios o desastres naturales.', 'Manejo de Extintores y Evacuación', 22, 30.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(8, 'CUR-008', 'Venta Consultiva Financiera', 'Desarrollar técnicas para ofrecer créditos según la necesidad del socio.', 'Desarrollo', 'Presencial', 8, 'Desarrollar técnicas para ofrecer créditos según la necesidad del socio.', 'Venta Consultiva Financiera', 22, 70.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(9, 'CUR-009', 'Técnicas de Negociación y Cierre', 'Mejorar la efectividad en la colocación de servicios financieros.', 'Desarrollo', 'Virtual', 6, 'Mejorar la efectividad en la colocación de servicios financieros.', 'Técnicas de Negociación y Cierre', 22, 65.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(10, 'CUR-010', 'Captación de Depósitos y Ahorro', 'Aprender estrategias para atraer liquidez a la cooperativa.', 'Desarrollo', 'Virtual', 6, 'Aprender estrategias para atraer liquidez a la cooperativa.', 'Captación de Depósitos y Ahorro', 22, 60.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(11, 'CUR-011', 'Análisis de Capacidad de Pago', 'Estudiar detalladamente la relación ingreso/gasto del solicitante.', 'Desarrollo', 'Presencial', 8, 'Estudiar detalladamente la relación ingreso/gasto del solicitante.', 'Análisis de Capacidad de Pago', 22, 80.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(12, 'CUR-012', 'Interpretación de Centrales de Riesgo', 'Aprender a leer e interpretar reportes de burós de crédito.', 'Desarrollo', 'Virtual', 6, 'Aprender a leer e interpretar reportes de burós de crédito.', 'Interpretación de Centrales de Riesgo', 22, 70.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(13, 'CUR-013', 'Evaluación de Garantías Reales', 'Conocer los aspectos legales de hipotecas y prendas vehiculares.', 'Desarrollo', 'Presencial', 8, 'Conocer los aspectos legales de hipotecas y prendas vehiculares.', 'Evaluación de Garantías Reales', 22, 85.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(14, 'CUR-014', 'Gestión de Riesgo Operativo', 'Identificar fallas en procesos que puedan generar pérdidas.', 'Cumplimiento Normativo', 'Virtual', 6, 'Identificar fallas en procesos que puedan generar pérdidas.', 'Gestión de Riesgo Operativo', 22, 75.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(15, 'CUR-015', 'Gestión de Cobranza Preventiva', 'Aprender a contactar al socio antes de que caiga en mora.', 'Desarrollo', 'Virtual', 5, 'Aprender a contactar al socio antes de que caiga en mora.', 'Gestión de Cobranza Preventiva', 22, 60.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(16, 'CUR-016', 'Actualización en Normas NIIF', 'Aplicar los estándares internacionales de contabilidad vigentes.', 'Cumplimiento Normativo', 'Virtual', 8, 'Aplicar los estándares internacionales de contabilidad vigentes.', 'Actualización en Normas NIIF', 22, 95.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(17, 'CUR-017', 'Manejo de Flujo de Caja y Liquidez', 'Optimizar el dinero disponible para desembolsos diarios.', 'Desarrollo', 'Presencial', 6, 'Optimizar el dinero disponible para desembolsos diarios.', 'Manejo de Flujo de Caja y Liquidez', 22, 70.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(18, 'CUR-018', 'Preparación de Reportes a Entes de Control', 'Cumplir con los informes para la Superintendencia.', 'Cumplimiento Normativo', 'Virtual', 6, 'Cumplir con los informes para la Superintendencia.', 'Preparación de Reportes a Entes de Control', 22, 80.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(19, 'CUR-019', 'Ciberseguridad para No Técnicos', 'Enseñar al personal a evitar phishing y proteger contraseñas.', 'Cumplimiento Normativo', 'Virtual', 4, 'Enseñar al personal a evitar phishing y proteger contraseñas.', 'Ciberseguridad para No Técnicos', 22, 50.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(20, 'CUR-020', 'Manejo del Core Financiero (Software)', 'Capacitar en el uso de la plataforma principal de la entidad.', 'Desarrollo', 'Presencial', 16, 'Capacitar en el uso de la plataforma principal de la entidad.', 'Manejo del Core Financiero (Software)', 22, 120.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(21, 'CUR-021', 'Protección de Datos Personales', 'Cumplir con la ley de Habeas Data y privacidad del socio.', 'Cumplimiento Normativo', 'Virtual', 4, 'Cumplir con la ley de Habeas Data y privacidad del socio.', 'Protección de Datos Personales', 22, 40.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(22, 'CUR-022', 'Liderazgo y Trabajo en Equipo', 'Desarrollar habilidades blandas para coordinadores y jefes.', 'Desarrollo', 'Presencial', 12, 'Desarrollar habilidades blandas para coordinadores y jefes.', 'Liderazgo y Trabajo en Equipo', 22, 100.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(23, 'CUR-023', 'Inteligencia Emocional en el Trabajo', 'Brindar herramientas para el manejo del estrés y la empatía.', 'Desarrollo', 'Virtual', 8, 'Brindar herramientas para el manejo del estrés y la empatía.', 'Inteligencia Emocional en el Trabajo', 22, 80.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(24, 'CUR-024', 'Comunicación Asertiva', 'Mejorar el flujo de información interna y externa.', 'Desarrollo', 'Virtual', 6, 'Mejorar el flujo de información interna y externa.', 'Comunicación Asertiva', 22, 60.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(25, 'CUR-025', 'Excel Avanzado para Finanzas', 'Dominar tablas dinámicas y fórmulas para análisis de datos.', 'Desarrollo', 'Virtual', 10, 'Dominar tablas dinámicas y fórmulas para análisis de datos.', 'Excel Avanzado para Finanzas', 22, 90.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(26, 'CUR-026', 'Protocolo de Servicio al Cliente', 'Estandarizar el saludo y la atención en las sucursales.', 'Desarrollo', 'Presencial', 4, 'Estandarizar el saludo y la atención en las sucursales.', 'Protocolo de Servicio al Cliente', 22, 50.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(27, 'CUR-027', 'Manejo de Clientes Difíciles', 'Aprender técnicas de desescalamiento de conflictos.', 'Desarrollo', 'Presencial', 6, 'Aprender técnicas de desescalamiento de conflictos.', 'Manejo de Clientes Difíciles', 22, 65.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(28, 'CUR-028', 'Gestión de PQRS Eficiente', 'Reducir los tiempos de respuesta a los reclamos de los socios.', 'Desarrollo', 'Virtual', 5, 'Reducir los tiempos de respuesta a los reclamos de los socios.', 'Gestión de PQRS Eficiente', 22, 55.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(29, 'CUR-029', 'Manipulación de Productos Químicos', 'Uso seguro de implementos de aseo (Para Servicios Generales).', 'Cumplimiento Normativo', 'Presencial', 3, 'Uso seguro de implementos de aseo (Para Servicios Generales).', 'Manipulación de Productos Químicos', 22, 30.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(30, 'CUR-030', 'Mantenimiento Preventivo de Sedes', 'Protocolos de revisión técnica locativa (Para Mantenimiento).', 'Cumplimiento Normativo', 'Presencial', 6, 'Protocolos de revisión técnica locativa (Para Mantenimiento).', 'Mantenimiento Preventivo de Sedes', 22, 50.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(31, 'CUR-031', 'Gestión de Compras y Proveedores', 'Aprender procesos de licitación y selección de compras.', 'Desarrollo', 'Virtual', 6, 'Aprender procesos de licitación y selección de compras.', 'Gestión de Compras y Proveedores', 22, 70.00, 1, 12, 1, NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_bienes`
--

CREATE TABLE `detalles_bienes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `solicitud_id` bigint(20) UNSIGNED NOT NULL,
  `bienes_requeridos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bienes_requeridos`)),
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalles_bienes`
--

INSERT INTO `detalles_bienes` (`id`, `solicitud_id`, `bienes_requeridos`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 10, '\"[\\\"silla\\\",\\\"organizador\\\",\\\"cuadernos\\\"]\"', NULL, '2026-02-14 06:58:54', '2026-02-14 06:58:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gerencias`
--

CREATE TABLE `gerencias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `codigo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `gerencias`
--

INSERT INTO `gerencias` (`id`, `nombre`, `codigo`, `descripcion`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Gerencia Administración', 'GA', 'Soporte físico de las oficinas y sucursales', 1, '2026-02-14 00:14:13', '2026-02-14 00:14:13', NULL),
(2, 'Gerencia Comercial', 'GC', 'Captación de clientes y gestión de asesores', 1, '2026-02-14 00:14:13', '2026-02-14 00:14:13', NULL),
(3, 'Gerencia Riesgo y Crédito', 'GRC', 'Análisis de capacidad de pago y prevención de riesgo', 1, '2026-02-14 00:14:13', '2026-02-14 00:14:13', NULL),
(4, 'Gerencia Financiera', 'GF', 'Gestión de liquidez y reportes regulatorios', 1, '2026-02-14 00:14:13', '2026-02-14 00:14:13', NULL),
(5, 'Gerencia TI', 'GTI', 'Mantenimiento de software y seguridad de datos', 1, '2026-02-14 00:14:13', '2026-02-14 00:14:13', NULL),
(6, 'Gerencia Talento Humano', 'GTH', 'Atracción, retención y desarrollo del personal', 1, '2026-02-14 00:14:13', '2026-02-14 00:14:13', NULL),
(7, 'Gerencia General', 'GG', 'Direccion General', 1, '2026-02-14 19:27:39', '2026-02-14 19:27:39', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_08_020747_add_area_and_cargo_to_users', 1),
(5, '2026_02_08_020836_create_areas_table', 1),
(6, '2026_02_08_020905_create_cargos_table', 1),
(7, '2026_02_08_021512_create_permission_tables', 1),
(8, '2026_02_08_022631_create_proceso_ingresos_table', 1),
(9, '2026_02_08_022721_create_solicituds_table', 1),
(10, '2026_02_08_023817_create_plantilla_solicituds_table', 1),
(11, '2026_02_08_100000_create_area_specific_tables', 1),
(12, '2026_02_09_add_fields_to_procesos_ingresos', 1),
(13, '2026_02_09_create_puestos_tabla', 1),
(14, '2026_02_10_create_detalles_tecnicas_tables', 1),
(15, '2026_02_13_000001_create_cursos_table', 1),
(16, '2026_02_13_000002_create_curso_x_cargo_table', 1),
(17, '2026_02_13_000003_create_asignacion_cursos_table', 1),
(18, '2026_02_13_000004_create_rutas_formacion_table', 1),
(19, '2026_02_13_000005_create_ruta_x_curso_table', 1),
(20, '2026_02_13_000006_create_auditoria_onboarding_table', 1),
(21, '2026_02_13_000007_create_reporte_cumplimiento_table', 1),
(22, '2026_02_13_000008_add_fields_to_procesos_ingresos', 1),
(23, '2026_02_13_000001_reorganizar_usuarios_cargos', 2),
(24, '2026_02_13_000009_create_posiciones_table', 2),
(25, '2026_02_13_000010_expand_cargos_table', 2),
(26, '2026_02_13_000011_expand_areas_table', 2),
(27, '2026_02_13_000012_create_historico_posiciones_table', 2),
(28, '2026_02_13_000013_add_posicion_to_users_table', 2),
(29, '2026_02_13_100000_delete_deprecated_tables', 3),
(31, '2026_02_14_000000_create_gerencias_table', 4),
(32, '2026_02_14_create_missing_solicitudes_tables', 5),
(33, '2026_02_13_225000_fix_puesto_trabajo_fk', 6),
(34, '2026_02_13_230000_drop_puestos_table', 6),
(35, '2026_02_13_240000_drop_unused_area_specific_tables', 7),
(36, '2026_02_14_120000_update_procesos_ingresos_jefe_fields', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 4),
(1, 'App\\Models\\User', 12),
(2, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 6),
(5, 'App\\Models\\User', 8),
(6, 'App\\Models\\User', 7),
(7, 'App\\Models\\User', 9),
(8, 'App\\Models\\User', 10),
(9, 'App\\Models\\User', 11),
(10, 'App\\Models\\User', 13),
(11, 'App\\Models\\User', 15),
(12, 'App\\Models\\User', 16),
(13, 'App\\Models\\User', 17),
(14, 'App\\Models\\User', 18),
(15, 'App\\Models\\User', 19),
(16, 'App\\Models\\User', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'ver-procesos', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(2, 'crear-procesos', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(3, 'editar-procesos', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(4, 'cancelar-procesos', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(5, 'ver-historico-procesos', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(6, 'ver-solicitudes', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(7, 'editar-solicitudes', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(8, 'completar-solicitudes', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(9, 'ver-solicitudes-area', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(10, 'especificar-requerimientos-ti', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(11, 'especificar-tallas', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(12, 'validar-solicitudes', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(13, 'generar-pdf-checkin', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(14, 'confirmar-entrada-activos', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(15, 'ver-checkin', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(16, 'gestionar-usuarios', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(17, 'asignar-roles', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(18, 'ver-usuarios', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(19, 'view-cursos', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(20, 'create-cursos', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(21, 'update-cursos', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(22, 'delete-cursos', 'web', '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(23, 'view-asignaciones', 'web', '2026-02-13 20:38:16', '2026-02-13 20:38:16'),
(24, 'create-asignaciones', 'web', '2026-02-13 20:38:16', '2026-02-13 20:38:16'),
(25, 'update-asignaciones', 'web', '2026-02-13 20:38:16', '2026-02-13 20:38:16'),
(26, 'delete-asignaciones', 'web', '2026-02-13 20:38:16', '2026-02-13 20:38:16'),
(27, 'view-rutas', 'web', '2026-02-13 20:38:16', '2026-02-13 20:38:16'),
(28, 'create-rutas', 'web', '2026-02-13 20:38:16', '2026-02-13 20:38:16'),
(29, 'update-rutas', 'web', '2026-02-13 20:38:16', '2026-02-13 20:38:16'),
(30, 'delete-rutas', 'web', '2026-02-13 20:38:16', '2026-02-13 20:38:16'),
(31, 'view-reportes', 'web', '2026-02-13 20:38:16', '2026-02-13 20:38:16'),
(32, 'export-reportes', 'web', '2026-02-13 20:38:16', '2026-02-13 20:38:16'),
(33, 'view-auditoria', 'web', '2026-02-13 20:38:16', '2026-02-13 20:38:16'),
(34, 'export-auditoria', 'web', '2026-02-13 20:38:16', '2026-02-13 20:38:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plantilla_solicitudes`
--

CREATE TABLE `plantilla_solicitudes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cargo_id` bigint(20) UNSIGNED NOT NULL,
  `area_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_solicitud` varchar(255) NOT NULL,
  `dias_maximos` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `plantilla_solicitudes`
--

INSERT INTO `plantilla_solicitudes` (`id`, `cargo_id`, `area_id`, `tipo_solicitud`, `dias_maximos`, `created_at`, `updated_at`) VALUES
(1, 1, 16, 'Tecnología', 5, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(2, 1, 20, 'Dotación', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(3, 1, 2, 'Servicios Generales', 7, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(4, 1, 22, 'Formación', 3, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(5, 1, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(6, 2, 16, 'Tecnología', 5, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(7, 2, 20, 'Dotación', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(8, 2, 2, 'Servicios Generales', 7, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(9, 2, 22, 'Formación', 3, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(10, 2, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(11, 3, 16, 'Tecnología', 5, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(12, 3, 20, 'Dotación', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(13, 3, 2, 'Servicios Generales', 7, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(14, 3, 22, 'Formación', 3, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(15, 3, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(16, 4, 16, 'Tecnología', 5, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(17, 4, 20, 'Dotación', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(18, 4, 2, 'Servicios Generales', 7, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(19, 4, 22, 'Formación', 3, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(20, 4, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(21, 5, 16, 'Tecnología', 5, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(22, 5, 20, 'Dotación', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(23, 5, 2, 'Servicios Generales', 7, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(24, 5, 22, 'Formación', 3, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(25, 5, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(26, 6, 16, 'Tecnología', 5, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(27, 6, 20, 'Dotación', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(28, 6, 2, 'Servicios Generales', 7, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(29, 6, 22, 'Formación', 3, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(30, 6, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(31, 7, 16, 'Tecnología', 5, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(32, 7, 20, 'Dotación', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(33, 7, 2, 'Servicios Generales', 7, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(34, 7, 22, 'Formación', 3, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(35, 7, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(36, 8, 16, 'Tecnología', 5, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(37, 8, 20, 'Dotación', 10, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(38, 8, 2, 'Servicios Generales', 7, '2026-02-13 20:38:13', '2026-02-13 20:38:13'),
(39, 8, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(40, 8, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(41, 9, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(42, 9, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(43, 9, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(44, 9, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(45, 9, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(46, 10, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(47, 10, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(48, 10, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(49, 10, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(50, 10, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(51, 11, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(52, 11, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(53, 11, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(54, 11, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(55, 11, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(56, 12, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(57, 12, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(58, 12, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(59, 12, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(60, 12, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(61, 13, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(62, 13, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(63, 13, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(64, 13, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(65, 13, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(66, 14, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(67, 14, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(68, 14, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(69, 14, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(70, 14, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(71, 15, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(72, 15, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(73, 15, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(74, 15, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(75, 15, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(76, 16, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(77, 16, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(78, 16, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(79, 16, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(80, 16, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(81, 17, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(82, 17, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(83, 17, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(84, 17, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(85, 17, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(86, 18, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(87, 18, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(88, 18, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(89, 18, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(90, 18, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(91, 19, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(92, 19, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(93, 19, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(94, 19, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(95, 19, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(96, 20, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(97, 20, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(98, 20, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(99, 20, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(100, 20, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(101, 21, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(102, 21, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(103, 21, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(104, 21, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(105, 21, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(106, 22, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(107, 22, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(108, 22, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(109, 22, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(110, 22, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(111, 23, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(112, 23, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(113, 23, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(114, 23, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(115, 23, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(116, 24, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(117, 24, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(118, 24, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(119, 24, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(120, 24, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(121, 25, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(122, 25, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(123, 25, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(124, 25, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(125, 25, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(126, 26, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(127, 26, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(128, 26, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(129, 26, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(130, 26, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(131, 27, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(132, 27, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(133, 27, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(134, 27, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(135, 27, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(136, 28, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(137, 28, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(138, 28, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(139, 28, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(140, 28, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(141, 29, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(142, 29, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(143, 29, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(144, 29, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(145, 29, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(146, 30, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(147, 30, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(148, 30, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(149, 30, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(150, 30, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(151, 31, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(152, 31, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(153, 31, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(154, 31, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(155, 31, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(156, 32, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(157, 32, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(158, 32, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(159, 32, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(160, 32, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(161, 33, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(162, 33, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(163, 33, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(164, 33, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(165, 33, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(166, 34, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(167, 34, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(168, 34, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(169, 34, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(170, 34, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(171, 35, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(172, 35, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(173, 35, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(174, 35, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(175, 35, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(176, 36, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(177, 36, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(178, 36, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(179, 36, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(180, 36, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(181, 37, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(182, 37, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(183, 37, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(184, 37, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(185, 37, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(186, 38, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(187, 38, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(188, 38, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(189, 38, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(190, 38, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(191, 39, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(192, 39, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(193, 39, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(194, 39, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(195, 39, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(196, 40, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(197, 40, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(198, 40, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(199, 40, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(200, 40, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(201, 41, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(202, 41, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(203, 41, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(204, 41, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(205, 41, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(206, 42, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(207, 42, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(208, 42, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(209, 42, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(210, 42, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(211, 43, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(212, 43, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(213, 43, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(214, 43, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(215, 43, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(216, 44, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(217, 44, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(218, 44, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(219, 44, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(220, 44, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(221, 45, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(222, 45, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(223, 45, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(224, 45, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(225, 45, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(226, 46, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(227, 46, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(228, 46, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(229, 46, 22, 'Formación', 3, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(230, 46, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(231, 47, 16, 'Tecnología', 5, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(232, 47, 20, 'Dotación', 10, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(233, 47, 2, 'Servicios Generales', 7, '2026-02-13 20:38:14', '2026-02-13 20:38:14'),
(234, 47, 22, 'Formación', 3, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(235, 47, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(236, 48, 16, 'Tecnología', 5, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(237, 48, 20, 'Dotación', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(238, 48, 2, 'Servicios Generales', 7, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(239, 48, 22, 'Formación', 3, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(240, 48, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(241, 49, 16, 'Tecnología', 5, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(242, 49, 20, 'Dotación', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(243, 49, 2, 'Servicios Generales', 7, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(244, 49, 22, 'Formación', 3, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(245, 49, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(246, 50, 16, 'Tecnología', 5, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(247, 50, 20, 'Dotación', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(248, 50, 2, 'Servicios Generales', 7, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(249, 50, 22, 'Formación', 3, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(250, 50, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(251, 51, 16, 'Tecnología', 5, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(252, 51, 20, 'Dotación', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(253, 51, 2, 'Servicios Generales', 7, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(254, 51, 22, 'Formación', 3, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(255, 51, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(256, 52, 16, 'Tecnología', 5, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(257, 52, 20, 'Dotación', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(258, 52, 2, 'Servicios Generales', 7, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(259, 52, 22, 'Formación', 3, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(260, 52, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(261, 53, 16, 'Tecnología', 5, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(262, 53, 20, 'Dotación', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(263, 53, 2, 'Servicios Generales', 7, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(264, 53, 22, 'Formación', 3, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(265, 53, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(266, 54, 16, 'Tecnología', 5, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(267, 54, 20, 'Dotación', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(268, 54, 2, 'Servicios Generales', 7, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(269, 54, 22, 'Formación', 3, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(270, 54, 2, 'Bienes y Servicios', 10, '2026-02-13 20:38:15', '2026-02-13 20:38:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesos_ingresos`
--

CREATE TABLE `procesos_ingresos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `tipo_documento` varchar(255) NOT NULL,
  `documento` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `cargo_id` bigint(20) UNSIGNED NOT NULL,
  `area_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `fecha_esperada_finalizacion` date DEFAULT NULL,
  `jefe_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jefe_cargo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `estado` enum('Pendiente','En Proceso','Finalizado','Cancelado') NOT NULL DEFAULT 'Pendiente',
  `observaciones` text DEFAULT NULL,
  `fecha_cancelacion` datetime DEFAULT NULL,
  `fecha_finalizacion` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `procesos_ingresos`
--

INSERT INTO `procesos_ingresos` (`id`, `codigo`, `nombre_completo`, `tipo_documento`, `documento`, `email`, `telefono`, `cargo_id`, `area_id`, `fecha_ingreso`, `fecha_esperada_finalizacion`, `jefe_id`, `jefe_cargo_id`, `estado`, `observaciones`, `fecha_cancelacion`, `fecha_finalizacion`, `created_at`, `updated_at`) VALUES
(2, 'ING-20260213211318', 'sebastian bello', 'CC', '18520425', NULL, NULL, 3, 2, '2026-03-05', NULL, 19, 2, 'Finalizado', NULL, NULL, NULL, '2026-02-14 02:13:18', '2026-02-14 06:58:59'),
(3, 'ING-20260214135105', 'alan', 'cc', '12312', NULL, NULL, 25, 10, '2026-02-28', NULL, NULL, 22, 'Pendiente', NULL, NULL, NULL, '2026-02-14 18:51:05', '2026-02-14 18:51:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puestos_trabajo`
--

CREATE TABLE `puestos_trabajo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero_puesto` varchar(255) NOT NULL,
  `piso` int(11) NOT NULL DEFAULT 1,
  `seccion` varchar(255) DEFAULT NULL,
  `capacidad` int(11) NOT NULL DEFAULT 1,
  `estado` enum('Disponible','Asignado','En Mantenimiento','Bloqueado') NOT NULL DEFAULT 'Disponible',
  `ubicacion_x` int(11) DEFAULT NULL,
  `ubicacion_y` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `equipamiento` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`equipamiento`)),
  `notas` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `puestos_trabajo`
--

INSERT INTO `puestos_trabajo` (`id`, `numero_puesto`, `piso`, `seccion`, `capacidad`, `estado`, `ubicacion_x`, `ubicacion_y`, `descripcion`, `equipamiento`, `notas`, `created_at`, `updated_at`) VALUES
(1, '1-OPS-1', 1, 'Operaciones', 1, 'Disponible', 160, 100, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(2, '1-OPS-2', 1, 'Operaciones', 1, 'Disponible', 220, 100, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(3, '1-OPS-3', 1, 'Operaciones', 1, 'Disponible', 280, 100, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(4, '1-OPS-4', 1, 'Operaciones', 1, 'Disponible', 340, 100, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(5, '1-OPS-5', 1, 'Operaciones', 1, 'Disponible', 400, 100, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(6, '1-OPS-6', 1, 'Operaciones', 1, 'Disponible', 460, 100, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(7, '1-ADM-1', 1, 'Administrativo', 1, 'Disponible', 160, 200, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\",\\\"Impresora\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(8, '1-ADM-2', 1, 'Administrativo', 1, 'Disponible', 220, 200, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\",\\\"Impresora\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(9, '1-ADM-3', 1, 'Administrativo', 1, 'Disponible', 280, 200, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\",\\\"Impresora\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(10, '1-ADM-4', 1, 'Administrativo', 1, 'Disponible', 340, 200, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\",\\\"Impresora\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(11, '1-AAC-1', 1, 'Atención al Cliente', 1, 'Disponible', 160, 300, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\",\\\"Headset\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(12, '1-AAC-2', 1, 'Atención al Cliente', 1, 'Asignado', 220, 300, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\",\\\"Headset\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-14 06:46:54'),
(13, '1-AAC-3', 1, 'Atención al Cliente', 1, 'Disponible', 280, 300, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\",\\\"Headset\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(14, '2-GER-1', 2, 'Gerencia', 1, 'Disponible', 200, 100, NULL, '\"[\\\"Computadora\\\",\\\"Monitor Dual\\\",\\\"Tel\\\\u00e9fono IP\\\",\\\"Impresora L\\\\u00e1ser\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(15, '2-GER-2', 2, 'Gerencia', 1, 'Disponible', 300, 100, NULL, '\"[\\\"Computadora\\\",\\\"Monitor Dual\\\",\\\"Tel\\\\u00e9fono IP\\\",\\\"Impresora L\\\\u00e1ser\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(16, '2-GER-3', 2, 'Gerencia', 1, 'Disponible', 400, 100, NULL, '\"[\\\"Computadora\\\",\\\"Monitor Dual\\\",\\\"Tel\\\\u00e9fono IP\\\",\\\"Impresora L\\\\u00e1ser\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(17, '2-COORD-1', 2, 'Coordinación', 1, 'Disponible', 170, 250, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(18, '2-COORD-2', 2, 'Coordinación', 1, 'Disponible', 240, 250, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(19, '2-COORD-3', 2, 'Coordinación', 1, 'Disponible', 310, 250, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(20, '2-COORD-4', 2, 'Coordinación', 1, 'Disponible', 380, 250, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(21, '2-COORD-5', 2, 'Coordinación', 1, 'Disponible', 450, 250, NULL, '\"[\\\"Computadora\\\",\\\"Monitor\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(22, '3-DEV-1', 3, 'Desarrollo', 1, 'Disponible', 150, 100, NULL, '\"[\\\"Computadora High-end\\\",\\\"2x Monitor Ultrawide\\\",\\\"Teclado Mec\\\\u00e1nico\\\",\\\"Mouse Gamer\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(23, '3-DEV-2', 3, 'Desarrollo', 1, 'Disponible', 200, 100, NULL, '\"[\\\"Computadora High-end\\\",\\\"2x Monitor Ultrawide\\\",\\\"Teclado Mec\\\\u00e1nico\\\",\\\"Mouse Gamer\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(24, '3-DEV-3', 3, 'Desarrollo', 1, 'Disponible', 250, 100, NULL, '\"[\\\"Computadora High-end\\\",\\\"2x Monitor Ultrawide\\\",\\\"Teclado Mec\\\\u00e1nico\\\",\\\"Mouse Gamer\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(25, '3-DEV-4', 3, 'Desarrollo', 1, 'Disponible', 300, 100, NULL, '\"[\\\"Computadora High-end\\\",\\\"2x Monitor Ultrawide\\\",\\\"Teclado Mec\\\\u00e1nico\\\",\\\"Mouse Gamer\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(26, '3-DEV-5', 3, 'Desarrollo', 1, 'Disponible', 350, 100, NULL, '\"[\\\"Computadora High-end\\\",\\\"2x Monitor Ultrawide\\\",\\\"Teclado Mec\\\\u00e1nico\\\",\\\"Mouse Gamer\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(27, '3-DEV-6', 3, 'Desarrollo', 1, 'Disponible', 400, 100, NULL, '\"[\\\"Computadora High-end\\\",\\\"2x Monitor Ultrawide\\\",\\\"Teclado Mec\\\\u00e1nico\\\",\\\"Mouse Gamer\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(28, '3-DEV-7', 3, 'Desarrollo', 1, 'Disponible', 450, 100, NULL, '\"[\\\"Computadora High-end\\\",\\\"2x Monitor Ultrawide\\\",\\\"Teclado Mec\\\\u00e1nico\\\",\\\"Mouse Gamer\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(29, '3-DEV-8', 3, 'Desarrollo', 1, 'Disponible', 500, 100, NULL, '\"[\\\"Computadora High-end\\\",\\\"2x Monitor Ultrawide\\\",\\\"Teclado Mec\\\\u00e1nico\\\",\\\"Mouse Gamer\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(30, '3-QA-1', 3, 'QA', 1, 'Disponible', 160, 250, NULL, '\"[\\\"Computadora\\\",\\\"Monitor Dual\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(31, '3-QA-2', 3, 'QA', 1, 'Disponible', 220, 250, NULL, '\"[\\\"Computadora\\\",\\\"Monitor Dual\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(32, '3-QA-3', 3, 'QA', 1, 'Disponible', 280, 250, NULL, '\"[\\\"Computadora\\\",\\\"Monitor Dual\\\",\\\"Tel\\\\u00e9fono\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(33, '4-SALA-1', 4, 'Salas', 1, 'Disponible', 180, 100, NULL, '\"[\\\"Proyector\\\",\\\"Pantalla\\\",\\\"Tel\\\\u00e9fono Conferencia\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(34, '4-SALA-2', 4, 'Salas', 1, 'Disponible', 260, 100, NULL, '\"[\\\"Proyector\\\",\\\"Pantalla\\\",\\\"Tel\\\\u00e9fono Conferencia\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(35, '4-SALA-3', 4, 'Salas', 1, 'Disponible', 340, 100, NULL, '\"[\\\"Proyector\\\",\\\"Pantalla\\\",\\\"Tel\\\\u00e9fono Conferencia\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(36, '4-SALA-4', 4, 'Salas', 1, 'Disponible', 420, 100, NULL, '\"[\\\"Proyector\\\",\\\"Pantalla\\\",\\\"Tel\\\\u00e9fono Conferencia\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(37, '4-SALA-5', 4, 'Salas', 1, 'Asignado', 500, 100, NULL, '\"[\\\"Proyector\\\",\\\"Pantalla\\\",\\\"Tel\\\\u00e9fono Conferencia\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-14 06:45:04'),
(38, '4-SALA-6', 4, 'Salas', 1, 'Disponible', 580, 100, NULL, '\"[\\\"Proyector\\\",\\\"Pantalla\\\",\\\"Tel\\\\u00e9fono Conferencia\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(39, '4-COLABO-1', 4, 'Colaboración', 1, 'Disponible', 140, 250, NULL, '\"[\\\"Computadora Port\\\\u00e1til\\\",\\\"Monitor Port\\\\u00e1til\\\",\\\"Conexi\\\\u00f3n WiFi\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(40, '4-COLABO-2', 4, 'Colaboración', 1, 'Disponible', 180, 250, NULL, '\"[\\\"Computadora Port\\\\u00e1til\\\",\\\"Monitor Port\\\\u00e1til\\\",\\\"Conexi\\\\u00f3n WiFi\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(41, '4-COLABO-3', 4, 'Colaboración', 1, 'Disponible', 220, 250, NULL, '\"[\\\"Computadora Port\\\\u00e1til\\\",\\\"Monitor Port\\\\u00e1til\\\",\\\"Conexi\\\\u00f3n WiFi\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(42, '4-COLABO-4', 4, 'Colaboración', 1, 'Disponible', 260, 250, NULL, '\"[\\\"Computadora Port\\\\u00e1til\\\",\\\"Monitor Port\\\\u00e1til\\\",\\\"Conexi\\\\u00f3n WiFi\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(43, '4-COLABO-5', 4, 'Colaboración', 1, 'Disponible', 300, 250, NULL, '\"[\\\"Computadora Port\\\\u00e1til\\\",\\\"Monitor Port\\\\u00e1til\\\",\\\"Conexi\\\\u00f3n WiFi\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(44, '4-COLABO-6', 4, 'Colaboración', 1, 'Disponible', 340, 250, NULL, '\"[\\\"Computadora Port\\\\u00e1til\\\",\\\"Monitor Port\\\\u00e1til\\\",\\\"Conexi\\\\u00f3n WiFi\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(45, '4-COLABO-7', 4, 'Colaboración', 1, 'Disponible', 380, 250, NULL, '\"[\\\"Computadora Port\\\\u00e1til\\\",\\\"Monitor Port\\\\u00e1til\\\",\\\"Conexi\\\\u00f3n WiFi\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(46, '4-COLABO-8', 4, 'Colaboración', 1, 'Disponible', 420, 250, NULL, '\"[\\\"Computadora Port\\\\u00e1til\\\",\\\"Monitor Port\\\\u00e1til\\\",\\\"Conexi\\\\u00f3n WiFi\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(47, '4-COLABO-9', 4, 'Colaboración', 1, 'Disponible', 460, 250, NULL, '\"[\\\"Computadora Port\\\\u00e1til\\\",\\\"Monitor Port\\\\u00e1til\\\",\\\"Conexi\\\\u00f3n WiFi\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15'),
(48, '4-COLABO-10', 4, 'Colaboración', 1, 'Disponible', 500, 250, NULL, '\"[\\\"Computadora Port\\\\u00e1til\\\",\\\"Monitor Port\\\\u00e1til\\\",\\\"Conexi\\\\u00f3n WiFi\\\"]\"', NULL, '2026-02-13 20:38:15', '2026-02-13 20:38:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(11, 'Root', 'web', '2026-02-14 02:01:56', '2026-02-14 02:01:56'),
(12, 'Jefe RRHH', 'web', '2026-02-14 02:01:56', '2026-02-14 02:01:56'),
(13, 'Jefe Tecnología', 'web', '2026-02-14 02:01:56', '2026-02-14 02:01:56'),
(14, 'Jefe Dotación', 'web', '2026-02-14 02:01:56', '2026-02-14 02:01:56'),
(15, 'Jefe Servicios Generales', 'web', '2026-02-14 02:01:56', '2026-02-14 02:01:56'),
(16, 'Jefe Bienes y Servicios', 'web', '2026-02-14 02:01:56', '2026-02-14 02:01:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 11),
(1, 12),
(2, 11),
(2, 12),
(3, 11),
(3, 12),
(4, 11),
(4, 12),
(5, 11),
(5, 12),
(6, 11),
(6, 12),
(6, 13),
(6, 14),
(6, 15),
(6, 16),
(7, 11),
(7, 12),
(7, 13),
(7, 14),
(7, 15),
(7, 16),
(8, 11),
(8, 12),
(8, 13),
(8, 14),
(8, 15),
(8, 16),
(9, 11),
(9, 12),
(9, 13),
(9, 14),
(9, 15),
(9, 16),
(10, 11),
(10, 12),
(11, 11),
(11, 12),
(12, 11),
(12, 12),
(13, 11),
(13, 12),
(14, 11),
(15, 11),
(15, 12),
(15, 13),
(15, 14),
(15, 15),
(15, 16),
(16, 11),
(17, 11),
(18, 11),
(19, 11),
(20, 11),
(21, 11),
(22, 11),
(23, 11),
(24, 11),
(25, 11),
(26, 11),
(27, 11),
(28, 11),
(29, 11),
(30, 11),
(31, 11),
(32, 11),
(33, 11),
(34, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('EdM0sRm4nlsmCeXDFFeSUEPWCgq4ZZQMUbctEmms', 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSTBuVm1vMFNNbUNQWHdreGRaV0dyR2F5U3dBdklmVjhTbk9oR3VHUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTU7fQ==', 1771036473),
('VzskZ3phG4EwEfmiBZhHRJMwVA2bYtKWE1pHTQaX', 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidnZ1cUZqRENVVnZFMmZpZ2lZaFllaHVyMUo5T2N4RTRTTGpZZkJxaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTU7fQ==', 1771079640);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proceso_ingreso_id` bigint(20) UNSIGNED NOT NULL,
  `area_id` bigint(20) UNSIGNED NOT NULL,
  `puesto_trabajo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo` varchar(255) NOT NULL,
  `fecha_limite` date NOT NULL,
  `estado` enum('Pendiente','En Proceso','Finalizada') NOT NULL DEFAULT 'Pendiente',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`id`, `proceso_ingreso_id`, `area_id`, `puesto_trabajo_id`, `tipo`, `fecha_limite`, `estado`, `observaciones`, `created_at`, `updated_at`) VALUES
(6, 2, 16, NULL, 'Tecnología', '2026-02-28', 'Finalizada', NULL, '2026-02-14 02:13:18', '2026-02-14 06:52:10'),
(7, 2, 20, NULL, 'Dotación', '2026-02-23', 'Finalizada', NULL, '2026-02-14 02:13:18', '2026-02-14 06:51:04'),
(8, 2, 2, 12, 'Servicios Generales', '2026-02-26', 'Finalizada', NULL, '2026-02-14 02:13:18', '2026-02-14 06:47:01'),
(9, 2, 22, NULL, 'Formación', '2026-03-02', 'Finalizada', NULL, '2026-02-14 02:13:18', '2026-02-14 02:20:50'),
(10, 2, 2, NULL, 'Bienes y Servicios', '2026-02-23', 'Finalizada', NULL, '2026-02-14 02:13:18', '2026-02-14 06:58:59'),
(11, 3, 16, NULL, 'Tecnología', '2026-02-23', 'Pendiente', NULL, '2026-02-14 18:51:05', '2026-02-14 18:51:05'),
(12, 3, 20, NULL, 'Dotación', '2026-02-18', 'Pendiente', NULL, '2026-02-14 18:51:05', '2026-02-14 18:51:05'),
(13, 3, 2, NULL, 'Servicios Generales', '2026-02-21', 'Pendiente', NULL, '2026-02-14 18:51:05', '2026-02-14 18:51:05'),
(14, 3, 22, NULL, 'Formación', '2026-02-25', 'Pendiente', NULL, '2026-02-14 18:51:05', '2026-02-14 18:51:05'),
(15, 3, 2, NULL, 'Bienes y Servicios', '2026-02-18', 'Pendiente', NULL, '2026-02-14 18:51:05', '2026-02-14 18:51:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_curso`
--

CREATE TABLE `solicitud_curso` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `solicitud_id` bigint(20) UNSIGNED NOT NULL,
  `curso_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `solicitud_curso`
--

INSERT INTO `solicitud_curso` (`id`, `solicitud_id`, `curso_id`, `created_at`, `updated_at`) VALUES
(1, 9, 5, NULL, NULL),
(2, 9, 19, NULL, NULL),
(3, 9, 24, NULL, NULL),
(4, 9, 25, NULL, NULL),
(5, 9, 31, NULL, NULL),
(6, 14, 5, NULL, NULL),
(7, 14, 23, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `posicion_id` bigint(20) UNSIGNED DEFAULT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cargo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rol_onboarding` enum('admin','jefe_area','coordinador','revisor','operador') DEFAULT NULL COMMENT 'Rol en el proceso de onboarding',
  `puede_aprobar_solicitudes` tinyint(1) NOT NULL DEFAULT 0 COMMENT '¿Puede aprobar solicitudes de nuevo ingreso?',
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `numero_documento` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jefe_directo_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `posicion_id`, `area_id`, `cargo_id`, `rol_onboarding`, `puede_aprobar_solicitudes`, `name`, `email`, `numero_documento`, `telefono`, `direccion`, `fecha_ingreso`, `fecha_salida`, `email_verified_at`, `password`, `activo`, `remember_token`, `created_at`, `updated_at`, `jefe_directo_id`) VALUES
(15, NULL, NULL, NULL, NULL, 0, 'Administrador Root', 'root@sinergia.com', NULL, NULL, NULL, NULL, NULL, '2026-02-14 02:02:01', '$2y$12$xBSgXfMAmXkCcr21ASrzgOTcn1dTcDAE3ZumdyNGtrtV3DoVQUgTC', 1, NULL, '2026-02-14 02:02:01', '2026-02-14 02:02:01', NULL),
(16, NULL, NULL, NULL, NULL, 0, 'María González - Jefe RRHH', 'jefe.rrhh@sinergia.com', NULL, NULL, NULL, NULL, NULL, '2026-02-14 02:02:01', '$2y$12$Kkoyzatt7w4qs1UiTzLKZeZYwwLVYmNPzH0YGUrossCeFbVmwbU3.', 1, NULL, '2026-02-14 02:02:01', '2026-02-14 02:02:01', NULL),
(17, NULL, NULL, NULL, NULL, 0, 'Carlos Rodríguez - Jefe Tecnología', 'jefe.tecnologia@sinergia.com', NULL, NULL, NULL, NULL, NULL, '2026-02-14 02:02:02', '$2y$12$T7JTMv2M3S3FZ/qG3tksj.ldeC.u55t3Kl2EiiXVbhM0OLQrathVy', 1, NULL, '2026-02-14 02:02:02', '2026-02-14 02:02:02', NULL),
(18, NULL, NULL, NULL, NULL, 0, 'Ana Martínez - Jefe Dotación', 'jefe.dotacion@sinergia.com', NULL, NULL, NULL, NULL, NULL, '2026-02-14 02:02:02', '$2y$12$gJvWA8Sc0l5ioV.NVC.92uAsPN0e6ypu6AGCk2LTgNEeysilBCgEq', 1, NULL, '2026-02-14 02:02:02', '2026-02-14 02:02:02', NULL),
(19, NULL, 2, NULL, NULL, 0, 'Juan Pérez - Jefe Servicios Generales', 'jefe.servicios@sinergia.com', NULL, NULL, NULL, NULL, NULL, '2026-02-14 02:02:02', '$2y$12$jO65/G5gkFSOUMhfpkuXH.nXQh8x6Fq5Xc3xji8C8jlsme90uViU2', 1, NULL, '2026-02-14 02:02:02', '2026-02-14 02:02:02', NULL),
(20, NULL, NULL, NULL, NULL, 0, 'Patricia López - Jefe Bienes y Servicios', 'jefe.bienes@sinergia.com', NULL, NULL, NULL, NULL, NULL, '2026-02-14 02:02:03', '$2y$12$OL2PZGxRwOJG/k/GWOdr3.Ix1V3PZQ2QJOAfo2DKrzIogh42Zy9iu', 1, NULL, '2026-02-14 02:02:03', '2026-02-14 02:02:03', NULL),
(21, NULL, NULL, NULL, NULL, 0, 'Roberto Sánchez - Jefe Ventas', 'jefe.ventas@sinergia.com', NULL, NULL, NULL, NULL, NULL, '2026-02-14 02:02:03', '$2y$12$r84f8FTwniTDQvNiy.uJg.xCIzKZa3ZtdD4ZRf/5D5r4ozOc6AHUm', 1, NULL, '2026-02-14 02:02:03', '2026-02-14 02:02:03', NULL),
(22, NULL, NULL, NULL, NULL, 0, 'Laura Torres - Jefe Capacitación', 'jefe.capacitacion@sinergia.com', NULL, NULL, NULL, NULL, NULL, '2026-02-14 02:02:04', '$2y$12$fHBtlIQ9MxMPThPXPgIxJeY5uXqCxtbazn74xonyXQVqnQrW0BvrW', 1, NULL, '2026-02-14 02:02:04', '2026-02-14 02:02:04', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `areas_gerencia_id_foreign` (`gerencia_id`),
  ADD KEY `areas_jefe_area_cargo_id_foreign` (`jefe_area_cargo_id`);

--
-- Indices de la tabla `asignacion_cursos`
--
ALTER TABLE `asignacion_cursos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `asignacion_cursos_proceso_ingreso_id_curso_id_unique` (`proceso_ingreso_id`,`curso_id`),
  ADD KEY `asignacion_cursos_asignado_por_id_foreign` (`asignado_por_id`),
  ADD KEY `asignacion_cursos_responsable_validacion_id_foreign` (`responsable_validacion_id`),
  ADD KEY `asignacion_cursos_proceso_ingreso_id_index` (`proceso_ingreso_id`),
  ADD KEY `asignacion_cursos_curso_id_index` (`curso_id`),
  ADD KEY `asignacion_cursos_estado_index` (`estado`);

--
-- Indices de la tabla `auditoria_onboarding`
--
ALTER TABLE `auditoria_onboarding`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auditoria_onboarding_usuario_id_index` (`usuario_id`),
  ADD KEY `auditoria_onboarding_entidad_entidad_id_index` (`entidad`,`entidad_id`),
  ADD KEY `auditoria_onboarding_accion_index` (`accion`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cargos_area_id_foreign` (`area_id`),
  ADD KEY `cargos_jefe_inmediato_cargo_id_foreign` (`jefe_inmediato_cargo_id`),
  ADD KEY `cargos_gerencia_id_foreign` (`gerencia_id`);

--
-- Indices de la tabla `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `checkins_codigo_verificacion_unique` (`codigo_verificacion`),
  ADD KEY `checkins_proceso_ingreso_id_foreign` (`proceso_ingreso_id`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cursos_codigo_unique` (`codigo`),
  ADD KEY `cursos_area_responsable_id_foreign` (`area_responsable_id`),
  ADD KEY `cursos_codigo_index` (`codigo`),
  ADD KEY `cursos_categoria_index` (`categoria`),
  ADD KEY `cursos_activo_categoria_index` (`activo`,`categoria`);

--
-- Indices de la tabla `detalles_bienes`
--
ALTER TABLE `detalles_bienes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalles_bienes_solicitud_id_foreign` (`solicitud_id`);

--
-- Indices de la tabla `gerencias`
--
ALTER TABLE `gerencias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gerencias_nombre_unique` (`nombre`),
  ADD UNIQUE KEY `gerencias_codigo_unique` (`codigo`),
  ADD KEY `gerencias_activo_codigo_index` (`activo`,`codigo`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `plantilla_solicitudes`
--
ALTER TABLE `plantilla_solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plantilla_solicitudes_cargo_id_foreign` (`cargo_id`),
  ADD KEY `plantilla_solicitudes_area_id_foreign` (`area_id`);

--
-- Indices de la tabla `procesos_ingresos`
--
ALTER TABLE `procesos_ingresos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `procesos_ingresos_codigo_unique` (`codigo`),
  ADD UNIQUE KEY `procesos_ingresos_documento_unique` (`documento`),
  ADD KEY `procesos_ingresos_cargo_id_foreign` (`cargo_id`),
  ADD KEY `procesos_ingresos_area_id_foreign` (`area_id`),
  ADD KEY `procesos_ingresos_jefe_id_foreign` (`jefe_id`),
  ADD KEY `procesos_ingresos_jefe_cargo_id_foreign` (`jefe_cargo_id`);

--
-- Indices de la tabla `puestos_trabajo`
--
ALTER TABLE `puestos_trabajo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `puestos_trabajo_numero_puesto_unique` (`numero_puesto`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitudes_proceso_ingreso_id_foreign` (`proceso_ingreso_id`),
  ADD KEY `solicitudes_area_id_foreign` (`area_id`),
  ADD KEY `solicitudes_puesto_trabajo_id_foreign` (`puesto_trabajo_id`);

--
-- Indices de la tabla `solicitud_curso`
--
ALTER TABLE `solicitud_curso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `solicitud_curso_solicitud_id_curso_id_unique` (`solicitud_id`,`curso_id`),
  ADD KEY `solicitud_curso_curso_id_foreign` (`curso_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_numero_documento_unique` (`numero_documento`),
  ADD KEY `users_jefe_directo_id_foreign` (`jefe_directo_id`),
  ADD KEY `users_rol_onboarding_index` (`rol_onboarding`),
  ADD KEY `users_puede_aprobar_solicitudes_index` (`puede_aprobar_solicitudes`),
  ADD KEY `users_posicion_id_foreign` (`posicion_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `asignacion_cursos`
--
ALTER TABLE `asignacion_cursos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `auditoria_onboarding`
--
ALTER TABLE `auditoria_onboarding`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de la tabla `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `detalles_bienes`
--
ALTER TABLE `detalles_bienes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `gerencias`
--
ALTER TABLE `gerencias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `plantilla_solicitudes`
--
ALTER TABLE `plantilla_solicitudes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;

--
-- AUTO_INCREMENT de la tabla `procesos_ingresos`
--
ALTER TABLE `procesos_ingresos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `puestos_trabajo`
--
ALTER TABLE `puestos_trabajo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `solicitud_curso`
--
ALTER TABLE `solicitud_curso`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `areas`
--
ALTER TABLE `areas`
  ADD CONSTRAINT `areas_gerencia_id_foreign` FOREIGN KEY (`gerencia_id`) REFERENCES `areas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `areas_jefe_area_cargo_id_foreign` FOREIGN KEY (`jefe_area_cargo_id`) REFERENCES `cargos` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `asignacion_cursos`
--
ALTER TABLE `asignacion_cursos`
  ADD CONSTRAINT `asignacion_cursos_asignado_por_id_foreign` FOREIGN KEY (`asignado_por_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `asignacion_cursos_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asignacion_cursos_proceso_ingreso_id_foreign` FOREIGN KEY (`proceso_ingreso_id`) REFERENCES `procesos_ingresos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asignacion_cursos_responsable_validacion_id_foreign` FOREIGN KEY (`responsable_validacion_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `auditoria_onboarding`
--
ALTER TABLE `auditoria_onboarding`
  ADD CONSTRAINT `auditoria_onboarding_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD CONSTRAINT `cargos_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cargos_gerencia_id_foreign` FOREIGN KEY (`gerencia_id`) REFERENCES `areas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cargos_jefe_inmediato_cargo_id_foreign` FOREIGN KEY (`jefe_inmediato_cargo_id`) REFERENCES `cargos` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `checkins`
--
ALTER TABLE `checkins`
  ADD CONSTRAINT `checkins_proceso_ingreso_id_foreign` FOREIGN KEY (`proceso_ingreso_id`) REFERENCES `procesos_ingresos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_area_responsable_id_foreign` FOREIGN KEY (`area_responsable_id`) REFERENCES `areas` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `detalles_bienes`
--
ALTER TABLE `detalles_bienes`
  ADD CONSTRAINT `detalles_bienes_solicitud_id_foreign` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `plantilla_solicitudes`
--
ALTER TABLE `plantilla_solicitudes`
  ADD CONSTRAINT `plantilla_solicitudes_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `plantilla_solicitudes_cargo_id_foreign` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`);

--
-- Filtros para la tabla `procesos_ingresos`
--
ALTER TABLE `procesos_ingresos`
  ADD CONSTRAINT `procesos_ingresos_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `procesos_ingresos_cargo_id_foreign` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`),
  ADD CONSTRAINT `procesos_ingresos_jefe_cargo_id_foreign` FOREIGN KEY (`jefe_cargo_id`) REFERENCES `cargos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `procesos_ingresos_jefe_id_foreign` FOREIGN KEY (`jefe_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `solicitudes_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `solicitudes_proceso_ingreso_id_foreign` FOREIGN KEY (`proceso_ingreso_id`) REFERENCES `procesos_ingresos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `solicitudes_puesto_trabajo_id_foreign` FOREIGN KEY (`puesto_trabajo_id`) REFERENCES `puestos_trabajo` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `solicitud_curso`
--
ALTER TABLE `solicitud_curso`
  ADD CONSTRAINT `solicitud_curso_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `solicitud_curso_solicitud_id_foreign` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_jefe_directo_id_foreign` FOREIGN KEY (`jefe_directo_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_posicion_id_foreign` FOREIGN KEY (`posicion_id`) REFERENCES `posiciones` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
