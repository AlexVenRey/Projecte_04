-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 29-03-2025 a las 18:43:34
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `projecte_04`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etiquetas`
--

DROP TABLE IF EXISTS `etiquetas`;
CREATE TABLE IF NOT EXISTS `etiquetas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `icono` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'fa-tag',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `etiquetas_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `etiquetas`
--

INSERT INTO `etiquetas` (`id`, `nombre`, `icono`, `created_at`, `updated_at`) VALUES
(1, 'Cultura', 'fa-landmark', '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(2, 'Educación', 'fa-graduation-cap', '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(3, 'Parques', 'fa-tree', '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(4, 'Transporte', 'fa-train', '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(5, 'Compras', 'fa-shopping-cart', '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(6, 'Ocio', 'fa-ticket-alt', '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(7, 'Sanidad', 'fa-hospital', '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(8, 'Deportes', 'fa-futbol', '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(9, 'Restaurantes', 'fa-utensils', '2025-03-29 16:54:40', '2025-03-29 16:54:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
CREATE TABLE IF NOT EXISTS `favoritos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint UNSIGNED NOT NULL,
  `lugar_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `favoritos_usuario_id_foreign` (`usuario_id`),
  KEY `favoritos_lugar_id_foreign` (`lugar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gimcanas`
--

DROP TABLE IF EXISTS `gimcanas`;
CREATE TABLE IF NOT EXISTS `gimcanas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `creado_por` bigint UNSIGNED NOT NULL,
  `estado` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gimcanas_creado_por_foreign` (`creado_por`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `gimcanas`
--

INSERT INTO `gimcanas` (`id`, `nombre`, `descripcion`, `creado_por`, `estado`, `created_at`, `updated_at`) VALUES
(1, '3546', '3546', 2, 'en_progreso', '2025-03-29 16:55:41', '2025-03-29 16:57:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gimcana_grupo`
--

