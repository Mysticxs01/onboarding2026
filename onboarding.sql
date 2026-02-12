-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-02-2026 a las 06:00:19
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Recursos Humanos', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(2, 'Tecnología', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(3, 'Servicios Generales', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(4, 'Formación y Capacitación', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(5, 'Bienes y Servicios', '2026-02-08 09:57:53', '2026-02-08 09:57:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-admin@empresa.com|127.0.0.1', 'i:1;', 1770526746),
('laravel-cache-admin@empresa.com|127.0.0.1:timer', 'i:1770526746;', 1770526746);

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id`, `nombre`, `area_id`, `created_at`, `updated_at`) VALUES
(1, 'Analista de RRHH', 1, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(2, 'Desarrollador', 2, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(3, 'Técnico de Servicios', 3, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(4, 'Instructor', 4, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(5, 'Administrador de Inventario', 5, '2026-02-08 09:57:53', '2026-02-08 09:57:53');

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_tecnologia`
--

CREATE TABLE `detalles_tecnologia` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `solicitud_id` bigint(20) UNSIGNED NOT NULL,
  `proceso_ingreso_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_computador` enum('Portátil','Escritorio') DEFAULT NULL,
  `marca_computador` varchar(255) DEFAULT NULL,
  `especificaciones` varchar(255) DEFAULT NULL,
  `software_requerido` text DEFAULT NULL,
  `monitor_adicional` tinyint(1) NOT NULL DEFAULT 0,
  `mouse_teclado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_uniformes`
--

