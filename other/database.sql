-- MySQL dump 10.19  Distrib 10.3.30-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: backend
-- ------------------------------------------------------
-- Server version	10.3.30-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES UTF8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `asset_kabupaten_kotas`
--

DROP TABLE IF EXISTS `asset_kabupaten_kotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_kabupaten_kotas` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_provinsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_kabupaten_kotas`
--

LOCK TABLES `asset_kabupaten_kotas` WRITE;
/*!40000 ALTER TABLE `asset_kabupaten_kotas` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_kabupaten_kotas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_kecamatans`
--

DROP TABLE IF EXISTS `asset_kecamatans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_kecamatans` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kota` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_kecamatans`
--

LOCK TABLES `asset_kecamatans` WRITE;
/*!40000 ALTER TABLE `asset_kecamatans` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_kecamatans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_kelurahans`
--

DROP TABLE IF EXISTS `asset_kelurahans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_kelurahans` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_kelurahans`
--

LOCK TABLES `asset_kelurahans` WRITE;
/*!40000 ALTER TABLE `asset_kelurahans` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_kelurahans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_provinsis`
--

DROP TABLE IF EXISTS `asset_provinsis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_provinsis` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_provinsis`
--

LOCK TABLES `asset_provinsis` WRITE;
/*!40000 ALTER TABLE `asset_provinsis` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_provinsis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_statuses`
--

DROP TABLE IF EXISTS `asset_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_statuses`
--

LOCK TABLES `asset_statuses` WRITE;
/*!40000 ALTER TABLE `asset_statuses` DISABLE KEYS */;
INSERT INTO `asset_statuses` VALUES (1,'ANTRIAN','pesanan'),(2,'PROSES','pesanan'),(3,'PACKING','pesanan'),(4,'SELESAI','pesanan'),(5,'DIBATALKAN','pesanan'),(6,'LUNAS','pembayaran'),(7,'BELUM LUNAS','pembayaran');
/*!40000 ALTER TABLE `asset_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invites`
--

DROP TABLE IF EXISTS `invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idoutlet` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invites`
--

LOCK TABLES `invites` WRITE;
/*!40000 ALTER TABLE `invites` DISABLE KEYS */;
INSERT INTO `invites` VALUES (1,'2GH5MP','db8fccdc-30dd-4ecc-b924-ed5d6d4ce608',1,'2021-11-22 10:09:47','2021-11-22 10:09:47'),(5,'dZUe9Z','201c2e44-4774-4647-939a-ef20956de77d',1,'2021-11-23 00:34:08','2021-11-23 00:34:08'),(11,'HP7Oh7','201c2e44-4774-4647-939a-ef20956de77d',1,'2021-11-23 00:47:26','2021-11-23 00:47:26'),(15,'5kNKQX','201c2e44-4774-4647-939a-ef20956de77d',1,'2021-11-23 00:59:05','2021-11-23 00:59:05'),(20,'NYdOxB','33ce0815-0918-4071-b88d-8ffcdfe11bb1',1,'2021-11-23 21:38:43','2021-11-23 21:38:43'),(28,'tVdc25','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 20:31:51','2021-12-01 20:31:51'),(29,'aV3nbl','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 20:34:11','2021-12-01 20:34:11'),(30,'5tUNlV','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 20:46:49','2021-12-01 20:46:49'),(31,'qtXqSq','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 20:47:58','2021-12-01 20:47:58'),(32,'LfNSd6','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 20:50:07','2021-12-01 20:50:07'),(33,'5tTFQM','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 20:50:15','2021-12-01 20:50:15'),(34,'o7Eret','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 20:59:13','2021-12-01 20:59:13'),(35,'smV3MA','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 20:59:41','2021-12-01 20:59:41'),(36,'gs0ptm','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 20:59:52','2021-12-01 20:59:52'),(37,'MejsnN','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 21:00:09','2021-12-01 21:00:09'),(38,'fj6e0P','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 21:17:14','2021-12-01 21:17:14'),(39,'Mo0ZJ4','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 21:17:30','2021-12-01 21:17:30'),(40,'10YiyU','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de',1,'2021-12-01 21:17:38','2021-12-01 21:17:38'),(41,'HKESKm','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 21:19:55','2021-12-01 21:19:55'),(42,'lLMKBV','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de',1,'2021-12-01 21:20:02','2021-12-01 21:20:02'),(43,'3YC8GQ','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 21:20:23','2021-12-01 21:20:23'),(44,'zejDkE','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 21:22:11','2021-12-01 21:22:11'),(45,'vDUSsl','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de',1,'2021-12-01 21:22:17','2021-12-01 21:22:17'),(46,'t5rXxN','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 21:23:00','2021-12-01 21:23:00'),(47,'zjWUaH','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 21:23:29','2021-12-01 21:23:29'),(48,'PDkAra','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 21:24:21','2021-12-01 21:24:21'),(49,'hp6Tgb','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-01 21:24:52','2021-12-01 21:24:52'),(50,'H5sLwF','989b84d5-81c4-4dce-b068-75c7348e4392',1,'2021-12-01 21:32:03','2021-12-01 21:32:03'),(51,'AnDj85','989b84d5-81c4-4dce-b068-75c7348e4392',1,'2021-12-01 21:33:09','2021-12-01 21:33:09'),(52,'b9pjLM','989b84d5-81c4-4dce-b068-75c7348e4392',1,'2021-12-01 21:59:31','2021-12-01 21:59:31'),(53,'G3gU5l','989b84d5-81c4-4dce-b068-75c7348e4392',1,'2021-12-01 22:01:59','2021-12-01 22:01:59'),(54,'f53Wv5','989b84d5-81c4-4dce-b068-75c7348e4392',1,'2021-12-01 22:05:29','2021-12-01 22:05:29'),(55,'Sia56B','989b84d5-81c4-4dce-b068-75c7348e4392',1,'2021-12-01 22:05:33','2021-12-01 22:05:33'),(57,'3VoyYG','1cb40906-1e4f-49f8-a353-cb07a1f2e085',1,'2021-12-08 01:51:31','2021-12-08 01:51:31'),(58,'TjN5gE','1cb40906-1e4f-49f8-a353-cb07a1f2e085',1,'2021-12-08 01:51:37','2021-12-08 01:51:37'),(59,'w02bvr','1cb40906-1e4f-49f8-a353-cb07a1f2e085',1,'2021-12-08 01:51:43','2021-12-08 01:51:43'),(60,'ajdk8x','1cb40906-1e4f-49f8-a353-cb07a1f2e085',1,'2021-12-08 01:51:52','2021-12-08 01:51:52'),(61,'bfcmcv','1cb40906-1e4f-49f8-a353-cb07a1f2e085',1,'2021-12-08 22:18:26','2021-12-08 22:18:26'),(62,'18OyJZ','989b84d5-81c4-4dce-b068-75c7348e4392',1,'2021-12-08 22:18:32','2021-12-08 22:18:32'),(63,'8icbpn','4720b3f3-6d9e-48bf-b783-a194576652dd',1,'2021-12-08 22:18:35','2021-12-08 22:18:35');
/*!40000 ALTER TABLE `invites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kiloans`
--

DROP TABLE IF EXISTS `kiloans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kiloans` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_layanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL,
  `idwaktu` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idoutlet` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kiloans`
--

LOCK TABLES `kiloans` WRITE;
/*!40000 ALTER TABLE `kiloans` DISABLE KEYS */;
/*!40000 ALTER TABLE `kiloans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2021_11_03_141344_create_waktus_table',1),(6,'2021_11_03_141445_create_satuans_table',1),(7,'2021_11_03_141458_create_outlets_table',1),(8,'2021_11_03_143751_create_kiloans_table',1),(9,'2021_11_03_144856_create_pelanggans_table',1),(10,'2021_11_03_144941_create_pesanans_table',1),(11,'2021_11_05_150324_create_pembayarans_table',1),(12,'2021_11_09_231302_create_invites_table',1),(13,'2021_11_20_112741_create_asset_provinsis_table',1),(14,'2021_11_20_112940_create_asset_kabupaten_kotas_table',1),(15,'2021_11_20_113040_create_asset_kecamatans_table',1),(16,'2021_11_20_153203_create_asset_kelurahans_table',1),(17,'2021_11_21_175125_create_asset_statuses_table',1),(18,'2021_11_21_211922_create_services_table',1),(19,'2021_11_29_215143_create_operasionals_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operasionals`
--

DROP TABLE IF EXISTS `operasionals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operasionals` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` int(11) NOT NULL,
  `kasir` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `outletid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operasionals`
--

LOCK TABLES `operasionals` WRITE;
/*!40000 ALTER TABLE `operasionals` DISABLE KEYS */;
INSERT INTO `operasionals` VALUES ('4cee3225-7199-4052-8a60-67af3f24e8f8','PEMASUKAN','cepat-2-default',40000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-05 09:45:13','2021-12-05 09:45:13'),('296c4ecb-1a15-4905-a546-095770d118b0','PEMASUKAN','cepat-2-default',40000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-05 09:45:24','2021-12-05 09:45:24'),('c79bfd3f-9862-4e38-8602-90c8e08cb0c0','PEMASUKAN','-3-',30000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-05 09:46:40','2021-12-05 09:46:40'),('aef2af02-5557-4a8c-8815-bc961c7c7039','PEMASUKAN','cepat-2-default',40000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-05 09:46:42','2021-12-05 09:46:42'),('7fc32e0f-c7ea-4b4b-a852-f0291d4c7857','PEMASUKAN','cepat-2-default',40000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-05 09:46:45','2021-12-05 09:46:45'),('5a248e17-152a-4797-97e3-5d358fbf809e','PEMASUKAN','cepat-2-default',40000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-05 09:46:49','2021-12-05 09:46:49'),('d59bb4d7-4eaa-4940-be43-a6e2cf267fbf','PEMASUKAN','-3-',30000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-05 09:46:51','2021-12-05 09:46:51'),('43c31e88-161b-4486-8bd4-ffbffea2b98e','PEMASUKAN','cepat-2-default',40000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-05 09:46:53','2021-12-05 09:46:53'),('dbd125ca-dd9d-4bea-b0f3-8a170d6fe381','PEMASUKAN','cepat-2-default',40000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-05 09:46:55','2021-12-05 09:46:55'),('a05d8172-b2d0-4d6f-8728-0161966f95f4','PEMASUKAN','cepat-1-default',20000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-06 21:24:49','2021-12-06 21:24:49'),('978c1bbb-aa71-4752-80f7-c33b92a27294','PEMASUKAN','cepat-20-default',400000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-06 21:24:52','2021-12-06 21:24:52'),('0631d889-5d17-4a74-a879-18d0d4c7c9d6','PEMASUKAN','cepat-3-default',60000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-06 22:49:37','2021-12-06 22:49:37'),('29f5d5cf-3232-4dcd-a36b-e6226618af9d','PEMASUKAN','cepat-1-default',20000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-06 22:49:40','2021-12-06 22:49:40'),('424de940-5b2c-4885-9685-6459bc37859e','PENGELUARAN','beli pewangiz',3000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-07 22:05:25','2021-12-07 22:05:25'),('0d6db66e-f047-4a4a-bbb3-27e1f4c29940','PENGELUARAN','Listrik',200000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-07 22:18:01','2021-12-07 22:18:01'),('bcc94bda-ec54-4d7e-8b21-f4716f8aca89','PENGELUARAN','Listrik',200000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-07 22:18:34','2021-12-07 22:18:34'),('c2b3c25d-1158-4458-a9b5-db356a65cea6','PENGELUARAN','Listrik',200000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-07 22:19:19','2021-12-07 22:19:19'),('60f5506e-f91b-40b7-acf8-e9f932a5b7ea','PEMASUKAN','cuci kering cepat-3-null',30000,'','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','2021-12-08 21:55:02','2021-12-08 21:55:02'),('8bd184e7-9e1c-470a-84b0-294dee1d583c','PENGELUARAN','beli pewangiz',3000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-08 22:32:03','2021-12-08 22:32:03'),('21ca5286-b12a-4553-92ae-c529676762bb','PEMASUKAN','cepat-2-default',40000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-08 22:49:29','2021-12-08 22:49:29'),('56592dae-e817-46e6-a0f8-bbc16d5b7a9d','PEMASUKAN','cepat-2-default',40000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-08 22:49:40','2021-12-08 22:49:40'),('6697e2ca-88df-4072-a534-292140d41979','PEMASUKAN','cepat-2-default',40000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-08 22:50:00','2021-12-08 22:50:00'),('7e3324af-5bae-4d20-a222-37f3e76d8f9d','PEMASUKAN','cepat-2-default',40000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-08 22:50:33','2021-12-08 22:50:33'),('734353a6-7d10-49eb-8e53-c3d4c8a4b826','PEMASUKAN','cepat-2-default',40000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-08 22:50:47','2021-12-08 22:50:47'),('fd53bd04-8e8e-47dc-99ca-83530795a265','PEMASUKAN','cepat-2-default',40000,'','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-08 22:51:38','2021-12-08 22:51:38'),('323b38a1-d10e-4bf8-adbb-c8189234df14','PENGELUARAN','beli pewangiz',3000,'','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','2021-12-09 21:52:31','2021-12-09 21:52:31');
/*!40000 ALTER TABLE `operasionals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `outlets`
--

DROP TABLE IF EXISTS `outlets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outlets` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_outlet` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_outlet` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sosial_media` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outlets`
--

LOCK TABLES `outlets` WRITE;
/*!40000 ALTER TABLE `outlets` DISABLE KEYS */;
INSERT INTO `outlets` VALUES ('db8fccdc-30dd-4ecc-b924-ed5d6d4ce608','developer','pusat',NULL,'jogja','902340982','2021-11-22 10:07:44','2021-11-22 10:07:44'),('96fec1d3-beba-45f1-8822-e8805e24076c','developer cabang 01','cabang','9b95977c-4ea0-4cc1-8373-67fb18051e1b','bantul','8234878963','2021-11-22 10:12:05','2021-11-22 10:12:05'),('33ce0815-0918-4071-b88d-8ffcdfe11bb1','developer01','pusat',NULL,'jogja','902340982','2021-11-22 23:16:20','2021-11-22 23:16:20'),('201c2e44-4774-4647-939a-ef20956de77d','developer 1','cabang','33ce0815-0918-4071-b88d-8ffcdfe11bb1','bantul','@developer_01','2021-11-22 23:31:48','2021-11-22 23:31:48'),('f868f833-da3d-46e4-8c3d-c95814a9bc70','developer2','pusat',NULL,'jogja','902340982','2021-11-23 21:40:35','2021-11-23 21:40:35'),('9a8a8381-0bce-425c-a28d-2ad7ac082d34','developer123','cabang','f868f833-da3d-46e4-8c3d-c95814a9bc70','bantul','@developer_01','2021-11-23 21:40:54','2021-11-23 21:40:54'),('c8849dbb-5e8f-46ce-93a6-f867189253ef','bening','pusat',NULL,'jogja','123907823','2021-11-23 23:06:57','2021-11-23 23:06:57'),('9391de1c-7665-4cdd-8b0e-dab0f4cee507','laundry baru','cabang','c8849dbb-5e8f-46ce-93a6-f867189253ef','bantul','8234878963','2021-11-23 23:07:13','2021-11-23 23:07:13'),('7fa0c01c-4a26-4ae3-9db2-39ed5df5b3e1','laundry','pusat',NULL,'jakarta','902340982','2021-11-23 23:09:56','2021-11-23 23:09:56'),('e8f2a6f6-616b-46ac-9279-b147f82b858e','laundry baru 2','cabang','7fa0c01c-4a26-4ae3-9db2-39ed5df5b3e1','bantul','3123123121','2021-11-23 23:10:31','2021-11-23 23:10:31'),('10f218da-1d3f-4937-8630-ebd423d32c00','laundryadmin123','pusat',NULL,'jakarta','admin','2021-11-24 20:34:55','2021-11-24 20:34:55'),('19a914b2-7d90-4185-bb17-2d73bc9fa03d','laundryadmin123 baru','cabang','10f218da-1d3f-4937-8630-ebd423d32c00','bantul','8234878963','2021-11-24 20:35:28','2021-11-24 20:35:28'),('3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','cabang seyegan','pusat',NULL,'seyegan donk',NULL,'2021-11-30 23:13:31','2021-11-30 23:13:31'),('c4f6d25c-88eb-49be-a1ad-2f6a0d5ad195','cabang seyegan nek iki','cabang','10f218da-1d3f-4937-8630-ebd423d32c00','rt03rw23 seyegan anget',NULL,'2021-11-30 23:20:13','2021-11-30 23:20:13'),('363ba052-7570-4ddc-be63-f1503784e5ab','sfsdad','cabang','10f218da-1d3f-4937-8630-ebd423d32c00','wweqwe',NULL,'2021-11-30 23:20:49','2021-11-30 23:20:49'),('6b62e8be-e474-4734-a5da-1f39b90f1b0f','laundryadmin123 badru','cabang','10f218da-1d3f-4937-8630-ebd423d32c00','bantul','8234878963','2021-11-30 23:22:26','2021-11-30 23:22:26'),('4720b3f3-6d9e-48bf-b783-a194576652dd','wewe','cabang','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','qqweqew',NULL,'2021-11-30 23:23:19','2021-11-30 23:23:19'),('2811d786-42cc-4872-ae3d-b4a80d12e411','seyegan','pusat',NULL,'seyegan donk',NULL,'2021-11-30 23:28:35','2021-11-30 23:28:35'),('e11a5ac6-772a-4584-947a-472e0d0cf4a7','seyegan anget','pusat',NULL,'seyegan gede',NULL,'2021-11-30 23:34:38','2021-11-30 23:34:38'),('c797f9c4-8012-47aa-9696-fb64268ddc1a','seyegan laundry','cabang','e11a5ac6-772a-4584-947a-472e0d0cf4a7','seyegan donk',NULL,'2021-11-30 23:36:42','2021-11-30 23:36:42'),('49cd7b91-4fcf-40a4-8f5a-d267e171c0ba','seyegan baru','pusat',NULL,'seyegan donk',NULL,'2021-11-30 23:37:21','2021-11-30 23:37:21'),('f64404bf-3b1e-4502-bf4e-55cc5901892d','cabang seyegann','cabang','49cd7b91-4fcf-40a4-8f5a-d267e171c0ba','seyegan donk',NULL,'2021-11-30 23:38:45','2021-11-30 23:38:45'),('d056c5db-d919-4719-99dc-776498fae127','cabang godean','cabang','49cd7b91-4fcf-40a4-8f5a-d267e171c0ba','godean seyegan sleman',NULL,'2021-11-30 23:39:49','2021-11-30 23:39:49'),('989b84d5-81c4-4dce-b068-75c7348e4392','seyegan jet laundry','cabang','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','seyegan',NULL,'2021-12-01 21:30:27','2021-12-01 21:30:27'),('3cf45541-e4e1-408c-a118-6736108b8d9f','laundryadmin1s23 badru','cabang','10f218da-1d3f-4937-8630-ebd423d32c00','bantul','8234878963','2021-12-01 21:59:06','2021-12-01 21:59:06'),('1cb40906-1e4f-49f8-a353-cb07a1f2e085','bandung','cabang','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','bandung',NULL,'2021-12-08 01:51:09','2021-12-08 01:51:09');
/*!40000 ALTER TABLE `outlets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pelanggans`
--

DROP TABLE IF EXISTS `pelanggans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pelanggans` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `outletid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pelanggans`
--

LOCK TABLES `pelanggans` WRITE;
/*!40000 ALTER TABLE `pelanggans` DISABLE KEYS */;
INSERT INTO `pelanggans` VALUES ('11c7979a-fc4c-4f07-a0cb-c0d9adeaf722','burhanudin','089652900319','96fec1d3-beba-45f1-8822-e8805e24076c','Kalasan','2021-11-22 10:23:50','2021-11-22 10:25:27'),('7f5212a8-da3f-4eda-bc3f-ddb4e64dc29d','burhan','089652900319','96fec1d3-beba-45f1-8822-e8805e24076c','rt03/rw23 bokong, snoharjo, margokaton, seyegan sleman.','2021-11-22 10:26:19','2021-11-22 10:26:19'),('21f2c087-dc11-483f-9c26-4216567f5c94','nafi','089652900319','9a8a8381-0bce-425c-a28d-2ad7ac082d34','rt03/rw23 bokong, snoharjo, margokaton, seyegan sleman.','2021-11-23 21:43:25','2021-11-23 21:43:25'),('e80c2190-93e1-4e45-ad03-b5203c80b694','Testing developer','288999788848','f868f833-da3d-46e4-8c3d-c95814a9bc70','Senet','2021-11-23 22:40:47','2021-11-23 22:40:47'),('052f48ad-9b1e-4aef-9a5b-e3eafd49273a','nafis','089652900319','f868f833-da3d-46e4-8c3d-c95814a9bc70','rt03/rw23 bokong, snoharjo, margokaton, seyegan sleman.','2021-11-23 22:43:40','2021-11-23 22:43:40'),('91d0854a-e784-4853-80f5-71eef78a4328','Senet','9652900319','f868f833-da3d-46e4-8c3d-c95814a9bc70','rt03/rw23 bokong, snoharjo, margokaton, seyegan sleman.','2021-11-23 22:45:58','2021-11-23 22:45:58'),('f192113f-a005-4206-b8a6-9940b68a204d','Sen3et','23432434243424','f868f833-da3d-46e4-8c3d-c95814a9bc70','rt03/rw23 bokong, snoharjo, margokaton, seyegan sleman.','2021-11-23 22:46:10','2021-11-23 22:46:10'),('7c2bb633-f281-436e-aeb6-3ebd534db536','Testinh','2889997888485584444','f868f833-da3d-46e4-8c3d-c95814a9bc70','Senetjfjfhhfuffi','2021-11-23 22:46:30','2021-11-23 22:46:30'),('b3f7984b-a1be-4405-9df5-a185a4a756d7','Sen3set','23432434243424','f868f833-da3d-46e4-8c3d-c95814a9bc70','senet','2021-11-23 22:46:50','2021-11-23 22:46:50'),('25d49d8f-15c1-4747-9446-a0438086aef8','Namaku namaku','895421900856','f868f833-da3d-46e4-8c3d-c95814a9bc70','RT 03/RW 23','2021-11-23 22:47:31','2021-11-23 22:47:31'),('ac20c916-c978-4fe3-9144-13abb919da8c','Namaku namakus','895421900856','f868f833-da3d-46e4-8c3d-c95814a9bc70','RT 03/RW 23','2021-11-23 22:48:49','2021-11-23 22:48:49'),('f37fea0e-257f-495d-a6ea-d5d54c4c3abd','Namaku namakudc','895421900856','f868f833-da3d-46e4-8c3d-c95814a9bc70','RT 03/RW 23','2021-11-23 22:49:07','2021-11-23 22:49:07'),('7615eeab-4a59-4a2a-b6e3-fd8059a7948f','Namaku namakudcg','895421900856','f868f833-da3d-46e4-8c3d-c95814a9bc70','RT 03/RW 23','2021-11-23 22:49:50','2021-11-23 22:49:50'),('51888552-d615-42a3-bb5b-89b0abced621','Namaku namakudcgy','895421900856','f868f833-da3d-46e4-8c3d-c95814a9bc70','RT 03/RW 23','2021-11-23 22:50:29','2021-11-23 22:50:29'),('b915064b-909b-4065-af84-04b352f5228d','Namaku namakudcgys','895421900856','f868f833-da3d-46e4-8c3d-c95814a9bc70','RT 03/RW 23','2021-11-23 22:54:27','2021-11-23 22:54:27'),('332ec2e2-a507-4a72-90e4-4a586eb771c5','Namaku namakudcgyss','895421900856','f868f833-da3d-46e4-8c3d-c95814a9bc70','RT 03/RW 23','2021-11-23 22:54:50','2021-11-23 22:54:50'),('a64e08e4-34b0-48f2-aab1-9b5eca57d8b6','hanfi','089652900319','19a914b2-7d90-4185-bb17-2d73bc9fa03d','Sleman','2021-11-24 20:42:01','2021-11-24 20:42:01'),('4bf84c1b-6afa-4991-895a-3e284733d15a','Ysgsggs','94949844','19a914b2-7d90-4185-bb17-2d73bc9fa03d','Tstsgsgsgs','2021-11-24 22:24:00','2021-11-24 22:24:00'),('6b93560b-533a-4eb5-8f86-8e293fd2daec','nafie','089652900319','10f218da-1d3f-4937-8630-ebd423d32c00','Sleman','2021-11-25 20:52:34','2021-11-25 20:52:34'),('c78e2a59-70c0-4df3-bbca-a40ff42db5f2','Test','0894964664991','10f218da-1d3f-4937-8630-ebd423d32c00','Bokong','2021-11-28 03:57:45','2021-11-28 03:57:45'),('08d06b5a-e345-4a60-b5ca-01b367ad0e05','Testing pelanggan baru','089452136455801','10f218da-1d3f-4937-8630-ebd423d32c00','Sonoharjo','2021-11-28 05:16:00','2021-11-28 05:16:00'),('d0cae718-c8b6-4b3c-9061-0766294cdc8d','nafiee','0896529030319','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','Sleman','2021-12-01 00:59:11','2021-12-01 00:59:11'),('668342e8-1826-47cd-a874-01c52a4cbd4e','na2fiee','08965290330319','19a914b2-7d90-4185-bb17-2d73bc9fa03d','Sleman','2021-12-01 01:00:37','2021-12-01 01:00:37'),('10a9dd0d-f841-405a-b617-0b758077d831','na2rfiee','089652902330319','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','Sleman','2021-12-01 01:01:20','2021-12-01 01:01:20'),('8d92855d-b3aa-4023-a287-5750c63ac987','Maya','5454946994','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','Bedingin','2021-12-01 01:02:52','2021-12-01 01:02:52'),('38a05e68-52af-4f2e-bc95-1e0e2dbebc3e','Hanafi','0895421900858','19a914b2-7d90-4185-bb17-2d73bc9fa03d','Sonoharjo','2021-12-02 23:11:48','2021-12-02 23:11:48'),('f420f2bd-acca-4043-9526-6a9e3f58b664','Burhann','0896529003197','19a914b2-7d90-4185-bb17-2d73bc9fa03d','Kalasan','2021-12-02 23:15:36','2021-12-02 23:15:36'),('df90c619-2b1a-43d1-857a-2770387f6310','Lee ji','875828804072','19a914b2-7d90-4185-bb17-2d73bc9fa03d','Sleman','2021-12-06 21:41:37','2021-12-06 21:41:37'),('2e3714dc-f3f8-495c-a3e2-9ea186c89751','Pardi','8888','19a914b2-7d90-4185-bb17-2d73bc9fa03d','Hhh','2021-12-06 21:45:48','2021-12-06 21:45:48'),('e498073e-c2dc-4701-b709-aa234fc1cc9d','Tholib','089688024769','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','Bokong','2021-12-10 02:49:03','2021-12-10 02:49:03');
/*!40000 ALTER TABLE `pelanggans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayarans`
--

DROP TABLE IF EXISTS `pembayarans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pembayarans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idpesanan` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metode_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal` int(11) NOT NULL,
  `diskon` int(11) DEFAULT NULL,
  `utang` int(11) DEFAULT NULL,
  `tagihan` int(11) NOT NULL,
  `bayar` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayarans`
--

LOCK TABLES `pembayarans` WRITE;
/*!40000 ALTER TABLE `pembayarans` DISABLE KEYS */;
INSERT INTO `pembayarans` VALUES (1,'4b86f59d-894e-4686-a8c6-15fdb92559bb','lunas','cash',24000,4000,NULL,20000,20000,'2021-11-22 10:44:51','2021-11-22 10:44:51'),(2,'007559c8-0f92-4e01-9708-71a3acecf420','lunas','cash',24000,4000,NULL,20000,20000,'2021-11-22 11:01:45','2021-11-22 11:01:45'),(3,'2df4cda8-390d-4124-be78-2c1673b9f708','utang','cash',24000,4000,NULL,20000,20000,'2021-11-23 22:06:53','2021-11-23 22:06:53'),(4,'f1f6b5ac-55a1-46f6-b3e5-0fe1d7a432c4','lunas','lunas',25000,4000,NULL,30000,20000,'2021-11-25 21:00:33','2021-11-25 21:00:33'),(5,'15c0c2bd-89e3-4b06-a457-5a9d8cd5cb22','lunas','lunas',25000,4000,NULL,30000,20000,'2021-11-28 21:24:38','2021-11-28 21:24:38'),(6,'bda676bd-d9bf-44f6-ba1c-c76559164e2a','lunas','cash',40000,NULL,NULL,40000,40000,'2021-11-29 23:30:59','2021-11-29 23:30:59'),(7,'22f5f2f9-aaa0-44f2-8b4f-82e7111425b4','lunas','cash',40000,NULL,NULL,40000,40000,'2021-11-29 23:31:11','2021-11-29 23:31:11'),(8,'55c868eb-1ec5-41d3-95e2-fe4fb69ae9f5','lunas','cash',40000,NULL,NULL,40000,40000,'2021-11-29 23:31:14','2021-11-29 23:31:14'),(9,'78e2bae1-7774-4540-a534-fc7c1d2c2e86','lunas','cash',40000,NULL,NULL,40000,40000,'2021-11-29 23:33:01','2021-11-29 23:33:01'),(10,'d7d5aeec-8d67-4cc5-a3d8-ff5807caa96b','lunas','lunas',25000,4000,NULL,30000,20000,'2021-12-01 22:59:38','2021-12-01 22:59:38'),(11,'2db6973d-01f8-4b93-b11a-5f0fbcd27f96','lunas','cash',40000,NULL,NULL,40000,40000,'2021-12-01 23:12:29','2021-12-01 23:12:29'),(12,'692853b8-4ee9-44e0-b941-280e759682cc','utang','cash',2000000,NULL,NULL,2000000,1000000,'2021-12-01 23:13:31','2021-12-01 23:13:31'),(13,'63d94322-0498-469e-aec7-eab0c5c6109d','utang','cash',2000000,NULL,NULL,2000000,1000000,'2021-12-01 23:14:38','2021-12-01 23:14:38'),(14,'437deb35-e31c-4e20-8a7f-c2e24645f24d','utang','Cash',400000,NULL,NULL,400000,0,'2021-12-01 23:30:38','2021-12-01 23:30:38'),(15,'ef2efae6-ad0d-42d6-9b45-244677988bd5','lunas','cash',40000,NULL,NULL,40000,40000,'2021-12-02 21:27:16','2021-12-02 21:27:16'),(16,'2c5f6122-3c88-4f18-9017-9ea5e460c03f','lunas','cash',40000,NULL,NULL,40000,40000,'2021-12-02 21:29:17','2021-12-02 21:29:17'),(17,'1fca1844-013d-4976-b3f3-96b230fb6238','utang','cash',40000,NULL,NULL,40000,20000,'2021-12-02 21:34:00','2021-12-02 21:34:00'),(18,'14a425f6-0530-4096-b8c8-914e51c1d4cd','utang','cash',40000,NULL,NULL,40000,1000,'2021-12-02 21:40:39','2021-12-02 21:40:39'),(19,'9e4a53d9-3c9f-4a37-b5b2-de4f5a419be4','utang','cash',40000,NULL,NULL,40000,1000,'2021-12-02 21:42:09','2021-12-02 21:42:09'),(20,'3edca8c7-9c39-4a05-b82e-67856bc4abed','utang','cash',40000,NULL,NULL,40000,1000,'2021-12-02 22:31:04','2021-12-02 22:31:04'),(21,'fa20a202-b7bf-42cd-bd29-539640474b48','utang','cash',25000,4000,20000,30000,20000,'2021-12-02 22:33:02','2021-12-02 22:33:02'),(22,'c38f00c7-ea7c-4b61-ab90-d502fb79e977','utang','cash',25000,4000,20000,30000,20000,'2021-12-02 22:45:41','2021-12-02 22:45:41'),(23,'3a330c34-b033-4c51-a3fb-0deafc89ad7b','lunas','cash',400000,NULL,NULL,400000,400000,'2021-12-02 22:54:20','2021-12-02 22:54:20'),(24,'85869e2a-1d05-460a-9a62-f7e5fde0be31','lunas','cash',400000,NULL,NULL,400000,400000,'2021-12-02 22:56:07','2021-12-02 22:56:07'),(25,'2dda4fc2-a380-4338-a53b-51e339cc7438','lunas','cash',400000,NULL,NULL,400000,400000,'2021-12-02 22:56:41','2021-12-02 22:56:41'),(26,'5757970e-69d8-4db8-82db-4e36c1f751f6','utang','cash',400000,NULL,NULL,400000,40000,'2021-12-02 22:57:46','2021-12-02 22:57:46'),(27,'7334d77f-f2b4-4ada-967e-69196a49981d','utang','cash',400000,NULL,NULL,400000,40000,'2021-12-02 23:01:02','2021-12-02 23:01:02'),(28,'0f2b6946-2d52-4f2f-8c6f-ec57782ab9b2','utang','cash',59000,1000,NULL,60000,40000,'2021-12-02 23:16:44','2021-12-02 23:16:44'),(29,'1db5ae5e-8478-4fb8-92ec-cc2b4e489f31','LUNAS','cash',59000,1000,0,60000,40000,'2021-12-02 23:17:28','2021-12-06 22:39:44'),(30,'40eecf2e-cc42-40bb-ac20-6e306d3b9d8f','LUNAS','cash',19000,1000,0,20000,10000,'2021-12-02 23:41:37','2021-12-06 22:45:40'),(31,'d9cf3e57-7842-4d53-ac8d-ee76366a3daf','lunas','cash',20000,NULL,NULL,20000,20000,'2021-12-03 18:52:16','2021-12-03 18:52:16'),(32,'5f4a48c1-bf61-462b-a439-0e40a55f41c5','utang','cash',40000,NULL,NULL,40000,20000,'2021-12-05 02:28:46','2021-12-05 02:28:46'),(33,'c6fc3c37-00dd-4dae-9e6a-12610315eb3a','lunas','cash',200000,NULL,NULL,200000,200000,'2021-12-05 02:29:42','2021-12-05 02:29:42'),(34,'1df4faa2-1b23-4fc1-9b18-dc1689765084','LUNAS','cash',10000,NULL,0,10000,10000,'2021-12-05 09:30:14','2021-12-09 23:08:47'),(35,'8a81a144-06ac-4198-937e-891eed22fefc','utang','cash',20000,NULL,NULL,20000,10000,'2021-12-05 09:51:22','2021-12-05 09:51:22'),(36,'d2eaa9d2-b9d1-4302-a594-b70aecfc98f0','utang','cash',25000,4000,20000,30000,20000,'2021-12-05 10:15:16','2021-12-05 10:15:16'),(37,'d4beac24-243d-4144-9afa-0283fe2643d7','utang','cash',20000,NULL,NULL,20000,2000,'2021-12-05 10:29:50','2021-12-05 10:29:50'),(38,'31b6b90d-fdcc-4541-aa6e-432a34aa009c','utang','cash',20000,NULL,NULL,20000,2000,'2021-12-05 10:32:26','2021-12-05 10:32:26'),(39,'ce1a479f-2336-4927-a7c7-bb3c89fb111e','utang','cash',20000,NULL,NULL,20000,2000,'2021-12-05 10:32:50','2021-12-05 10:32:50'),(40,'4e75ab28-e6f2-4c87-b4ed-024d7944abbc','utang','cash',20000,NULL,NULL,20000,2000,'2021-12-05 10:35:06','2021-12-05 10:35:06'),(41,'595af248-1ae3-4f26-992a-b2a1d6dcccc9','utang','cash',20000,NULL,NULL,20000,2000,'2021-12-05 10:37:09','2021-12-05 10:37:09'),(42,'8e3a5d88-2769-4c0b-9419-d33386aa2fc5','DP','cash',20000,NULL,NULL,20000,10000,'2021-12-07 21:10:06','2021-12-07 21:10:06'),(43,'fa6e5e36-48bd-4f86-a6c9-3be51430252d','LUNAS','Cash',40000,NULL,0,40000,0,'2021-12-07 21:13:26','2021-12-08 22:54:32'),(44,'906bb93e-e865-40d2-a7cd-6c7883dfb8bd','LUNAS','cash',60000,NULL,NULL,60000,60000,'2021-12-07 22:22:27','2021-12-07 22:22:27'),(45,'de1ce295-0d4e-4231-a0bb-c766f474981d','LUNAS','cash',60000,NULL,NULL,60000,60000,'2021-12-07 22:24:44','2021-12-07 22:24:44'),(46,'a01a6625-aceb-4c70-b43d-6b75e0589b09','LUNAS','Cash',40000,NULL,0,40000,0,'2021-12-08 21:11:32','2021-12-10 22:25:51'),(47,'e0416ecb-d109-47eb-8673-0526bfdd1fb2','BELUM BAYAR','Cash',40000,NULL,NULL,40000,0,'2021-12-08 21:21:53','2021-12-08 21:21:53'),(48,'3e8c90a7-4712-4d74-aa77-410fa7b40212','LUNAS','cash',25000,4000,0,30000,20000,'2021-12-08 21:54:36','2021-12-08 21:54:36'),(49,'ba2da1e3-4390-4743-b06f-065ef77d5cb4','LUNAS','cash',30000,NULL,NULL,30000,30000,'2021-12-10 02:49:24','2021-12-10 02:49:24'),(50,'e18b30f7-5a64-41bb-9bae-db894bc72088','BELUM BAYAR','Cash',60000,NULL,NULL,60000,0,'2021-12-10 22:17:45','2021-12-10 22:17:45'),(51,'b91c28b4-e462-4cbf-b213-6e701f4971db','BELUM BAYAR','Cash',60000,NULL,NULL,60000,0,'2021-12-10 22:18:09','2021-12-10 22:18:09'),(52,'36c90ff7-e121-430c-bd15-67c3ba535ce8','BELUM BAYAR','Cash',60000,NULL,NULL,60000,0,'2021-12-10 22:18:53','2021-12-10 22:18:53'),(53,'7a4fab39-6324-479c-a3a7-b37951fb3a85','DP','cash',100000,NULL,NULL,100000,50000,'2021-12-10 22:41:26','2021-12-10 22:41:26');
/*!40000 ALTER TABLE `pembayarans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','0cc3789445e7d5ab53b3ed9341823b161802a7e5560f2cc70d8c0239150a5f66','[\"*\"]',NULL,'2021-11-22 10:05:57','2021-11-22 10:05:57'),(2,'App\\Models\\User','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','9d955cf2051221508376b21cb405f4dd2c2a4575537e55fc19fcf464aff776f1','[\"*\"]','2021-11-22 10:17:36','2021-11-22 10:06:00','2021-11-22 10:17:36'),(3,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','955a2ff636e7da2d820aa934641984e6a6faa0210164021b6c36673277873ed4','[\"*\"]',NULL,'2021-11-22 10:18:37','2021-11-22 10:18:37'),(4,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','9f5b7eb385ed79edbae9be4172fe05ea91addc9525e8040d641147c32559d045','[\"*\"]',NULL,'2021-11-22 10:18:40','2021-11-22 10:18:40'),(5,'App\\Models\\User','9c00b46f-5149-4a4c-8504-8609d11b2849','9c00b46f-5149-4a4c-8504-8609d11b2849','3ea7b0c24df8ed691ea67d5e45b5ff7d88e4358e8a9da049e330b0f53536480a','[\"*\"]',NULL,'2021-11-22 10:18:59','2021-11-22 10:18:59'),(6,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','4c48c946963147f007a64f0d513614e5a7e611fcfd7d2e8cd1e28d17b21e5eac','[\"*\"]',NULL,'2021-11-22 10:19:05','2021-11-22 10:19:05'),(7,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','c64cff5bb72a8da6e9378acd56aa69864e42305b537fc3dd6c5c1bf48c72d37d','[\"*\"]',NULL,'2021-11-22 10:19:10','2021-11-22 10:19:10'),(8,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','e7037075cf185efddaeae4a1671b1c7f6956528bf42f34dedf1d9ff660395587','[\"*\"]',NULL,'2021-11-22 10:19:11','2021-11-22 10:19:11'),(9,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','6ded483ddc6c411185ebbe0f2739fc33af383476768bb36c85ffc5b385a7655e','[\"*\"]',NULL,'2021-11-22 10:19:13','2021-11-22 10:19:13'),(10,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','b0192ffa464e1135323eb0681f5e288d39a90d62ada570257daabe3caae734f2','[\"*\"]',NULL,'2021-11-22 10:19:15','2021-11-22 10:19:15'),(11,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','8509bcd752dd23e45b01d07183011251e481d390660402cd49fa775b521652f7','[\"*\"]',NULL,'2021-11-22 10:19:16','2021-11-22 10:19:16'),(12,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','c490ecea4f68c02117c2ec4ef3cec9a4486bcdf74be6256a0f6353cc3d70c3d3','[\"*\"]',NULL,'2021-11-22 10:19:18','2021-11-22 10:19:18'),(13,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','cff66b0f8f40788bf98d42f90723e48df3e9f3e04493760396c1fefdc05a25ec','[\"*\"]',NULL,'2021-11-22 10:19:20','2021-11-22 10:19:20'),(14,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','7062593042221ba97c0cae059cd84d83e319cbebca9069f09e3e5d04961e8a5d','[\"*\"]',NULL,'2021-11-22 10:19:22','2021-11-22 10:19:22'),(15,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','0fcf2296d37650a8c93e30c34abce8b99295e771056eafe73f575cd2ba38686f','[\"*\"]','2021-11-22 11:05:20','2021-11-22 10:19:26','2021-11-22 11:05:20'),(16,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','06d63e3fc946537e5b1241a89104fd146098aa4eb40b2f789dbee66a74f95523','[\"*\"]',NULL,'2021-11-22 10:20:06','2021-11-22 10:20:06'),(17,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','14ba87a4c6662f75a53fd8d26a259291ffa6842b3d1f39fe150fcb9a9aad7ca8','[\"*\"]',NULL,'2021-11-22 11:06:21','2021-11-22 11:06:21'),(18,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','dd78f796984206a8384cc3a9e4ccf10f321d38c835227471160bbd146d8dac31','[\"*\"]',NULL,'2021-11-22 20:42:16','2021-11-22 20:42:16'),(19,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','b46a1cbb87840f145529466ccc594200234c63291ebefb194aec92655f288de9','[\"*\"]',NULL,'2021-11-22 20:42:18','2021-11-22 20:42:18'),(20,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','9eec3bdffb5d47d98a8052d0ae5ec1d06cf8bd355feae49c3d940ed95cd180cb','[\"*\"]',NULL,'2021-11-22 20:59:56','2021-11-22 20:59:56'),(21,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','8ba2514fabd9c130ba2bc00da4bb7db001ff538c08bfc3f7731437c850527747','[\"*\"]',NULL,'2021-11-22 21:01:10','2021-11-22 21:01:10'),(22,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','3dbdbc575b1bcc385d9ea258a28133e38640917b4c3cdd591058a2e6b3415f7d','[\"*\"]',NULL,'2021-11-22 21:04:49','2021-11-22 21:04:49'),(23,'App\\Models\\User','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','ce8b49c339639c986a903e5e12f9ac4cd73bbab50cd5a59260ca91de32a3a3a1','[\"*\"]',NULL,'2021-11-22 21:49:57','2021-11-22 21:49:57'),(24,'App\\Models\\User','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','47bfe48100aa87f3290b480677376142349b6bdd0d2ceaaa4f8cf0c7eca4d6b6','[\"*\"]',NULL,'2021-11-22 21:50:56','2021-11-22 21:50:56'),(26,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','73a65237d9c07b88321068a16678fc0ddf023631b9e7e74095bbc5a7038f62cb','[\"*\"]',NULL,'2021-11-22 22:05:00','2021-11-22 22:05:00'),(27,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','5820aa23a8e049bcb1479058f513cf764840beef008222604d4e4e9323fd2ff0','[\"*\"]','2021-11-23 21:38:42','2021-11-22 22:18:02','2021-11-23 21:38:42'),(28,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','3b64a72d1d8ff9f72786cef3df39d06fbc1d85f3235bf0cf507e332867250d89','[\"*\"]',NULL,'2021-11-22 22:19:40','2021-11-22 22:19:40'),(29,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','b88e9de0b2fcccd51d92a25a5279f9aaaf4974f5ffb26cea0f3d679e4475a336','[\"*\"]',NULL,'2021-11-22 22:22:15','2021-11-22 22:22:15'),(30,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','d2f1eaa532898c22f35e3492ce4d170764aa65b5df87b418a2450fbb485d4f5e','[\"*\"]',NULL,'2021-11-22 22:22:19','2021-11-22 22:22:19'),(31,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','0891203ab1b4856240682dd8e844f575258e19b80876399056878c4d6b976570','[\"*\"]','2021-11-23 23:06:57','2021-11-22 22:24:48','2021-11-23 23:06:57'),(32,'App\\Models\\User','19cdfb46-265b-4674-9219-2264de3ad31e','19cdfb46-265b-4674-9219-2264de3ad31e','fcb7a372948a6d50d39e95ed8a6caa4a86cede2990c2deff7ceb4da276416ef9','[\"*\"]',NULL,'2021-11-22 22:33:07','2021-11-22 22:33:07'),(33,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','1b1365adc09eea2382ec984ef236543e3a86c396973f84de4e3208b05e5eb8f0','[\"*\"]',NULL,'2021-11-22 22:34:58','2021-11-22 22:34:58'),(34,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','de3fb2559b86974a63f76f280fe008a954498cfa7299adcc08c5e66a15fdd3dc','[\"*\"]',NULL,'2021-11-22 22:38:08','2021-11-22 22:38:08'),(35,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','a0920fb9426d69695678f0b01d4cf0957b57dba922e033ecb989a693d7c73234','[\"*\"]',NULL,'2021-11-22 22:51:13','2021-11-22 22:51:13'),(36,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','adca2202257a1b57dd12249341eb9ba4af3e5d721d83d510a3dffaa9901a7c8f','[\"*\"]',NULL,'2021-11-22 22:57:24','2021-11-22 22:57:24'),(37,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','3a9cb59c92e16953982c6bb84a2494f5c1eafd814272431d0783ae2518e7c8ba','[\"*\"]',NULL,'2021-11-22 23:03:14','2021-11-22 23:03:14'),(38,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','5a989e8751ae97ae3b7c5c29aee7154e948f61106d4cb999b9fbbca374b2503d','[\"*\"]',NULL,'2021-11-22 23:09:55','2021-11-22 23:09:55'),(39,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','cdf4ef9d74b54775c4b5c9ff296926fd24795df5b4c2b45c03b110121dcb1611','[\"*\"]',NULL,'2021-11-22 23:15:26','2021-11-22 23:15:26'),(40,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','7d77773627d680fc82c6cc75c2c050e87e13b5b41d412ca5f488a59e488e6931','[\"*\"]',NULL,'2021-11-22 23:27:48','2021-11-22 23:27:48'),(41,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','e7cd4a5755cf80d0a191bfdffeba49a0b258d8ebe2541f5846568a0a295eecf2','[\"*\"]',NULL,'2021-11-22 23:29:23','2021-11-22 23:29:23'),(42,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','7e76b78420921536944c961686b79521d77c250bfdc04139cc0bf6493d8d7797','[\"*\"]',NULL,'2021-11-22 23:30:34','2021-11-22 23:30:34'),(43,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','3e1ddf21051bdee8e23a10f93010ad50bc83aea2e61956fa2ae2404883b7fe6b','[\"*\"]',NULL,'2021-11-23 00:26:50','2021-11-23 00:26:50'),(44,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','fbce0b507c54f3fb6bd66dcc4445358268fb4d7f54f0e2f62ae26d305a516905','[\"*\"]',NULL,'2021-11-23 00:28:08','2021-11-23 00:28:08'),(45,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','c926dd4db63433f2e22b09038c704b7e0d75bfe6bca42e657767e840476f022d','[\"*\"]','2021-11-28 03:56:22','2021-11-23 15:45:48','2021-11-28 03:56:22'),(46,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','3b75c8e103c4784190d1ddbbbf27ea042dc9114be70045d4af9ad56614769d7f','[\"*\"]',NULL,'2021-11-23 15:48:25','2021-11-23 15:48:25'),(47,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','39a767250b248962a60620d1e0c75eb7def45745845ad720b23b8547bc5053a3','[\"*\"]','2021-11-23 22:57:22','2021-11-23 21:19:03','2021-11-23 22:57:22'),(48,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','731f0c7694381383e3ab36c2c84f1bc6348846af93e10687e39745bc00618cc0','[\"*\"]',NULL,'2021-11-23 21:37:55','2021-11-23 21:37:55'),(49,'App\\Models\\User','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','f7c38418f261473510c6506e79fcbb3aeefc7caf60106d7ab68b0ba079d562d4','[\"*\"]',NULL,'2021-11-23 21:38:05','2021-11-23 21:38:05'),(50,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','341c6b8044a07b34005c4876faed5b43165ebabccace89337c7346c14b38b6a3','[\"*\"]','2021-11-23 21:42:37','2021-11-23 21:38:15','2021-11-23 21:42:37'),(51,'App\\Models\\User','a408932b-6337-4d8e-96a3-c95db0d136a5','a408932b-6337-4d8e-96a3-c95db0d136a5','8be29f2aa74cab2229b5889965b8a78bb0e005f722b420ee6514b43f1c2cb62d','[\"*\"]','2021-11-23 22:50:46','2021-11-23 21:41:44','2021-11-23 22:50:46'),(52,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','b6c4a5295cb5c16b9927803a16506cd34747dbcf17a076f4a355ef24040e916e','[\"*\"]','2021-11-23 22:08:49','2021-11-23 21:47:46','2021-11-23 22:08:49'),(53,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','7122ea812eaffd0905ba6ec7ae36642fefcb6326889098eed142b1580c0749c0','[\"*\"]',NULL,'2021-11-23 21:53:28','2021-11-23 21:53:28'),(54,'App\\Models\\User','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','c459b1c7028a8228ed5ead4283c6a38265f9a24311734db990a25f7debf44881','[\"*\"]',NULL,'2021-11-23 21:53:58','2021-11-23 21:53:58'),(55,'App\\Models\\User','a408932b-6337-4d8e-96a3-c95db0d136a5','a408932b-6337-4d8e-96a3-c95db0d136a5','a12cb7a73c319dd37e7bcc762558315d775d1dbe8790cbf1cb23d3984f8a14df','[\"*\"]',NULL,'2021-11-23 22:11:23','2021-11-23 22:11:23'),(56,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','061fac18fa61106c6a2b523be1ae2768311f0d4a6b4d85787a90f8c4015a372d','[\"*\"]',NULL,'2021-11-23 23:00:07','2021-11-23 23:00:07'),(57,'App\\Models\\User','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','79a0a398aac5fc885a8b58d64114d56eaceb8a91db867948497d92bff38402b9','[\"*\"]','2021-11-23 23:02:22','2021-11-23 23:00:28','2021-11-23 23:02:22'),(58,'App\\Models\\User','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','08cc2d8ab56e09019a70ed89b0cef9a4f55873cd3cdd186eec954f8b427602b1','[\"*\"]','2021-11-23 23:39:44','2021-11-23 23:04:20','2021-11-23 23:39:44'),(59,'App\\Models\\User','a408932b-6337-4d8e-96a3-c95db0d136a5','a408932b-6337-4d8e-96a3-c95db0d136a5','b3884b9d3aa2af30ec4801ded6fb422d3dd21ebfe707048d7f3c418e4ea7c173','[\"*\"]','2021-11-23 23:39:27','2021-11-23 23:06:27','2021-11-23 23:39:27'),(60,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','9bd4f4e7e8df55c8367dd3cc28cc73ff135ac4c1b14cdcfe34793a043de9ff49','[\"*\"]',NULL,'2021-11-23 23:07:57','2021-11-23 23:07:57'),(61,'App\\Models\\User','64f63faf-3d40-454a-bd8b-943886e30af4','64f63faf-3d40-454a-bd8b-943886e30af4','ef6022e6170a7ac2d72be0c1a84a73980231cc680f788b9f9326d12cb1ca2c00','[\"*\"]','2021-11-23 23:36:47','2021-11-23 23:12:27','2021-11-23 23:36:47'),(62,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','4ea9f7a75a69b5845e914b685d8d1d3d7cfb3b72b50cabcade4216b2ce750b4c','[\"*\"]','2021-11-23 23:31:32','2021-11-23 23:30:50','2021-11-23 23:31:32'),(63,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','0303d634a3b02a9b56944df57f5e7f319859b42f13a56bfb4ead23c874e2973c','[\"*\"]','2021-11-24 21:57:40','2021-11-24 20:33:18','2021-11-24 21:57:40'),(64,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','f55f5633e4206414c42d8cad40e1778b871809d0f1bbcd29d3a6429463a23c71','[\"*\"]','2021-11-28 22:55:09','2021-11-24 20:37:53','2021-11-28 22:55:09'),(65,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','f28f880e1dd73d54a01edb0e1ee08b5708bab6cd2bb51d17fe9e32707a751c7f','[\"*\"]',NULL,'2021-11-24 21:25:52','2021-11-24 21:25:52'),(66,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','b096b13bb860bdd3ac953e257c1633fc05243123708285d3ceea0f0592a35e10','[\"*\"]',NULL,'2021-11-24 21:59:07','2021-11-24 21:59:07'),(67,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','cba3886550e098be9f8702dac634e557d2d5824538020da8d1f45adb0cd857d9','[\"*\"]',NULL,'2021-11-24 21:59:44','2021-11-24 21:59:44'),(68,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','a3077a5d1541102632377ae90b49672dc27fc361e91ec09ba89d2a72bca1d13c','[\"*\"]','2021-12-04 23:42:04','2021-11-24 22:21:55','2021-12-04 23:42:04'),(69,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','39db85b5df2cb5762dfc2b02bd1a658a2e8ac144b3824404da625b357d88411f','[\"*\"]','2021-12-03 23:22:57','2021-11-25 20:34:22','2021-12-03 23:22:57'),(70,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','1c00a925ef4f9920d02042c5a98614176a95b3d781569c3ed6a55a67811ab4bb','[\"*\"]',NULL,'2021-11-25 20:40:06','2021-11-25 20:40:06'),(71,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','cf0c38b0a62d6b67bb55dfa1e4ea35fd8412d738329fba124277205dc1beed10','[\"*\"]','2021-11-28 20:29:00','2021-11-25 20:40:17','2021-11-28 20:29:00'),(72,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','9b35af97126b46c22005a022ba61fc1a4dadc2b291005e32823732ffeb018d24','[\"*\"]',NULL,'2021-11-25 21:00:11','2021-11-25 21:00:11'),(73,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','18d8765db04b5a3374f24c8f36c267db255893d93c64fd1e675dbc033b3dde40','[\"*\"]',NULL,'2021-11-26 16:55:39','2021-11-26 16:55:39'),(74,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','06369eedd5f7ae00bb831cfc4e9154ae8415aec756d1c151365d73ad53671075','[\"*\"]',NULL,'2021-11-26 16:55:41','2021-11-26 16:55:41'),(75,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','eb7510ac0660a02c83d1161e9d6832501f4951eb8aef08f1ead0f475dde021d5','[\"*\"]',NULL,'2021-11-26 16:55:49','2021-11-26 16:55:49'),(76,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','422af3a76d52c6faaaaf0b675c16b9354345babf2ae386552fdaf5ab34d1ddc2','[\"*\"]',NULL,'2021-11-26 17:01:43','2021-11-26 17:01:43'),(77,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','0dfff8973c8d692bd564b90f601f5d0b5994381bf015caee6b940f98943abe03','[\"*\"]',NULL,'2021-11-26 17:03:21','2021-11-26 17:03:21'),(78,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','f3b63f3d287b35c622d4f32db054c1376041974951c7598116f3a832f5668978','[\"*\"]',NULL,'2021-11-26 17:04:37','2021-11-26 17:04:37'),(79,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','afaba9139b2afcf636bdc511704082e2578ac12fc546cc5d317ca12bc237b253','[\"*\"]',NULL,'2021-11-26 20:35:42','2021-11-26 20:35:42'),(80,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','5d296570d70cff4f4a808597f44c5ce37ff2843dbb1c9e46fb94a20045be0fa2','[\"*\"]',NULL,'2021-11-26 20:38:06','2021-11-26 20:38:06'),(81,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','d5f21a06ad8e99656418c7ce36863da6b80a332c76ffdb3058e8e496ee06a044','[\"*\"]',NULL,'2021-11-26 20:39:20','2021-11-26 20:39:20'),(82,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','f22e7c704f2e40fbd7dd4a87a6954d7b6f40a0d6f29b79258d4cce625a3c7745','[\"*\"]','2021-11-26 20:40:01','2021-11-26 20:39:59','2021-11-26 20:40:01'),(83,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','54490b41803241f330beb87abcbf4b22b93acd10590047a8cc2a093c4b311a74','[\"*\"]','2021-11-26 23:46:56','2021-11-26 20:52:03','2021-11-26 23:46:56'),(84,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','0a5cefaf6947a711e6e9088b3a6ca12104c80fc004941def6d308f28a45365b0','[\"*\"]','2021-11-28 20:38:40','2021-11-26 23:50:06','2021-11-28 20:38:40'),(85,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','7fcc3b8d41eb061efe3b3e986d34569a98218f60cb92d747e733312dd09060c6','[\"*\"]','2021-12-02 23:38:53','2021-11-28 03:57:00','2021-12-02 23:38:53'),(86,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','6f35a8d8ce9fd0a23e61a4642ec2ad007e80866577e448ad1b7e560ee0956356','[\"*\"]','2021-11-28 23:00:46','2021-11-28 20:29:21','2021-11-28 23:00:46'),(87,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','586f957bf34c351e7d59629bc5a6b6a47be0c09cf7d5c344377b4d65a49cdeec','[\"*\"]','2021-11-28 20:56:25','2021-11-28 20:36:00','2021-11-28 20:56:25'),(88,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','4efbc30c6a63e601462fcaad74b0d63fa2d2804354ce74faac4b517ce60f131a','[\"*\"]','2021-11-28 21:58:54','2021-11-28 20:39:06','2021-11-28 21:58:54'),(89,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','4424bf4c321f854495ddef8d4a2a07f52361b35c1921547b4144b6e77d48ef68','[\"*\"]','2021-11-28 22:09:45','2021-11-28 22:09:43','2021-11-28 22:09:45'),(90,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','379282e2986a51dce3671aa303bd9190ca87be918b65c50c8b9a0163a640b02c','[\"*\"]','2021-11-28 23:28:03','2021-11-28 22:16:43','2021-11-28 23:28:03'),(91,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','b2da1285276ae9214935e239f176bee111ed6a26f925e87da7f62c98c809a962','[\"*\"]','2021-12-08 23:12:38','2021-11-28 23:16:07','2021-12-08 23:12:38'),(92,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','45c88a977cc84ca38ef87c8be799ecab8ee75485329a64ef4e5a3f5405932fd4','[\"*\"]','2021-11-30 23:20:49','2021-11-28 23:28:51','2021-11-30 23:20:49'),(93,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','34b2f08aca51e4c9256b00dcce4503ac63e38b3302517fe1e3347d362f5078a1','[\"*\"]','2021-11-30 23:24:02','2021-11-30 23:23:05','2021-11-30 23:24:02'),(94,'App\\Models\\User','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','d6fa232d7742a2a2457ce35fcb39701a6939b5f27754e37f0b5290953d4ebde4','[\"*\"]','2021-11-30 23:24:58','2021-11-30 23:24:56','2021-11-30 23:24:58'),(95,'App\\Models\\User','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','d7925659c328b2d74873c1234c7fd9f7912c60152c5c5722d618a6312c982ceb','[\"*\"]','2021-11-30 23:34:38','2021-11-30 23:25:12','2021-11-30 23:34:38'),(96,'App\\Models\\User','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','173fc88eb48059d18fe58d59f33c81664a89c10980cbb58478ed885cc8c0bd91','[\"*\"]','2021-11-30 23:27:26','2021-11-30 23:27:07','2021-11-30 23:27:26'),(97,'App\\Models\\User','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','a32e2ed9dcca87152b039c056f823548dc8a9e2404c5a35aadc1d86d4ea58047','[\"*\"]','2021-11-30 23:37:21','2021-11-30 23:35:55','2021-11-30 23:37:21'),(98,'App\\Models\\User','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','a7bd3cfa9fc857579e8866493628e7bb5114300842ae4a126bf07153ae43619a','[\"*\"]','2021-11-30 23:42:14','2021-11-30 23:37:56','2021-11-30 23:42:14'),(99,'App\\Models\\User','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','179ee69627bbcc28a218e7491290301e1a056e6d54cdcb0733f3882da30d936b','[\"*\"]','2021-12-07 22:55:11','2021-11-30 23:42:36','2021-12-07 22:55:11'),(100,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','5e8ef10d0792dd587b92b909db3c76811bc5df537e651d162b9000250a6373d4','[\"*\"]','2021-12-08 22:59:38','2021-12-01 01:00:14','2021-12-08 22:59:38'),(101,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','bc4a83e6e7afc640ecbb2ae505906ed2a15088bbd2782a5fc17f7778460e1c2b','[\"*\"]','2021-12-01 22:28:40','2021-12-01 02:06:08','2021-12-01 22:28:40'),(102,'App\\Models\\User','b35734f2-efeb-4665-b1a5-c017086691e2','b35734f2-efeb-4665-b1a5-c017086691e2','aa076699f664a81fa6f3979d206bc7e55f7274e5fc5e8e4379b8ea770996c7a5','[\"*\"]','2021-12-01 20:18:39','2021-12-01 20:18:23','2021-12-01 20:18:39'),(103,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','ab5bc0e87ba86def149ee6db492a6d0f0d33523d0f94443eee59a8562626643e','[\"*\"]','2021-12-01 21:52:47','2021-12-01 20:23:56','2021-12-01 21:52:47'),(104,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','5acb370b2c06176dd7f943704f42a1a383d0c392049fdf84b9c99e336a5a5f5f','[\"*\"]','2021-12-01 22:22:58','2021-12-01 21:53:17','2021-12-01 22:22:58'),(105,'App\\Models\\User','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','716c072247c2cdb18053f166ff0d9226de9c7c88671c5952b146600b73f0441f','[\"*\"]','2021-12-01 22:23:43','2021-12-01 22:23:41','2021-12-01 22:23:43'),(106,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','e4884e98a0d6079f3455241474099449c7c58602c6ebea146a642450cfde0795','[\"*\"]','2021-12-01 22:33:40','2021-12-01 22:24:55','2021-12-01 22:33:40'),(107,'App\\Models\\User','b35734f2-efeb-4665-b1a5-c017086691e2','b35734f2-efeb-4665-b1a5-c017086691e2','5026030b5b020e4294be0d1ba4fb8f58da20f53decf7e1d6b1d1f5d31d49153b','[\"*\"]',NULL,'2021-12-01 22:25:34','2021-12-01 22:25:34'),(108,'App\\Models\\User','b35734f2-efeb-4665-b1a5-c017086691e2','b35734f2-efeb-4665-b1a5-c017086691e2','459a386dbbd999707e326176dc8373ca1bad6295f071e1d4dbed253b4ece0dd6','[\"*\"]',NULL,'2021-12-01 22:25:40','2021-12-01 22:25:40'),(109,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','2bcb1413a2d9a0f8d846794641c240b0c8a7fc43b8041ffc21fe469b0f94bcc1','[\"*\"]','2021-12-02 23:49:29','2021-12-02 23:39:13','2021-12-02 23:49:29'),(110,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','0fd753b841476fe0ed47cbdd172484aa20ca54f6f7b9c5f4fc50f16ab6396c72','[\"*\"]',NULL,'2021-12-03 00:02:04','2021-12-03 00:02:04'),(111,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','ca662f9e5087b173724b3cf849d5c1ee600ca032629f1bc6548ac44e9ed46867','[\"*\"]','2021-12-05 09:29:08','2021-12-03 00:02:40','2021-12-05 09:29:08'),(112,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','1444569c777fd7983c066ff261ac3f4a40c2b8b8f30127c1cd7134f8c298acb4','[\"*\"]','2021-12-10 22:42:18','2021-12-05 00:06:58','2021-12-10 22:42:18'),(113,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','f32f16ef9ab6dded12d213998c195210da96eb819a150b6096b978db45a5a168','[\"*\"]','2021-12-10 02:55:45','2021-12-05 09:29:26','2021-12-10 02:55:45'),(114,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','353fe2ddc56aaee53488b9f4c48f556a78410b4764b16f65147a1610c9724627','[\"*\"]','2021-12-06 22:37:04','2021-12-05 10:14:59','2021-12-06 22:37:04'),(115,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','a21590bd7bb255f929d8bdb1123be666fbd7774f502c8d437d009d2d876b6ddc','[\"*\"]','2021-12-07 20:03:32','2021-12-06 21:32:22','2021-12-07 20:03:32'),(116,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','3ba9d92bd5a72fe54e87ff122299d88bacf5f25fbd7936dcd9ea19f743061831','[\"*\"]','2021-12-07 22:55:57','2021-12-07 22:55:20','2021-12-07 22:55:57'),(117,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','0d147b08807c1d8e1d2d82f4224dd5a685278d151fb7477df218a814b80400ca','[\"*\"]','2021-12-08 01:52:31','2021-12-07 23:21:09','2021-12-08 01:52:31'),(118,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','9cd3d9513d1d6aba5790f8cfc90f806d5d590a0e5b39f05b934984b317c0541d','[\"*\"]','2021-12-08 14:23:51','2021-12-08 14:23:46','2021-12-08 14:23:51'),(119,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','a2ecca9f9cc7caed6138c2e51ed9450411d8b5592869685a2988062cc6d1f9e8','[\"*\"]','2021-12-08 14:39:22','2021-12-08 14:30:52','2021-12-08 14:39:22'),(120,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','20d5caeeeac1ae19bae81ec4f6d0be43dd77b7c980efbe55eda6a19d50feffc2','[\"*\"]','2021-12-08 14:53:29','2021-12-08 14:48:35','2021-12-08 14:53:29'),(121,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','6d255dc99c4d33d742f9c635fe205821b754b804bc962335968d2680353a7a0e','[\"*\"]',NULL,'2021-12-08 14:54:37','2021-12-08 14:54:37'),(122,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','b78091b53ea428125ba82b1e915ae7368e245d0f3b83b3d8585caa9c436a77dc','[\"*\"]','2021-12-10 00:18:35','2021-12-08 14:55:08','2021-12-10 00:18:35'),(123,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','703b677c500984f6bfce627fbef32fda1260c791c5b60b64ea2215fbf28d1f87','[\"*\"]','2021-12-08 20:07:56','2021-12-08 14:55:25','2021-12-08 20:07:56'),(124,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','3ffce30f145c387b8df546acdfb9d7c603870e3f3449cc58f2083b3abf9502f9','[\"*\"]','2021-12-08 20:08:19','2021-12-08 20:08:18','2021-12-08 20:08:19'),(125,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','c062cbd48166de9e9b7260d073bb779e53f60395f01f60ac4c50cabfe29181e0','[\"*\"]','2021-12-08 22:19:57','2021-12-08 21:07:24','2021-12-08 22:19:57'),(126,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','68dc96b60cb144d45492f54dea1362dd4bf7d9c9b79bfd26f6e072907a43c94e','[\"*\"]','2021-12-08 21:58:51','2021-12-08 21:52:48','2021-12-08 21:58:51'),(127,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','8c3e7c1b365ed1880eb937a6c839b2866facc11301b6a482290450c5881e45f8','[\"*\"]','2021-12-09 23:10:57','2021-12-08 22:08:16','2021-12-09 23:10:57'),(128,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','04af1bd39146c8c0ad47fb0cd001a7e2d513007944a088b8b4e5573ce9bdd7e7','[\"*\"]','2021-12-10 02:44:52','2021-12-08 22:15:43','2021-12-10 02:44:52'),(129,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','d05a4198965ce181cbe3b623b013f34eeacd662cd7b5a4c5d36dab59251cb513','[\"*\"]','2021-12-08 22:19:38','2021-12-08 22:19:29','2021-12-08 22:19:38'),(130,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','be72dc41910b7945d7268c1b66b288da9669c53802b74a3edae1d882f2901fbe','[\"*\"]','2021-12-08 23:49:52','2021-12-08 22:20:00','2021-12-08 23:49:52'),(131,'App\\Models\\User','ee44780f-0a96-46de-98bd-c57e4019c3a6','ee44780f-0a96-46de-98bd-c57e4019c3a6','aa5eabbaadfed76d17eab1d74ad66153c303dac6e21f93ae330a946f4664983c','[\"*\"]','2021-12-10 22:34:44','2021-12-08 23:23:06','2021-12-10 22:34:44'),(132,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','4a538c3d252383b1b14c17153c7d80cf9b97d5c727f19c920d7dfed35508abd8','[\"*\"]','2021-12-10 01:04:20','2021-12-09 20:52:50','2021-12-10 01:04:20'),(133,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','92e6b4863085d13e69dd7f5ebecc49ddd12c29fe86af99f96a5a1344978a16f5','[\"*\"]',NULL,'2021-12-09 22:41:32','2021-12-09 22:41:32'),(134,'App\\Models\\User','e50ab01f-f6b4-4814-b59d-0745278c7b86','e50ab01f-f6b4-4814-b59d-0745278c7b86','5a7a40b91d5ac4eafb5ac177e29f79ccec37c2a1c4407d3afb6e2e5f5f17c91f','[\"*\"]','2021-12-10 00:00:38','2021-12-09 23:18:03','2021-12-10 00:00:38'),(135,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','903f8b7d268d8a8fa137cbb78685b22123266af15c648432a2bb38b102ff8b44','[\"*\"]','2021-12-10 02:19:46','2021-12-10 00:00:51','2021-12-10 02:19:46'),(136,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','f3a5913c3a4b2e38356d61a9bfd5dae1545f4f632454ce8311993275058a953d','[\"*\"]','2021-12-10 17:21:50','2021-12-10 02:31:44','2021-12-10 17:21:50'),(137,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','f3c376ab4a2c908da9f5b49fd56f21ffa70f39410f6416fbe66621eddd0cf85d','[\"*\"]','2021-12-10 03:03:02','2021-12-10 02:46:38','2021-12-10 03:03:02'),(138,'App\\Models\\User','eda36c22-3ff8-4449-b369-e354a0a2661b','eda36c22-3ff8-4449-b369-e354a0a2661b','94ec5416d70ef2fbb354edc641e39b565904ade294bb011c9c7506a56b1c2243','[\"*\"]','2021-12-10 10:35:38','2021-12-10 10:34:18','2021-12-10 10:35:38');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanans`
--

DROP TABLE IF EXISTS `pesanans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pesanans` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idwaktu` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deadline` date NOT NULL,
  `nota_transaksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idlayanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idpelanggan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `outletid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int(11) NOT NULL,
  `kasir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanans`
--

LOCK TABLES `pesanans` WRITE;
/*!40000 ALTER TABLE `pesanans` DISABLE KEYS */;
INSERT INTO `pesanans` VALUES ('007559c8-0f92-4e01-9708-71a3acecf420','b66be103-5878-48ae-9b0e-4d78a50a59fc','2021-11-24','reguler2021112200687','SELESAI','dd35cfc8-08ec-4c85-842b-0c969ed1bbaa','11c7979a-fc4c-4f07-a0cb-c0d9adeaf722','cepat','96fec1d3-beba-45f1-8822-e8805e24076c',2,'karyawan','2021-11-22 11:01:45','2021-11-23 23:21:27'),('2df4cda8-390d-4124-be78-2c1673b9f708','b48f7fe2-b57f-4a5e-88f5-67435b9be8a8','2021-11-25','reguler2021112300687','ANTRIAN','8bc23611-9789-4c8c-bfdc-bfa2926c214e','21f2c087-dc11-483f-9c26-4216567f5c94','bayar kapan?','f868f833-da3d-46e4-8c3d-c95814a9bc70',2,'developer01','2021-11-23 22:06:53','2021-11-23 22:06:53'),('f1f6b5ac-55a1-46f6-b3e5-0fe1d7a432c4','7971784d-b548-4105-bf43-f61b1a6c6e24','2021-11-26','reguler20211125lI17IFj9','PROSES','0c405396-4d07-48f6-9668-099a4a7bd0db','6b93560b-533a-4eb5-8f86-8e293fd2daec','cepat','10f218da-1d3f-4937-8630-ebd423d32c00',3,'admin','2021-11-25 21:00:33','2021-11-28 02:49:00'),('15c0c2bd-89e3-4b06-a457-5a9d8cd5cb22','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-11-29','reguler20211128ALjtijP6','SELESAI','145607c0-ab80-4231-b0ef-7c05d0339f17','6b93560b-533a-4eb5-8f86-8e293fd2daec','cepat','19a914b2-7d90-4185-bb17-2d73bc9fa03d',3,'karyawan admin','2021-11-28 21:24:38','2021-12-05 09:46:40'),('bda676bd-d9bf-44f6-ba1c-c76559164e2a','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-11-30','reguler202111299CC1vQ86','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-11-29 23:30:59','2021-12-05 09:46:42'),('22f5f2f9-aaa0-44f2-8b4f-82e7111425b4','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-11-30','reguler20211129EpT5yid6','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-11-29 23:31:11','2021-12-05 09:34:28'),('55c868eb-1ec5-41d3-95e2-fe4fb69ae9f5','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-11-30','reguler20211129QrJEMVa6','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-11-29 23:31:14','2021-12-05 09:46:45'),('78e2bae1-7774-4540-a534-fc7c1d2c2e86','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-11-30','reguler20211129nZTYqHS6','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-11-29 23:33:01','2021-12-05 09:46:49'),('d7d5aeec-8d67-4cc5-a3d8-ff5807caa96b','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-02','reguler20211201jhiNkug6','SELESAI','145607c0-ab80-4231-b0ef-7c05d0339f17','6b93560b-533a-4eb5-8f86-8e293fd2daec','cepat','19a914b2-7d90-4185-bb17-2d73bc9fa03d',3,'karyawan admin','2021-12-01 22:59:38','2021-12-05 09:46:51'),('2db6973d-01f8-4b93-b11a-5f0fbcd27f96','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-02','reguler20211201ZwJsMFN6','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-12-01 23:12:29','2021-12-05 09:45:24'),('692853b8-4ee9-44e0-b941-280e759682cc','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-02','reguler20211201Iw0oD7h6','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','668342e8-1826-47cd-a874-01c52a4cbd4e',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',100,'karyawan admin','2021-12-01 23:13:31','2021-12-05 01:25:09'),('63d94322-0498-469e-aec7-eab0c5c6109d','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-02','reguler20211201wYBcb0a6','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','668342e8-1826-47cd-a874-01c52a4cbd4e',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',100,'karyawan admin','2021-12-01 23:14:38','2021-12-05 01:25:23'),('437deb35-e31c-4e20-8a7f-c2e24645f24d','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-02','reguler202112011eSzSm46','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','668342e8-1826-47cd-a874-01c52a4cbd4e',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',20,'karyawan admin','2021-12-01 23:30:38','2021-12-05 01:29:47'),('ef2efae6-ad0d-42d6-9b45-244677988bd5','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler202112024mPErd46','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','668342e8-1826-47cd-a874-01c52a4cbd4e',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-12-02 21:27:16','2021-12-05 09:46:53'),('2c5f6122-3c88-4f18-9017-9ea5e460c03f','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202IoXE9NB6','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','668342e8-1826-47cd-a874-01c52a4cbd4e',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-12-02 21:29:17','2021-12-05 09:46:55'),('1fca1844-013d-4976-b3f3-96b230fb6238','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202D60aT6k6','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','668342e8-1826-47cd-a874-01c52a4cbd4e',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-12-02 21:34:00','2021-12-06 21:24:45'),('14a425f6-0530-4096-b8c8-914e51c1d4cd','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202qSDKvLF6','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','668342e8-1826-47cd-a874-01c52a4cbd4e',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-12-02 21:40:39','2021-12-06 21:29:41'),('9e4a53d9-3c9f-4a37-b5b2-de4f5a419be4','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202oW0Em0P6','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','668342e8-1826-47cd-a874-01c52a4cbd4e',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-12-02 21:42:09','2021-12-06 21:30:44'),('3edca8c7-9c39-4a05-b82e-67856bc4abed','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202nLiSWYf6','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','668342e8-1826-47cd-a874-01c52a4cbd4e',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-12-02 22:31:04','2021-12-07 20:02:35'),('fa20a202-b7bf-42cd-bd29-539640474b48','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202EBYxVcx6','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','668342e8-1826-47cd-a874-01c52a4cbd4e','cepat','19a914b2-7d90-4185-bb17-2d73bc9fa03d',3,'karyawan admin','2021-12-02 22:33:02','2021-12-08 20:35:44'),('c38f00c7-ea7c-4b61-ab90-d502fb79e977','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202SmuIBan10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','668342e8-1826-47cd-a874-01c52a4cbd4e','cepat','19a914b2-7d90-4185-bb17-2d73bc9fa03d',3,'karyawan admin','2021-12-02 22:45:41','2021-12-08 20:35:41'),('3a330c34-b033-4c51-a3fb-0deafc89ad7b','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202cQBO3e710','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','4bf84c1b-6afa-4991-895a-3e284733d15a',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',20,'karyawan admin','2021-12-02 22:54:20','2021-12-08 20:35:37'),('85869e2a-1d05-460a-9a62-f7e5fde0be31','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202t7geVhX10','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','4bf84c1b-6afa-4991-895a-3e284733d15a',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',20,'karyawan admin','2021-12-02 22:56:07','2021-12-06 21:24:52'),('2dda4fc2-a380-4338-a53b-51e339cc7438','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202eyO90EY10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','4bf84c1b-6afa-4991-895a-3e284733d15a',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',20,'karyawan admin','2021-12-02 22:56:41','2021-12-08 20:35:33'),('5757970e-69d8-4db8-82db-4e36c1f751f6','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202ubRT3Tm10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','4bf84c1b-6afa-4991-895a-3e284733d15a',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',20,'karyawan admin','2021-12-02 22:57:46','2021-12-07 21:14:13'),('7334d77f-f2b4-4ada-967e-69196a49981d','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202mCELxbj10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','4bf84c1b-6afa-4991-895a-3e284733d15a',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',20,'karyawan admin','2021-12-02 23:01:02','2021-12-07 21:06:10'),('0f2b6946-2d52-4f2f-8c6f-ec57782ab9b2','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler202112020QEhzl910','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','38a05e68-52af-4f2e-bc95-1e0e2dbebc3e','Dianter ya','19a914b2-7d90-4185-bb17-2d73bc9fa03d',3,'karyawan admin','2021-12-02 23:16:44','2021-12-08 20:35:31'),('1db5ae5e-8478-4fb8-92ec-cc2b4e489f31','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202B0slRa210','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','38a05e68-52af-4f2e-bc95-1e0e2dbebc3e','Dianter ya','19a914b2-7d90-4185-bb17-2d73bc9fa03d',3,'karyawan admin','2021-12-02 23:17:28','2021-12-06 22:49:37'),('40eecf2e-cc42-40bb-ac20-6e306d3b9d8f','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211202HPRlooI10','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','38a05e68-52af-4f2e-bc95-1e0e2dbebc3e','Ok','19a914b2-7d90-4185-bb17-2d73bc9fa03d',1,'karyawan admin','2021-12-02 23:41:37','2021-12-06 22:49:40'),('d9cf3e57-7842-4d53-ac8d-ee76366a3daf','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-03','reguler20211203pxHOrIs10','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',1,'karyawan admin','2021-12-03 18:52:16','2021-12-06 21:24:49'),('5f4a48c1-bf61-462b-a439-0e40a55f41c5','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-05','reguler20211205uy0qvVM10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','38a05e68-52af-4f2e-bc95-1e0e2dbebc3e',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-12-05 02:28:46','2021-12-07 21:02:08'),('c6fc3c37-00dd-4dae-9e6a-12610315eb3a','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-05','reguler20211205s5yJdYS10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','38a05e68-52af-4f2e-bc95-1e0e2dbebc3e',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',10,'karyawan admin','2021-12-05 02:29:42','2021-12-07 21:05:15'),('1df4faa2-1b23-4fc1-9b18-dc1689765084','1820621a-9290-49a2-ae86-1bd32b19a0a0','2021-12-05','reguler20211205xYzNIzr10','SELESAI','659b8607-a0ba-4b09-a017-07084537a5cb','d0cae718-c8b6-4b3c-9061-0766294cdc8d',NULL,'3e6ebeeb-06e2-46bd-a028-1cdbb51d09de',1,'admin','2021-12-05 09:30:14','2021-12-09 23:08:15'),('8a81a144-06ac-4198-937e-891eed22fefc','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-05','reguler20211205Rw4z0l110','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','38a05e68-52af-4f2e-bc95-1e0e2dbebc3e',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',1,'karyawan admin','2021-12-05 09:51:22','2021-12-08 20:35:30'),('d2eaa9d2-b9d1-4302-a594-b70aecfc98f0','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-05','reguler20211205eSkAEYC10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','668342e8-1826-47cd-a874-01c52a4cbd4e','cepat','19a914b2-7d90-4185-bb17-2d73bc9fa03d',3,'karyawan admin','2021-12-05 10:15:16','2021-12-10 22:25:02'),('d4beac24-243d-4144-9afa-0283fe2643d7','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-05','reguler20211205LTxGf1L10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',1,'karyawan admin','2021-12-05 10:29:50','2021-12-08 22:33:51'),('31b6b90d-fdcc-4541-aa6e-432a34aa009c','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-05','reguler20211205nzcbclc10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',1,'karyawan admin','2021-12-05 10:32:26','2021-12-08 22:33:48'),('ce1a479f-2336-4927-a7c7-bb3c89fb111e','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-05','reguler20211205eXBXC6W10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',1,'karyawan admin','2021-12-05 10:32:50','2021-12-08 22:33:46'),('4e75ab28-e6f2-4c87-b4ed-024d7944abbc','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-05','reguler20211205oVer4gp10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',1,'karyawan admin','2021-12-05 10:35:06','2021-12-07 21:15:04'),('595af248-1ae3-4f26-992a-b2a1d6dcccc9','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-05','reguler20211205VTWwZ7B10','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',1,'karyawan admin','2021-12-05 10:37:09','2021-12-07 20:02:29'),('8e3a5d88-2769-4c0b-9419-d33386aa2fc5','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-08','reguler20211207mrJZwqz10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',1,'karyawan admin','2021-12-07 21:10:06','2021-12-08 22:33:44'),('fa6e5e36-48bd-4f86-a6c9-3be51430252d','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-08','reguler20211207MqESaWR10','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-12-07 21:13:26','2021-12-08 22:51:38'),('906bb93e-e865-40d2-a7cd-6c7883dfb8bd','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-08','reguler20211207ipYLhlr10','SELESAI','2c73d717-d06e-4c11-b73f-57c6353acf68','38a05e68-52af-4f2e-bc95-1e0e2dbebc3e','Ok','19a914b2-7d90-4185-bb17-2d73bc9fa03d',3,'karyawan admin','2021-12-07 22:22:27','2021-12-10 22:21:01'),('de1ce295-0d4e-4231-a0bb-c766f474981d','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-08','reguler20211207SG6kucY10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','38a05e68-52af-4f2e-bc95-1e0e2dbebc3e','Ok','19a914b2-7d90-4185-bb17-2d73bc9fa03d',3,'karyawan admin','2021-12-07 22:24:44','2021-12-08 22:33:39'),('a01a6625-aceb-4c70-b43d-6b75e0589b09','910e8270-9e15-462d-8d2d-fbac691460db','2021-12-08','regular20211208rDtiVwT10','SELESAI','4496f972-752f-46fa-a6fe-448a829e05ca','f420f2bd-acca-4043-9526-6a9e3f58b664',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-12-08 21:11:32','2021-12-10 22:25:55'),('e0416ecb-d109-47eb-8673-0526bfdd1fb2','910e8270-9e15-462d-8d2d-fbac691460db','2021-12-08','regular202112085VNGbKz10','PACKING','4496f972-752f-46fa-a6fe-448a829e05ca','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',2,'karyawan admin','2021-12-08 21:21:53','2021-12-10 22:25:28'),('3e8c90a7-4712-4d74-aa77-410fa7b40212','1820621a-9290-49a2-ae86-1bd32b19a0a0','2021-12-08','reguler20211208oBfz9do10','SELESAI','659b8607-a0ba-4b09-a017-07084537a5cb','d0cae718-c8b6-4b3c-9061-0766294cdc8d','cepat','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de',3,'admin','2021-12-08 21:54:36','2021-12-08 21:55:02'),('ba2da1e3-4390-4743-b06f-065ef77d5cb4','1820621a-9290-49a2-ae86-1bd32b19a0a0','2021-12-10','reguler20211210TYFuxIV10','PROSES','659b8607-a0ba-4b09-a017-07084537a5cb','e498073e-c2dc-4701-b709-aa234fc1cc9d',NULL,'3e6ebeeb-06e2-46bd-a028-1cdbb51d09de',3,'admin','2021-12-10 02:49:24','2021-12-10 02:51:56'),('e18b30f7-5a64-41bb-9bae-db894bc72088','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-11','reguler2021121012I9VnI10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',3,'karyawan admin','2021-12-10 22:17:45','2021-12-10 22:25:33'),('b91c28b4-e462-4cbf-b213-6e701f4971db','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-11','reguler20211210Tw2m7RH10','PACKING','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',3,'karyawan admin','2021-12-10 22:18:09','2021-12-10 22:26:08'),('36c90ff7-e121-430c-bd15-67c3ba535ce8','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-11','reguler20211210zAFNeWR10','PROSES','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',3,'karyawan admin','2021-12-10 22:18:53','2021-12-10 22:26:15'),('7a4fab39-6324-479c-a3a7-b37951fb3a85','1ea9ff15-9136-485c-beb1-5946f9eb11a6','2021-12-11','reguler20211210ZC18eNY10','ANTRIAN','2c73d717-d06e-4c11-b73f-57c6353acf68','a64e08e4-34b0-48f2-aab1-9b5eca57d8b6',NULL,'19a914b2-7d90-4185-bb17-2d73bc9fa03d',5,'karyawan admin','2021-12-10 22:41:26','2021-12-10 22:41:26');
/*!40000 ALTER TABLE `pesanans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `satuans`
--

DROP TABLE IF EXISTS `satuans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `satuans` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_layanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL,
  `idwaktu` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `idoutlet` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `satuans`
--

LOCK TABLES `satuans` WRITE;
/*!40000 ALTER TABLE `satuans` DISABLE KEYS */;
/*!40000 ALTER TABLE `satuans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_layanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL,
  `idwaktu` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `idoutlet` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES ('dd35cfc8-08ec-4c85-842b-0c969ed1bbaa','express',20000,'b66be103-5878-48ae-9b0e-4d78a50a59fc','kiloan','satuan','cuci setrika',1,'96fec1d3-beba-45f1-8822-e8805e24076c','2021-11-22 10:43:46','2021-11-22 10:43:46'),('8bc23611-9789-4c8c-bfdc-bfa2926c214e','express',20000,'b48f7fe2-b57f-4a5e-88f5-67435b9be8a8','kiloan','satuan','cuci setrika',1,'f868f833-da3d-46e4-8c3d-c95814a9bc70','2021-11-23 22:06:00','2021-11-23 22:06:00'),('66eb5005-d74c-4f5e-84c7-bd472f540f91','cepat',20000,'57dc7db3-a630-421c-ac4b-d2e6d6af6ba0','outfit','satuan','sepatu',1,'c8849dbb-5e8f-46ce-93a6-f867189253ef','2021-11-23 23:33:20','2021-11-23 23:33:20'),('2c73d717-d06e-4c11-b73f-57c6353acf68','cepat',20000,'1ea9ff15-9136-485c-beb1-5946f9eb11a6','outfit','kiloan','default',1,'19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-11-29 21:04:06','2021-11-29 21:04:06'),('80a1085d-6da8-4b7d-a5d1-87c40415abc0','cuci',30000,'274469bc-e235-4d45-b496-5aef64fddc35','null','kiloan','null',1,'10f218da-1d3f-4937-8630-ebd423d32c00','2021-11-29 21:22:35','2021-11-29 21:53:52'),('93555849-cf46-42a5-8ac5-6232aecf0e64','cuci + strika(promo 1000)',1000,'8e59c9f6-e192-4175-bbed-8cc1958fc743','null','kiloan','null',1,'10f218da-1d3f-4937-8630-ebd423d32c00','2021-11-29 21:51:12','2021-11-29 21:54:01'),('9b9f0084-191b-426c-8426-1f39c7416f9f','bantal',1000,'8e59c9f6-e192-4175-bbed-8cc1958fc743','aksesoris','kiloan','bantal',1,'10f218da-1d3f-4937-8630-ebd423d32c00','2021-11-29 22:04:30','2021-11-29 22:04:30'),('0ae6c985-e86f-4a40-8ba2-165675c7e10f','bantal',1000,'c46cae29-9959-44f8-98cc-489e823dec4a','pakain wanita','kiloan','bantal',1,'19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-11-29 22:05:19','2021-11-29 22:05:19'),('4496f972-752f-46fa-a6fe-448a829e05ca','cepat',20000,'910e8270-9e15-462d-8d2d-fbac691460db','outfit','satuan','default',1,'19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-11-29 22:21:56','2021-11-29 22:21:56'),('e33c9450-2407-408f-aae2-ee21af6cf5ad','erwere',232323,'c6aaa970-70f1-4042-bb59-823d72b86d02','aksesoris','satuan','erwere',1,'10f218da-1d3f-4937-8630-ebd423d32c00','2021-11-29 22:31:17','2021-11-29 22:31:17'),('e2f5a4cd-9537-41bd-a8ef-63adf0d54bde','cuci kering',1000,'6983bc60-8463-4e5e-949c-7f3ee13b30f5','null','kiloan','null',1,'49cd7b91-4fcf-40a4-8f5a-d267e171c0ba','2021-12-01 00:01:36','2021-12-01 00:01:36'),('de500910-aafc-4f62-8b68-85d1a3886e83','karpet mobil',20000,'d1724cb2-e4da-4a32-91c4-e007689f4f96','pakain wanita','satuan','karpet',1,'49cd7b91-4fcf-40a4-8f5a-d267e171c0ba','2021-12-01 00:03:20','2021-12-01 00:03:36'),('659b8607-a0ba-4b09-a017-07084537a5cb','cuci kering cepat',10000,'1820621a-9290-49a2-ae86-1bd32b19a0a0','null','kiloan','null',1,'3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','2021-12-01 21:27:40','2021-12-01 21:27:40'),('2cd25ec7-7c9b-4ed9-bc24-db6955926db7','karpet satuan kering',12000,'d6a27286-5859-4b64-9e41-38e3bdd51d9f','aksesoris','satuan','karpet satuan',1,'3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','2021-12-01 21:29:07','2021-12-09 21:08:48'),('405523b0-77f3-4f11-895e-a8581491a0f2','strika',2000,'d6a27286-5859-4b64-9e41-38e3bdd51d9f','null','satuan','null',1,'989b84d5-81c4-4dce-b068-75c7348e4392','2021-12-09 21:05:14','2021-12-09 21:10:07');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `uid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `outlet_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('eda36c22-3ff8-4449-b369-e354a0a2661b','admin','admin@gmail.com','$2y$10$RTZKSrQs5dflkxpgd.Kg7ufaR9aLpTFBIfvQi4qf8tX8D2dG649m6','owner','jogja','324234234','ACTIVE','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de',NULL,NULL,'2021-11-23 23:30:18','2021-12-10 10:34:18'),('e50ab01f-f6b4-4814-b59d-0745278c7b86','testadminn','admin123@dev.com','$2y$10$cYjc7JIWe/JusLJPelMHV.5Q.gktQXJfAf8KyO3qB11/i/qXljHr6','owner','sonoharjo','0892323523637','ACTIVE',NULL,NULL,NULL,'2021-12-09 23:17:50','2021-12-09 23:18:03'),('ee44780f-0a96-46de-98bd-c57e4019c3a6','karyawan admin','adminkar@gmail.com','$2y$10$nIYLrilwYhJ.mlw9p9ucFuNXRbFfFncaZ3rzvU.Yqfs.NE4xt7DvK','karyawan','seyegan','78902389089','ACTIVE','19a914b2-7d90-4185-bb17-2d73bc9fa03d',NULL,NULL,'2021-11-24 20:37:09','2021-12-08 23:23:06'),('8908b65a-2cc9-4b7c-8b49-893268367386','karyawan adwmin','adminkarya@gmail.com','$2y$10$DvqR8Sj95xK9rFFdn92GTOzM.cqYp30l1ImpHPoMaztCSr0k8ugdO','karyawan','an','7890238349089','INACTIVE','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de',NULL,NULL,'2021-12-07 23:50:55','2021-12-07 23:50:55'),('7084f110-d081-43af-b011-cc874a70ade5','sadasdasd','asdad@gmail.com','$2y$10$Vt4oWwvOlw4XnuRXWhQat.ZWW1hJQlWwtCP/JoL9Ybx.hXta/gTZG','owner','sonoharjo','0892662316','INACTIVE',NULL,NULL,NULL,'2021-12-07 23:18:55','2021-12-07 23:18:55'),('875b0960-98e5-4e60-91d3-07d55ba140bb','Ggff','dddd@gmail.com','$2y$10$YOAgtpWpxunQnP3VnqZ7juez5HGsShif9A6Z5VrNMvuTwjPE68QI.','karyawan','Gfffg','32423423','INACTIVE','19a914b2-7d90-4185-bb17-2d73bc9fa03d',NULL,NULL,'2021-11-24 21:50:17','2021-11-24 21:50:17'),('1268cd16-78f2-476a-b4b5-9b8e0be0fbc4','developer','developer@gmail.com','$2y$10$rKoygE.B.dlxt/JK1ysiZuAUbt8vFQrHxesv98lsI6oLkXxO2v0Ou','owner','jogja','0897823423','ACTIVE','7fa0c01c-4a26-4ae3-9db2-39ed5df5b3e1',NULL,NULL,'2021-11-22 10:05:15','2021-11-23 23:09:56'),('64f63faf-3d40-454a-bd8b-943886e30af4','developer01','developer01@gmail.com','$2y$10$8e.HTr5A.iv0.LZ86JasG.wTERaUUumytprYrRsgTGKm0FSm2h90C','owner','jogja','0897823423123','ACTIVE','c8849dbb-5e8f-46ce-93a6-f867189253ef',NULL,NULL,'2021-11-22 21:53:54','2021-11-23 23:12:27'),('5b23f282-5311-42dd-9303-c9df9e328ce7','hanafi dev','developerkar@gmail.com','$2y$10$syb2iTFCkl9bqvrq/5kCge/cdrL3vZioHN6gTtXVN16axn.lQv4.a','karyawan','seyegan','08123123223423','INACTIVE','33ce0815-0918-4071-b88d-8ffcdfe11bb1',NULL,NULL,'2021-11-23 21:40:04','2021-11-23 21:40:04'),('a408932b-6337-4d8e-96a3-c95db0d136a5','hanafi dev','developerkarya@gmail.com','$2y$10$XLONFqt.pfN40x6Pn0ZHTuPthOxuRos2INX0LW7s06wLWSxAlMwL6','karyawan','seyegan','081231123223423','ACTIVE','9a8a8381-0bce-425c-a28d-2ad7ac082d34',NULL,NULL,'2021-11-23 21:41:17','2021-11-23 23:06:27'),('7392ec97-8e60-413b-b5bd-5c42b89f18b9','username ki','email@dev.com','$2y$10$UeaC7wUpnF/2.NibxgQ5Iu17vfSUXDxfM/GoHsI2tqayKvRHcXKoi','karyawan','alamat','129283981923','INACTIVE','201c2e44-4774-4647-939a-ef20956de77d',NULL,NULL,'2021-11-23 00:43:23','2021-11-23 00:43:23'),('2c9d3215-f921-42f6-b634-486beb27bdae','username ki','email3@dev.com','$2y$10$tpJhH6AGSZYtyvttPcAv1uluMrWHvBrmWPlen/EZWE4tfw3QWyYKC','karyawan','alamat','1292383981923','INACTIVE','201c2e44-4774-4647-939a-ef20956de77d',NULL,NULL,'2021-11-23 00:45:40','2021-11-23 00:45:40'),('c816d3e3-5f64-4e42-9586-a692fec9e768','username ki','email33@dev.com','$2y$10$tU8PpeacDQQYfR254vqMQ.D9ul8o6qsSWlnY5VBstXbMm1QVGhRlO','karyawan','alamat','12922383981923','INACTIVE','201c2e44-4774-4647-939a-ef20956de77d',NULL,NULL,'2021-11-23 00:58:19','2021-11-23 00:58:19'),('c1672b59-db7f-44ee-8d09-ff7f8dff1e75','testing developer','helloword@ff.com','$2y$10$gqUJA.cLUapa29dRwimdIOt3kOr8crNkHvvrlw3EkgdhZ3f8nvlou','owner','sonoharjo','0892372366236623','INACTIVE',NULL,NULL,NULL,'2021-12-09 23:15:25','2021-12-09 23:15:25'),('38629292-f0bc-4ea9-a17c-901b6a9bd1ed','Shhshsnsnns','Hshsjh@dev.id','$2y$10$n1iGnTvIbFAOJ1rd2DKD7OFRzBq1FAbrmjUuhzAY8W4k7Y15VlW8S','karyawan','Terssbbbs','6362773666','INACTIVE','19a914b2-7d90-4185-bb17-2d73bc9fa03d',NULL,NULL,'2021-11-24 21:58:26','2021-11-24 21:58:26'),('19cdfb46-265b-4674-9219-2264de3ad31e','karyawan','karyawan@gmail.com','$2y$10$YUIH0ZLtvZezDhSqBzuNveLcnGhuOu35AZP./W7Ei0oYDnLL/Xttm','karyawan','kalasan','0897823423','ACTIVE','96fec1d3-beba-45f1-8822-e8805e24076c',NULL,NULL,'2021-11-22 10:13:07','2021-11-22 22:33:07'),('21d76bdb-afa5-46ef-b6bc-0d9e7b97b9e2','karyawa1n','karyawan0012@gmail.com','$2y$10$R2i9K8Fgv1iuhgVRfaaYzO/osq/AfZtpNcAh2cexlYeUKjIR4BwOG','karyawan','kalasan','08978122123423','INACTIVE','201c2e44-4774-4647-939a-ef20956de77d',NULL,NULL,'2021-11-23 00:37:35','2021-11-23 00:37:35'),('bb9f442a-9afb-4039-b2e8-a6f489abab97','karyawa1n sdsad','karyawan0012a@gmail.com','$2y$10$uUmkyVhujLnSb5nULMNYhOlFlRzzCA47QGPig7uSJaWG6vQ2vcEd6','karyawan','kalasan','0812312323423','INACTIVE','201c2e44-4774-4647-939a-ef20956de77d',NULL,NULL,'2021-11-23 00:38:24','2021-11-23 00:38:24'),('629ee9bd-2b98-4c16-8f99-69f2dbf12879','karyawa1n sdsad','karyawan00132a@gmail.com','$2y$10$09zwRU0yOXzi4hqB8.stZ.Ss2MJqqdGT5pEgTtmxPghhgOroN9MSq','karyawan','kalasan','08123123423423','INACTIVE','201c2e44-4774-4647-939a-ef20956de77d',NULL,NULL,'2021-11-23 00:52:44','2021-11-23 00:52:44'),('9c00b46f-5149-4a4c-8504-8609d11b2849','karyawan','karyawan1@gmail.com','$2y$10$Fgmducd29rsms99UUF1sp.V6Wrrf8qw1zjK2zqHDFI/idHcJMXwBm','karyawan','kalasan','0897823423','ACTIVE','96fec1d3-beba-45f1-8822-e8805e24076c',NULL,NULL,'2021-11-22 10:13:59','2021-11-22 10:18:59'),('eeb7dbb3-4c7d-4fd9-a5ce-25d9ce2c9b45','Developer kueh','nafi@dev.com','$2y$10$kwltUPdkxWhA.HFQjzg9GeOEG/fH1KR97f3TPZhrHgx.Gztf7UHES','karyawan','Yogyakarta','0895421900858','INACTIVE','201c2e44-4774-4647-939a-ef20956de77d',NULL,NULL,'2021-11-23 00:35:21','2021-11-23 00:35:21'),('089f852c-6bbb-41e4-b0e8-5a5c9f4da558','Nafdev','nafi@dev.id','$2y$10$qxY69RowU/VDy12lS69bEOkMAH4O/19E.XTqyww7NXtp89wqG2OEC','karyawan','Bokong','636363787','INACTIVE','201c2e44-4774-4647-939a-ef20956de77d',NULL,NULL,'2021-11-23 00:40:00','2021-11-23 00:40:00'),('43cfc339-5c79-4680-879c-e00a60407d8e','Testingddg','tefstnafi@gmail.comm','$2y$10$dCwuyt8I9IGWmezWGV3X6eNUwq2h4Awu.M5vW9hIiFEDPQcjmTHuK','karyawan','Tesgggbs','56727665','INACTIVE','19a914b2-7d90-4185-bb17-2d73bc9fa03d',NULL,NULL,'2021-11-24 21:55:01','2021-11-24 21:55:01'),('50ddf660-71bd-4086-a5f5-9f41fcf08b83','Test','test@dev.coco','$2y$10$Gz2mGl/zFuJuSKGfAtbpnexZ4uRrzYWQAzWy.t75IS1BAIcX4WDgW','karyawan','Hshshhhs','73727288777','INACTIVE','201c2e44-4774-4647-939a-ef20956de77d',NULL,NULL,'2021-11-23 00:56:41','2021-11-23 00:56:41'),('189fc7be-dd5c-4d74-9140-02816f3f0d81','Test','Test@dev.comit','$2y$10$ml/7PSehuyVwG16R7mLRtufnaEmOcNdKVsa2vSBvQvYSGJacTnqmK','karyawan','Tetshhhshs','62727272666566','INACTIVE','201c2e44-4774-4647-939a-ef20956de77d',NULL,NULL,'2021-11-23 01:08:04','2021-11-23 01:08:04'),('751d9291-0371-4a93-b52f-eca1057d3778','test developer','test@gmail.com','$2y$10$YTlszq.zukK48H0QI9MJ8eUjsu4tvpUUJYw8YsofL0IX0s9UYmk7q','owner','sonoharjo','08925523552323','INACTIVE',NULL,NULL,NULL,'2021-12-09 23:13:49','2021-12-09 23:13:49'),('7b76b52c-9096-4b6d-af2c-46a79ac81be9','testing developewe','test@gmdail.com','$2y$10$SdD/yJavqDcg3Ts58X/1Sumi.ff8t8f46/Va.QarfJECSUByi4WSm','owner','sonoharjo','089236213621323','INACTIVE',NULL,NULL,NULL,'2021-12-09 23:16:55','2021-12-09 23:16:55'),('3c4f6f35-ee46-4565-a0a8-d47f6ddde78c','testdev','testdev@gmail.com','$2y$10$tAXqjHmjZuEgNTq/4BXCY.uesGNxYvIN4JGBHR9n6gSEDsxYwnnA6','owner','jogja','3242334234','ACTIVE','49cd7b91-4fcf-40a4-8f5a-d267e171c0ba',NULL,NULL,'2021-11-30 23:24:39','2021-12-01 22:23:41'),('b35734f2-efeb-4665-b1a5-c017086691e2','testdesv','testdsev@gmail.com','$2y$10$kWj0d3d60phWi6W2oJpqmuSR9CFi29Lmjy8c8JTJmc3ha.02LcqJ2','owner','jogja','32424334234','ACTIVE',NULL,NULL,NULL,'2021-12-01 20:18:04','2021-12-01 22:25:40'),('4dfacae3-1abc-4fd6-a585-82fb24232475','Testingdd','testnafi@gmail.comm','$2y$10$LXfcOJat9JSFpc.C7HUR9ODpIF61yEOV9EulBeHudwep6bjevUnfy','karyawan','Tesgggbs','5672766','INACTIVE','19a914b2-7d90-4185-bb17-2d73bc9fa03d',NULL,NULL,'2021-11-24 21:53:49','2021-11-24 21:53:49'),('69a77ed8-10d6-4436-9e50-5fbc0f764861','Test','testttt@dev.id','$2y$10$FC/vNA2iZswqk4cez71RsOh8QjZUIFKog2Y26k/FLnehsguNqKoYW','karyawan','Bokohar','6636662772666','INACTIVE','201c2e44-4774-4647-939a-ef20956de77d',NULL,NULL,'2021-11-23 01:13:38','2021-11-23 01:13:38'),('585c8ab4-6116-44fc-afd0-2920a9528a99','Testtttttaggbb','testttt@dev.idv','$2y$10$LVgsZSvWc5UhMdicPGU91u7NAz5y5/Rc0XR.HVI0AH7TAs1Dylyu.','karyawan','Bokoharhhhg','08963666277265','INACTIVE','201c2e44-4774-4647-939a-ef20956de77d',NULL,NULL,'2021-11-23 01:27:09','2021-11-23 01:27:09'),('e36c56fd-9900-4bd1-a863-8f1d9b3fa1da','Testtttttaggbbt','testttt@dev.idvy','$2y$10$yOgeLw0Hd.LSRxThIJS3SuxsKvofuLwEsQulmNLXvN1ug9cdL2B5e','karyawan','Bokoharhhhgh','089636662772655','INACTIVE','201c2e44-4774-4647-939a-ef20956de77d',NULL,NULL,'2021-11-23 01:28:25','2021-11-23 01:28:25');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `waktus`
--

DROP TABLE IF EXISTS `waktus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `waktus` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu` int(11) NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `paket` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idoutlet` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `waktus`
--

LOCK TABLES `waktus` WRITE;
/*!40000 ALTER TABLE `waktus` DISABLE KEYS */;
INSERT INTO `waktus` VALUES ('b66be103-5878-48ae-9b0e-4d78a50a59fc','express',48,'satuan',1,'reguler','96fec1d3-beba-45f1-8822-e8805e24076c','2021-11-22 10:21:47','2021-11-22 10:21:47'),('2dab8f74-5565-49c4-a292-d72903fc2d80','regular',48,'satuan',1,'reguler','f868f833-da3d-46e4-8c3d-c95814a9bc70','2021-11-23 21:48:34','2021-11-23 21:48:34'),('b48f7fe2-b57f-4a5e-88f5-67435b9be8a8','regular()',48,'satuan',1,'reguler','f868f833-da3d-46e4-8c3d-c95814a9bc70','2021-11-23 21:59:35','2021-11-23 21:59:35'),('4cc88c08-8309-4ec9-9e28-a6380ce5bb54','expresss',48,'satuan',1,'reguler','c8849dbb-5e8f-46ce-93a6-f867189253ef','2021-11-23 23:26:17','2021-11-23 23:26:17'),('57dc7db3-a630-421c-ac4b-d2e6d6af6ba0','regularr',3,'satuan',1,'reguler','c8849dbb-5e8f-46ce-93a6-f867189253ef','2021-11-23 23:29:26','2021-11-23 23:29:26'),('c46cae29-9959-44f8-98cc-489e823dec4a','cuci',3,'kiloan',1,'reguler','10f218da-1d3f-4937-8630-ebd423d32c00','2021-11-26 22:14:35','2021-11-26 22:14:35'),('274469bc-e235-4d45-b496-5aef64fddc35','cuci + strika',3,'kiloan',1,'regular','10f218da-1d3f-4937-8630-ebd423d32c00','2021-11-26 22:56:45','2021-11-26 22:56:45'),('8e59c9f6-e192-4175-bbed-8cc1958fc743','strika',2,'kiloan',1,'reguler','10f218da-1d3f-4937-8630-ebd423d32c00','2021-11-26 22:58:21','2021-11-26 22:59:33'),('1ea9ff15-9136-485c-beb1-5946f9eb11a6','cuci 1',3,'kiloan',1,'reguler','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-11-28 20:46:27','2021-11-28 20:46:27'),('30d6b0e1-e650-486d-9c2f-b5bc1a73e896','cuci 2',3,'kiloan',1,'reguler','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-11-28 22:55:06','2021-11-28 22:55:06'),('910e8270-9e15-462d-8d2d-fbac691460db','cek in',1,'satuan',1,'regular','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-11-29 00:12:18','2021-11-29 00:12:18'),('c6aaa970-70f1-4042-bb59-823d72b86d02','karpet cepat',1,'satuan',1,'regular','10f218da-1d3f-4937-8630-ebd423d32c00','2021-11-29 22:09:44','2021-11-29 22:09:44'),('9f2c4430-6068-42c7-81d3-712049e6996e','qwewqe',2,'satuan',1,'regular','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-11-29 22:26:54','2021-11-29 22:26:54'),('75bc6308-f960-4ca1-993a-d72fdb00ebeb','cekkk',2,'satuan',1,'express','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-11-29 22:39:17','2021-11-29 22:39:17'),('49e82b1d-595b-4f38-b3fc-5d41d77600cf','ssdsd',12,'kiloan',1,'regular','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-11-29 23:16:06','2021-11-29 23:16:06'),('6983bc60-8463-4e5e-949c-7f3ee13b30f5','expres(promo)',3,'kiloan',1,'express','49cd7b91-4fcf-40a4-8f5a-d267e171c0ba','2021-12-01 00:01:21','2021-12-01 00:01:21'),('d1724cb2-e4da-4a32-91c4-e007689f4f96','satuan karpet',3,'satuan',1,'express','49cd7b91-4fcf-40a4-8f5a-d267e171c0ba','2021-12-01 00:02:54','2021-12-01 00:02:54'),('1820621a-9290-49a2-ae86-1bd32b19a0a0','regular',2,'kiloan',1,'reguler','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','2021-12-01 21:25:17','2021-12-01 21:27:00'),('d6a27286-5859-4b64-9e41-38e3bdd51d9f','karpet cepat(1)',2,'satuan',1,'express','3e6ebeeb-06e2-46bd-a028-1cdbb51d09de','2021-12-01 21:28:35','2021-12-01 21:28:35'),('ba5f6078-55ff-4a34-8ff5-6070186696fa','cuci 2s',3,'kiloan',1,'reguler','19a914b2-7d90-4185-bb17-2d73bc9fa03d','2021-12-01 22:08:17','2021-12-01 22:08:17'),('909ff2c9-1c9b-49c0-b69a-4bdd2a4f8519','expres(seyegan)',1,'kiloan',1,'express','989b84d5-81c4-4dce-b068-75c7348e4392','2021-12-01 22:19:16','2021-12-01 22:19:16'),('2cc57169-f2b8-4628-8ace-076002810e08','expres(bandung',2,'kiloan',1,'reguler','1cb40906-1e4f-49f8-a353-cb07a1f2e085','2021-12-09 21:07:26','2021-12-09 21:08:31');
/*!40000 ALTER TABLE `waktus` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-12-10 15:43:33
