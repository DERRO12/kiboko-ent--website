-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: kiboko
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `brand_categories`
--

DROP TABLE IF EXISTS `brand_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brand_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brand_categories`
--

LOCK TABLES `brand_categories` WRITE;
/*!40000 ALTER TABLE `brand_categories` DISABLE KEYS */;
INSERT INTO `brand_categories` VALUES (1,'Personal Care','personal-care',0,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(2,'Home Care','home-care',1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(3,'Foods','foods',2,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(4,'Beverages','beverages',3,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(5,'Oral Care','oral-care',4,'2026-11-11 14:53:46','2026-11-11 14:53:46');
/*!40000 ALTER TABLE `brand_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brand_category_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'partner',
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `media_id` bigint(20) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_slug_unique` (`slug`),
  KEY `brands_brand_category_id_foreign` (`brand_category_id`),
  KEY `brands_media_id_foreign` (`media_id`),
  KEY `brands_type_is_active_sort_order_index` (`type`,`is_active`,`sort_order`),
  CONSTRAINT `brands_brand_category_id_foreign` FOREIGN KEY (`brand_category_id`) REFERENCES `brand_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `brands_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,1,'partner','Unilever','unilever',25,'Unilever products distributed by Kiboko across Uganda.','https://www.unilever.com',0,1,1,'2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(2,2,'partner','P&G','pg',26,'P&G products distributed by Kiboko across Uganda.','https://www.pg.com',1,1,1,'2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(3,3,'partner','Nestlé','nestle',27,'Nestlé products distributed by Kiboko across Uganda.','https://www.nestle.com',2,1,1,'2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(4,5,'partner','Colgate','colgate',28,'Colgate products distributed by Kiboko across Uganda.','https://www.colgatepalmolive.com',3,1,1,'2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(5,3,'partner','Kellogg\'s','kelloggs',29,'Kellogg\'s products distributed by Kiboko across Uganda.','https://www.kelloggs.com',4,1,1,'2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(6,2,'partner','Reckitt','reckitt',30,'Reckitt products distributed by Kiboko across Uganda.','https://www.reckitt.com',5,1,1,'2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(7,3,'partner','Bidco','bidco',31,'Bidco products distributed by Kiboko across Uganda.','https://www.bidcoafrica.com',6,1,1,'2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(8,3,'own','KIBS','kiboko-fresh',40,'Kiboko Fresh is part of the Kiboko own-brand portfolio.',NULL,7,1,1,'2026-11-11 14:53:46','2026-11-11 15:21:15',NULL),(9,3,'own','Savanna Gold','savanna-gold',33,'Savanna Gold is part of the Kiboko own-brand portfolio.',NULL,8,1,1,'2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(10,4,'own','Nile Pure','nile-pure',34,'Nile Pure is part of the Kiboko own-brand portfolio.',NULL,9,0,1,'2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(11,1,'own','Kiboko Care','kiboko-care',35,'Kiboko Care is part of the Kiboko own-brand portfolio.',NULL,10,0,1,'2026-11-11 14:53:46','2026-11-11 14:53:46',NULL);
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_milestones`
--

DROP TABLE IF EXISTS `company_milestones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_milestones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `year` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_milestones`
--

LOCK TABLES `company_milestones` WRITE;
/*!40000 ALTER TABLE `company_milestones` DISABLE KEYS */;
INSERT INTO `company_milestones` VALUES (1,'2010','Founded in Kampala','Kiboko starts operations from a single warehouse.',0,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(2,'2014','First regional depot','Expansion into Eastern Uganda.',1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(3,'2018','International principals','Partnerships with leading global FMCG manufacturers.',2,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(4,'2022','Fleet of 100+ vehicles','Own delivery fleet reaches every region.',3,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(5,'2025','Nationwide coverage','Eight hubs serving thousands of outlets.',4,'2026-11-11 14:53:46','2026-11-11 14:53:46');
/*!40000 ALTER TABLE `company_milestones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_values`
--

DROP TABLE IF EXISTS `company_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_values` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `icon` varchar(60) DEFAULT NULL,
  `heading` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_values`
--

LOCK TABLES `company_values` WRITE;
/*!40000 ALTER TABLE `company_values` DISABLE KEYS */;
INSERT INTO `company_values` VALUES (1,'fa-bullseye','Our Purpose','To connect great brands with the people of Uganda, improving lives through access to quality products.',0,1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(2,'fa-eye','Our Vision','To be the most trusted and efficient distribution partner in East Africa.',1,1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(3,'fa-handshake','Our Values','Integrity, customer focus, teamwork, excellence in execution and respect for our communities.',2,1,'2026-11-11 14:53:46','2026-11-11 14:53:46');
/*!40000 ALTER TABLE `company_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_enquiries`
--

DROP TABLE IF EXISTS `contact_enquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_enquiries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `reply` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_enquiries_is_read_index` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_enquiries`
--

LOCK TABLES `contact_enquiries` WRITE;
/*!40000 ALTER TABLE `contact_enquiries` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_enquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coverage_locations`
--

DROP TABLE IF EXISTS `coverage_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coverage_locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `district` varchar(255) DEFAULT NULL,
  `region` varchar(40) DEFAULT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'hub',
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `description` text DEFAULT NULL,
  `media_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coverage_locations_media_id_foreign` (`media_id`),
  KEY `coverage_locations_region_index` (`region`),
  CONSTRAINT `coverage_locations_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coverage_locations`
--

LOCK TABLES `coverage_locations` WRITE;
/*!40000 ALTER TABLE `coverage_locations` DISABLE KEYS */;
INSERT INTO `coverage_locations` VALUES (1,'Kampala Head Office & Hub','Kampala','Central','hub',0.3476000,32.5825000,'Head office and main national distribution centre.',NULL,1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(2,'Masaka Depot','Masaka','Central','depot',-0.3338000,31.7341000,'Serving the greater Masaka region.',NULL,1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(3,'Jinja Hub','Jinja','Eastern','hub',0.4244000,33.2042000,'Eastern region distribution hub.',NULL,1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(4,'Mbale Hub','Mbale','Eastern','hub',1.0827000,34.1750000,'Serving Elgon and Bukedi sub-regions.',NULL,1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(5,'Soroti Depot','Soroti','Eastern','depot',1.7146000,33.6111000,'Serving Teso sub-region.',NULL,1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(6,'Gulu Hub','Gulu','Northern','hub',2.7724000,32.2881000,'Northern region distribution hub.',NULL,1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(7,'Lira Depot','Lira','Northern','depot',2.2499000,32.8999000,'Serving Lango sub-region.',NULL,1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(8,'Arua Hub','Arua','Northern','hub',3.0201000,30.9111000,'Serving West Nile.',NULL,1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(9,'Mbarara Hub','Mbarara','Western','hub',-0.6072000,30.6545000,'Western region distribution hub.',NULL,1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(10,'Fort Portal Depot','Kabarole','Western','depot',0.6710000,30.2750000,'Serving the Rwenzori sub-region.',NULL,1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(11,'Hoima Hub','Hoima','Western','hub',1.4331000,31.3524000,'Serving Bunyoro sub-region.',NULL,1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(12,'Kabale Depot','Kabale','Western','depot',-1.2486000,29.9899000,'Serving Kigezi sub-region.',NULL,1,'2026-11-11 14:53:46','2026-11-11 14:53:46');
/*!40000 ALTER TABLE `coverage_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
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
-- Table structure for table `hero_sections`
--

DROP TABLE IF EXISTS `hero_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hero_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) unsigned NOT NULL,
  `heading` varchar(255) NOT NULL,
  `text` text DEFAULT NULL,
  `media_id` bigint(20) unsigned DEFAULT NULL,
  `button_label` varchar(255) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `button2_label` varchar(255) DEFAULT NULL,
  `button2_url` varchar(255) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hero_sections_media_id_foreign` (`media_id`),
  KEY `hero_sections_page_id_is_active_sort_order_index` (`page_id`,`is_active`,`sort_order`),
  CONSTRAINT `hero_sections_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `hero_sections_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hero_sections`
--

LOCK TABLES `hero_sections` WRITE;
/*!40000 ALTER TABLE `hero_sections` DISABLE KEYS */;
INSERT INTO `hero_sections` VALUES (1,1,'Uganda\'s Trusted Distribution Partner','Connecting leading brands to people across Uganda.',37,'Partner With Us','/contact-us','Explore Our Capabilities','/what-we-do',0,1,'2026-11-11 14:53:45','2026-11-11 15:10:38'),(2,1,'From Brand to Basket','Importation, warehousing, sales and last-mile delivery, all under one roof.',4,'Partner With Us','/contact-us','Explore Our Capabilities','/what-we-do',1,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(3,1,'Reaching Every Corner of Uganda','Thousands of retail outlets served every week by our own fleet and sales teams.',5,'See Our Reach','/our-reach','Contact Us','/contact-us',2,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(4,2,'About Kiboko','A proudly Ugandan distribution company built on trust, reach and execution excellence.',6,NULL,NULL,NULL,NULL,0,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(5,3,'What We Do','A comprehensive distribution platform that creates market access, builds brands and drives growth.',8,NULL,NULL,NULL,NULL,0,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(6,4,'Our Brands & Partners','We are proud to represent some of the world’s most loved brands, alongside our own growing portfolio.',9,NULL,NULL,NULL,NULL,0,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(7,5,'Our Reach','Nationwide coverage through strategically located hubs, depots and a modern delivery fleet.',10,NULL,NULL,NULL,NULL,0,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(8,6,'Insights','News, activations, product launches and community stories from across Kiboko.',11,NULL,NULL,NULL,NULL,0,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(9,7,'Contact Us','We’d love to hear from you.',12,NULL,NULL,NULL,NULL,0,1,'2026-11-11 14:53:45','2026-11-11 14:53:45');
/*!40000 ALTER TABLE `hero_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insight_categories`
--

DROP TABLE IF EXISTS `insight_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `insight_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `insight_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insight_categories`
--

LOCK TABLES `insight_categories` WRITE;
/*!40000 ALTER TABLE `insight_categories` DISABLE KEYS */;
INSERT INTO `insight_categories` VALUES (1,'News','news',0,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(2,'Activations','activations',1,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(3,'Product Launches','product-launches',2,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(4,'CSR','csr',3,'2026-11-11 14:53:46','2026-11-11 14:53:46');
/*!40000 ALTER TABLE `insight_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insights`
--

DROP TABLE IF EXISTS `insights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `insights` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `insight_category_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `media_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `insights_slug_unique` (`slug`),
  KEY `insights_insight_category_id_foreign` (`insight_category_id`),
  KEY `insights_user_id_foreign` (`user_id`),
  KEY `insights_media_id_foreign` (`media_id`),
  KEY `insights_status_published_at_index` (`status`,`published_at`),
  CONSTRAINT `insights_insight_category_id_foreign` FOREIGN KEY (`insight_category_id`) REFERENCES `insight_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `insights_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `insights_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insights`
--

LOCK TABLES `insights` WRITE;
/*!40000 ALTER TABLE `insights` DISABLE KEYS */;
INSERT INTO `insights` VALUES (1,1,1,'Kiboko opens a new regional depot in Mbale','kiboko-opens-a-new-regional-depot-in-mbale','Our new Mbale depot brings faster deliveries to retailers across Eastern Uganda.','<p>Our new Mbale depot brings faster deliveries to retailers across Eastern Uganda.</p><p>This is sample article content. Edit or replace it from the admin dashboard under Insights Articles.</p><h3>Why it matters</h3><p>Every step we take is about bringing great brands closer to the people of Uganda, more reliably and more efficiently.</p>',19,'published','2026-11-06 14:53:46','2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(2,2,1,'Weekend market activation reaches 20,000 shoppers','weekend-market-activation-reaches-20000-shoppers','Sampling, games and great offers drew crowds at markets across Kampala.','<p>Sampling, games and great offers drew crowds at markets across Kampala.</p><p>This is sample article content. Edit or replace it from the admin dashboard under Insights Articles.</p><h3>Why it matters</h3><p>Every step we take is about bringing great brands closer to the people of Uganda, more reliably and more efficiently.</p>',20,'published','2026-10-30 14:53:46','2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(3,3,1,'New product range launched in supermarkets nationwide','new-product-range-launched-in-supermarkets-nationwide','A new range from one of our principal partners is now on shelves countrywide.','<p>A new range from one of our principal partners is now on shelves countrywide.</p><p>This is sample article content. Edit or replace it from the admin dashboard under Insights Articles.</p><h3>Why it matters</h3><p>Every step we take is about bringing great brands closer to the people of Uganda, more reliably and more efficiently.</p>',21,'published','2026-10-22 14:53:46','2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(4,4,1,'Staff volunteer at community clean-up in Kampala','staff-volunteer-at-community-clean-up-in-kampala','Over 100 Kiboko team members joined hands to give back to our community.','<p>Over 100 Kiboko team members joined hands to give back to our community.</p><p>This is sample article content. Edit or replace it from the admin dashboard under Insights Articles.</p><h3>Why it matters</h3><p>Every step we take is about bringing great brands closer to the people of Uganda, more reliably and more efficiently.</p>',22,'published','2026-10-14 14:53:46','2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(5,1,1,'Fleet expansion adds 30 new delivery vehicles','fleet-expansion-adds-30-new-delivery-vehicles','More vehicles mean more frequent deliveries to upcountry outlets.','<p>More vehicles mean more frequent deliveries to upcountry outlets.</p><p>This is sample article content. Edit or replace it from the admin dashboard under Insights Articles.</p><h3>Why it matters</h3><p>Every step we take is about bringing great brands closer to the people of Uganda, more reliably and more efficiently.</p>',23,'published','2026-10-02 14:53:46','2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(6,1,1,'Kiboko signs new distribution partnership','kiboko-signs-new-distribution-partnership','A new principal partner joins the Kiboko portfolio.','<p>A new principal partner joins the Kiboko portfolio.</p><p>This is sample article content. Edit or replace it from the admin dashboard under Insights Articles.</p><h3>Why it matters</h3><p>Every step we take is about bringing great brands closer to the people of Uganda, more reliably and more efficiently.</p>',24,'published','2026-09-17 14:53:46','2026-11-11 14:53:46','2026-11-11 14:53:46',NULL);
/*!40000 ALTER TABLE `insights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `folder` varchar(60) NOT NULL DEFAULT 'general',
  `title` varchar(255) DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `thumb_path` varchar(255) DEFAULT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `mime` varchar(100) DEFAULT NULL,
  `size` int(10) unsigned NOT NULL DEFAULT 0,
  `width` int(10) unsigned DEFAULT NULL,
  `height` int(10) unsigned DEFAULT NULL,
  `uploaded_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_uploaded_by_foreign` (`uploaded_by`),
  KEY `media_folder_index` (`folder`),
  CONSTRAINT `media_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (1,'branding','Kiboko logo','media/branding/kiboko-logo.svg',NULL,'Kiboko Enterprises','image/svg+xml',540,360,100,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(2,'branding','Kiboko icon','media/branding/kiboko-icon.svg',NULL,'Kiboko icon','image/svg+xml',242,80,80,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(3,'seed','Kiboko distribution truck','media/seed/hero-home-truck.svg',NULL,'Kiboko delivery truck on a Ugandan road','image/svg+xml',1465,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(4,'seed','Kiboko warehouse','media/seed/hero-home-warehouse.svg',NULL,'Stocked shelves inside the Kiboko warehouse','image/svg+xml',5270,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(5,'seed','Retail shelf','media/seed/hero-home-retail.svg',NULL,'FMCG products on a supermarket shelf','image/svg+xml',6159,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(6,'seed','Kiboko offices','media/seed/hero-about.svg',NULL,'Kiboko head office building','image/svg+xml',5147,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(7,'seed','Warehouse operations','media/seed/about-story.svg',NULL,'Kiboko team managing stock in the warehouse','image/svg+xml',5274,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(8,'seed','Distribution operations','media/seed/hero-services.svg',NULL,'Kiboko distribution network','image/svg+xml',2025,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(9,'seed','Supermarket aisle','media/seed/hero-brands.svg',NULL,'Supermarket aisle stocked with leading brands','image/svg+xml',6164,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(10,'seed','Truck in the Ugandan countryside','media/seed/hero-reach.svg',NULL,'Kiboko truck delivering across rural Uganda','image/svg+xml',1472,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(11,'seed','Kiboko team','media/seed/hero-insights.svg',NULL,'Kiboko sales team at a briefing','image/svg+xml',1604,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(12,'seed','Kiboko head office','media/seed/hero-contact.svg',NULL,'Kiboko head office reception','image/svg+xml',5151,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(13,'seed','Importation and sourcing','media/seed/service-importation.svg',NULL,'Containers being offloaded at port','image/svg+xml',2592,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(14,'seed','Warehousing and storage','media/seed/service-warehousing.svg',NULL,'Racked pallets in a modern warehouse','image/svg+xml',5277,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(15,'seed','Sales and merchandising','media/seed/service-sales.svg',NULL,'Merchandiser arranging products on a shelf','image/svg+xml',6170,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(16,'seed','Distribution and logistics','media/seed/service-distribution.svg',NULL,'Delivery truck leaving the depot','image/svg+xml',1466,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(17,'seed','Brand building and trade marketing','media/seed/service-brand-building.svg',NULL,'Brand activation team engaging shoppers','image/svg+xml',1627,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(18,'seed','Supply chain solutions','media/seed/service-supply-chain.svg',NULL,'Supply chain planning dashboard','image/svg+xml',2024,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(19,'seed','New depot opening','media/seed/insight-new-depot.svg',NULL,'New Kiboko depot','image/svg+xml',5271,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(20,'seed','Market activation','media/seed/insight-activation.svg',NULL,'Brand activation in a Kampala market','image/svg+xml',1610,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(21,'seed','Product launch','media/seed/insight-launch.svg',NULL,'New product on display','image/svg+xml',6161,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(22,'seed','Community outreach','media/seed/insight-csr.svg',NULL,'Kiboko staff at a community event','image/svg+xml',1611,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(23,'seed','Fleet expansion','media/seed/insight-fleet.svg',NULL,'New delivery vehicles','image/svg+xml',1455,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(24,'seed','New partnership','media/seed/insight-partnership.svg',NULL,'Partnership signing','image/svg+xml',2017,1600,900,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(25,'brands','Unilever logo','media/brands/logo-unilever.svg',NULL,'Unilever logo','image/svg+xml',340,400,200,NULL,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(26,'brands','P&G logo','media/brands/logo-pg.svg',NULL,'P&G logo','image/svg+xml',339,400,200,NULL,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(27,'brands','Nestlé logo','media/brands/logo-nestle.svg',NULL,'Nestlé logo','image/svg+xml',339,400,200,NULL,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(28,'brands','Colgate logo','media/brands/logo-colgate.svg',NULL,'Colgate logo','image/svg+xml',339,400,200,NULL,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(29,'brands','Kellogg\'s logo','media/brands/logo-kelloggs.svg',NULL,'Kellogg\'s logo','image/svg+xml',341,400,200,NULL,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(30,'brands','Reckitt logo','media/brands/logo-reckitt.svg',NULL,'Reckitt logo','image/svg+xml',339,400,200,NULL,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(31,'brands','Bidco logo','media/brands/logo-bidco.svg',NULL,'Bidco logo','image/svg+xml',337,400,200,NULL,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(32,'brands','Kiboko Fresh logo','media/brands/logo-kiboko-fresh.svg',NULL,'Kiboko Fresh logo','image/svg+xml',332,400,200,NULL,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(33,'brands','Savanna Gold logo','media/brands/logo-savanna-gold.svg',NULL,'Savanna Gold logo','image/svg+xml',332,400,200,NULL,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(34,'brands','Nile Pure logo','media/brands/logo-nile-pure.svg',NULL,'Nile Pure logo','image/svg+xml',341,400,200,NULL,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(35,'brands','Kiboko Care logo','media/brands/logo-kiboko-care.svg',NULL,'Kiboko Care logo','image/svg+xml',343,400,200,NULL,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(36,'services','wh1 (1)','media/services/2026/11/6gXCnERB56q47BJ3pOzSY4n6bibmdtIq.jpg','media/services/2026/11/6gXCnERB56q47BJ3pOzSY4n6bibmdtIq_thumb.jpg','Warehousing & Storage','image/jpeg',182825,1024,682,1,'2026-11-11 15:06:06','2026-11-11 15:06:06'),(37,'services','kiboko-ent (1)','media/services/2026/11/OkJWkvL90Gv7qdb2GrCHhRRwMKK8dEgh.jpg','media/services/2026/11/OkJWkvL90Gv7qdb2GrCHhRRwMKK8dEgh_thumb.jpg','Distribution & Logistics','image/jpeg',344086,1920,1269,1,'2026-11-11 15:08:53','2026-11-11 15:08:53'),(38,'settings','kibokologowhitebg-8b75828c','media/settings/2026/11/rihMhnB6fa24oqe0u8tQ57q38NoupkxQ.webp',NULL,'Logo','image/webp',4060,200,54,1,'2026-11-11 15:10:01','2026-11-11 15:10:01'),(39,'brands','pglogo-c1c47d82162361a1196f74c946d788af','media/brands/2026/11/QncWbQEGC5JdjXnbK7Lcqy697dC5pW96.png',NULL,'P&G','image/png',14619,240,150,1,'2026-11-11 15:14:12','2026-11-11 15:14:12'),(40,'brands','1774422577_Asset33KIBSPNGLOGO','media/brands/2026/11/Vx5lR9HIWsZzBUz7Aqj0sBHjyCdoXCdM.jpg','media/brands/2026/11/Vx5lR9HIWsZzBUz7Aqj0sBHjyCdoXCdM_thumb.jpg','KIBS','image/jpeg',125695,1920,1352,1,'2026-11-11 15:21:15','2026-11-11 15:21:15');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_11_11_000100_create_cms_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `navigation_items`
--

DROP TABLE IF EXISTS `navigation_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `navigation_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `location` varchar(20) NOT NULL DEFAULT 'header',
  `new_tab` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `navigation_items_location_is_active_sort_order_index` (`location`,`is_active`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `navigation_items`
--

LOCK TABLES `navigation_items` WRITE;
/*!40000 ALTER TABLE `navigation_items` DISABLE KEYS */;
INSERT INTO `navigation_items` VALUES (1,'Home','/','header',0,1,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(2,'About Us','/about-us','header',0,2,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(3,'What We Do','/what-we-do','header',0,3,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(4,'Brands & Partners','/brands-partners','header',0,4,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(5,'Our Reach','/our-reach','header',0,5,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(6,'Insights','/insights','header',0,6,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(7,'Careers','/careers','header',0,7,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(8,'Contact','/contact-us','header',0,8,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(9,'About Us','/about-us','footer',0,1,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(10,'What We Do','/what-we-do','footer',0,2,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(11,'Brands & Partners','/brands-partners','footer',0,3,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(12,'Our Reach','/our-reach','footer',0,4,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(13,'Insights','/insights','footer',0,5,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(14,'Careers','/careers','footer',0,6,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(15,'Contact Us','/contact-us','footer',0,7,1,'2026-11-11 14:53:45','2026-11-11 14:53:45');
/*!40000 ALTER TABLE `navigation_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_sections`
--

DROP TABLE IF EXISTS `page_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) unsigned NOT NULL,
  `type` varchar(40) NOT NULL,
  `eyebrow` varchar(255) DEFAULT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `media_id` bigint(20) unsigned DEFAULT NULL,
  `background_media_id` bigint(20) unsigned DEFAULT NULL,
  `background_color` varchar(20) DEFAULT NULL,
  `button_label` varchar(255) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `variant` varchar(60) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_sections_media_id_foreign` (`media_id`),
  KEY `page_sections_background_media_id_foreign` (`background_media_id`),
  KEY `page_sections_page_id_is_visible_sort_order_index` (`page_id`,`is_visible`,`sort_order`),
  CONSTRAINT `page_sections_background_media_id_foreign` FOREIGN KEY (`background_media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `page_sections_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `page_sections_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_sections`
--

LOCK TABLES `page_sections` WRITE;
/*!40000 ALTER TABLE `page_sections` DISABLE KEYS */;
INSERT INTO `page_sections` VALUES (1,1,'stats',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'home',10,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(2,1,'services_cards','What We Do','From Brand to Basket','We manage the full journey of your products, from the port to the shelf, so that your brand is available, visible and growing in every market we serve.',NULL,NULL,NULL,'Explore Our Capabilities','/what-we-do','4',20,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(3,1,'brands_carousel','Our Partners','Trusted Brands. Strong Partnerships.',NULL,NULL,NULL,NULL,'View All Brands','/brands-partners',NULL,30,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(4,1,'cta',NULL,'Ready to grow your brand in Uganda?','Talk to us about importation, distribution and route-to-market solutions built for your brand.',NULL,NULL,NULL,'Partner With Us','/contact-us',NULL,40,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(5,2,'story','Our Story','Built on trust. Driven by growth.','<p>Kiboko Enterprises began with a simple idea: that great brands deserve to reach every Ugandan household, wherever they are.</p><p>From a single warehouse in Kampala, we have grown into a nationwide distribution business serving thousands of retail outlets through our own sales force, warehouses and delivery fleet.</p><p>Today we partner with leading local and international manufacturers, combining deep market knowledge with modern systems to move products efficiently from brand to basket.</p>',7,NULL,NULL,'Our Journey',NULL,NULL,10,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(6,2,'values','What Drives Us','Our Purpose, Vision & Values',NULL,NULL,NULL,NULL,NULL,NULL,NULL,20,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(7,2,'cta',NULL,'Let’s build something great together','Discover how Kiboko can take your brand further across Uganda.',NULL,NULL,NULL,'Partner With Us','/contact-us',NULL,30,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(8,3,'services_grid','Our Capabilities','A complete route-to-market platform','Six integrated capabilities that take your products from source to shelf.',NULL,NULL,NULL,NULL,NULL,NULL,10,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(9,3,'cta',NULL,'Need a distribution partner?','Our team will design a route-to-market plan for your brand.',NULL,NULL,NULL,'Partner With Us','/contact-us',NULL,20,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(10,4,'brands_tabs','Our Portfolio','Brands Uganda Loves',NULL,NULL,NULL,NULL,'& Many More','/contact-us',NULL,10,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(11,4,'cta',NULL,'Become a Kiboko partner','Join the principals who trust Kiboko to grow their brands in Uganda.',NULL,NULL,NULL,'Partner With Us','/contact-us',NULL,20,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(12,5,'reach_map','Our Network','Nationwide Coverage','<p>Our distribution hubs and depots cover the Central, Eastern, Northern and Western regions, making sure products reach shops, supermarkets, wholesalers and markets on time.</p>',NULL,NULL,NULL,NULL,NULL,'reach',10,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(13,6,'insights_list','Latest Updates','News & Stories',NULL,NULL,NULL,NULL,NULL,NULL,'9',10,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(14,7,'contact','Get in Touch','Talk to our team','Whether you are a brand looking for a distribution partner, a retailer or a job seeker, our team is ready to help.',NULL,NULL,NULL,'Send Message',NULL,NULL,10,1,'2026-11-11 14:53:45','2026-11-11 14:53:45');
/*!40000 ALTER TABLE `page_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_is_published_index` (`is_published`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Home','home',1,0,'2026-11-11 14:53:45','2026-11-11 14:53:45',NULL),(2,'About Us','about-us',1,1,'2026-11-11 14:53:45','2026-11-11 14:53:45',NULL),(3,'What We Do','what-we-do',1,2,'2026-11-11 14:53:45','2026-11-11 14:53:45',NULL),(4,'Brands & Partners','brands-partners',1,3,'2026-11-11 14:53:45','2026-11-11 14:53:45',NULL),(5,'Our Reach','our-reach',1,4,'2026-11-11 14:53:45','2026-11-11 14:53:45',NULL),(6,'Insights','insights',1,5,'2026-11-11 14:53:45','2026-11-11 14:53:45',NULL),(7,'Contact Us','contact-us',1,6,'2026-11-11 14:53:45','2026-11-11 14:53:45',NULL);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_role` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_role`
--

LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` VALUES (1,1),(1,2),(2,1),(2,2),(3,1),(3,2),(4,1),(4,2),(5,1),(5,2),(6,1),(6,2),(7,1),(7,2),(8,1),(8,2),(9,1),(9,2),(10,1),(10,2),(11,1),(11,2),(12,1),(12,2),(13,1),(13,2),(14,1),(14,2),(15,1),(15,2),(16,1),(17,1),(18,1),(18,2),(19,1),(19,2),(20,1);
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'Pages','pages','2026-11-11 14:53:45','2026-11-11 14:53:45'),(2,'Page Sections','sections','2026-11-11 14:53:45','2026-11-11 14:53:45'),(3,'Hero Sections','heroes','2026-11-11 14:53:45','2026-11-11 14:53:45'),(4,'Navigation','navigation','2026-11-11 14:53:45','2026-11-11 14:53:45'),(5,'Company Statistics','statistics','2026-11-11 14:53:45','2026-11-11 14:53:45'),(6,'Services','services','2026-11-11 14:53:45','2026-11-11 14:53:45'),(7,'Own Brands','brands','2026-11-11 14:53:45','2026-11-11 14:53:45'),(8,'Principal Partners','partners','2026-11-11 14:53:45','2026-11-11 14:53:45'),(9,'Brand Categories','brand_categories','2026-11-11 14:53:45','2026-11-11 14:53:45'),(10,'Company Story','milestones','2026-11-11 14:53:45','2026-11-11 14:53:45'),(11,'Purpose, Vision & Values','values','2026-11-11 14:53:45','2026-11-11 14:53:45'),(12,'Distribution Coverage','locations','2026-11-11 14:53:45','2026-11-11 14:53:45'),(13,'Insight Categories','insight_categories','2026-11-11 14:53:45','2026-11-11 14:53:45'),(14,'Insights Articles','insights','2026-11-11 14:53:45','2026-11-11 14:53:45'),(15,'SEO Management','seo','2026-11-11 14:53:45','2026-11-11 14:53:45'),(16,'Users','users','2026-11-11 14:53:45','2026-11-11 14:53:45'),(17,'Roles & Permissions','roles','2026-11-11 14:53:45','2026-11-11 14:53:45'),(18,'Media','media','2026-11-11 14:53:45','2026-11-11 14:53:45'),(19,'Enquiries','enquiries','2026-11-11 14:53:45','2026-11-11 14:53:45'),(20,'Settings','settings','2026-11-11 14:53:45','2026-11-11 14:53:45');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Administrator','super-admin','2026-11-11 14:53:45','2026-11-11 14:53:45'),(2,'Content Editor','editor','2026-11-11 14:53:45','2026-11-11 14:53:45');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_metadata`
--

DROP TABLE IF EXISTS `seo_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_metadata` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seoable_type` varchar(255) NOT NULL,
  `seoable_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `media_id` bigint(20) unsigned DEFAULT NULL,
  `canonical_url` varchar(255) DEFAULT NULL,
  `noindex` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seo_metadata_seoable_type_seoable_id_unique` (`seoable_type`,`seoable_id`),
  KEY `seo_metadata_seoable_type_seoable_id_index` (`seoable_type`,`seoable_id`),
  KEY `seo_metadata_media_id_foreign` (`media_id`),
  CONSTRAINT `seo_metadata_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_metadata`
--

LOCK TABLES `seo_metadata` WRITE;
/*!40000 ALTER TABLE `seo_metadata` DISABLE KEYS */;
INSERT INTO `seo_metadata` VALUES (1,'App\\Models\\Page',1,NULL,'Connecting leading brands to people across Uganda.',NULL,NULL,0,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(2,'App\\Models\\Page',2,'About Us','A proudly Ugandan distribution company built on trust, reach and execution excellence.',NULL,NULL,0,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(3,'App\\Models\\Page',3,'What We Do','A comprehensive distribution platform that creates market access, builds brands and drives growth.',NULL,NULL,0,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(4,'App\\Models\\Page',4,'Brands & Partners','We are proud to represent some of the world’s most loved brands, alongside our own growing portfolio.',NULL,NULL,0,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(5,'App\\Models\\Page',5,'Our Reach','Nationwide coverage through strategically located hubs, depots and a modern delivery fleet.',NULL,NULL,0,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(6,'App\\Models\\Page',6,'Insights','News, activations, product launches and community stories from across Kiboko.',NULL,NULL,0,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(7,'App\\Models\\Page',7,'Contact Us','We’d love to hear from you.',NULL,NULL,0,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(8,'App\\Models\\Service',1,NULL,'We source and import quality products from global manufacturers, handling clearance, compliance and freight.',NULL,NULL,0,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(9,'App\\Models\\Service',2,NULL,'Modern, secure warehouses with stock control systems that keep products safe and ready to move.',NULL,NULL,0,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(10,'App\\Models\\Service',3,NULL,'Dedicated sales and merchandising teams that win shelf space and keep products visible.',NULL,NULL,0,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(11,'App\\Models\\Service',4,NULL,'Reliable last-mile delivery to wholesalers, supermarkets and retailers across Uganda.',NULL,NULL,0,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(12,'App\\Models\\Service',5,NULL,'Activations, promotions and trade programmes that grow demand for your brands.',NULL,NULL,0,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(13,'App\\Models\\Service',6,NULL,'End-to-end planning, forecasting and reporting that make your supply chain efficient.',NULL,NULL,0,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(14,'App\\Models\\Insight',1,NULL,'Our new Mbale depot brings faster deliveries to retailers across Eastern Uganda.',NULL,NULL,0,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(15,'App\\Models\\Insight',2,NULL,'Sampling, games and great offers drew crowds at markets across Kampala.',NULL,NULL,0,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(16,'App\\Models\\Insight',3,NULL,'A new range from one of our principal partners is now on shelves countrywide.',NULL,NULL,0,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(17,'App\\Models\\Insight',4,NULL,'Over 100 Kiboko team members joined hands to give back to our community.',NULL,NULL,0,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(18,'App\\Models\\Insight',5,NULL,'More vehicles mean more frequent deliveries to upcountry outlets.',NULL,NULL,0,'2026-11-11 14:53:46','2026-11-11 14:53:46'),(19,'App\\Models\\Insight',6,NULL,'A new principal partner joins the Kiboko portfolio.',NULL,NULL,0,'2026-11-11 14:53:46','2026-11-11 14:53:46');
/*!40000 ALTER TABLE `seo_metadata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `icon` varchar(60) DEFAULT NULL,
  `media_id` bigint(20) unsigned DEFAULT NULL,
  `show_on_home` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`),
  KEY `services_media_id_foreign` (`media_id`),
  KEY `services_is_active_sort_order_index` (`is_active`,`sort_order`),
  CONSTRAINT `services_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Importation & Sourcing','importation-sourcing','We source and import quality products from global manufacturers, handling clearance, compliance and freight.','<p>Our importation team manages supplier relationships, shipping, customs clearance, standards certification and landed-cost control, giving principals a reliable, compliant flow of stock into Uganda.</p><h3>What you get</h3><ul><li>A dedicated account team</li><li>Clear monthly performance reporting</li><li>Coverage in all regions of Uganda</li></ul>',NULL,'fa-ship',13,1,0,1,'2026-11-11 14:53:45','2026-11-11 14:53:45',NULL),(2,'Warehousing & Storage','warehousing-storage','Modern, secure warehouses with stock control systems that keep products safe and ready to move.','<p>Our warehouses are racked, secured and run on a warehouse management system with batch and expiry tracking, first-expiry-first-out picking and regular stock audits.</p><h3>What you get</h3><ul><li>A dedicated account team</li><li>Clear monthly performance reporting</li><li>Coverage in all regions of Uganda</li></ul>',NULL,'fa-warehouse',36,1,1,1,'2026-11-11 14:53:45','2026-11-11 15:06:06',NULL),(3,'Sales & Merchandising','sales-merchandising','Dedicated sales and merchandising teams that win shelf space and keep products visible.','<p>Route-based sales representatives and merchandisers visit outlets on fixed journey plans to take orders, secure displays, enforce planograms and collect market intelligence.</p><h3>What you get</h3><ul><li>A dedicated account team</li><li>Clear monthly performance reporting</li><li>Coverage in all regions of Uganda</li></ul>',NULL,'fa-store',15,1,2,1,'2026-11-11 14:53:45','2026-11-11 14:53:45',NULL),(4,'Distribution & Logistics','distribution-logistics','Reliable last-mile delivery to wholesalers, supermarkets and retailers across Uganda.','<p>Our own fleet, supported by vetted partners, delivers on scheduled routes from regional hubs, with proof-of-delivery and real-time tracking.</p><h3>What you get</h3><ul><li>A dedicated account team</li><li>Clear monthly performance reporting</li><li>Coverage in all regions of Uganda</li></ul>',NULL,'fa-truck-fast',37,1,3,1,'2026-11-11 14:53:46','2026-11-11 15:08:53',NULL),(5,'Brand Building & Trade Marketing','brand-building-trade-marketing','Activations, promotions and trade programmes that grow demand for your brands.','<p>We plan and execute consumer activations, sampling, retailer incentive programmes and point-of-sale campaigns, and report on their impact.</p><h3>What you get</h3><ul><li>A dedicated account team</li><li>Clear monthly performance reporting</li><li>Coverage in all regions of Uganda</li></ul>',NULL,'fa-bullhorn',17,0,4,1,'2026-11-11 14:53:46','2026-11-11 14:53:46',NULL),(6,'Supply Chain Solutions','supply-chain-solutions','End-to-end planning, forecasting and reporting that make your supply chain efficient.','<p>Demand planning, replenishment, secondary-sales reporting and data insight help principals reduce stock-outs, control costs and plan growth.</p><h3>What you get</h3><ul><li>A dedicated account team</li><li>Clear monthly performance reporting</li><li>Coverage in all regions of Uganda</li></ul>',NULL,'fa-diagram-project',18,0,5,1,'2026-11-11 14:53:46','2026-11-11 14:53:46',NULL);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('0Ea5RFu0F8PaXiPGmQujPXKWpbBgk6fVzWTUZYCi',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS1FsUXdvUWU1RUVnSkJ2MGt3SzBKaFlLYVpYS0Y0YTZ1OFYxODVCciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC93aGF0LXdlLWRvIjtzOjU6InJvdXRlIjtzOjQ6InBhZ2UiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1794419628),('4NUOz14JQDtyZRIBX9TWRLsJEQh0CsW0Lg2s6iID',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS2JUeDdRU2RIS1ZxblBxd2U3Y2ZNNU5OWTI1SW5NSjROdGVzUVBSMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9vdXItcmVhY2giO3M6NToicm91dGUiO3M6NDoicGFnZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1794419628),('bFEvaWXdU3rTOhuxZZhkYblyYECO64v4dEyWhyBK',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjdpUEtYbElDZkVNUXRhTjhWMVUxNXB0T3pjYVVaYlpuOFdESEJtQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnNpZ2h0cyI7czo1OiJyb3V0ZSI7czo0OiJwYWdlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794419629),('bTgxibO9kReUgdU8sahsYIemEGk6WvBi4E2WKHMy',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmNRYWxES290Vk1rM215VWNidHVmSjNvOW1IUUJuZXA5bHFiOVFoMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9icmFuZHMtcGFydG5lcnMiO3M6NToicm91dGUiO3M6NDoicGFnZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1794419628),('CyLGgdP7U50WoKuzA8TJBXgtOLNBbUjSuSGYLZYl',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiejNvRk9PMjlxVkFHR1phTEtxbUtnRjUyVm5WN3F6UmVvbTRabnV2VSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1794419630),('DVM19woChQYUA9EXxpzdVpPVeir56FB1g9BInH3K',NULL,'127.0.0.1','','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMXV6MjNOZnEweUtWSTlHbGlEcjFXZGFQVTNVQW1wSkFYSTVLc2JWSSI7czoxNToiY29udGFjdF9mb3JtX2F0IjtpOjE3OTQ0MTk2Mjk7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jb250YWN0LXVzIjtzOjU6InJvdXRlIjtzOjQ6InBhZ2UiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1794419629),('HxOi2Cqvijc27dF2qyl6hXOiJWD5TTlFxffLGi80',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXVPeHA3cE1TUFBzTXh5TGZGOUNXYTBZWUdDUk5GZE9udmVVM3M2MiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXJlZXJzIjtzOjU6InJvdXRlIjtzOjc6ImNhcmVlcnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1794419629),('MVSVO9zf2spLjokaIPs96VEiDdxJXutBYrp0zDdZ',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiNXhSbnVCZEoySk9zWmh5WW14clFaYVA2bHEzSWRaWXpEQkxkYWhzeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hYm91dC11cyI7czo1OiJyb3V0ZSI7czo0OiJwYWdlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImEzNDExZDE0NDNiNzkxYzEyOTlhMTEyN2JhMThiODJkOTM1YjY2ODFhNjU3NGZjOGE0NDQwNTk5MzY4NTE1NzkiO30=',1794421584),('P0kekAoXATyNo5AqzBIOpHW03HB3qtni3lv77ohP',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV2ZZMVNxTUpuM1pKUWxQc3Z1NElGM2NmaUI5bjZLendPYVY0SzN2WiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJwYWdlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794419627),('Q8aaIt8ux2RkmthUDBq6WIjPS7JqOJDhJmkzuKrD',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUnZyMENtOHlRNFRZckhEc3g5N09IMDR2dk9zY3F2Ym5YQ1BxRkNJeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yb2JvdHMudHh0IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1794419630),('QYOTAiZjjBlnkVoB4gRxp2q7Tcqhg98t81XGHYFP',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoicFR3N29DNWFEalRZUVBLc0NFanFldzFIWmNiamNRUmhZcDZTUzA3dyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hYm91dC11cyI7czo1OiJyb3V0ZSI7czo0OiJwYWdlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794419627),('SInOEglCs89pr5ycgYEDJ9BbrQTdwAFAzHiHo7b8',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUWx1V3NhcjRaZXZwY3g3MVh1NXhDOFpCaHhtbmttM3Q5RHhTbXV2ZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlbWFwLnhtbCI7czo1OiJyb3V0ZSI7czo3OiJzaXRlbWFwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794419629),('sZz6LFMndVZTj4FYJ8oJohhWYNVyNKWxSEKZU8gy',NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Mobile Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOHk2MmpiTHFVZWowRzd0c0puQkRVZ2NuTURhMzd6WENIM2l5Z0x6ZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hYm91dC11cyI7czo1OiJyb3V0ZSI7czo0OiJwYWdlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxNToiY29udGFjdF9mb3JtX2F0IjtpOjE3OTQ0MTk2NTI7fQ==',1794419653),('xyMrYG4hByEqhE46JL04QS5xfDiIxeQlbiYvhAKh',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoid0pwY1JqQlJHYk5QUWQyR20yMFNKcWZYOEVxa0RTbFlvQkFsTlBsRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC93aGF0LXdlLWRvL2ltcG9ydGF0aW9uLXNvdXJjaW5nIjtzOjU6InJvdXRlIjtzOjEzOiJzZXJ2aWNlcy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794419630);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statistics`
--

DROP TABLE IF EXISTS `statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(40) NOT NULL DEFAULT 'home',
  `number` varchar(20) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `label` varchar(255) NOT NULL,
  `icon` varchar(60) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `statistics_group_is_active_sort_order_index` (`group`,`is_active`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistics`
--

LOCK TABLES `statistics` WRITE;
/*!40000 ALTER TABLE `statistics` DISABLE KEYS */;
INSERT INTO `statistics` VALUES (1,'home','15','+','Years of Experience','fa-award',0,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(2,'home','12,000','+','Retail Outlets','fa-store',1,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(3,'home','50','+','Brands','fa-tags',2,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(4,'home','400','+','Team Members','fa-users',3,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(5,'reach','12,000','+','Retail Outlets','fa-store',4,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(6,'reach','8',NULL,'Distribution Hubs','fa-warehouse',5,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(7,'reach','120','+','Delivery Vehicles','fa-truck',6,1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(8,'reach','100','%','Nationwide Coverage','fa-map-location-dot',7,1,'2026-11-11 14:53:45','2026-11-11 14:53:45');
/*!40000 ALTER TABLE `statistics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'Site Administrator','admin@kiboko.local',NULL,'$2y$12$S6QbLBWEEhQhZgvnPfQuZOEINFMHDuAZcylAj1h1s5obuiIQLsPLG',1,NULL,'2026-11-11 14:53:45','2026-11-11 14:53:45');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `website_settings`
--

DROP TABLE IF EXISTS `website_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `website_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(40) NOT NULL DEFAULT 'general',
  `key` varchar(80) NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'text',
  `value` text DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `website_settings_key_unique` (`key`),
  KEY `website_settings_group_index` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `website_settings`
--

LOCK TABLES `website_settings` WRITE;
/*!40000 ALTER TABLE `website_settings` DISABLE KEYS */;
INSERT INTO `website_settings` VALUES (1,'general','site_name','Site name','text','Kiboko Enterprises',0,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(2,'general','tagline','Tagline','text','Uganda\'s Trusted Distribution Partner',1,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(3,'general','logo','Logo','image','38',2,'2026-11-11 14:53:45','2026-11-11 15:10:01'),(4,'general','footer_logo','Footer logo (optional)','image',NULL,3,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(5,'general','favicon','Favicon','image','2',4,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(6,'general','fallback_image','Fallback image (shown when an image is missing)','image',NULL,5,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(7,'general','careers_url','Careers website (all Careers links go here)','url','https://jobs.kibokogroup.com',6,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(8,'general','hero_interval','Homepage slider speed (milliseconds)','number','6000',7,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(9,'general','footer_about','Footer description','textarea','Kiboko Enterprises connects leading local and international FMCG brands to retailers and consumers in every region of Uganda.',8,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(10,'general','copyright','Copyright text ({year} = current year)','text','© {year} Kiboko Enterprises. All rights reserved.',9,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(11,'contact','contact_address','Head office address','textarea','Plot 00, Industrial Area\nKampala, Uganda',10,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(12,'contact','contact_phone','Telephone','text','+256 700 000 000',11,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(13,'contact','contact_phone_2','Second telephone (optional)','text',NULL,12,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(14,'contact','contact_email','Email address','email','info@kibokogroup.com',13,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(15,'contact','notify_email','Send website enquiries to (blank = email above)','email',NULL,14,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(16,'contact','business_hours','Business hours','textarea','Mon – Fri: 8:00am – 5:00pm\nSat: 8:00am – 1:00pm',15,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(17,'social','social_facebook','Facebook URL','url',NULL,16,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(18,'social','social_x','X (Twitter) URL','url',NULL,17,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(19,'social','social_linkedin','LinkedIn URL','url',NULL,18,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(20,'social','social_instagram','Instagram URL','url',NULL,19,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(21,'social','social_youtube','YouTube URL','url',NULL,20,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(22,'seo','seo_description','Default meta description','textarea','Kiboko Enterprises is a leading Ugandan FMCG distribution company providing importation, warehousing, sales, merchandising and nationwide distribution.',21,'2026-11-11 14:53:45','2026-11-11 14:53:45'),(23,'seo','og_image','Default social share image','image','3',22,'2026-11-11 14:53:45','2026-11-11 14:53:45');
/*!40000 ALTER TABLE `website_settings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-11 21:29:27