CREATE TABLE `detalles_uniformes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `solicitud_id` bigint(20) UNSIGNED NOT NULL,
  `proceso_ingreso_id` bigint(20) UNSIGNED NOT NULL,
  `talla_camisa` varchar(255) DEFAULT NULL,
  `talla_pantalon` varchar(255) DEFAULT NULL,
  `talla_zapatos` varchar(255) DEFAULT NULL,
  `genero` varchar(255) DEFAULT NULL,
  `cantidad_uniformes` int(11) NOT NULL DEFAULT 2,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(11, '2026_02_09_add_fields_to_procesos_ingresos', 1),
(12, '2026_02_09_create_puestos_tabla', 1),
(13, '2026_02_10_create_detalles_tecnicas_tables', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 2),
(5, 'App\\Models\\User', 6),
(6, 'App\\Models\\User', 5),
(7, 'App\\Models\\User', 7),
(8, 'App\\Models\\User', 8),
(9, 'App\\Models\\User', 9);

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
(1, 'ver-procesos', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(2, 'crear-procesos', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(3, 'editar-procesos', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(4, 'cancelar-procesos', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(5, 'ver-historico-procesos', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(6, 'ver-solicitudes', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(7, 'editar-solicitudes', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(8, 'completar-solicitudes', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(9, 'ver-solicitudes-area', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(10, 'especificar-requerimientos-ti', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(11, 'especificar-tallas', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(12, 'validar-solicitudes', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(13, 'generar-pdf-checkin', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(14, 'confirmar-entrada-activos', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(15, 'ver-checkin', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(16, 'gestionar-usuarios', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(17, 'asignar-roles', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(18, 'ver-usuarios', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53');

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
(1, 1, 2, 'Tecnología', 5, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(2, 1, 1, 'Dotación', 10, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(3, 1, 3, 'Servicios Generales', 7, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(4, 1, 4, 'Formación', 3, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(5, 1, 5, 'Bienes y Servicios', 10, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(6, 2, 2, 'Tecnología', 5, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(7, 2, 1, 'Dotación', 10, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(8, 2, 3, 'Servicios Generales', 7, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(9, 2, 4, 'Formación', 3, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(10, 2, 5, 'Bienes y Servicios', 10, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(11, 3, 2, 'Tecnología', 5, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(12, 3, 1, 'Dotación', 10, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(13, 3, 3, 'Servicios Generales', 7, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(14, 3, 4, 'Formación', 3, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(15, 3, 5, 'Bienes y Servicios', 10, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(16, 4, 2, 'Tecnología', 5, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(17, 4, 1, 'Dotación', 10, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(18, 4, 3, 'Servicios Generales', 7, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(19, 4, 4, 'Formación', 3, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(20, 4, 5, 'Bienes y Servicios', 10, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(21, 5, 2, 'Tecnología', 5, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(22, 5, 1, 'Dotación', 10, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(23, 5, 3, 'Servicios Generales', 7, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(24, 5, 4, 'Formación', 3, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(25, 5, 5, 'Bienes y Servicios', 10, '2026-02-08 09:57:53', '2026-02-08 09:57:53');

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
  `cargo_id` bigint(20) UNSIGNED NOT NULL,
  `area_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `jefe_id` bigint(20) UNSIGNED NOT NULL,
  `estado` enum('Pendiente','En Proceso','Finalizado','Cancelado') NOT NULL DEFAULT 'Pendiente',
  `observaciones` text DEFAULT NULL,
  `fecha_cancelacion` datetime DEFAULT NULL,
  `fecha_finalizacion` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puestos`
--

CREATE TABLE `puestos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(255) NOT NULL,
  `fila` int(11) NOT NULL,
  `columna` int(11) NOT NULL,
  `estado` enum('Disponible','Ocupado') NOT NULL DEFAULT 'Disponible',
  `proceso_ingreso_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `puestos`
--

INSERT INTO `puestos` (`id`, `numero`, `fila`, `columna`, `estado`, `proceso_ingreso_id`, `created_at`, `updated_at`) VALUES
(1, 'A1', 1, 1, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(2, 'A2', 1, 2, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(3, 'A3', 1, 3, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(4, 'A4', 1, 4, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(5, 'A5', 1, 5, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(6, 'A6', 1, 6, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(7, 'B1', 2, 1, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(8, 'B2', 2, 2, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(9, 'B3', 2, 3, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(10, 'B4', 2, 4, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(11, 'B5', 2, 5, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(12, 'B6', 2, 6, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(13, 'C1', 3, 1, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(14, 'C2', 3, 2, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(15, 'C3', 3, 3, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(16, 'C4', 3, 4, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(17, 'C5', 3, 5, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(18, 'C6', 3, 6, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(19, 'D1', 4, 1, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(20, 'D2', 4, 2, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(21, 'D3', 4, 3, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(22, 'D4', 4, 4, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(23, 'D5', 4, 5, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(24, 'D6', 4, 6, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(25, 'E1', 5, 1, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(26, 'E2', 5, 2, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(27, 'E3', 5, 3, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(28, 'E4', 5, 4, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(29, 'E5', 5, 5, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(30, 'E6', 5, 6, 'Disponible', NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53');

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
(1, 'Root', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(2, 'Admin', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(3, 'Jefe', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(4, 'Operador', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(5, 'Operador Dotación', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(6, 'Operador Tecnología', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(7, 'Operador Servicios Generales', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(8, 'Operador Formación', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(9, 'Operador Bienes y Servicios', 'web', '2026-02-08 09:57:53', '2026-02-08 09:57:53');

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
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(6, 3),
(7, 1),
(7, 4),
(8, 1),
(8, 4),
(9, 1),
(9, 4),
(10, 1),
(10, 3),
(11, 1),
(11, 3),
(12, 1),
(12, 3),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(18, 2);

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
('0LWrBMeLJSlBzxTnnTorUASkH1MsYH3ATTYEdYm3', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiN3hJWFJWZGtscmpYemI0REFaRjNwZzBpdmM4bTZOTTRCVm11cU4zYyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc29saWNpdHVkZXMiO3M6NToicm91dGUiO3M6MTc6InNvbGljaXR1ZGVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Njt9', 1770526733);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proceso_ingreso_id` bigint(20) UNSIGNED NOT NULL,
  `area_id` bigint(20) UNSIGNED NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `fecha_limite` date NOT NULL,
  `estado` enum('Pendiente','En Proceso','Finalizada') NOT NULL DEFAULT 'Pendiente',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cargo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `area_id`, `cargo_id`, `name`, `email`, `email_verified_at`, `password`, `activo`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Jefe de RRHH', 'jefe.rrhh@example.com', NULL, '$2y$12$G1RaiIddgLdJvZq9nRJRS.rElwHJCV2uwZ6L87ir6Y1ueruNouUc.', 1, NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(2, 2, NULL, 'Jefe de Tecnología', 'jefe.tecnologia@example.com', NULL, '$2y$12$cTBqEAIwRifHtDfL2O0oM.T0aaNAunTZZoLg8ObB01eMqdsGT6cZO', 1, NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(3, 1, NULL, 'Root Admin', 'root@test.com', NULL, '$2y$12$318cVO4Kg9nWq0/Rviqh8.QNpEs7RG.nmXYA3eVbhGx5TY9hW.jti', 1, NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(4, 1, NULL, 'Administrador', 'admin@test.com', NULL, '$2y$12$2MpQSugTNN.QOzmNCJH4tO7APhVAxYPg/aMirtGFleqxKFfpL0.aC', 1, NULL, '2026-02-08 09:57:53', '2026-02-08 09:57:53'),
