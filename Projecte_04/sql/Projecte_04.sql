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
-- Table structure for table `acertijos`
--

DROP TABLE IF EXISTS `acertijos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acertijos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gimcana_id` bigint unsigned NOT NULL,
  `lugar_id` bigint unsigned NOT NULL,
  `texto_acertijo` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `pista` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `latitud_acertijo` decimal(10,8) NOT NULL,
  `longitud_acertijo` decimal(11,8) NOT NULL,
  `orden` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `acertijos_gimcana_id_foreign` (`gimcana_id`),
  KEY `acertijos_lugar_id_foreign` (`lugar_id`),
  CONSTRAINT `acertijos_gimcana_id_foreign` FOREIGN KEY (`gimcana_id`) REFERENCES `gimcanas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `acertijos_lugar_id_foreign` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acertijos`
--

LOCK TABLES `acertijos` WRITE;
/*!40000 ALTER TABLE `acertijos` DISABLE KEYS */;
/*!40000 ALTER TABLE `acertijos` ENABLE KEYS */;
UNLOCK TABLES;

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
INSERT INTO `etiquetas` VALUES (1,'Cultura','fa-landmark','2025-03-30 20:41:48','2025-03-30 20:41:48'),(2,'Educación','fa-graduation-cap','2025-03-30 20:41:48','2025-03-30 20:41:48'),(3,'Parques','fa-tree','2025-03-30 20:41:48','2025-03-30 20:41:48'),(4,'Transporte','fa-train','2025-03-30 20:41:48','2025-03-30 20:41:48'),(5,'Compras','fa-shopping-cart','2025-03-30 20:41:48','2025-03-30 20:41:48'),(6,'Ocio','fa-ticket-alt','2025-03-30 20:41:48','2025-03-30 20:41:48'),(7,'Sanidad','fa-hospital','2025-03-30 20:41:48','2025-03-30 20:41:48'),(8,'Deportes','fa-futbol','2025-03-30 20:41:48','2025-03-30 20:41:48'),(9,'Restaurantes','fa-utensils','2025-03-30 20:41:48','2025-03-30 20:41:48');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gimcana_grupo`
--

LOCK TABLES `gimcana_grupo` WRITE;
/*!40000 ALTER TABLE `gimcana_grupo` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gimcana_lugar`
--