DROP TABLE IF EXISTS `gimcana_grupo`;
CREATE TABLE IF NOT EXISTS `gimcana_grupo` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `gimcana_id` bigint UNSIGNED NOT NULL,
  `grupo_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gimcana_grupo_gimcana_id_foreign` (`gimcana_id`),
  KEY `gimcana_grupo_grupo_id_foreign` (`grupo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `gimcana_grupo`
--

INSERT INTO `gimcana_grupo` (`id`, `gimcana_id`, `grupo_id`, `created_at`, `updated_at`) VALUES
(1, 1, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gimcana_lugar`
--

DROP TABLE IF EXISTS `gimcana_lugar`;
CREATE TABLE IF NOT EXISTS `gimcana_lugar` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `gimcana_id` bigint UNSIGNED NOT NULL,
  `lugar_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gimcana_lugar_gimcana_id_foreign` (`gimcana_id`),
  KEY `gimcana_lugar_lugar_id_foreign` (`lugar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `gimcana_lugar`
--

INSERT INTO `gimcana_lugar` (`id`, `gimcana_id`, `lugar_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 1, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gimcana_usuario`
--

DROP TABLE IF EXISTS `gimcana_usuario`;
CREATE TABLE IF NOT EXISTS `gimcana_usuario` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `gimcana_id` bigint UNSIGNED NOT NULL,
  `usuario_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gimcana_usuario_gimcana_id_foreign` (`gimcana_id`),
  KEY `gimcana_usuario_usuario_id_foreign` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

DROP TABLE IF EXISTS `grupos`;
CREATE TABLE IF NOT EXISTS `grupos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Grupo A', 'Este es el grupo A', '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(2, 'Grupo B', 'Este es el grupo B', '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(3, 'qweQWE123', 'Grupo para la gimcana 1', '2025-03-29 16:57:15', '2025-03-29 16:57:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint UNSIGNED NOT NULL,
  `accion` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logs_usuario_id_foreign` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugares`
--

DROP TABLE IF EXISTS `lugares`;
CREATE TABLE IF NOT EXISTS `lugares` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `latitud` decimal(10,8) NOT NULL,
  `longitud` decimal(10,8) NOT NULL,
  `descripcion` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `color_marcador` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '#3388ff',
  `creado_por` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lugares_creado_por_foreign` (`creado_por`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `lugares`
--

INSERT INTO `lugares` (`id`, `nombre`, `latitud`, `longitud`, `descripcion`, `color_marcador`, `creado_por`, `created_at`, `updated_at`) VALUES
(1, 'Hospital Universitari de Bellvitge', 41.34440600, 2.10452800, 'Hospital universitario de referencia', '#FF0000', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(2, 'Institut Joan XXIII', 41.34968400, 2.10789400, 'Centro educativo de formación profesional', '#0000FF', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(3, 'Parc de Bellvitge', 41.34814200, 2.11135800, 'Parque público con áreas verdes y zonas de recreo', '#00FF00', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(4, 'Estación de Metro Bellvitge', 41.35071800, 2.11090100, 'Estación de la línea 1 del metro de Barcelona', '#FFA500', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(5, 'Centro Comercial Gran Via 2', 41.35786600, 2.12933600, 'Centro comercial con tiendas, restaurantes y cine', '#800080', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(6, 'GolaGol', 41.35049100, 2.09964110, 'Centro deportivo especializado en fútbol sala.', '#FF6347', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(7, 'Museu de L’Hospitalet', 41.36106850, 2.09723650, 'Museo dedicado a la historia y cultura de L’Hospitalet.', '#FFD700', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(8, 'La Farga', 41.36295930, 2.10461660, 'Centro comercial con tiendas, restaurantes y eventos.', '#4B0082', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(9, 'Sala Salamandra', 41.36002600, 2.10954500, 'Sala de conciertos y discoteca historica.', '#FF4500', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(10, 'Auditori Barradas', 41.36147930, 2.10247930, 'Auditorio para eventos culturales y musicales.', '#4682B4', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(11, 'Parque Can Boixeres', 41.36495280, 2.09637640, 'Parque urbano con amplias áreas verdes cerca de Rambla Just Oliveres.', '#32CD32', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(12, 'Pàdel Top Club', 41.35799630, 2.11235310, 'Club deportivo especializado en pádel cerca del Zoco.', '#8A2BE2', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(13, 'Frankfurt del Centre', 41.36199010, 2.10165850, 'Restaurante especializado en comida rápida y frankfurts ubicado cerca de Rambla Just Oliveres.', '#FF69B4', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(14, 'Escola Canigó', 41.36205260, 2.10076420, 'Centro educativo de primaria y secundaria.', '#1E90FF', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(15, 'Capitolio', 41.35572790, 2.09584460, 'Discoteca Latinoamarecina en Hospitalet.', '#FF8C00', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(16, 'Estadi Municipal de Futbol de L\'Hospitalet', 41.34737600, 2.10235500, 'Estadio municipal para partidos de fútbol y eventos deportivos.', '#FF4500', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(17, 'Gaiper Extreme Padel', 41.35188200, 2.10270000, 'Centro deportivo especializado en pádel.', '#8A2BE2', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(18, 'Mercadona', 41.34692780, 2.10780820, 'Supermercado con productos frescos y de calidad.', '#32CD32', 1, '2025-03-29 16:54:40', '2025-03-29 16:54:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugares_etiquetas`
--

DROP TABLE IF EXISTS `lugares_etiquetas`;
CREATE TABLE IF NOT EXISTS `lugares_etiquetas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `lugar_id` bigint UNSIGNED NOT NULL,
  `etiqueta_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lugares_etiquetas_lugar_id_foreign` (`lugar_id`),
  KEY `lugares_etiquetas_etiqueta_id_foreign` (`etiqueta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugar_etiqueta`
--

DROP TABLE IF EXISTS `lugar_etiqueta`;
CREATE TABLE IF NOT EXISTS `lugar_etiqueta` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `lugar_id` bigint UNSIGNED NOT NULL,
  `etiqueta_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lugar_etiqueta_lugar_id_foreign` (`lugar_id`),
  KEY `lugar_etiqueta_etiqueta_id_foreign` (`etiqueta_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `lugar_etiqueta`
--

INSERT INTO `lugar_etiqueta` (`id`, `lugar_id`, `etiqueta_id`, `created_at`, `updated_at`) VALUES
(1, 1, 7, NULL, NULL),
(2, 2, 2, NULL, NULL),
(3, 3, 3, NULL, NULL),
(4, 4, 4, NULL, NULL),
(5, 5, 5, NULL, NULL),
(6, 5, 6, NULL, NULL),
(7, 6, 8, NULL, NULL),
(8, 7, 1, NULL, NULL),
(9, 8, 5, NULL, NULL),
(10, 9, 6, NULL, NULL),
(11, 10, 1, NULL, NULL),
(12, 11, 3, NULL, NULL),
(13, 12, 8, NULL, NULL),
(14, 13, 9, NULL, NULL),
(15, 14, 2, NULL, NULL),
(16, 15, 6, NULL, NULL),
(17, 16, 8, NULL, NULL),
(18, 17, 8, NULL, NULL),
(19, 18, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_03_18_000000_create_usuarios_table', 1),
(2, '2025_03_18_000001_create_grupos_table', 1),
(3, '2025_03_18_000002_create_usuarios_grupos_table', 1),
(4, '2025_03_18_000003_create_etiquetas_table', 1),
(5, '2025_03_18_000004_create_lugares_table', 1),
(6, '2025_03_18_000005_create_lugares_etiquetas_table', 1),
(7, '2025_03_18_000006_create_favoritos_table', 1),
(8, '2025_03_18_000007_create_puntos_control_table', 1),
(9, '2025_03_18_000008_create_pruebas_table', 1),
(10, '2025_03_18_000009_create_rutas_table', 1),
(11, '2025_03_18_000010_create_progreso_gimcana_table', 1),
(12, '2025_03_18_000011_create_logs_table', 1),
(13, '2025_03_18_000012_create_sessions_table', 1),
(14, '2025_03_18_000013_create_gimcanas_table', 1),
(15, '2025_03_18_000014_create_gimcana_lugar_table', 1),
(16, '2025_03_24_150640_create_lugar_etiqueta_table', 1),
(17, '2025_03_25_162156_create_puntos_usuarios_table', 1),
(18, '2025_03_25_183648_create_gimcana_usuario_table', 1),
(19, '2025_03_25_185412_create_gimcana_grupo_table', 1),
(20, '2025_03_26_185238_add_esta_listo_to_usuarios_grupos_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progreso_gimcana`
--

DROP TABLE IF EXISTS `progreso_gimcana`;
CREATE TABLE IF NOT EXISTS `progreso_gimcana` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint UNSIGNED NOT NULL,
  `punto_control_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `progreso_gimcana_usuario_id_foreign` (`usuario_id`),
  KEY `progreso_gimcana_punto_control_id_foreign` (`punto_control_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pruebas`
--

DROP TABLE IF EXISTS `pruebas`;
CREATE TABLE IF NOT EXISTS `pruebas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `punto_control_id` bigint UNSIGNED NOT NULL,
  `descripcion` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pruebas_punto_control_id_foreign` (`punto_control_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntos_control`
--

DROP TABLE IF EXISTS `puntos_control`;
CREATE TABLE IF NOT EXISTS `puntos_control` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `lugar_id` bigint UNSIGNED NOT NULL,
  `pista` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `puntos_control_lugar_id_foreign` (`lugar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntos_usuarios`
--

DROP TABLE IF EXISTS `puntos_usuarios`;
CREATE TABLE IF NOT EXISTS `puntos_usuarios` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint UNSIGNED NOT NULL,
  `lugar_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `puntos_usuarios_usuario_id_foreign` (`usuario_id`),
  KEY `puntos_usuarios_lugar_id_foreign` (`lugar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutas`
--

DROP TABLE IF EXISTS `rutas`;
CREATE TABLE IF NOT EXISTS `rutas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint UNSIGNED NOT NULL,
  `origen` geometry NOT NULL,
  `destino` geometry NOT NULL,
  `tiempo_estimado` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rutas_usuario_id_foreign` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb3_unicode_ci,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_foreign` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5fZcZfmomfLVXpnrfDR2AjfY3HDODrtsX7nKL1EY', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMUlKbGdDelVCZERDNUZxRFhFQWxOMkliNHhKaXdtRVpDQlpJSmViMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jbGllbnRlL2dpbWNhbmFzLzEvZ3J1cG8tYWN0dWFsIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Nzt9', 1743273510);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rol` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `ubicacion_actual` geometry DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `ubicacion_actual`, `created_at`, `updated_at`) VALUES
(1, 'Alejandro González', 'alejandro@admin.com', '$2y$12$GXCd0ArT/7eXsic.4UWr5.r9qtKJQgXsg0gneWdQp.dv4cL2hMPbG', 'admin', NULL, '2025-03-29 16:54:38', '2025-03-29 16:54:38'),
(2, 'Sergi Masip', 'sergi@admin.com', '$2y$12$GDoEyoEVNYLh156yvMUJHuTmaJ1eC5yLCwZhDqh9FN6y3CTrjlDiS', 'admin', NULL, '2025-03-29 16:54:38', '2025-03-29 16:54:38'),
(3, 'Adrián Vazquez', 'adrian@admin.com', '$2y$12$jTtkzOJsRexLImABIqk4kOLMnLNj/DsDkaA6yWfRA54siAd.W0c/W', 'admin', NULL, '2025-03-29 16:54:38', '2025-03-29 16:54:38'),
(4, 'Àlex Ventura', 'alex@admin.com', '$2y$12$p58wcSsFsKoTtLzSDDHPTeGENQQJzOtd4KUD3OsxKFuDuKVzix3eu', 'admin', NULL, '2025-03-29 16:54:38', '2025-03-29 16:54:38'),
(5, 'María García', 'maria@example.com', '$2y$12$Uqga2l3X8S/89HYiRlPArOY1bFRATVe9EeXtRKuG7ReUijWtFBzYy', 'usuario', NULL, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(6, 'Juan Rodríguez', 'juan@example.com', '$2y$12$xO/hcglE2JF9q2qcX5EGd.CFSPARKBWPdBpaBMQxI7Gs8PJDeSQ8S', 'usuario', NULL, '2025-03-29 16:54:40', '2025-03-29 16:54:40'),
(7, 'Laura Martínez', 'laura@example.com', '$2y$12$09TnOde1Qx2tad3QGFYl7ulS3BPLBO8JJgtXPzX0T5b/F5g1Flvka', 'usuario', 0x000000000101000000a23e6e64c33f014097a4d70baab24440, '2025-03-29 16:54:40', '2025-03-29 16:59:21'),
(8, 'Carlos López', 'carlos@example.com', '$2y$12$8QNkYbZPXsr2VaNRfsG8suwqNvQUGHODS24AG.QlIF17m03n3Zusu', 'usuario', NULL, '2025-03-29 16:54:40', '2025-03-29 16:54:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_grupos`
--

DROP TABLE IF EXISTS `usuarios_grupos`;
CREATE TABLE IF NOT EXISTS `usuarios_grupos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint UNSIGNED NOT NULL,
  `grupo_id` bigint UNSIGNED NOT NULL,
  `esta_listo` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Indica si el usuario está listo para comenzar la gimcana',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarios_grupos_usuario_id_foreign` (`usuario_id`),
  KEY `usuarios_grupos_grupo_id_foreign` (`grupo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios_grupos`
--

INSERT INTO `usuarios_grupos` (`id`, `usuario_id`, `grupo_id`, `esta_listo`, `created_at`, `updated_at`) VALUES
(1, 7, 3, 1, '2025-03-29 16:57:15', '2025-03-29 16:57:20');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_lugar_id_foreign` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `gimcanas`
--
ALTER TABLE `gimcanas`
  ADD CONSTRAINT `gimcanas_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `gimcana_grupo`
--
ALTER TABLE `gimcana_grupo`
  ADD CONSTRAINT `gimcana_grupo_gimcana_id_foreign` FOREIGN KEY (`gimcana_id`) REFERENCES `gimcanas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gimcana_grupo_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `gimcana_lugar`
--
ALTER TABLE `gimcana_lugar`
  ADD CONSTRAINT `gimcana_lugar_gimcana_id_foreign` FOREIGN KEY (`gimcana_id`) REFERENCES `gimcanas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gimcana_lugar_lugar_id_foreign` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `gimcana_usuario`
--
ALTER TABLE `gimcana_usuario`
  ADD CONSTRAINT `gimcana_usuario_gimcana_id_foreign` FOREIGN KEY (`gimcana_id`) REFERENCES `gimcanas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gimcana_usuario_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lugares`
--
ALTER TABLE `lugares`
  ADD CONSTRAINT `lugares_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `lugares_etiquetas`
--
ALTER TABLE `lugares_etiquetas`
  ADD CONSTRAINT `lugares_etiquetas_etiqueta_id_foreign` FOREIGN KEY (`etiqueta_id`) REFERENCES `etiquetas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lugares_etiquetas_lugar_id_foreign` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lugar_etiqueta`
--
ALTER TABLE `lugar_etiqueta`
  ADD CONSTRAINT `lugar_etiqueta_etiqueta_id_foreign` FOREIGN KEY (`etiqueta_id`) REFERENCES `etiquetas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lugar_etiqueta_lugar_id_foreign` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `progreso_gimcana`
--
ALTER TABLE `progreso_gimcana`
  ADD CONSTRAINT `progreso_gimcana_punto_control_id_foreign` FOREIGN KEY (`punto_control_id`) REFERENCES `puntos_control` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `progreso_gimcana_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pruebas`
--
ALTER TABLE `pruebas`
  ADD CONSTRAINT `pruebas_punto_control_id_foreign` FOREIGN KEY (`punto_control_id`) REFERENCES `puntos_control` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `puntos_control`
--
ALTER TABLE `puntos_control`
  ADD CONSTRAINT `puntos_control_lugar_id_foreign` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `puntos_usuarios`
--
ALTER TABLE `puntos_usuarios`
  ADD CONSTRAINT `puntos_usuarios_lugar_id_foreign` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `puntos_usuarios_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `rutas`
--
ALTER TABLE `rutas`
  ADD CONSTRAINT `rutas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios_grupos`
--
ALTER TABLE `usuarios_grupos`
  ADD CONSTRAINT `usuarios_grupos_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarios_grupos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