(5, 2, NULL, 'Operador Tecnología', 'operador.ti@test.com', NULL, '$2y$12$adGQRmRHafURcJ.9d9zbiegjiuYurexoN0m7J4UkoCHV6z9pNamFq', 1, NULL, '2026-02-08 09:57:54', '2026-02-08 09:57:54'),
(6, 1, NULL, 'Operador Dotación', 'operador.dotacion@test.com', NULL, '$2y$12$lkkKJ5jzZIt2yyETla3zuO39AxxPtAcdV5ICUX9xaWWUE1xAzp5TO', 1, NULL, '2026-02-08 09:57:54', '2026-02-08 09:57:54'),
(7, 3, NULL, 'Operador Servicios', 'operador.servicios@test.com', NULL, '$2y$12$1PSu6Dp.Qlr94.fmb/LRteuqpzTylj.IGSDmGy1.t0i8JYMOgeIoi', 1, NULL, '2026-02-08 09:57:54', '2026-02-08 09:57:54'),
(8, 4, NULL, 'Operador Formación', 'operador.formacion@test.com', NULL, '$2y$12$4BMEhaPAwVFB9HyACBXslOHc3RU5ZH9VImfmIPZcJhQ7QntFdxWGS', 1, NULL, '2026-02-08 09:57:54', '2026-02-08 09:57:54'),
(9, 5, NULL, 'Operador Bienes', 'operador.bienes@test.com', NULL, '$2y$12$wmxEk.kmX5rJycAr4SQKzeb/ehdFk9klv8DvagyRjhlKzKr2Kuprq', 1, NULL, '2026-02-08 09:57:54', '2026-02-08 09:57:54');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `cargos_area_id_foreign` (`area_id`);

--
-- Indices de la tabla `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `checkins_codigo_verificacion_unique` (`codigo_verificacion`),
  ADD KEY `checkins_proceso_ingreso_id_foreign` (`proceso_ingreso_id`);

--
-- Indices de la tabla `detalles_tecnologia`
--
ALTER TABLE `detalles_tecnologia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalles_tecnologia_solicitud_id_foreign` (`solicitud_id`),
  ADD KEY `detalles_tecnologia_proceso_ingreso_id_foreign` (`proceso_ingreso_id`);

--
-- Indices de la tabla `detalles_uniformes`
--
ALTER TABLE `detalles_uniformes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalles_uniformes_solicitud_id_foreign` (`solicitud_id`),
  ADD KEY `detalles_uniformes_proceso_ingreso_id_foreign` (`proceso_ingreso_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

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
  ADD KEY `procesos_ingresos_jefe_id_foreign` (`jefe_id`);

--
-- Indices de la tabla `puestos`
--
ALTER TABLE `puestos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `puestos_numero_unique` (`numero`),
  ADD KEY `puestos_proceso_ingreso_id_foreign` (`proceso_ingreso_id`);

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
  ADD KEY `solicitudes_area_id_foreign` (`area_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_tecnologia`
--
ALTER TABLE `detalles_tecnologia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_uniformes`
--
ALTER TABLE `detalles_uniformes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `plantilla_solicitudes`
--
ALTER TABLE `plantilla_solicitudes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `procesos_ingresos`
--
ALTER TABLE `procesos_ingresos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `puestos`
--
ALTER TABLE `puestos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD CONSTRAINT `cargos_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `checkins`
--
ALTER TABLE `checkins`
  ADD CONSTRAINT `checkins_proceso_ingreso_id_foreign` FOREIGN KEY (`proceso_ingreso_id`) REFERENCES `procesos_ingresos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalles_tecnologia`
--
ALTER TABLE `detalles_tecnologia`
  ADD CONSTRAINT `detalles_tecnologia_proceso_ingreso_id_foreign` FOREIGN KEY (`proceso_ingreso_id`) REFERENCES `procesos_ingresos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalles_tecnologia_solicitud_id_foreign` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalles_uniformes`
--
ALTER TABLE `detalles_uniformes`
  ADD CONSTRAINT `detalles_uniformes_proceso_ingreso_id_foreign` FOREIGN KEY (`proceso_ingreso_id`) REFERENCES `procesos_ingresos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalles_uniformes_solicitud_id_foreign` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `procesos_ingresos_jefe_id_foreign` FOREIGN KEY (`jefe_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `puestos`
--
ALTER TABLE `puestos`
  ADD CONSTRAINT `puestos_proceso_ingreso_id_foreign` FOREIGN KEY (`proceso_ingreso_id`) REFERENCES `procesos_ingresos` (`id`) ON DELETE SET NULL;

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
  ADD CONSTRAINT `solicitudes_proceso_ingreso_id_foreign` FOREIGN KEY (`proceso_ingreso_id`) REFERENCES `procesos_ingresos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
