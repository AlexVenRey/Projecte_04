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
INSERT INTO `etiquetas` VALUES (1,'Cultura','fa-landmark','2025-03-30 16:27:38','2025-03-30 16:27:38'),(2,'Educación','fa-graduation-cap','2025-03-30 16:27:38','2025-03-30 16:27:38'),(3,'Parques','fa-tree','2025-03-30 16:27:38','2025-03-30 16:27:38'),(4,'Transporte','fa-train','2025-03-30 16:27:38','2025-03-30 16:27:38'),(5,'Compras','fa-shopping-cart','2025-03-30 16:27:38','2025-03-30 16:27:38'),(6,'Ocio','fa-ticket-alt','2025-03-30 16:27:38','2025-03-30 16:27:38'),(7,'Sanidad','fa-hospital','2025-03-30 16:27:38','2025-03-30 16:27:38'),(8,'Deportes','fa-futbol','2025-03-30 16:27:38','2025-03-30 16:27:38'),(9,'Restaurantes','fa-utensils','2025-03-30 16:27:38','2025-03-30 16:27:38');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gimcana_grupo`
--

LOCK TABLES `gimcana_grupo` WRITE;
/*!40000 ALTER TABLE `gimcana_grupo` DISABLE KEYS */;
INSERT INTO `gimcana_grupo` VALUES (1,1,3,'2025-03-30 16:27:38','2025-03-30 16:27:38');
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
INSERT INTO `gimcana_lugar` VALUES (1,1,1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(2,1,2,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(3,1,3,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(4,1,4,'2025-03-30 16:27:38','2025-03-30 16:27:38');
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
INSERT INTO `gimcanas` VALUES (1,'3546','3546',2,'en_progreso','2025-03-30 16:27:38','2025-03-30 16:27:38');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupos`
--

LOCK TABLES `grupos` WRITE;
/*!40000 ALTER TABLE `grupos` DISABLE KEYS */;
INSERT INTO `grupos` VALUES (1,'Grupo A','Este es el grupo A','2025-03-30 16:27:38','2025-03-30 16:27:38'),(2,'Grupo B','Este es el grupo B','2025-03-30 16:27:38','2025-03-30 16:27:38'),(3,'qweQWE123','Grupo para la gimcana 1','2025-03-30 16:27:38','2025-03-30 16:27:38');
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
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lugar_etiqueta`
--

LOCK TABLES `lugar_etiqueta` WRITE;
/*!40000 ALTER TABLE `lugar_etiqueta` DISABLE KEYS */;
INSERT INTO `lugar_etiqueta` VALUES (1,1,7,NULL,NULL),(2,2,2,NULL,NULL),(3,3,3,NULL,NULL),(4,4,4,NULL,NULL),(5,5,5,NULL,NULL),(6,5,6,NULL,NULL),(7,6,8,NULL,NULL),(8,7,1,NULL,NULL),(9,8,5,NULL,NULL),(10,9,6,NULL,NULL),(11,10,1,NULL,NULL),(12,11,3,NULL,NULL),(13,12,8,NULL,NULL),(14,13,9,NULL,NULL),(15,14,2,NULL,NULL),(16,15,6,NULL,NULL),(17,16,8,NULL,NULL),(18,17,8,NULL,NULL),(19,18,5,NULL,NULL),(20,1,7,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(21,2,8,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(22,2,9,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(23,3,8,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(24,4,4,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(25,5,3,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(26,5,4,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(27,5,9,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(28,6,2,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(29,6,8,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(30,7,3,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(31,7,5,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(32,7,7,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(33,8,6,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(34,8,7,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(35,8,8,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(36,9,4,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(37,9,6,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(38,10,1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(39,11,5,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(40,11,9,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(41,12,2,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(42,12,4,'2025-03-30 16:27:39','2025-03-30 16:27:39'),(43,13,5,'2025-03-30 16:27:39','2025-03-30 16:27:39'),(44,13,6,'2025-03-30 16:27:39','2025-03-30 16:27:39'),(45,13,7,'2025-03-30 16:27:39','2025-03-30 16:27:39'),(46,14,5,'2025-03-30 16:27:39','2025-03-30 16:27:39'),(47,14,9,'2025-03-30 16:27:39','2025-03-30 16:27:39'),(48,15,2,'2025-03-30 16:27:39','2025-03-30 16:27:39'),(49,15,7,'2025-03-30 16:27:39','2025-03-30 16:27:39'),(50,16,7,'2025-03-30 16:27:39','2025-03-30 16:27:39'),(51,16,9,'2025-03-30 16:27:39','2025-03-30 16:27:39'),(52,17,3,'2025-03-30 16:27:39','2025-03-30 16:27:39'),(53,17,7,'2025-03-30 16:27:39','2025-03-30 16:27:39'),(54,18,9,'2025-03-30 16:27:39','2025-03-30 16:27:39');
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lugares`
--

