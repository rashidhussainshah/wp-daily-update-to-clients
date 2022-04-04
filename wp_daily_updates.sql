-- MySQL dump 10.13  Distrib 8.0.28, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: wp_daily_updates
-- ------------------------------------------------------
-- Server version	8.0.28-0ubuntu0.20.04.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int unsigned DEFAULT NULL,
  `order` int NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,1,'Category 1','category-1','2022-01-24 00:22:05','2022-01-24 00:22:05'),(2,NULL,1,'Category 2','category-2','2022-01-24 00:22:05','2022-01-24 00:22:05');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_rows`
--

DROP TABLE IF EXISTS `data_rows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_rows` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `data_type_id` int unsigned NOT NULL,
  `field` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `browse` tinyint(1) NOT NULL DEFAULT '1',
  `read` tinyint(1) NOT NULL DEFAULT '1',
  `edit` tinyint(1) NOT NULL DEFAULT '1',
  `add` tinyint(1) NOT NULL DEFAULT '1',
  `delete` tinyint(1) NOT NULL DEFAULT '1',
  `details` text COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `data_rows_data_type_id_foreign` (`data_type_id`),
  CONSTRAINT `data_rows_data_type_id_foreign` FOREIGN KEY (`data_type_id`) REFERENCES `data_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_rows`
--

LOCK TABLES `data_rows` WRITE;
/*!40000 ALTER TABLE `data_rows` DISABLE KEYS */;
INSERT INTO `data_rows` VALUES (1,1,'id','number','ID',1,0,0,0,0,0,NULL,1),(2,1,'name','text','Name',1,1,1,1,1,1,NULL,2),(3,1,'email','text','Email',1,1,1,1,1,1,NULL,3),(4,1,'password','password','Password',1,0,0,1,1,0,NULL,4),(5,1,'remember_token','text','Remember Token',0,0,0,0,0,0,NULL,5),(6,1,'created_at','timestamp','Created At',0,1,1,0,0,0,NULL,6),(7,1,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,7),(8,1,'avatar','image','Avatar',0,1,1,1,1,1,NULL,8),(9,1,'user_belongsto_role_relationship','relationship','Role',0,1,1,1,1,0,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsTo\",\"column\":\"role_id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"roles\",\"pivot\":0}',10),(10,1,'user_belongstomany_role_relationship','relationship','voyager::seeders.data_rows.roles',0,1,1,1,1,0,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsToMany\",\"column\":\"id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"user_roles\",\"pivot\":\"1\",\"taggable\":\"0\"}',11),(11,1,'settings','hidden','Settings',0,0,0,0,0,0,NULL,12),(12,2,'id','number','ID',1,0,0,0,0,0,NULL,1),(13,2,'name','text','Name',1,1,1,1,1,1,NULL,2),(14,2,'created_at','timestamp','Created At',0,0,0,0,0,0,NULL,3),(15,2,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,4),(16,3,'id','number','ID',1,0,0,0,0,0,NULL,1),(17,3,'name','text','Name',1,1,1,1,1,1,NULL,2),(18,3,'created_at','timestamp','Created At',0,0,0,0,0,0,NULL,3),(19,3,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,4),(20,3,'display_name','text','Display Name',1,1,1,1,1,1,NULL,5),(21,1,'role_id','text','Role',1,1,1,1,1,1,NULL,9),(22,4,'id','number','ID',1,0,0,0,0,0,NULL,1),(23,4,'parent_id','select_dropdown','Parent',0,0,1,1,1,1,'{\"default\":\"\",\"null\":\"\",\"options\":{\"\":\"-- None --\"},\"relationship\":{\"key\":\"id\",\"label\":\"name\"}}',2),(24,4,'order','text','Order',1,1,1,1,1,1,'{\"default\":1}',3),(25,4,'name','text','Name',1,1,1,1,1,1,NULL,4),(26,4,'slug','text','Slug',1,1,1,1,1,1,'{\"slugify\":{\"origin\":\"name\"}}',5),(27,4,'created_at','timestamp','Created At',0,0,1,0,0,0,NULL,6),(28,4,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,7),(29,5,'id','number','ID',1,0,0,0,0,0,'{}',1),(30,5,'author_id','text','Author',1,0,1,1,0,1,'{}',2),(31,5,'category_id','text','Category',0,0,1,1,1,0,'{}',3),(32,5,'title','text','Title',1,1,1,1,1,1,'{}',4),(33,5,'excerpt','text_area','Excerpt',0,0,1,1,1,1,'{}',5),(34,5,'body','rich_text_box','Body',1,0,1,1,1,1,'{}',6),(35,5,'image','image','Post Image',0,1,1,1,1,1,'{\"resize\":{\"width\":\"1000\",\"height\":\"null\"},\"quality\":\"70%\",\"upsize\":true,\"thumbnails\":[{\"name\":\"medium\",\"scale\":\"50%\"},{\"name\":\"small\",\"scale\":\"25%\"},{\"name\":\"cropped\",\"crop\":{\"width\":\"300\",\"height\":\"250\"}}]}',7),(36,5,'slug','text','Slug',1,0,1,1,1,1,'{\"slugify\":{\"origin\":\"title\",\"forceUpdate\":true},\"validation\":{\"rule\":\"unique:posts,slug\"}}',8),(37,5,'meta_description','text_area','Meta Description',0,0,1,1,1,1,'{}',9),(38,5,'meta_keywords','text_area','Meta Keywords',0,0,1,1,1,1,'{}',10),(39,5,'status','select_dropdown','Status',1,1,1,1,1,1,'{\"default\":\"DRAFT\",\"options\":{\"PUBLISHED\":\"published\",\"DRAFT\":\"draft\",\"PENDING\":\"pending\"}}',11),(40,5,'created_at','timestamp','Created At',0,1,1,0,0,0,'{}',12),(41,5,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',13),(42,5,'seo_title','text','SEO Title',0,1,1,1,1,1,'{}',14),(43,5,'featured','checkbox','Featured',1,1,1,1,1,1,'{}',15),(44,6,'id','number','ID',1,0,0,0,0,0,NULL,1),(45,6,'author_id','text','Author',1,0,0,0,0,0,NULL,2),(46,6,'title','text','Title',1,1,1,1,1,1,NULL,3),(47,6,'excerpt','text_area','Excerpt',1,0,1,1,1,1,NULL,4),(48,6,'body','rich_text_box','Body',1,0,1,1,1,1,NULL,5),(49,6,'slug','text','Slug',1,0,1,1,1,1,'{\"slugify\":{\"origin\":\"title\"},\"validation\":{\"rule\":\"unique:pages,slug\"}}',6),(50,6,'meta_description','text','Meta Description',1,0,1,1,1,1,NULL,7),(51,6,'meta_keywords','text','Meta Keywords',1,0,1,1,1,1,NULL,8),(52,6,'status','select_dropdown','Status',1,1,1,1,1,1,'{\"default\":\"INACTIVE\",\"options\":{\"INACTIVE\":\"INACTIVE\",\"ACTIVE\":\"ACTIVE\"}}',9),(53,6,'created_at','timestamp','Created At',1,1,1,0,0,0,NULL,10),(54,6,'updated_at','timestamp','Updated At',1,0,0,0,0,0,NULL,11),(55,6,'image','image','Page Image',0,1,1,1,1,1,NULL,12),(56,7,'id','text','Id',1,0,0,0,0,0,'{}',1),(57,7,'name','text','Name',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required|max:191|unique:projects,name\"},\"display\":{\"width\":\"6\"}}',2),(58,7,'client_id','hidden','Client',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',3),(59,7,'payment_mode','select_dropdown','Payment Mode',1,0,1,1,1,1,'{\"default\":\"Direct\",\"options\":{\"Direct\":\"Direct\",\"Fiver\":\"Fiver\",\"Upwork\":\"Upwork\",\"Payonner\":\"Payonner\"},\"display\":{\"width\":\"6\"}}',4),(60,7,'start_date','timestamp','Start Date',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',5),(61,7,'expected_delivery_date','timestamp','Expected Delivery Date',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',6),(62,7,'created_at','timestamp','Created At',0,0,1,1,0,1,'{}',8),(63,7,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',9),(64,7,'deleted_at','timestamp','Deleted At',0,0,0,0,0,0,'{}',10),(65,7,'project_belongsto_user_relationship','relationship','Client',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"scope\":\"onlyClient\",\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"client_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',7),(66,8,'id','text','Id',1,0,0,0,0,0,'{}',1),(67,8,'project_id','hidden','Project Id',1,0,1,1,1,1,'{}',2),(68,8,'title','text','Title',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',6),(69,8,'status','text','Status',1,1,1,1,1,1,'{\"default\":\"In Progress\",\"options\":{\"In Progress\":\"In Progress\",\"QA\":\"QA\",\"Completed\":\"Completed\"},\"display\":{\"width\":\"6\"}}',7),(70,8,'type','text','Type',1,1,1,1,1,1,'{\"default\":\"User Story\",\"options\":{\"User Story\":\"User Story\",\"Assignment\":\"Assignment\",\"Milestone\":\"Milestone\",\"Project\":\"Project\",\"Sprint\":\"Sprint\",\"Update\":\"Update\",\"Feature\":\"Feature\"},\"display\":{\"width\":\"6\"}}',8),(74,8,'attachment_files','file','Attachment Files',0,0,1,1,1,1,'{}',10),(75,8,'created_at','timestamp','Created At',0,0,1,1,0,1,'{}',11),(76,8,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',12),(77,8,'deleted_at','timestamp','Deleted At',0,0,0,0,0,0,'{}',13),(78,8,'project_target_belongsto_project_relationship','relationship','Project',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\Models\\\\Project\",\"table\":\"projects\",\"type\":\"belongsTo\",\"column\":\"project_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',4),(79,9,'id','text','Id',1,0,0,0,0,0,'{}',1),(80,9,'project_target_id','hidden','Project Target Id',1,1,1,1,1,1,'{}',3),(81,9,'developer_id','hidden','Developer Id',1,0,1,1,1,1,'{}',2),(87,9,'description','rich_text_box','Description',1,1,1,1,1,1,'{}',11),(88,9,'attachment_files','file','Attachment Files',0,0,1,1,1,1,'{}',12),(89,9,'created_at','timestamp','Created At',0,0,1,0,0,1,'{}',13),(90,9,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',14),(91,9,'deleted_at','timestamp','Deleted At',0,0,0,0,0,0,'{}',15),(92,9,'project_target_task_belongsto_project_target_task_relationship','relationship','Project Target',1,1,1,1,1,1,'{\"model\":\"App\\\\Models\\\\ProjectTarget\",\"table\":\"project_targets\",\"type\":\"belongsTo\",\"column\":\"project_target_id\",\"key\":\"id\",\"label\":\"title\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',4),(93,9,'project_target_task_belongsto_user_relationship','relationship','Developer',0,0,1,0,0,1,'{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"developer_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',6),(94,8,'developer_id','hidden','Developer Id',1,0,1,1,1,1,'{}',3),(95,8,'project_target_belongsto_user_relationship','relationship','Developer',0,0,1,0,0,1,'{\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"developer_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',5),(96,10,'id','text','Id',1,0,0,0,0,0,'{}',1),(99,10,'project_id','hidden','Project Id',1,0,1,1,1,1,'{}',2),(100,10,'developer_id','hidden','Developer Id',1,0,1,1,1,1,'{}',3),(101,10,'created_at','timestamp','Created At',0,0,1,0,0,1,'{}',4),(102,10,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',5),(103,10,'eod_belongsto_project_relationship','relationship','Project',1,1,1,1,1,1,'{\"model\":\"App\\\\Models\\\\Project\",\"table\":\"projects\",\"type\":\"belongsTo\",\"column\":\"project_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',6),(104,10,'email','rich_text_box','Email',1,1,1,1,1,1,'{}',7),(105,12,'id','text','Id',1,0,0,0,0,0,'{}',1),(106,12,'project_id','hidden','Project Id',1,1,1,1,1,1,'{}',3),(109,12,'cc','text','CC',0,0,1,1,1,1,'{\"description\":\"CC emails should be comma separated like a@webpenter.com, b@webpenter.com\"}',9),(110,12,'bcc','text','BCC',0,0,1,1,1,1,'{\"description\":\"BCC emails should be comma separated like a@webpenter.com, b@webpenter.com\"}',10),(111,12,'subject','text','Email Subject',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',8),(112,12,'greetings','rich_text_box','Email Greetings',1,0,1,1,1,1,'{}',11),(113,12,'signature','rich_text_box','Signature',1,0,1,1,1,1,'{}',12),(114,12,'developer_id','hidden','Developer Id',1,1,1,1,1,1,'{}',5),(115,12,'created_at','timestamp','Created At',0,0,1,0,0,1,'{}',13),(116,12,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',14),(117,12,'eod_configuration_belongsto_project_relationship','relationship','Project',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\Models\\\\Project\",\"table\":\"projects\",\"type\":\"belongsTo\",\"column\":\"project_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',2),(118,12,'eod_configuration_belongsto_user_relationship','relationship','From',1,1,1,1,1,0,'{\"display\":{\"width\":\"6\",\"scope\":\"onlyDeveloper\"},\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"developer_id\",\"key\":\"id\",\"label\":\"email\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',4),(119,12,'eod_configuration_belongsto_user_relationship_1','relationship','To',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"scope\":\"onlyClient\",\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"client_id\",\"key\":\"id\",\"label\":\"email\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',6),(120,12,'client_id','hidden','Client Id',1,0,1,1,1,1,'{}',7),(123,13,'id','text','Id',1,0,0,0,0,0,'{}',1),(124,13,'mailable','text','Mailable',1,1,1,1,1,1,'{}',2),(125,13,'subject','text','Subject',0,1,1,1,1,1,'{}',3),(126,13,'html_template','rich_text_box','Html Template',1,1,1,1,1,1,'{}',4),(127,13,'text_template','text_area','Text Template',0,1,1,1,1,1,'{}',5),(128,13,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',6),(129,13,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',7);
/*!40000 ALTER TABLE `data_rows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_types`
--

DROP TABLE IF EXISTS `data_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_singular` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_plural` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `controller` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generate_permissions` tinyint(1) NOT NULL DEFAULT '0',
  `server_side` tinyint NOT NULL DEFAULT '0',
  `details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_types_name_unique` (`name`),
  UNIQUE KEY `data_types_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_types`
--

LOCK TABLES `data_types` WRITE;
/*!40000 ALTER TABLE `data_types` DISABLE KEYS */;
INSERT INTO `data_types` VALUES (1,'users','users','User','Users','voyager-person','TCG\\Voyager\\Models\\User','TCG\\Voyager\\Policies\\UserPolicy','TCG\\Voyager\\Http\\Controllers\\VoyagerUserController','',1,0,NULL,'2022-01-24 00:22:04','2022-01-24 00:22:04'),(2,'menus','menus','Menu','Menus','voyager-list','TCG\\Voyager\\Models\\Menu',NULL,'','',1,0,NULL,'2022-01-24 00:22:04','2022-01-24 00:22:04'),(3,'roles','roles','Role','Roles','voyager-lock','TCG\\Voyager\\Models\\Role',NULL,'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController','',1,0,NULL,'2022-01-24 00:22:04','2022-01-24 00:22:04'),(4,'categories','categories','Category','Categories','voyager-categories','TCG\\Voyager\\Models\\Category',NULL,'','',1,0,NULL,'2022-01-24 00:22:05','2022-01-24 00:22:05'),(5,'posts','posts','Post','Posts','voyager-news','TCG\\Voyager\\Models\\Post','TCG\\Voyager\\Policies\\PostPolicy',NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"desc\",\"default_search_key\":null,\"scope\":null}','2022-01-24 00:22:05','2022-03-13 09:20:05'),(6,'pages','pages','Page','Pages','voyager-file-text','TCG\\Voyager\\Models\\Page',NULL,'','',1,0,NULL,'2022-01-24 00:22:06','2022-01-24 00:22:06'),(7,'projects','projects','Project','Projects','voyager-file-text','App\\Models\\Project',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-01-24 08:39:21','2022-02-21 01:31:36'),(8,'project_targets','project-targets','Project Target','Project Targets','voyager-folder','App\\Models\\ProjectTarget',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-01-24 09:52:26','2022-02-21 01:36:18'),(9,'project_target_tasks','project-target-tasks','Project Target Task','Project Target Tasks','voyager-news','App\\Models\\ProjectTargetTask',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":\"currentDeveloper\"}','2022-01-27 00:56:21','2022-04-02 03:40:25'),(10,'eods','eods','Eod','Eods','voyager-rocket','App\\Models\\Eod',NULL,'App\\Http\\Controllers\\Voyager\\EodController',NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":\"currentDeveloper\"}','2022-02-20 09:50:07','2022-04-02 03:50:26'),(12,'eod_configurations','eod-configurations','Eod Configuration','Eod Configurations','voyager-settings','App\\Models\\EodConfiguration',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":\"currentDeveloper\"}','2022-02-20 10:21:17','2022-03-13 10:52:34'),(13,'mail_templates','mail-templates','Mail Template','Mail Templates','voyager-mail','App\\Models\\VoyagerMailTemplate',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-03-19 08:46:13','2022-03-19 10:25:29');
/*!40000 ALTER TABLE `data_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eod_configurations`
--

DROP TABLE IF EXISTS `eod_configurations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eod_configurations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL COMMENT 'email to client id',
  `cc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'email css address or address',
  `bcc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'email css address or address',
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'email subject',
  `greetings` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'email heading',
  `signature` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'email signature',
  `developer_id` bigint unsigned NOT NULL COMMENT 'email send to client from this account',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eod_configurations`
--

LOCK TABLES `eod_configurations` WRITE;
/*!40000 ALTER TABLE `eod_configurations` DISABLE KEYS */;
INSERT INTO `eod_configurations` VALUES (1,1,5,'zaars59208@gmail.com','rashid.bukhari143@gmail.com,rashid.bukhari78600@gmail.com','Daily Report of onyxsa.co,uk','<p style=\"text-align: left;\"><strong>Today\'s Activities:</strong></p>\r\n<p style=\"text-align: left;\"><strong>Hi Tal</strong>, Hope you will be fine,</p>\r\n<p style=\"text-align: left;\"><strong>Project: Tal Sanga</strong></p>','<h4><strong>Plan for Tomorrow:</strong></h4>\r\n<p>&nbsp;</p>\r\n<p><strong>Rashid Hussain Shah</strong><br />Senior Software Engineer <br />skype: +92 300 8968490 <br />email: <a title=\"Click to send email\" href=\"mailto:rashid@webpenter.com\" target=\"_blank\" rel=\"noopener\">rashid@webpenter.com</a></p>\r\n<p>&nbsp;</p>',1,'2022-02-20 10:47:52','2022-04-02 04:07:50'),(2,1,3,NULL,NULL,'Daily Report of onyxsa.co,uk','Hi Tal,\r\nHope you will be fine,','s',3,'2022-02-20 11:05:25','2022-02-20 11:05:25'),(3,1,5,NULL,NULL,'Daily Report','<p>hi</p>','<p>&nbsp;</p>\r\n<p>Ahmad</p>\r\n<p>Wordpress Developer</p>',2,'2022-04-02 03:42:45','2022-04-02 03:42:45');
/*!40000 ALTER TABLE `eod_configurations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eod_project_target`
--

DROP TABLE IF EXISTS `eod_project_target`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eod_project_target` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `eod_id` bigint unsigned NOT NULL,
  `project_target_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eod_project_target`