LOCK TABLES `gimcana_lugar` WRITE;
/*!40000 ALTER TABLE `gimcana_lugar` DISABLE KEYS */;
INSERT INTO `gimcana_lugar` VALUES (1,1,1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(2,1,2,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(3,1,3,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(4,1,4,'2025-03-30 20:41:48','2025-03-30 20:41:48');
/*!40000 ALTER TABLE `gimcana_lugar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gimcana_participantes`
--

DROP TABLE IF EXISTS `gimcana_participantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gimcana_participantes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gimcana_id` bigint unsigned NOT NULL,
  `usuario_id` bigint unsigned NOT NULL,
  `fecha_union` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gimcana_participantes_gimcana_id_usuario_id_unique` (`gimcana_id`,`usuario_id`),
  KEY `gimcana_participantes_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `gimcana_participantes_gimcana_id_foreign` FOREIGN KEY (`gimcana_id`) REFERENCES `gimcanas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gimcana_participantes_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gimcana_participantes`
--

LOCK TABLES `gimcana_participantes` WRITE;
/*!40000 ALTER TABLE `gimcana_participantes` DISABLE KEYS */;
/*!40000 ALTER TABLE `gimcana_participantes` ENABLE KEYS */;
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
  `grupo_ganador_id` bigint unsigned DEFAULT NULL,
  `fecha_finalizacion` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gimcanas_creado_por_foreign` (`creado_por`),
  KEY `gimcanas_grupo_ganador_id_foreign` (`grupo_ganador_id`),
  CONSTRAINT `gimcanas_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gimcanas_grupo_ganador_id_foreign` FOREIGN KEY (`grupo_ganador_id`) REFERENCES `grupos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gimcanas`
--

LOCK TABLES `gimcanas` WRITE;
/*!40000 ALTER TABLE `gimcanas` DISABLE KEYS */;
INSERT INTO `gimcanas` VALUES (1,'Gimcana Bellvitge','Recorrido por los lugares emblemáticos de Bellvitge',1,'en_progreso','2025-03-30 20:41:48','2025-03-30 20:41:48',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupos`
--

LOCK TABLES `grupos` WRITE;
/*!40000 ALTER TABLE `grupos` DISABLE KEYS */;
INSERT INTO `grupos` VALUES (1,'Grupo A','Este es el grupo A','2025-03-30 20:41:48','2025-03-30 20:41:48'),(2,'Grupo B','Este es el grupo B','2025-03-30 20:41:48','2025-03-30 20:41:48');
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
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lugar_etiqueta`
--

LOCK TABLES `lugar_etiqueta` WRITE;
/*!40000 ALTER TABLE `lugar_etiqueta` DISABLE KEYS */;
INSERT INTO `lugar_etiqueta` VALUES (1,1,7,NULL,NULL),(2,2,2,NULL,NULL),(3,3,3,NULL,NULL),(4,4,4,NULL,NULL),(5,5,5,NULL,NULL),(6,5,6,NULL,NULL),(7,6,8,NULL,NULL),(8,7,1,NULL,NULL),(9,8,5,NULL,NULL),(10,9,6,NULL,NULL),(11,10,1,NULL,NULL),(12,11,3,NULL,NULL),(13,12,8,NULL,NULL),(14,13,9,NULL,NULL),(15,14,2,NULL,NULL),(16,15,6,NULL,NULL),(17,16,8,NULL,NULL),(18,17,8,NULL,NULL),(19,18,5,NULL,NULL),(20,1,8,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(21,1,9,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(22,2,3,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(23,3,4,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(24,3,5,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(25,4,5,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(26,4,8,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(27,5,8,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(28,6,5,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(29,6,6,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(30,6,9,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(31,7,1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(32,7,5,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(33,8,1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(34,8,2,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(35,8,7,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(36,9,2,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(37,9,9,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(38,10,2,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(39,10,3,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(40,10,6,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(41,11,8,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(42,12,1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(43,12,2,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(44,12,3,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(45,13,7,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(46,13,8,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(47,13,9,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(48,14,4,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(49,14,7,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(50,14,9,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(51,15,1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(52,15,4,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(53,15,7,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(54,16,7,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(55,16,8,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(56,16,9,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(57,17,1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(58,17,7,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(59,17,8,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(60,18,2,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(61,18,8,'2025-03-30 20:41:48','2025-03-30 20:41:48');
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
  CONSTRAINT `lugares_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lugares`
--

LOCK TABLES `lugares` WRITE;
/*!40000 ALTER TABLE `lugares` DISABLE KEYS */;
INSERT INTO `lugares` VALUES (1,'Institut Joan XXIII',41.34968400,2.10789400,'Centro educativo de formación profesional','#0000FF',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(2,'Parc de Bellvitge',41.34814200,2.11135800,'Parque público con áreas verdes y zonas de recreo','#00FF00',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(3,'Hospital Universitari de Bellvitge',41.34440600,2.10452800,'Hospital universitario de referencia','#FF0000',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(4,'Estación de Metro Bellvitge',41.35071800,2.11090100,'Estación de la línea 1 del metro de Barcelona','#FFA500',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(5,'Centro Comercial Gran Via 2',41.35786600,2.12933600,'Centro comercial con tiendas, restaurantes y cine','#800080',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(6,'GolaGol',41.35049100,2.09964110,'Centro deportivo especializado en fútbol sala.','#FF6347',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(7,'Museu de L\'Hospitalet',41.36106850,2.09723650,'Museo dedicado a la historia y cultura de L\'Hospitalet.','#FFD700',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(8,'La Farga',41.36295930,2.10461660,'Centro comercial con tiendas, restaurantes y eventos.','#4B0082',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(9,'Sala Salamandra',41.36002600,2.10954500,'Sala de conciertos y discoteca historica.','#FF4500',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(10,'Auditori Barradas',41.36147930,2.10247930,'Auditorio para eventos culturales y musicales.','#4682B4',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(11,'Parque Can Boixeres',41.36495280,2.09637640,'Parque urbano con amplias áreas verdes cerca de Rambla Just Oliveres.','#32CD32',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(12,'Pàdel Top Club',41.35799630,2.11235310,'Club deportivo especializado en pádel cerca del Zoco.','#8A2BE2',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(13,'Frankfurt del Centre',41.36199010,2.10165850,'Restaurante especializado en comida rápida y frankfurts ubicado cerca de Rambla Just Oliveres.','#FF69B4',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(14,'Escola Canigó',41.36205260,2.10076420,'Centro educativo de primaria y secundaria.','#1E90FF',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(15,'Capitolio',41.35572790,2.09584460,'Discoteca Latinoamarecina en Hospitalet.','#FF8C00',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(16,'Estadi Municipal de Futbol de L\'Hospitalet',41.34737600,2.10235500,'Estadio municipal para partidos de fútbol y eventos deportivos.','#FF4500',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(17,'Gaiper Extreme Padel',41.35188200,2.10270000,'Centro deportivo especializado en pádel.','#8A2BE2',1,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(18,'Mercadona',41.34692780,2.10780820,'Supermercado con productos frescos y de calidad.','#32CD32',1,'2025-03-30 20:41:48','2025-03-30 20:41:48');
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025_03_18_000000_create_usuarios_table',1),(2,'2025_03_18_000001_create_grupos_table',1),(3,'2025_03_18_000002_create_usuarios_grupos_table',1),(4,'2025_03_18_000003_create_etiquetas_table',1),(5,'2025_03_18_000004_create_lugares_table',1),(6,'2025_03_18_000005_create_lugares_etiquetas_table',1),(7,'2025_03_18_000006_create_favoritos_table',1),(8,'2025_03_18_000007_create_puntos_control_table',1),(9,'2025_03_18_000008_create_pruebas_table',1),(10,'2025_03_18_000009_create_rutas_table',1),(11,'2025_03_18_000010_create_progreso_gimcana_table',1),(12,'2025_03_18_000011_create_logs_table',1),(13,'2025_03_18_000012_create_sessions_table',1),(14,'2025_03_18_000013_create_gimcanas_table',1),(15,'2025_03_18_000014_create_gimcana_lugar_table',1),(16,'2025_03_24_150640_create_lugar_etiqueta_table',1),(17,'2025_03_24_193300_create_gimcana_participantes_table',1),(18,'2025_03_24_200631_create_acertijos_table',1),(19,'2025_03_24_202342_create_users_table',1),(20,'2025_03_25_162156_create_puntos_usuarios_table',1),(21,'2025_03_25_183648_create_gimcana_usuario_table',1),(22,'2025_03_25_185412_create_gimcana_grupo_table',1),(23,'2025_03_26_185238_add_esta_listo_to_usuarios_grupos_table',1),(24,'2025_03_29_000000_add_respuesta_to_pruebas_table',1),(25,'2025_03_30_220019_add_grupo_ganador_to_gimcanas_table',1);
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
  `acertijo_actual_id` bigint unsigned DEFAULT NULL,
  `acertijo_resuelto` tinyint(1) NOT NULL DEFAULT '0',
  `pista_revelada` tinyint(1) NOT NULL DEFAULT '0',
  `lugar_encontrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `progreso_gimcana_usuario_id_foreign` (`usuario_id`),
  KEY `progreso_gimcana_punto_control_id_foreign` (`punto_control_id`),
  KEY `progreso_gimcana_acertijo_actual_id_foreign` (`acertijo_actual_id`),
  CONSTRAINT `progreso_gimcana_acertijo_actual_id_foreign` FOREIGN KEY (`acertijo_actual_id`) REFERENCES `acertijos` (`id`) ON DELETE CASCADE,
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
  `respuesta` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pruebas_punto_control_id_foreign` (`punto_control_id`),
  CONSTRAINT `pruebas_punto_control_id_foreign` FOREIGN KEY (`punto_control_id`) REFERENCES `puntos_control` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pruebas`
--

LOCK TABLES `pruebas` WRITE;
/*!40000 ALTER TABLE `pruebas` DISABLE KEYS */;
INSERT INTO `pruebas` VALUES (1,1,'De que color es la puerta del instituto?','blanca,blanco,Blanca,Blanco','2025-03-30 20:41:48','2025-03-30 20:41:48'),(2,2,'Hay un gimnasio al aire libre en el parque?','si,Si,SI,sí,Sí,SÍ','2025-03-30 20:41:48','2025-03-30 20:41:48'),(3,3,'De que color es el letrero del hospital que pone BELLVITGE','rojo,Rojo,ROJO,rojas,Rojas,ROJAS,roja,Roja,ROJA','2025-03-30 20:41:48','2025-03-30 20:41:48'),(4,4,'Que linea de metro es la que corresponde a esa parada','1,L1,l1,linea 1,línea 1,Linea 1,Línea 1','2025-03-30 20:41:48','2025-03-30 20:41:48');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `puntos_control`
--

LOCK TABLES `puntos_control` WRITE;
/*!40000 ALTER TABLE `puntos_control` DISABLE KEYS */;
INSERT INTO `puntos_control` VALUES (1,1,'Encuentra Institut Joan XXIII','2025-03-30 20:41:48','2025-03-30 20:41:48'),(2,2,'Encuentra Parc de Bellvitge','2025-03-30 20:41:48','2025-03-30 20:41:48'),(3,3,'Encuentra Hospital Universitari de Bellvitge','2025-03-30 20:41:48','2025-03-30 20:41:48'),(4,4,'Encuentra Estación de Metro Bellvitge','2025-03-30 20:41:48','2025-03-30 20:41:48');
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
INSERT INTO `sessions` VALUES ('WAsoiOBn0oxX9B6VEZEBcMzp0J5uBI1dzI1uW8Zd',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoidWtUMldwREY0YUsxSUIyRVJad1cwMUVHNkJlQm82SUxHQU5KaTFMdyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2NsaWVudGUvZ2ltY2FuYXMiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0NzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2NsaWVudGUvZ3J1cG9zLzEvbWllbWJyb3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=',1743374525);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rol` enum('admin','usuario') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'usuario',
  `remember_token` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@admin.com','$2y$12$/ZaYKmJLUEN/Ikg4hjJaM.jdU1N1scYQuhikCsGSzyX.ZwRiCbxoe','admin',NULL,'2025-03-30 20:41:45','2025-03-30 20:41:45');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
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
  `ubicacion_actual` json DEFAULT NULL,
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
INSERT INTO `usuarios` VALUES (1,'Alejandro González','alejandro@admin.com','$2y$12$oXHekoR5I8dk20cVBIXpyeMRYAh7l9Uorm33S.xsKC5eiSfZMBtGa','admin',NULL,'2025-03-30 20:41:47','2025-03-30 20:41:47'),(2,'Sergi Masip','sergi@admin.com','$2y$12$MSWqDZokGPcskYWjVm6c5e9YWfBZOUuNU6FH8jIcYdR6R8qycYaee','admin',NULL,'2025-03-30 20:41:47','2025-03-30 20:41:47'),(3,'Adrián Vazquez','adrian@admin.com','$2y$12$zWso37vhhnK2hGWi.7/HFOfRbLe0UDQkgAHBByl7ZqJqWmLdhu/Gi','admin',NULL,'2025-03-30 20:41:47','2025-03-30 20:41:47'),(4,'Àlex Ventura','alex@admin.com','$2y$12$PPZ3zNALle0QWQ1z.XEVg.YHW0qocnj8o/D0KoBm922nvfDy5eSwC','admin',NULL,'2025-03-30 20:41:47','2025-03-30 20:41:47'),(5,'María García','maria@example.com','$2y$12$qRJIMV3WN4qdfa7lJtPpwOdZWRboAWDIGvosMYY1ZqrfRYDZWifiG','usuario',NULL,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(6,'Juan Rodríguez','juan@example.com','$2y$12$ZLLb30ZGXbVG4WVAnJNy9O86JLPfnpmtneyJ2m4UBJFGw7rqq/EV6','usuario',NULL,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(7,'Laura Martínez','laura@example.com','$2y$12$dLYJzvpV0/PAGLbloBHIy.KJijYISHCZ0e6B4hMp52Sdv1GJyBEme','usuario',NULL,'2025-03-30 20:41:48','2025-03-30 20:41:48'),(8,'Carlos López','carlos@example.com','$2y$12$x6oPlGGKkq1Pv8vUTMa8He8ibsSNSwDPVVnB0.25ef1qsIup0FiQ.','usuario',NULL,'2025-03-30 20:41:48','2025-03-30 20:41:48');
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
  `esta_listo` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarios_grupos_usuario_id_foreign` (`usuario_id`),
  KEY `usuarios_grupos_grupo_id_foreign` (`grupo_id`),
  CONSTRAINT `usuarios_grupos_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `usuarios_grupos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_grupos`
--

LOCK TABLES `usuarios_grupos` WRITE;
/*!40000 ALTER TABLE `usuarios_grupos` DISABLE KEYS */;
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

-- Dump completed on 2025-03-31  0:42:58