LOCK TABLES `lugares` WRITE;
/*!40000 ALTER TABLE `lugares` DISABLE KEYS */;
INSERT INTO `lugares` VALUES (1,'Hospital Universitari de Bellvitge',41.34440600,2.10452800,'Hospital universitario de referencia','#FF0000',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(2,'Institut Joan XXIII',41.34968400,2.10789400,'Centro educativo de formación profesional','#0000FF',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(3,'Parc de Bellvitge',41.34814200,2.11135800,'Parque público con áreas verdes y zonas de recreo','#00FF00',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(4,'Estación de Metro Bellvitge',41.35071800,2.11090100,'Estación de la línea 1 del metro de Barcelona','#FFA500',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(5,'Centro Comercial Gran Via 2',41.35786600,2.12933600,'Centro comercial con tiendas, restaurantes y cine','#800080',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(6,'GolaGol',41.35049100,2.09964110,'Centro deportivo especializado en fútbol sala.','#FF6347',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(7,'Museu de L’Hospitalet',41.36106850,2.09723650,'Museo dedicado a la historia y cultura de L’Hospitalet.','#FFD700',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(8,'La Farga',41.36295930,2.10461660,'Centro comercial con tiendas, restaurantes y eventos.','#4B0082',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(9,'Sala Salamandra',41.36002600,2.10954500,'Sala de conciertos y discoteca historica.','#FF4500',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(10,'Auditori Barradas',41.36147930,2.10247930,'Auditorio para eventos culturales y musicales.','#4682B4',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(11,'Parque Can Boixeres',41.36495280,2.09637640,'Parque urbano con amplias áreas verdes cerca de Rambla Just Oliveres.','#32CD32',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(12,'Pàdel Top Club',41.35799630,2.11235310,'Club deportivo especializado en pádel cerca del Zoco.','#8A2BE2',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(13,'Frankfurt del Centre',41.36199010,2.10165850,'Restaurante especializado en comida rápida y frankfurts ubicado cerca de Rambla Just Oliveres.','#FF69B4',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(14,'Escola Canigó',41.36205260,2.10076420,'Centro educativo de primaria y secundaria.','#1E90FF',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(15,'Capitolio',41.35572790,2.09584460,'Discoteca Latinoamarecina en Hospitalet.','#FF8C00',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(16,'Estadi Municipal de Futbol de L\'Hospitalet',41.34737600,2.10235500,'Estadio municipal para partidos de fútbol y eventos deportivos.','#FF4500',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(17,'Gaiper Extreme Padel',41.35188200,2.10270000,'Centro deportivo especializado en pádel.','#8A2BE2',1,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(18,'Mercadona',41.34692780,2.10780820,'Supermercado con productos frescos y de calidad.','#32CD32',1,'2025-03-30 16:27:38','2025-03-30 16:27:38');
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025_03_18_000000_create_usuarios_table',1),(2,'2025_03_18_000001_create_grupos_table',1),(3,'2025_03_18_000002_create_usuarios_grupos_table',1),(4,'2025_03_18_000003_create_etiquetas_table',1),(5,'2025_03_18_000004_create_lugares_table',1),(6,'2025_03_18_000005_create_lugares_etiquetas_table',1),(7,'2025_03_18_000006_create_favoritos_table',1),(8,'2025_03_18_000007_create_puntos_control_table',1),(9,'2025_03_18_000008_create_pruebas_table',1),(10,'2025_03_18_000009_create_rutas_table',1),(11,'2025_03_18_000010_create_progreso_gimcana_table',1),(12,'2025_03_18_000011_create_logs_table',1),(13,'2025_03_18_000012_create_sessions_table',1),(14,'2025_03_18_000013_create_gimcanas_table',1),(15,'2025_03_18_000014_create_gimcana_lugar_table',1),(16,'2025_03_24_150640_create_lugar_etiqueta_table',1),(17,'2025_03_25_162156_create_puntos_usuarios_table',1),(18,'2025_03_25_183648_create_gimcana_usuario_table',1),(19,'2025_03_25_185412_create_gimcana_grupo_table',1),(20,'2025_03_26_185238_add_esta_listo_to_usuarios_grupos_table',1),(21,'2025_03_29_000000_add_respuesta_to_pruebas_table',1);
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
INSERT INTO `pruebas` VALUES (1,1,'¿Cuál es el código que ves en Hospital Universitari de Bellvitge?','123','2025-03-30 16:27:38','2025-03-30 16:27:38'),(2,2,'¿Cuál es el código que ves en Institut Joan XXIII?','123','2025-03-30 16:27:38','2025-03-30 16:27:38'),(3,3,'¿Cuál es el código que ves en Parc de Bellvitge?','123','2025-03-30 16:27:38','2025-03-30 16:27:38'),(4,4,'¿Cuál es el código que ves en Estación de Metro Bellvitge?','123','2025-03-30 16:27:38','2025-03-30 16:27:38');
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
INSERT INTO `puntos_control` VALUES (1,1,'Encuentra Hospital Universitari de Bellvitge','2025-03-30 16:27:38','2025-03-30 16:27:38'),(2,2,'Encuentra Institut Joan XXIII','2025-03-30 16:27:38','2025-03-30 16:27:38'),(3,3,'Encuentra Parc de Bellvitge','2025-03-30 16:27:38','2025-03-30 16:27:38'),(4,4,'Encuentra Estación de Metro Bellvitge','2025-03-30 16:27:38','2025-03-30 16:27:38');
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
INSERT INTO `usuarios` VALUES (1,'Alejandro González','alejandro@admin.com','$2y$12$tLqsNJubav8J.gja9M7WMeEQl.zXJ3WyLf1umvCnvaqt0nrD4vtVe','admin',NULL,'2025-03-30 16:27:37','2025-03-30 16:27:37'),(2,'Sergi Masip','sergi@admin.com','$2y$12$9sq2mYBi8rVToQra7JHlQusQog83pfYGuuDU3nzbppo8xzYewuIOm','admin',NULL,'2025-03-30 16:27:37','2025-03-30 16:27:37'),(3,'Adrián Vazquez','adrian@admin.com','$2y$12$FNcUohP/9N5kviqBdSFqz.WFplTTFnl7rHmqNnua51RZ79EGDPf7S','admin',NULL,'2025-03-30 16:27:37','2025-03-30 16:27:37'),(4,'Àlex Ventura','alex@admin.com','$2y$12$iH2IohG.avDFAeXSf8jZH.jDE53gp.nUOYaAKF/Mb8A9muI0cnpau','admin',NULL,'2025-03-30 16:27:37','2025-03-30 16:27:37'),(5,'María García','maria@example.com','$2y$12$KDK9hrb1CGJj9oqUWLVx4uZke06QSTrqxmdryLb7QM4Lrnuaiwc/G','usuario',NULL,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(6,'Juan Rodríguez','juan@example.com','$2y$12$UgGFMRsWE4SR1FozVedpVum7NGv2nHKPDaUZB4y0VMEcveKJ4IJ6e','usuario',NULL,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(7,'Laura Martínez','laura@example.com','$2y$12$G1AJgfQGCwrC38bC2Inxd.009Xybo.CjfEQ5KSnj2fuvgcKjvzaf2','usuario',NULL,'2025-03-30 16:27:38','2025-03-30 16:27:38'),(8,'Carlos López','carlos@example.com','$2y$12$VJfPaubThU4BZD/HDjiJa.duWPG6Fz9ZcdwRhSGTGdGGdP85qs9FS','usuario',NULL,'2025-03-30 16:27:38','2025-03-30 16:27:38');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_grupos`
--

LOCK TABLES `usuarios_grupos` WRITE;
/*!40000 ALTER TABLE `usuarios_grupos` DISABLE KEYS */;
INSERT INTO `usuarios_grupos` VALUES (1,7,3,1,'2025-03-30 16:27:38','2025-03-30 16:27:38');
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

-- Dump completed on 2025-03-30 20:28:16