--

LOCK TABLES `eod_project_target` WRITE;
/*!40000 ALTER TABLE `eod_project_target` DISABLE KEYS */;
/*!40000 ALTER TABLE `eod_project_target` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eods`
--

DROP TABLE IF EXISTS `eods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'daily email that we need to send',
  `project_id` bigint unsigned NOT NULL,
  `developer_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eods`
--

LOCK TABLES `eods` WRITE;
/*!40000 ALTER TABLE `eods` DISABLE KEYS */;
INSERT INTO `eods` VALUES (1,'<p style=\"text-align: left;\"><strong>Today\'s Activities:</strong></p>\r\n<p style=\"text-align: left;\"><strong>Hi Tal</strong>, Hope you will be fine,</p>\r\n<p style=\"text-align: left;\"><strong>Project: Tal Sanga</strong></p>\r\n<ul>\r\n<li><strong>TSOCU-60: Client feedback on 01 April 2022</strong> <span class=\"In Progress\">[In Progress]</span></li>\r\n<ul>\r\n<li>Resolve website cache issue</li>\r\n<li>&nbsp;<img src=\"http://127.0.0.1:8000/storage/eods/April2022/Screenshot from 2022-04-02 01-04-50.png\" alt=\"\" /></li>\r\n</ul>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<p><strong>Rashid Hussain Shah</strong><br />Senior Software Engineer <br />skype: +92 300 8968490 <br />email: <a title=\"Click to send email\" href=\"mailto:rashid@webpenter.com\" target=\"_blank\" rel=\"noopener\">rashid@webpenter.com</a></p>\r\n<p>&nbsp;</p>',1,1,'2022-04-02 03:51:14','2022-04-02 03:51:14');
/*!40000 ALTER TABLE `eods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
-- Table structure for table `mail_templates`
--

DROP TABLE IF EXISTS `mail_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mail_templates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `mailable` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci,
  `html_template` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `text_template` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail_templates`
--

LOCK TABLES `mail_templates` WRITE;
/*!40000 ALTER TABLE `mail_templates` DISABLE KEYS */;
INSERT INTO `mail_templates` VALUES (1,'App\\Mail\\EodMail','Welcome, {{ name }}','<h1>Hello, {{ eodHtmlTemplate }}.</h1>','Hello, {{ eodHtmlTemplate }}.','2022-03-19 08:01:00','2022-04-01 14:21:39');
/*!40000 ALTER TABLE `mail_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `icon_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `order` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `menu_items_menu_id_foreign` (`menu_id`),
  CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,1,'Dashboard','','_self','voyager-boat',NULL,NULL,1,'2022-01-24 00:22:04','2022-01-24 00:22:04','voyager.dashboard',NULL),(2,1,'Media','','_self','voyager-images',NULL,NULL,5,'2022-01-24 00:22:04','2022-01-24 00:22:04','voyager.media.index',NULL),(3,1,'Users','','_self','voyager-person',NULL,NULL,3,'2022-01-24 00:22:04','2022-01-24 00:22:04','voyager.users.index',NULL),(4,1,'Roles','','_self','voyager-lock',NULL,NULL,2,'2022-01-24 00:22:04','2022-01-24 00:22:04','voyager.roles.index',NULL),(5,1,'Tools','','_self','voyager-tools',NULL,NULL,9,'2022-01-24 00:22:04','2022-01-24 00:22:04',NULL,NULL),(6,1,'Menu Builder','','_self','voyager-list',NULL,5,10,'2022-01-24 00:22:04','2022-01-24 00:22:04','voyager.menus.index',NULL),(7,1,'Database','','_self','voyager-data',NULL,5,11,'2022-01-24 00:22:04','2022-01-24 00:22:04','voyager.database.index',NULL),(8,1,'Compass','','_self','voyager-compass',NULL,5,12,'2022-01-24 00:22:05','2022-01-24 00:22:05','voyager.compass.index',NULL),(9,1,'BREAD','','_self','voyager-bread',NULL,5,13,'2022-01-24 00:22:05','2022-01-24 00:22:05','voyager.bread.index',NULL),(10,1,'Settings','','_self','voyager-settings',NULL,NULL,14,'2022-01-24 00:22:05','2022-01-24 00:22:05','voyager.settings.index',NULL),(11,1,'Categories','','_self','voyager-categories',NULL,NULL,8,'2022-01-24 00:22:05','2022-01-24 00:22:05','voyager.categories.index',NULL),(12,1,'Posts','','_self','voyager-news',NULL,NULL,6,'2022-01-24 00:22:06','2022-01-24 00:22:06','voyager.posts.index',NULL),(13,1,'Pages','','_self','voyager-file-text',NULL,NULL,7,'2022-01-24 00:22:06','2022-01-24 00:22:06','voyager.pages.index',NULL),(14,1,'Projects','','_self','voyager-file-text',NULL,NULL,15,'2022-01-24 08:39:21','2022-01-24 08:39:21','voyager.projects.index',NULL),(15,1,'Project Targets','','_self','voyager-folder',NULL,NULL,16,'2022-01-24 09:52:26','2022-01-24 09:52:26','voyager.project-targets.index',NULL),(16,1,'Project Target Tasks','','_self','voyager-news','#000000',NULL,17,'2022-01-27 00:56:21','2022-01-27 01:03:28','voyager.project-target-tasks.index','null'),(17,1,'EOD','','_self','voyager-rocket','#000000',NULL,18,'2022-02-20 09:50:07','2022-02-20 09:56:10','voyager.eods.index','null'),(18,1,'Eod Configurations','','_self','voyager-settings',NULL,NULL,19,'2022-02-20 10:21:17','2022-02-20 10:21:17','voyager.eod-configurations.index',NULL),(19,1,'Mail Templates','','_self','voyager-mail','#000000',NULL,20,'2022-03-19 08:46:13','2022-03-19 08:47:39','voyager.mail-templates.index','null');
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'admin','2022-01-24 00:22:04','2022-01-24 00:22:04');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2016_01_01_000000_add_voyager_user_fields',1),(4,'2016_01_01_000000_create_data_types_table',1),(5,'2016_05_19_173453_create_menu_table',1),(6,'2016_10_21_190000_create_roles_table',1),(7,'2016_10_21_190000_create_settings_table',1),(8,'2016_11_30_135954_create_permission_table',1),(9,'2016_11_30_141208_create_permission_role_table',1),(10,'2016_12_26_201236_data_types__add__server_side',1),(11,'2017_01_13_000000_add_route_to_menu_items_table',1),(12,'2017_01_14_005015_create_translations_table',1),(13,'2017_01_15_000000_make_table_name_nullable_in_permissions_table',1),(14,'2017_03_06_000000_add_controller_to_data_types_table',1),(15,'2017_04_21_000000_add_order_to_data_rows_table',1),(16,'2017_07_05_210000_add_policyname_to_data_types_table',1),(17,'2017_08_05_000000_add_group_to_settings_table',1),(18,'2017_11_26_013050_add_user_role_relationship',1),(19,'2017_11_26_015000_create_user_roles_table',1),(20,'2018_03_11_000000_add_user_settings',1),(21,'2018_03_14_000000_add_details_to_data_types_table',1),(22,'2018_03_16_000000_make_settings_value_nullable',1),(23,'2019_08_19_000000_create_failed_jobs_table',1),(24,'2019_12_14_000001_create_personal_access_tokens_table',1),(25,'2016_01_01_000000_create_pages_table',2),(26,'2016_01_01_000000_create_posts_table',2),(27,'2016_02_15_204651_create_categories_table',2),(28,'2017_04_11_000000_alter_post_nullable_fields_table',2),(32,'2022_01_24_120331_create_projects_table',3),(46,'2022_02_20_151353_create_eod_configurations_table',6),(47,'2022_01_24_121647_create_project_targets_table',7),(48,'2022_02_21_061613_create_eod_project_target',7),(51,'2022_01_24_121734_create_project_target_tasks_table',8),(52,'2022_03_13_145935_create_eod_project_target_tasks',9),(56,'2018_10_10_000000_create_mail_templates_table',10),(57,'2022_01_27_061023_create_eods_table',10),(58,'2022_03_13_150823_create_eod_project_target',11);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,0,'Hello World','Hang the jib grog grog blossom grapple dance the hempen jig gangway pressgang bilge rat to go on account lugger. Nelsons folly gabion line draught scallywag fire ship gaff fluke fathom case shot. Sea Legs bilge rat sloop matey gabion long clothes run a shot across the bow Gold Road cog league.','<p>Hello World. Scallywag grog swab Cat o\'nine tails scuttle rigging hardtack cable nipper Yellow Jack. Handsomely spirits knave lad killick landlubber or just lubber deadlights chantey pinnace crack Jennys tea cup. Provost long clothes black spot Yellow Jack bilged on her anchor league lateen sail case shot lee tackle.</p>\n<p>Ballast spirits fluke topmast me quarterdeck schooner landlubber or just lubber gabion belaying pin. Pinnace stern galleon starboard warp carouser to go on account dance the hempen jig jolly boat measured fer yer chains. Man-of-war fire in the hole nipperkin handsomely doubloon barkadeer Brethren of the Coast gibbet driver squiffy.</p>','pages/page1.jpg','hello-world','Yar Meta Description','Keyword1, Keyword2','ACTIVE','2022-01-24 00:22:06','2022-01-24 00:22:06');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permission_role` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_permission_id_index` (`permission_id`),
  KEY `permission_role_role_id_index` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_role`
--

LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` VALUES (1,1),(1,3),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(41,1),(41,3),(42,1),(42,3),(43,1),(43,3),(44,1),(44,3),(45,1),(45,3),(46,1),(46,3),(47,1),(47,3),(48,1),(48,3),(49,1),(49,3),(50,1),(50,3),(51,1),(51,3),(52,1),(52,3),(53,1),(53,3),(54,1),(54,3),(55,1),(55,3),(56,1),(56,3),(57,1),(57,3),(58,1),(59,1),(59,3),(60,1),(61,1),(61,3),(62,1),(62,3),(63,1),(63,3),(64,1),(64,3),(65,1),(65,3),(66,1),(67,1),(68,1),(69,1),(70,1);
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permissions_key_index` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'browse_admin',NULL,'2022-01-24 00:22:05','2022-01-24 00:22:05'),(2,'browse_bread',NULL,'2022-01-24 00:22:05','2022-01-24 00:22:05'),(3,'browse_database',NULL,'2022-01-24 00:22:05','2022-01-24 00:22:05'),(4,'browse_media',NULL,'2022-01-24 00:22:05','2022-01-24 00:22:05'),(5,'browse_compass',NULL,'2022-01-24 00:22:05','2022-01-24 00:22:05'),(6,'browse_menus','menus','2022-01-24 00:22:05','2022-01-24 00:22:05'),(7,'read_menus','menus','2022-01-24 00:22:05','2022-01-24 00:22:05'),(8,'edit_menus','menus','2022-01-24 00:22:05','2022-01-24 00:22:05'),(9,'add_menus','menus','2022-01-24 00:22:05','2022-01-24 00:22:05'),(10,'delete_menus','menus','2022-01-24 00:22:05','2022-01-24 00:22:05'),(11,'browse_roles','roles','2022-01-24 00:22:05','2022-01-24 00:22:05'),(12,'read_roles','roles','2022-01-24 00:22:05','2022-01-24 00:22:05'),(13,'edit_roles','roles','2022-01-24 00:22:05','2022-01-24 00:22:05'),(14,'add_roles','roles','2022-01-24 00:22:05','2022-01-24 00:22:05'),(15,'delete_roles','roles','2022-01-24 00:22:05','2022-01-24 00:22:05'),(16,'browse_users','users','2022-01-24 00:22:05','2022-01-24 00:22:05'),(17,'read_users','users','2022-01-24 00:22:05','2022-01-24 00:22:05'),(18,'edit_users','users','2022-01-24 00:22:05','2022-01-24 00:22:05'),(19,'add_users','users','2022-01-24 00:22:05','2022-01-24 00:22:05'),(20,'delete_users','users','2022-01-24 00:22:05','2022-01-24 00:22:05'),(21,'browse_settings','settings','2022-01-24 00:22:05','2022-01-24 00:22:05'),(22,'read_settings','settings','2022-01-24 00:22:05','2022-01-24 00:22:05'),(23,'edit_settings','settings','2022-01-24 00:22:05','2022-01-24 00:22:05'),(24,'add_settings','settings','2022-01-24 00:22:05','2022-01-24 00:22:05'),(25,'delete_settings','settings','2022-01-24 00:22:05','2022-01-24 00:22:05'),(26,'browse_categories','categories','2022-01-24 00:22:05','2022-01-24 00:22:05'),(27,'read_categories','categories','2022-01-24 00:22:05','2022-01-24 00:22:05'),(28,'edit_categories','categories','2022-01-24 00:22:05','2022-01-24 00:22:05'),(29,'add_categories','categories','2022-01-24 00:22:05','2022-01-24 00:22:05'),(30,'delete_categories','categories','2022-01-24 00:22:05','2022-01-24 00:22:05'),(31,'browse_posts','posts','2022-01-24 00:22:06','2022-01-24 00:22:06'),(32,'read_posts','posts','2022-01-24 00:22:06','2022-01-24 00:22:06'),(33,'edit_posts','posts','2022-01-24 00:22:06','2022-01-24 00:22:06'),(34,'add_posts','posts','2022-01-24 00:22:06','2022-01-24 00:22:06'),(35,'delete_posts','posts','2022-01-24 00:22:06','2022-01-24 00:22:06'),(36,'browse_pages','pages','2022-01-24 00:22:06','2022-01-24 00:22:06'),(37,'read_pages','pages','2022-01-24 00:22:06','2022-01-24 00:22:06'),(38,'edit_pages','pages','2022-01-24 00:22:06','2022-01-24 00:22:06'),(39,'add_pages','pages','2022-01-24 00:22:06','2022-01-24 00:22:06'),(40,'delete_pages','pages','2022-01-24 00:22:06','2022-01-24 00:22:06'),(41,'browse_projects','projects','2022-01-24 08:39:21','2022-01-24 08:39:21'),(42,'read_projects','projects','2022-01-24 08:39:21','2022-01-24 08:39:21'),(43,'edit_projects','projects','2022-01-24 08:39:21','2022-01-24 08:39:21'),(44,'add_projects','projects','2022-01-24 08:39:21','2022-01-24 08:39:21'),(45,'delete_projects','projects','2022-01-24 08:39:21','2022-01-24 08:39:21'),(46,'browse_project_targets','project_targets','2022-01-24 09:52:26','2022-01-24 09:52:26'),(47,'read_project_targets','project_targets','2022-01-24 09:52:26','2022-01-24 09:52:26'),(48,'edit_project_targets','project_targets','2022-01-24 09:52:26','2022-01-24 09:52:26'),(49,'add_project_targets','project_targets','2022-01-24 09:52:26','2022-01-24 09:52:26'),(50,'delete_project_targets','project_targets','2022-01-24 09:52:26','2022-01-24 09:52:26'),(51,'browse_project_target_tasks','project_target_tasks','2022-01-27 00:56:21','2022-01-27 00:56:21'),(52,'read_project_target_tasks','project_target_tasks','2022-01-27 00:56:21','2022-01-27 00:56:21'),(53,'edit_project_target_tasks','project_target_tasks','2022-01-27 00:56:21','2022-01-27 00:56:21'),(54,'add_project_target_tasks','project_target_tasks','2022-01-27 00:56:21','2022-01-27 00:56:21'),(55,'delete_project_target_tasks','project_target_tasks','2022-01-27 00:56:21','2022-01-27 00:56:21'),(56,'browse_eods','eods','2022-02-20 09:50:07','2022-02-20 09:50:07'),(57,'read_eods','eods','2022-02-20 09:50:07','2022-02-20 09:50:07'),(58,'edit_eods','eods','2022-02-20 09:50:07','2022-02-20 09:50:07'),(59,'add_eods','eods','2022-02-20 09:50:07','2022-02-20 09:50:07'),(60,'delete_eods','eods','2022-02-20 09:50:07','2022-02-20 09:50:07'),(61,'browse_eod_configurations','eod_configurations','2022-02-20 10:21:17','2022-02-20 10:21:17'),(62,'read_eod_configurations','eod_configurations','2022-02-20 10:21:17','2022-02-20 10:21:17'),(63,'edit_eod_configurations','eod_configurations','2022-02-20 10:21:17','2022-02-20 10:21:17'),(64,'add_eod_configurations','eod_configurations','2022-02-20 10:21:17','2022-02-20 10:21:17'),(65,'delete_eod_configurations','eod_configurations','2022-02-20 10:21:17','2022-02-20 10:21:17'),(66,'browse_mail_templates','mail_templates','2022-03-19 08:46:13','2022-03-19 08:46:13'),(67,'read_mail_templates','mail_templates','2022-03-19 08:46:13','2022-03-19 08:46:13'),(68,'edit_mail_templates','mail_templates','2022-03-19 08:46:13','2022-03-19 08:46:13'),(69,'add_mail_templates','mail_templates','2022-03-19 08:46:13','2022-03-19 08:46:13'),(70,'delete_mail_templates','mail_templates','2022-03-19 08:46:13','2022-03-19 08:46:13');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('PUBLISHED','DRAFT','PENDING') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,0,NULL,'Lorem Ipsum Post',NULL,'This is the excerpt for the Lorem Ipsum Post','<p>This is the body of the lorem ipsum post</p>','posts/post1.jpg','lorem-ipsum-post','This is the meta description','keyword1, keyword2, keyword3','PUBLISHED',0,'2022-01-24 00:22:06','2022-01-24 00:22:06'),(2,0,NULL,'My Sample Post',NULL,'This is the excerpt for the sample Post','<p>This is the body for the sample post, which includes the body.</p>\n                <h2>We can use all kinds of format!</h2>\n                <p>And include a bunch of other stuff.</p>','posts/post2.jpg','my-sample-post','Meta Description for sample post','keyword1, keyword2, keyword3','PUBLISHED',0,'2022-01-24 00:22:06','2022-01-24 00:22:06'),(3,0,NULL,'Latest Post',NULL,'This is the excerpt for the latest post','<p>This is the body for the latest post</p>','posts/post3.jpg','latest-post','This is the meta description','keyword1, keyword2, keyword3','PUBLISHED',0,'2022-01-24 00:22:06','2022-01-24 00:22:06'),(4,0,NULL,'Yarr Post',NULL,'Reef sails nipperkin bring a spring upon her cable coffer jury mast spike marooned Pieces of Eight poop deck pillage. Clipper driver coxswain galleon hempen halter come about pressgang gangplank boatswain swing the lead. Nipperkin yard skysail swab lanyard Blimey bilge water ho quarter Buccaneer.','<p>Swab deadlights Buccaneer fire ship square-rigged dance the hempen jig weigh anchor cackle fruit grog furl. Crack Jennys tea cup chase guns pressgang hearties spirits hogshead Gold Road six pounders fathom measured fer yer chains. Main sheet provost come about trysail barkadeer crimp scuttle mizzenmast brig plunder.</p>\n<p>Mizzen league keelhaul galleon tender cog chase Barbary Coast doubloon crack Jennys tea cup. Blow the man down lugsail fire ship pinnace cackle fruit line warp Admiral of the Black strike colors doubloon. Tackle Jack Ketch come about crimp rum draft scuppers run a shot across the bow haul wind maroon.</p>\n<p>Interloper heave down list driver pressgang holystone scuppers tackle scallywag bilged on her anchor. Jack Tar interloper draught grapple mizzenmast hulk knave cable transom hogshead. Gaff pillage to go on account grog aft chase guns piracy yardarm knave clap of thunder.</p>','posts/post4.jpg','yarr-post','this be a meta descript','keyword1, keyword2, keyword3','PUBLISHED',0,'2022-01-24 00:22:06','2022-01-24 00:22:06');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_target_tasks`
--

DROP TABLE IF EXISTS `project_target_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_target_tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_target_id` bigint unsigned NOT NULL,
  `developer_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment_files` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_target_tasks_developer_id_foreign` (`developer_id`),
  CONSTRAINT `project_target_tasks_developer_id_foreign` FOREIGN KEY (`developer_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_target_tasks`
--

LOCK TABLES `project_target_tasks` WRITE;
/*!40000 ALTER TABLE `project_target_tasks` DISABLE KEYS */;
INSERT INTO `project_target_tasks` VALUES (1,7,1,'<ul>\r\n<li><span style=\"color: #172b4d; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, \'Fira Sans\', \'Droid Sans\', \'Helvetica Neue\', sans-serif; letter-spacing: -0.07px; white-space: pre-wrap;\">Instagram profile link has been added</span></li>\r\n<li><span style=\"color: #172b4d; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, \'Fira Sans\', \'Droid Sans\', \'Helvetica Neue\', sans-serif; letter-spacing: -0.07px; white-space: pre-wrap;\">Test Other links</span></li>\r\n</ul>','[]','2022-03-13 09:29:21','2022-03-13 09:29:21',NULL),(2,8,1,'<ul>\r\n<li>Do domain setup</li>\r\n<li>Setup python for scraping</li>\r\n</ul>','[]','2022-03-13 09:35:38','2022-03-19 11:02:48',NULL),(3,9,1,'<ul>\r\n<li>Official logos are uploaded</li>\r\n<li>Dummy data are removed</li>\r\n</ul>','[]','2022-03-13 09:38:21','2022-03-13 09:38:21',NULL),(4,10,1,'<ul>\r\n<li>R&amp;D on uplisting</li>\r\n<li>Integrate APIS</li>\r\n</ul>','[]','2022-03-14 01:05:00','2022-03-14 01:05:00',NULL),(5,10,1,'<ol>\r\n<li style=\"text-align: left;\">test</li>\r\n<li style=\"text-align: left;\">test 1</li>\r\n</ol>','[]','2022-03-16 01:09:21','2022-03-16 01:09:21',NULL),(6,9,3,'<ul>\r\n<li>change logo of facebook</li>\r\n<li>change&nbsp; logo of insta</li>\r\n<li>discuss things with client</li>\r\n</ul>','[]','2022-03-19 10:50:44','2022-03-19 10:50:44',NULL),(7,1,3,'<ul>\r\n<li>Sign up on uplisting</li>\r\n<li>Test uplisting API</li>\r\n<li>Discuss with Team</li>\r\n</ul>','[]','2022-03-19 10:51:37','2022-03-19 10:51:37',NULL),(8,11,3,'<ol>\r\n<li>Discuss project related things with team</li>\r\n</ol>','[]','2022-03-19 10:52:16','2022-03-19 10:52:16',NULL),(9,12,3,'<ul>\r\n<li>Setup 2 team pages</li>\r\n</ul>','[]','2022-03-19 10:53:52','2022-03-19 10:53:52',NULL),(10,1,1,'<ol>\r\n<li>Test</li>\r\n<li>Bushra</li>\r\n</ol>','[]','2022-04-01 00:29:02','2022-04-01 00:29:02',NULL),(11,13,3,'<ul>\r\n<li>resign header and page body&nbsp;</li>\r\n<li>footer</li>\r\n</ul>','[]','2022-04-01 14:31:08','2022-04-01 14:31:08',NULL),(12,13,1,'<ul>\r\n<li>Abc</li>\r\n<li>Defg</li>\r\n<li>Hij</li>\r\n</ul>','[]','2022-04-02 02:50:39','2022-04-02 02:50:39',NULL),(13,14,1,'<ul>\r\n<li>Resolve website cache issue</li>\r\n</ul>','[]','2022-04-02 03:11:16','2022-04-02 03:15:45',NULL);
/*!40000 ALTER TABLE `project_target_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_targets`
--

DROP TABLE IF EXISTS `project_targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_targets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `developer_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('In Progress','QA','Completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'In Progress',
  `type` enum('User Story','Assignment','Milestone','Project','Sprint','Update','Feature') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'User Story',
  `attachment_files` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_targets`
--

LOCK TABLES `project_targets` WRITE;
/*!40000 ALTER TABLE `project_targets` DISABLE KEYS */;
INSERT INTO `project_targets` VALUES (1,1,1,'R&D on uplisting','In Progress','User Story','[]','2022-02-21 01:34:20','2022-02-21 01:34:20',NULL),(2,1,1,'Uplisting integration','In Progress','User Story','[]','2022-02-21 01:34:33','2022-02-21 01:34:33',NULL),(3,1,1,'abc','In Progress','User Story','[]','2022-03-12 09:47:48','2022-03-12 09:51:43','2022-03-12 09:51:43'),(4,1,1,'def','In Progress','User Story','[]','2022-03-12 09:48:07','2022-03-12 09:51:41','2022-03-12 09:51:41'),(5,1,1,'abc','In Progress','User Story','[]','2022-03-12 09:54:11','2022-03-13 09:20:42','2022-03-13 09:20:42'),(6,1,1,'def','In Progress','User Story','[]','2022-03-13 09:55:20','2022-03-13 09:20:38','2022-03-13 09:20:38'),(7,1,1,'TSOCU-44 [header] add proper links that are provided by client','In Progress','User Story','[]','2022-03-13 09:22:25','2022-03-13 09:22:25',NULL),(8,2,1,'SY-2 [Domain Setup] Sub domain setup on webpenter.com for Airbnb listing scraping','In Progress','User Story','[]','2022-03-13 09:34:42','2022-03-13 09:43:45','2022-03-13 09:43:45'),(9,1,1,'TSOCU-42 [homepage] use official logos','Completed','User Story','[]','2022-03-13 09:37:49','2022-03-13 09:37:49',NULL),(10,1,1,'TSOCU-31 [uplisting.io] R&D of Uplisting API and test API using Postman or other tool','In Progress','User Story','[]','2022-03-14 01:04:31','2022-03-14 01:04:31',NULL),(11,1,3,'Daily Stand Up Meeting','In Progress','User Story','[]','2022-03-19 10:51:55','2022-03-19 10:51:55',NULL),(12,1,3,'Team Page','In Progress','User Story','[]','2022-03-19 10:53:29','2022-03-19 10:53:29',NULL),(13,1,3,'FTR-12 resign homepage','In Progress','User Story','[]','2022-04-01 14:30:15','2022-04-01 14:30:15',NULL),(14,1,1,'TSOCU-60: Client feedback on 01 April 2022','In Progress','User Story','[]','2022-04-02 03:11:00','2022-04-02 03:15:15',NULL);
/*!40000 ALTER TABLE `project_targets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Direct',
  `start_date` timestamp NOT NULL,
  `expected_delivery_date` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_client_id_foreign` (`client_id`),
  CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'Tal Sanga (staging.onyxsa.co.uk)',5,'Direct','2022-01-24 14:38:00','2022-01-31 14:39:00','2022-01-24 09:41:41','2022-01-24 09:56:20',NULL),(2,'Steve\'s Project',6,'Direct','2022-02-01 14:33:00','2022-03-20 14:33:00','2022-03-13 09:33:20','2022-03-13 09:33:20',NULL);
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','Administrator','2022-01-24 00:22:05','2022-01-24 00:27:01'),(2,'User','Normal User','2022-01-24 00:22:05','2022-01-24 00:27:10'),(3,'Developer','Developer','2022-01-24 00:26:42','2022-01-24 00:26:42'),(4,'Client','Client','2022-01-24 07:03:09','2022-01-24 07:03:09');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `details` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '1',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site.title','Site Title','Site Title','','text',1,'Site'),(2,'site.description','Site Description','Site Description','','text',2,'Site'),(3,'site.logo','Site Logo','','','image',3,'Site'),(4,'site.google_analytics_tracking_id','Google Analytics Tracking ID','','','text',4,'Site'),(5,'admin.bg_image','Admin Background Image','','','image',5,'Admin'),(6,'admin.title','Admin Title','Voyager','','text',1,'Admin'),(7,'admin.description','Admin Description','Welcome to Voyager. The Missing Admin for Laravel','','text',2,'Admin'),(8,'admin.loader','Admin Loader','','','image',3,'Admin'),(9,'admin.icon_image','Admin Icon Image','','','image',4,'Admin'),(10,'admin.google_analytics_client_id','Google Analytics Client ID (used for admin dashboard)','','','text',1,'Admin');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translations`
--

DROP TABLE IF EXISTS `translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `column_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foreign_key` int unsigned NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `translations_table_name_column_name_foreign_key_locale_unique` (`table_name`,`column_name`,`foreign_key`,`locale`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translations`
--

LOCK TABLES `translations` WRITE;
/*!40000 ALTER TABLE `translations` DISABLE KEYS */;
INSERT INTO `translations` VALUES (1,'data_types','display_name_singular',5,'pt','Post','2022-01-24 00:22:06','2022-01-24 00:22:06'),(2,'data_types','display_name_singular',6,'pt','Página','2022-01-24 00:22:06','2022-01-24 00:22:06'),(3,'data_types','display_name_singular',1,'pt','Utilizador','2022-01-24 00:22:06','2022-01-24 00:22:06'),(4,'data_types','display_name_singular',4,'pt','Categoria','2022-01-24 00:22:06','2022-01-24 00:22:06'),(5,'data_types','display_name_singular',2,'pt','Menu','2022-01-24 00:22:06','2022-01-24 00:22:06'),(6,'data_types','display_name_singular',3,'pt','Função','2022-01-24 00:22:06','2022-01-24 00:22:06'),(7,'data_types','display_name_plural',5,'pt','Posts','2022-01-24 00:22:06','2022-01-24 00:22:06'),(8,'data_types','display_name_plural',6,'pt','Páginas','2022-01-24 00:22:06','2022-01-24 00:22:06'),(9,'data_types','display_name_plural',1,'pt','Utilizadores','2022-01-24 00:22:06','2022-01-24 00:22:06'),(10,'data_types','display_name_plural',4,'pt','Categorias','2022-01-24 00:22:06','2022-01-24 00:22:06'),(11,'data_types','display_name_plural',2,'pt','Menus','2022-01-24 00:22:06','2022-01-24 00:22:06'),(12,'data_types','display_name_plural',3,'pt','Funções','2022-01-24 00:22:06','2022-01-24 00:22:06'),(13,'categories','slug',1,'pt','categoria-1','2022-01-24 00:22:06','2022-01-24 00:22:06'),(14,'categories','name',1,'pt','Categoria 1','2022-01-24 00:22:06','2022-01-24 00:22:06'),(15,'categories','slug',2,'pt','categoria-2','2022-01-24 00:22:06','2022-01-24 00:22:06'),(16,'categories','name',2,'pt','Categoria 2','2022-01-24 00:22:06','2022-01-24 00:22:06'),(17,'pages','title',1,'pt','Olá Mundo','2022-01-24 00:22:06','2022-01-24 00:22:06'),(18,'pages','slug',1,'pt','ola-mundo','2022-01-24 00:22:06','2022-01-24 00:22:06'),(19,'pages','body',1,'pt','<p>Olá Mundo. Scallywag grog swab Cat o\'nine tails scuttle rigging hardtack cable nipper Yellow Jack. Handsomely spirits knave lad killick landlubber or just lubber deadlights chantey pinnace crack Jennys tea cup. Provost long clothes black spot Yellow Jack bilged on her anchor league lateen sail case shot lee tackle.</p>\r\n<p>Ballast spirits fluke topmast me quarterdeck schooner landlubber or just lubber gabion belaying pin. Pinnace stern galleon starboard warp carouser to go on account dance the hempen jig jolly boat measured fer yer chains. Man-of-war fire in the hole nipperkin handsomely doubloon barkadeer Brethren of the Coast gibbet driver squiffy.</p>','2022-01-24 00:22:06','2022-01-24 00:22:06'),(20,'menu_items','title',1,'pt','Painel de Controle','2022-01-24 00:22:06','2022-01-24 00:22:06'),(21,'menu_items','title',2,'pt','Media','2022-01-24 00:22:06','2022-01-24 00:22:06'),(22,'menu_items','title',12,'pt','Publicações','2022-01-24 00:22:06','2022-01-24 00:22:06'),(23,'menu_items','title',3,'pt','Utilizadores','2022-01-24 00:22:06','2022-01-24 00:22:06'),(24,'menu_items','title',11,'pt','Categorias','2022-01-24 00:22:06','2022-01-24 00:22:06'),(25,'menu_items','title',13,'pt','Páginas','2022-01-24 00:22:06','2022-01-24 00:22:06'),(26,'menu_items','title',4,'pt','Funções','2022-01-24 00:22:06','2022-01-24 00:22:06'),(27,'menu_items','title',5,'pt','Ferramentas','2022-01-24 00:22:06','2022-01-24 00:22:06'),(28,'menu_items','title',6,'pt','Menus','2022-01-24 00:22:06','2022-01-24 00:22:06'),(29,'menu_items','title',7,'pt','Base de dados','2022-01-24 00:22:06','2022-01-24 00:22:06'),(30,'menu_items','title',10,'pt','Configurações','2022-01-24 00:22:06','2022-01-24 00:22:06');
/*!40000 ALTER TABLE `translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `user_roles_user_id_index` (`user_id`),
  KEY `user_roles_role_id_index` (`role_id`),
  CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'users/default.png',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'Rashid','rashid@webpenter.com','users/default.png',NULL,'$2y$10$CrqiZRNAF0yjKTtmRZYq9.U/86h6afMp2kbAi3W5Whpa.Y.W6sHdW','iymBM2QafOKGFIn3NsoWqb8ojWThWIFSN7NAxNAptc3Nl4mJDJwdPyEKDB2Z','{\"locale\":\"en\"}','2022-01-24 00:22:05','2022-01-24 00:34:21'),(2,3,'Ahmad','ahmad@webpenter.com','users/default.png',NULL,'$2y$10$6E0k7gyzFWNHPhQrdror4ubMz.jokgYkj6QLkT2Za3qFO32zUF0pi',NULL,'{\"locale\":\"en\"}','2022-01-24 00:30:21','2022-01-24 00:30:21'),(3,3,'Ayub','ayub@webpenter.com','users/default.png',NULL,'$2y$10$.BcJPeujYhUuEHUDejtyIekuWUhS.DWuVWujS1mVMAuGlseACtQ82',NULL,'{\"locale\":\"en\"}','2022-01-24 00:31:35','2022-01-24 00:31:35'),(4,3,'Zahid','zahid@webpenter.com','users/default.png',NULL,'$2y$10$0E8ytFB7eDzRVACYz6zf.e.9./l8q87hmO9fpd49nVDDuRpT.T2vS',NULL,'{\"locale\":\"en\"}','2022-01-24 00:32:38','2022-01-24 00:32:38'),(5,4,'Tal Sanga','tal.sanga@onyxsa.co.uk','users/default.png',NULL,'$2y$10$X8ORJXBWimp7MGsDfLi5.OLsZOD9nExgVfGHD9WJdwMVV96zYrSIa',NULL,'{\"locale\":\"en\"}','2022-01-24 09:38:22','2022-01-24 09:38:22'),(6,4,'Steve','steve@webpenter.com','users/default.png',NULL,'$2y$10$PA.Dtnba1VtJpwLgFAip7OxlxbPayUG0J26PAaEdeKbK..XCEEDIK',NULL,'{\"locale\":\"en\"}','2022-03-13 09:32:42','2022-03-13 09:32:42');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-04 10:52:11
