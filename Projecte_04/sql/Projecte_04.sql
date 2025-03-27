-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: projecte_04
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `etiquetas`
--

DROP TABLE IF EXISTS `etiquetas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `etiquetas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `icono` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'fa-tag',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `etiquetas_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etiquetas`
--

LOCK TABLES `etiquetas` WRITE;
/*!40000 ALTER TABLE `etiquetas` DISABLE KEYS */;
INSERT INTO `etiquetas` VALUES (1,'Cultura','fa-landmark','2025-03-27 15:59:29','2025-03-27 15:59:29'),(2,'Educación','fa-graduation-cap','2025-03-27 15:59:29','2025-03-27 15:59:29'),(3,'Parques','fa-tree','2025-03-27 15:59:29','2025-03-27 15:59:29'),(4,'Transporte','fa-train','2025-03-27 15:59:29','2025-03-27 15:59:29'),(5,'Compras','fa-shopping-cart','2025-03-27 15:59:29','2025-03-27 15:59:29'),(6,'Ocio','fa-ticket-alt','2025-03-27 15:59:29','2025-03-27 15:59:29'),(7,'Sanidad','fa-hospital','2025-03-27 15:59:29','2025-03-27 15:59:29'),(8,'Deportes','fa-futbol','2025-03-27 15:59:29','2025-03-27 15:59:29'),(9,'Restaurantes','fa-utensils','2025-03-27 15:59:29','2025-03-27 15:59:29');
/*!40000 ALTER TABLE `etiquetas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favoritos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned NOT NULL,
  `lugar_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `favoritos_usuario_id_foreign` (`usuario_id`),
  KEY `favoritos_lugar_id_foreign` (`lugar_id`),
  CONSTRAINT `favoritos_lugar_id_foreign` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favoritos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favoritos`
--

LOCK TABLES `favoritos` WRITE;
/*!40000 ALTER TABLE `favoritos` DISABLE KEYS */;
/*!40000 ALTER TABLE `favoritos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gimcana_grupo`
--

DROP TABLE IF EXISTS `gimcana_grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gimcana_grupo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gimcana_id` bigint unsigned NOT NULL,
  `grupo_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gimcana_grupo_gimcana_id_foreign` (`gimcana_id`),
  KEY `gimcana_grupo_grupo_id_foreign` (`grupo_id`),
  CONSTRAINT `gimcana_grupo_gimcana_id_foreign` FOREIGN KEY (`gimcana_id`) REFERENCES `gimcanas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gimcana_grupo_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gimcana_grupo`
--

LOCK TABLES `gimcana_grupo` WRITE;
/*!40000 ALTER TABLE `gimcana_grupo` DISABLE KEYS */;
INSERT INTO `gimcana_grupo` VALUES (1,1,3,NULL,NULL),(2,1,4,NULL,NULL);
/*!40000 ALTER TABLE `gimcana_grupo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gimcana_lugar`
--

DROP TABLE IF EXISTS `gimcana_lugar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gimcana_lugar` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gimcana_id` bigint unsigned NOT NULL,
  `lugar_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gimcana_lugar_gimcana_id_foreign` (`gimcana_id`),
  KEY `gimcana_lugar_lugar_id_foreign` (`lugar_id`),
  CONSTRAINT `gimcana_lugar_gimcana_id_foreign` FOREIGN KEY (`gimcana_id`) REFERENCES `gimcanas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gimcana_lugar_lugar_id_foreign` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gimcana_lugar`
--

LOCK TABLES `gimcana_lugar` WRITE;
/*!40000 ALTER TABLE `gimcana_lugar` DISABLE KEYS */;
INSERT INTO `gimcana_lugar` VALUES (1,1,1,NULL,NULL),(2,1,2,NULL,NULL),(3,1,3,NULL,NULL);
/*!40000 ALTER TABLE `gimcana_lugar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gimcana_usuario`
--

DROP TABLE IF EXISTS `gimcana_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gimcana_usuario` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gimcana_id` bigint unsigned NOT NULL,
  `usuario_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gimcana_usuario_gimcana_id_foreign` (`gimcana_id`),
  KEY `gimcana_usuario_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `gimcana_usuario_gimcana_id_foreign` FOREIGN KEY (`gimcana_id`) REFERENCES `gimcanas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gimcana_usuario_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gimcana_usuario`
--

LOCK TABLES `gimcana_usuario` WRITE;
/*!40000 ALTER TABLE `gimcana_usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `gimcana_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gimcanas`
--

DROP TABLE IF EXISTS `gimcanas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gimcanas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `creado_por` bigint unsigned NOT NULL,
  `estado` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gimcanas_creado_por_foreign` (`creado_por`),
  CONSTRAINT `gimcanas_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gimcanas`
--

LOCK TABLES `gimcanas` WRITE;
/*!40000 ALTER TABLE `gimcanas` DISABLE KEYS */;
INSERT INTO `gimcanas` VALUES (1,'qweQWE123','qweQWE123',7,'en_progreso','2025-03-27 16:00:20','2025-03-27 16:04:18');
/*!40000 ALTER TABLE `gimcanas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupos`
--

DROP TABLE IF EXISTS `grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupos`
--

LOCK TABLES `grupos` WRITE;
/*!40000 ALTER TABLE `grupos` DISABLE KEYS */;
INSERT INTO `grupos` VALUES (1,'Grupo A','Este es el grupo A','2025-03-27 15:59:29','2025-03-27 15:59:29'),(2,'Grupo B','Este es el grupo B','2025-03-27 15:59:29','2025-03-27 15:59:29'),(3,'qweQWE123','Grupo para la gimcana 1','2025-03-27 16:00:31','2025-03-27 16:00:31'),(4,'qweQWE1231212','Grupo para la gimcana 1','2025-03-27 16:04:12','2025-03-27 16:04:12');
/*!40000 ALTER TABLE `grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned NOT NULL,
  `accion` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logs_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `logs_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lugar_etiqueta`
--

DROP TABLE IF EXISTS `lugar_etiqueta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lugar_etiqueta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lugar_id` bigint unsigned NOT NULL,
  `etiqueta_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lugar_etiqueta_lugar_id_foreign` (`lugar_id`),
  KEY `lugar_etiqueta_etiqueta_id_foreign` (`etiqueta_id`),
  CONSTRAINT `lugar_etiqueta_etiqueta_id_foreign` FOREIGN KEY (`etiqueta_id`) REFERENCES `etiquetas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lugar_etiqueta_lugar_id_foreign` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lugar_etiqueta`
--

LOCK TABLES `lugar_etiqueta` WRITE;
/*!40000 ALTER TABLE `lugar_etiqueta` DISABLE KEYS */;
INSERT INTO `lugar_etiqueta` VALUES (1,1,7,NULL,NULL),(2,2,2,NULL,NULL),(3,3,3,NULL,NULL),(4,4,4,NULL,NULL),(5,5,5,NULL,NULL),(6,5,6,NULL,NULL);
/*!40000 ALTER TABLE `lugar_etiqueta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lugares`
--

DROP TABLE IF EXISTS `lugares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lugares` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `latitud` decimal(10,8) NOT NULL,
  `longitud` decimal(10,8) NOT NULL,
  `descripcion` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `color_marcador` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '#3388ff',
  `creado_por` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lugares_creado_por_foreign` (`creado_por`),
  CONSTRAINT `lugares_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lugares`
--

LOCK TABLES `lugares` WRITE;
/*!40000 ALTER TABLE `lugares` DISABLE KEYS */;
INSERT INTO `lugares` VALUES (1,'Hospital Universitari de Bellvitge',41.34420000,2.10190000,'Hospital universitario de referencia','#FF0000',1,'2025-03-27 15:59:29','2025-03-27 15:59:29'),(2,'Institut Joan XXIII',41.34790000,2.10450000,'Centro educativo de formación profesional','#0000FF',1,'2025-03-27 15:59:29','2025-03-27 15:59:29'),(3,'Parc de Bellvitge',41.34670000,2.10670000,'Parque público con áreas verdes y zonas de recreo','#00FF00',1,'2025-03-27 15:59:29','2025-03-27 15:59:29'),(4,'Estación de Metro Bellvitge',41.36110000,2.11270000,'Estación de la línea 1 del metro de Barcelona','#FFA500',1,'2025-03-27 15:59:29','2025-03-27 15:59:29'),(5,'Centro Comercial Gran Via 2',41.35890000,2.12890000,'Centro comercial con tiendas, restaurantes y cine','#800080',1,'2025-03-27 15:59:29','2025-03-27 15:59:29');
/*!40000 ALTER TABLE `lugares` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lugares_etiquetas`
--

DROP TABLE IF EXISTS `lugares_etiquetas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lugares_etiquetas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lugar_id` bigint unsigned NOT NULL,
  `etiqueta_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lugares_etiquetas_lugar_id_foreign` (`lugar_id`),
  KEY `lugares_etiquetas_etiqueta_id_foreign` (`etiqueta_id`),
  CONSTRAINT `lugares_etiquetas_etiqueta_id_foreign` FOREIGN KEY (`etiqueta_id`) REFERENCES `etiquetas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lugares_etiquetas_lugar_id_foreign` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lugares_etiquetas`
--

LOCK TABLES `lugares_etiquetas` WRITE;
/*!40000 ALTER TABLE `lugares_etiquetas` DISABLE KEYS */;
/*!40000 ALTER TABLE `lugares_etiquetas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025_03_18_000000_create_usuarios_table',1),(2,'2025_03_18_000001_create_grupos_table',1),(3,'2025_03_18_000002_create_usuarios_grupos_table',1),(4,'2025_03_18_000003_create_etiquetas_table',1),(5,'2025_03_18_000004_create_lugares_table',1),(6,'2025_03_18_000005_create_lugares_etiquetas_table',1),(7,'2025_03_18_000006_create_favoritos_table',1),(8,'2025_03_18_000007_create_puntos_control_table',1),(9,'2025_03_18_000008_create_pruebas_table',1),(10,'2025_03_18_000009_create_rutas_table',1),(11,'2025_03_18_000010_create_progreso_gimcana_table',1),(12,'2025_03_18_000011_create_logs_table',1),(13,'2025_03_18_000012_create_sessions_table',1),(14,'2025_03_18_000013_create_gimcanas_table',1),(15,'2025_03_18_000014_create_gimcana_lugar_table',1),(16,'2025_03_24_150640_create_lugar_etiqueta_table',1),(17,'2025_03_25_162156_create_puntos_usuarios_table',1),(18,'2025_03_25_183648_create_gimcana_usuario_table',1),(19,'2025_03_25_185412_create_gimcana_grupo_table',1),(20,'2025_03_26_185238_add_esta_listo_to_usuarios_grupos_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `progreso_gimcana`
--

DROP TABLE IF EXISTS `progreso_gimcana`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `progreso_gimcana` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned NOT NULL,
  `punto_control_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `progreso_gimcana_usuario_id_foreign` (`usuario_id`),
  KEY `progreso_gimcana_punto_control_id_foreign` (`punto_control_id`),
  CONSTRAINT `progreso_gimcana_punto_control_id_foreign` FOREIGN KEY (`punto_control_id`) REFERENCES `puntos_control` (`id`) ON DELETE CASCADE,
  CONSTRAINT `progreso_gimcana_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `progreso_gimcana`
--

LOCK TABLES `progreso_gimcana` WRITE;
/*!40000 ALTER TABLE `progreso_gimcana` DISABLE KEYS */;
/*!40000 ALTER TABLE `progreso_gimcana` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pruebas`
--

DROP TABLE IF EXISTS `pruebas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pruebas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `punto_control_id` bigint unsigned NOT NULL,
  `descripcion` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pruebas_punto_control_id_foreign` (`punto_control_id`),
  CONSTRAINT `pruebas_punto_control_id_foreign` FOREIGN KEY (`punto_control_id`) REFERENCES `puntos_control` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pruebas`
--

LOCK TABLES `pruebas` WRITE;
/*!40000 ALTER TABLE `pruebas` DISABLE KEYS */;
/*!40000 ALTER TABLE `pruebas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `puntos_control`
--

DROP TABLE IF EXISTS `puntos_control`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `puntos_control` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lugar_id` bigint unsigned NOT NULL,
  `pista` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `puntos_control_lugar_id_foreign` (`lugar_id`),
  CONSTRAINT `puntos_control_lugar_id_foreign` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `puntos_control`
--

LOCK TABLES `puntos_control` WRITE;
/*!40000 ALTER TABLE `puntos_control` DISABLE KEYS */;
/*!40000 ALTER TABLE `puntos_control` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `puntos_usuarios`
--

DROP TABLE IF EXISTS `puntos_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `puntos_usuarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned NOT NULL,
  `lugar_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `puntos_usuarios_usuario_id_foreign` (`usuario_id`),
  KEY `puntos_usuarios_lugar_id_foreign` (`lugar_id`),
  CONSTRAINT `puntos_usuarios_lugar_id_foreign` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`) ON DELETE CASCADE,
  CONSTRAINT `puntos_usuarios_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `puntos_usuarios`
--

LOCK TABLES `puntos_usuarios` WRITE;
/*!40000 ALTER TABLE `puntos_usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `puntos_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rutas`
--

DROP TABLE IF EXISTS `rutas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rutas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned NOT NULL,
  `origen` geometry NOT NULL,
  `destino` geometry NOT NULL,
  `tiempo_estimado` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rutas_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `rutas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rutas`
--

LOCK TABLES `rutas` WRITE;
/*!40000 ALTER TABLE `rutas` DISABLE KEYS */;
/*!40000 ALTER TABLE `rutas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb3_unicode_ci,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_foreign` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`),
  CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('8TECScOd41rSQuOpOAKA6s1kvWW5vmZfSh0tU8gs',5,'127.0.0.1','Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMnFuamVDUE1tMUZab1F6NzlDTnp0OGd1MHlNOGVXc3ZkTVlLSU80TSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2NsaWVudGUvZ3J1cG9zLzEvbWllbWJyb3MiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo1MToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2NsaWVudGUvZ2ltY2FuYXMvbnVsbC9sdWdhcmVzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9',1743095062),('L6aoLis2srULKJDv21KONbCVZAzshNLo53kbRaZS',7,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoibTFud2Nlc1o1M1B6QnAxZkFWSU1UaU1TOU9aV0ZGcXBkUkd6eG03WCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL2dpbWNhbmEiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo1MToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2NsaWVudGUvZ2ltY2FuYXMvbnVsbC9sdWdhcmVzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Nzt9',1743095060);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Alejandro González','alejandro@admin.com','$2y$12$pgqfAl45Jl3l0Alzfb.x0OdsSOaXmIXmUa/gKqGLssqIlMAtPatIi','admin',NULL,'2025-03-27 15:59:28','2025-03-27 15:59:28'),(2,'Sergi Masip','sergi@admin.com','$2y$12$U7Ct3bXytVY/vs06Kda3Oe9reLIUPZer4DNPO.J1RksnD9L5PuG9G','admin',NULL,'2025-03-27 15:59:28','2025-03-27 15:59:28'),(3,'Adrián Vazquez','adrian@admin.com','$2y$12$d5dmc4NzTQsrLO7WmUrjMu18R0un.3QjnIm.V.1U2IjIWWvmIOUxa','admin',NULL,'2025-03-27 15:59:28','2025-03-27 15:59:28'),(4,'Àlex Ventura','alex@admin.com','$2y$12$5Kt12urRbC5pC.BVHbn8Xey.oBDA8UWQk8pOluUmpgksMzabADtwO','admin',NULL,'2025-03-27 15:59:28','2025-03-27 15:59:28'),(5,'María García','maria@example.com','$2y$12$PV.9.n1jeo9KBxlZc16UiO/Wn5b5F5pU2Q4T2aekpsMx17TfunbWe','usuario',NULL,'2025-03-27 15:59:29','2025-03-27 15:59:29'),(6,'Juan Rodríguez','juan@example.com','$2y$12$2FE0GDMZZpHouzLNWcVWdOKR3uQKk7Ac44onhJc467wVndg9XrCHu','usuario',NULL,'2025-03-27 15:59:29','2025-03-27 15:59:29'),(7,'Laura Martínez','laura@example.com','$2y$12$ArcASjRaANiH12xOoE12/eXjWgbt4cN08AJ2GNbBPLPB/MgeRQPhe','usuario',NULL,'2025-03-27 15:59:29','2025-03-27 15:59:29'),(8,'Carlos López','carlos@example.com','$2y$12$PysVGB0BaUGrD8zt8Uj2RO8VktvkmqgNfNJFm8.VgROXws/1qNeRm','usuario',NULL,'2025-03-27 15:59:29','2025-03-27 15:59:29');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios_grupos`
--

DROP TABLE IF EXISTS `usuarios_grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_grupos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned NOT NULL,
  `grupo_id` bigint unsigned NOT NULL,
  `esta_listo` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Indica si el usuario está listo para comenzar la gimcana',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarios_grupos_usuario_id_foreign` (`usuario_id`),
  KEY `usuarios_grupos_grupo_id_foreign` (`grupo_id`),
  CONSTRAINT `usuarios_grupos_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `usuarios_grupos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_grupos`
--

LOCK TABLES `usuarios_grupos` WRITE;
/*!40000 ALTER TABLE `usuarios_grupos` DISABLE KEYS */;
INSERT INTO `usuarios_grupos` VALUES (1,5,3,1,'2025-03-27 16:00:31','2025-03-27 16:04:16'),(2,7,4,1,'2025-03-27 16:04:12','2025-03-27 16:04:18');
/*!40000 ALTER TABLE `usuarios_grupos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-27 18:07:43
