-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: portal_demo_with_master
-- ------------------------------------------------------
-- Server version	8.0.30

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,1,'AWS','AWS','2022-01-24 00:22:05','2023-04-09 23:25:00'),(2,1,1,'AWS LightSail','aws-lightsail','2022-01-24 00:22:05','2023-04-09 23:25:26'),(3,NULL,1,'Core PHP','core-php','2023-06-18 10:15:44','2023-06-18 10:15:44'),(4,NULL,1,'Laravel','laravel','2023-06-18 10:15:54','2023-06-18 10:15:54'),(5,NULL,1,'Vuejs','vuejs','2023-06-18 10:16:06','2023-06-18 10:16:06'),(6,NULL,1,'Wordpress','wordpress','2023-06-18 10:16:18','2023-06-18 10:16:18'),(7,NULL,1,'Laravel Vuejs','laravel-vuejs','2023-06-18 10:16:32','2023-06-18 10:16:32'),(8,NULL,1,'Wordpress Homey','wordpress-homey','2023-06-18 10:16:46','2023-06-18 10:16:46'),(9,NULL,1,'Wordpress Houzez','wordpress-houzez','2023-06-18 10:17:04','2023-06-18 10:17:04'),(10,NULL,1,'React','react','2024-01-07 10:52:00','2024-01-07 10:52:00'),(11,NULL,1,'React Native','react-native','2024-01-07 10:52:17','2024-01-07 10:52:17'),(12,NULL,1,'WordPress Plugin','wordpress-plugin','2024-01-07 10:52:41','2024-01-07 10:52:41'),(13,NULL,1,'JavaScript','javascript','2024-01-07 11:35:15','2024-01-07 11:35:15'),(14,NULL,1,'HTML','html','2024-01-07 11:46:20','2024-01-07 11:46:20'),(15,NULL,1,'ChatGPT','chatgpt','2024-01-07 11:49:00','2024-01-07 11:49:00'),(16,NULL,1,'MySQL','mysql','2024-01-07 11:50:57','2024-01-07 11:50:57'),(17,NULL,1,'SEO','seo','2024-02-29 17:52:12','2024-02-29 17:52:12'),(18,NULL,1,'JQUERY','jquery','2024-03-09 11:10:47','2024-03-09 11:10:47'),(19,NULL,1,'speed up wordpress website','speed-up-wordpress-website','2024-04-12 13:23:09','2024-04-12 13:23:09'),(20,NULL,1,'PHP','php','2024-05-16 09:38:50','2024-05-16 09:38:50'),(21,NULL,1,'PHP in English','php-in-english','2024-05-16 09:42:25','2024-05-16 09:42:25');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_information`
--

DROP TABLE IF EXISTS `client_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_information` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website_email_or_username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'website login admin email/username',
  `website_login_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'website login admin password',
  `server_login_information` text COLLATE utf8mb4_unicode_ci COMMENT 'SSH or FTP or Cpanel login',
  `server_login_files` text COLLATE utf8mb4_unicode_ci COMMENT '.ssh or .ppk or any other file',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `client_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_information`
--

LOCK TABLES `client_information` WRITE;
/*!40000 ALTER TABLE `client_information` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contracts`
--

DROP TABLE IF EXISTS `contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contracts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachments` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `contract_detail` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contracts`
--

LOCK TABLES `contracts` WRITE;
/*!40000 ALTER TABLE `contracts` DISABLE KEYS */;
INSERT INTO `contracts` VALUES (1,'',NULL,NULL,NULL,NULL,NULL,19,'<p>Arif Rahim is working as a share holder in every contract he is working on.<br />I mean He will get 35% of all the earnings which He generates through his contracts<br /><br />This Deal is Started from 01-AUG-2023&nbsp;</p>','2023-08-30 19:47:40','2024-03-03 23:37:18','2024-03-03 23:37:18'),(2,'',NULL,NULL,NULL,NULL,NULL,2,'<p>Ahmed Raza salary 75k</p>\r\n<p>according to the contract ahmed will give us 4 hours and take leave of Sunday only so we will give Rs 37500 each month&nbsp;</p>\r\n<p>&nbsp;</p>','2024-01-31 19:46:10','2024-01-31 19:46:10',NULL),(3,'',NULL,NULL,NULL,NULL,NULL,3,'<p>Ayub salary after increament 30k with 2k internet allowance total 32k</p>','2024-01-31 19:46:00','2024-03-03 23:37:05',NULL),(4,'Contract with Ahmed Bilal',NULL,NULL,NULL,NULL,NULL,66,'<p>Internship started on 1/01/24, internship amount 10k +&nbsp; Rs 700 internet allowance</p>','2024-01-31 19:48:00','2024-04-28 20:09:56',NULL),(5,'',NULL,NULL,NULL,NULL,NULL,36,'<p>Internship 10k</p>','2024-01-31 19:49:13','2024-01-31 19:49:13',NULL),(6,'Sadam Bhai with matt',NULL,NULL,'Usd','Active',NULL,89,'<p>Sadam Bhai contact with Matt Sadam own hours we will give 18$ per hour (given total payment, we will receive 20$, 2$ fee because of 10% of Upwork fee) We will give 10$ to resources, remaining 8$ income we will divide by 2 4$ to sadam and 4$ to company For resource income we will send 14$ to sadam&nbsp;&nbsp;</p>','2024-04-24 22:10:27','2024-04-24 22:10:27',NULL),(7,'Contract with Khurshid Bilal',NULL,NULL,NULL,'Active',NULL,38,'<p>internship started from 3/1/2024</p>\r\n<p>we will give Rs 5500 each month for now</p>','2024-04-28 20:11:07','2024-04-28 20:11:07',NULL);
/*!40000 ALTER TABLE `contracts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (2,'Udemu: React Native - The Practical Guide [2024]','download link \r\nhttps://coursecouponclub.com/react-native-the-practical-guide/\r\ncourse link\r\nhttps://www.udemy.com/course/react-native-the-practical-guide/','2024-01-04 23:32:44','2024-01-07 11:02:22',11),(3,'React - The Complete Guide 2024 (incl. React Router & Redux)','download link \r\nhttps://coursecouponclub.com/react-the-complete-guide-incl-hooks-react-router-redux/','2024-01-04 23:34:55','2024-01-07 10:54:34',10),(4,'Master Laravel 10 for Beginners & Intermediate 2023','need to find link','2024-01-04 23:41:28','2024-01-07 10:53:41',4),(6,'Mosh: React Tutorial for Beginners','https://www.youtube.com/watch?v=SqcY0GlETPk\r\n\r\nTABLE OF CONTENT\r\n\r\n00:00:00 Course Intro\r\n00:01:55 Prerequisites\r\n00:02:43 What is React?\r\n00:04:57 Setting Up the Development Environment \r\n00:06:24 Creating a React App\r\n00:09:17 Project Structure\r\n00:11:20 Creating a React Component\r\n00:16:41 How React Works\r\n00:19:00 React Ecosystem\r\n00:21:04 Building Components\r\n00:21:40 Creating a ListGroup Component\r\n00:27:15 Fragments\r\n00:29:42 Rendering Lists\r\n00:33:11 Conditional Rendering\r\n00:38:36 Handling Events\r\n00:44:43 Managing State\r\n00:50:44 Passing Data Via Props\r\n00:54:42 Passing Functions Via Props\r\n00:58:27 State Vs Props\r\n01:00:00 Passing Children\r\n01:05:04 Inspecting Components with React Dev Tools \r\n01:07:14 Exercise: Building a Button Component \r\n01:14:15 Exercise: Showing an Alert','2024-01-07 11:01:49','2024-01-07 11:01:49',11),(7,'Mosh: JavaScript Tutorial for Beginners: Learn JavaScript in 1 Hour','https://www.youtube.com/watch?v=W6NZfCO5SIk&t=2468s\r\n\r\n\r\n\r\n**another course**\r\nhttps://mega.nz/folder/ohRiELpY#wjvmJY3xKPLuFbbM6VxcQg/folder/h0IwyR4I','2024-01-07 11:43:42','2024-03-09 11:29:13',13),(8,'Mosh: HTML Tutorial for Beginners: HTML Crash Course','https://www.youtube.com/watch?v=qz0aGYrrlhU','2024-01-07 11:47:53','2024-01-07 11:47:53',14),(9,'Mosh: ChatGPT Tutorial for Developers - 38 Ways to 10x Your Productivity','https://www.youtube.com/watch?v=sTeoEFzVNSc\r\n\r\n**another course**\r\nhttps://drive.google.com/drive/folders/18ebVVM2KDpqSzUsz5HtOZ7wA3rje7kbW','2024-01-07 11:49:46','2024-03-09 11:29:48',15),(10,'Mosh: MySQL Tutorial for Beginners [Full Course]','https://www.youtube.com/watch?v=7S_tz1z_5bA','2024-01-07 11:52:00','2024-01-07 11:52:00',16),(11,'SEO (Search Engine Optimization) Complete Course','https://drive.google.com/drive/folders/1hsJJJ-ZT8kuP2TGnzvzFEXVy5XylWFfb','2024-02-29 17:53:22','2024-03-09 11:01:08',17),(12,'JQuery Course','https://drive.google.com/drive/folders/1VG2QZDLV-tN-rAylPaW1HshVJub9i6B-','2024-03-09 11:11:22','2024-03-09 11:11:22',18),(13,'Become a WordPress Developer: Unlocking Power with Code','https://www.youtube.com/watch?v=FVqzKAUsM68\r\n\r\n\r\nI\'ve posted a newer video here on YouTube about Block Themes and Full Site Editing!   \r\n\r\n • WordPress Block Themes And Full Site ...  \r\n\r\nPlease check the pinned comment for information about changes to the JS & CSS files we\'re trying to load from the GitHub repository.\r\n\r\nTimestamps: \r\n0:0:00 Quick Overview\r\n0:2:06 Detailed Overview\r\n0:12:05 Installing WordPress Locally\r\n0:25:35 First Taste of PHP\r\n0:41:55 Create Theme\r\n0:54:20 Functions\r\n1:09:14 Arrays\r\n1:21:43 The Loop\r\n1:36:10 Header & Footer\r\n1:54:44 Convert HTML/CSS Into Theme\r\n2:14:42 Interior Page Template\r\n2:32:28 Parent / Child Pages\r\n2:49:16 When do we need to echo?\r\n2:58:17 Children Links Menu\r\n\r\nFollow me for updates on new videos or projects:\r\nInstagram:  \r\n\r\n / javaschiff  \r\nTwitter:  \r\n\r\n / learnwebcode  \r\nFacebook:  \r\n\r\n / brad-schiff-1542576316048470  \r\nTwitch:  \r\n\r\n / learnwebcode','2024-04-12 11:01:53','2024-04-12 11:01:53',6),(14,'PHP Programming Language Tutorial - Full Course','https://www.youtube.com/watch?v=OK_JCtrrv-c\r\n\r\n⌨️ 1. (0:00) Introduction\r\n⌨️ 2. (1:56) Windows Installation\r\n⌨️ 3. (7:32) Choosing a Text Editor\r\n⌨️ 4. (11:06) Hello World & Setup\r\n⌨️ 5. (20:29) Writing HTML\r\n⌨️ 6. (27:30) Variables\r\n⌨️ 7. (38:09) Data Types\r\n⌨️ 8. (44:27) Working With Strings\r\n⌨️ 9. (54:50) Working With Numbers\r\n⌨️ 10. (1:05:14) Getting User Input\r\n⌨️ 11. (1:15:37) Building a Basic Calculator\r\n⌨️ 12. (1:22:13) Building a Mad Libs Game\r\n⌨️ 13. (1:28:59) URL Parameters\r\n⌨️ 14. (1:35:52) POST vs GET\r\n⌨️ 15. (1:41:44) Arrays\r\n⌨️ 16. (1:50:26) Using Checkboxes\r\n⌨️ 17. (1:57:22) Associative Arrays\r\n⌨️ 18. (2:04:55) Functions\r\n⌨️ 19. (2:12:10) Return Statements\r\n⌨️ 20. (2:19:10) If Statements\r\n⌨️ 21. (2:37:16) If Statements (con\'t)\r\n⌨️ 22. (2:47:13) Building a Better Calculator\r\n⌨️ 23. (2:56:53) Switch Statements\r\n⌨️ 24. (3:05:09) While Loops\r\n⌨️ 25. (3:15:18) For Loops\r\n⌨️ 26. (3:26:24) Comments\r\n⌨️ 27. (3:31:08) Including HTML\r\n⌨️ 28. (3:36:51) Include: PHP\r\n⌨️ 29. (3:45:57) Classes & Objects\r\n⌨️ 30. (3:56:23) Constructors\r\n⌨️ 31. (4:06:18) Object Functions - PHP - Tutorial 31\r\n⌨️ 32. (4:13:52) Getters & Setters\r\n⌨️ 33. (4:29:17) Inheritance','2024-05-16 09:41:33','2024-05-16 09:43:26',21);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credentials`
--

DROP TABLE IF EXISTS `credentials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `credentials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username_or_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credentials`
--

LOCK TABLES `credentials` WRITE;
/*!40000 ALTER TABLE `credentials` DISABLE KEYS */;
INSERT INTO `credentials` VALUES (1,'Homey webpenter demo','https://homey.webpenter.com/wp-login.php','wearewebpenter@gmail.com','iampak@786','Host Logins: \r\n\r\nGuest Logins:','2024-03-09 15:11:16','2024-03-09 15:11:16',NULL),(2,'Webpenter shop','https://shop.webpenter.com/wp-login.php','shop_wearewebpenter','!Webpenter@7@8@6',NULL,'2024-03-09 15:21:56','2024-03-09 15:21:56',NULL),(3,'webpenter official site','https://webpenter.com/wp-login.php','wearewebpenter@gmail.com','UcUmttveMX7gl%BQ8yfqh3e(',NULL,'2024-03-09 15:25:00','2024-03-09 15:25:00',NULL),(4,'Houzez Webpenter Demo','https://houzez.webpenter.com/wp-login.php','wearewebpenter@gmail.com','iampak@786','Host Logins: \r\n\r\nGuest Logins:','2024-04-16 15:20:39','2024-04-16 15:20:39',NULL),(5,'SSH password hostinger','hostinger.pk','wearewebpenter@gmail.com','no ','this is SSH password for our hosting \r\n\r\n\r\nTp!9i@7iv3AkFUd','2024-05-02 12:23:34','2024-05-02 12:23:52',NULL);
/*!40000 ALTER TABLE `credentials` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=350 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_rows`
--

LOCK TABLES `data_rows` WRITE;
/*!40000 ALTER TABLE `data_rows` DISABLE KEYS */;
INSERT INTO `data_rows` VALUES (1,1,'id','number','ID',1,0,0,0,0,0,'{}',1),(2,1,'name','text','Name',1,1,1,1,1,1,'{}',2),(3,1,'email','text','Email',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required|email|unique:users\"}}',3),(4,1,'password','password','Password',1,0,0,1,1,0,'{}',4),(5,1,'remember_token','text','Remember Token',0,0,0,0,0,0,'{}',5),(6,1,'created_at','timestamp','Created At',0,0,1,0,0,0,'{}',6),(7,1,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',7),(8,1,'avatar','image','Avatar',0,1,1,1,1,1,'{}',8),(9,1,'user_belongsto_role_relationship','relationship','Role',1,1,1,1,1,0,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsTo\",\"column\":\"role_id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"roles\",\"pivot\":\"0\",\"taggable\":\"0\"}',10),(10,1,'user_belongstomany_role_relationship','relationship','voyager::seeders.data_rows.roles',0,0,1,1,1,0,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsToMany\",\"column\":\"id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"user_roles\",\"pivot\":\"1\",\"taggable\":\"0\"}',11),(11,1,'settings','hidden','Settings',0,0,0,0,0,0,'{}',12),(12,2,'id','number','ID',1,0,0,0,0,0,NULL,1),(13,2,'name','text','Name',1,1,1,1,1,1,NULL,2),(14,2,'created_at','timestamp','Created At',0,0,0,0,0,0,NULL,3),(15,2,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,4),(16,3,'id','number','ID',1,0,0,0,0,0,NULL,1),(17,3,'name','text','Name',1,1,1,1,1,1,NULL,2),(18,3,'created_at','timestamp','Created At',0,0,0,0,0,0,NULL,3),(19,3,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,4),(20,3,'display_name','text','Display Name',1,1,1,1,1,1,NULL,5),(21,1,'role_id','text','Role',0,1,1,1,1,1,'{}',9),(22,4,'id','number','ID',1,0,0,0,0,0,NULL,1),(23,4,'parent_id','select_dropdown','Parent',0,0,1,1,1,1,'{\"default\":\"\",\"null\":\"\",\"options\":{\"\":\"-- None --\"},\"relationship\":{\"key\":\"id\",\"label\":\"name\"}}',2),(24,4,'order','text','Order',1,1,1,1,1,1,'{\"default\":1}',3),(25,4,'name','text','Name',1,1,1,1,1,1,NULL,4),(26,4,'slug','text','Slug',1,1,1,1,1,1,'{\"slugify\":{\"origin\":\"name\"}}',5),(27,4,'created_at','timestamp','Created At',0,0,1,0,0,0,NULL,6),(28,4,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,7),(29,5,'id','number','ID',1,0,0,0,0,0,'{}',1),(30,5,'author_id','text','Author',1,0,1,1,0,1,'{}',2),(31,5,'category_id','text','Category',0,0,1,1,1,0,'{}',3),(32,5,'title','text','Title',1,1,1,1,1,1,'{}',4),(33,5,'excerpt','text_area','Excerpt',0,0,1,1,1,1,'{}',5),(34,5,'body','rich_text_box','Body',1,0,1,1,1,1,'{}',6),(35,5,'image','image','Post Image',0,1,1,1,1,1,'{\"resize\":{\"width\":\"1000\",\"height\":\"null\"},\"quality\":\"70%\",\"upsize\":true,\"thumbnails\":[{\"name\":\"medium\",\"scale\":\"50%\"},{\"name\":\"small\",\"scale\":\"25%\"},{\"name\":\"cropped\",\"crop\":{\"width\":\"300\",\"height\":\"250\"}}]}',7),(36,5,'slug','text','Slug',1,0,1,1,1,1,'{\"slugify\":{\"origin\":\"title\",\"forceUpdate\":true},\"validation\":{\"rule\":\"unique:posts,slug\"}}',8),(37,5,'meta_description','text_area','Meta Description',0,0,1,1,1,1,'{}',9),(38,5,'meta_keywords','text_area','Meta Keywords',0,0,1,1,1,1,'{}',10),(39,5,'status','select_dropdown','Status',1,1,1,1,1,1,'{\"default\":\"DRAFT\",\"options\":{\"PUBLISHED\":\"published\",\"DRAFT\":\"draft\",\"PENDING\":\"pending\"}}',11),(40,5,'created_at','timestamp','Created At',0,1,1,0,0,0,'{}',12),(41,5,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',13),(42,5,'seo_title','text','SEO Title',0,1,1,1,1,1,'{}',14),(43,5,'featured','checkbox','Featured',1,1,1,1,1,1,'{}',15),(44,6,'id','number','ID',1,0,0,0,0,0,NULL,1),(45,6,'author_id','text','Author',1,0,0,0,0,0,NULL,2),(46,6,'title','text','Title',1,1,1,1,1,1,NULL,3),(47,6,'excerpt','text_area','Excerpt',1,0,1,1,1,1,NULL,4),(48,6,'body','rich_text_box','Body',1,0,1,1,1,1,NULL,5),(49,6,'slug','text','Slug',1,0,1,1,1,1,'{\"slugify\":{\"origin\":\"title\"},\"validation\":{\"rule\":\"unique:pages,slug\"}}',6),(50,6,'meta_description','text','Meta Description',1,0,1,1,1,1,NULL,7),(51,6,'meta_keywords','text','Meta Keywords',1,0,1,1,1,1,NULL,8),(52,6,'status','select_dropdown','Status',1,1,1,1,1,1,'{\"default\":\"INACTIVE\",\"options\":{\"INACTIVE\":\"INACTIVE\",\"ACTIVE\":\"ACTIVE\"}}',9),(53,6,'created_at','timestamp','Created At',1,1,1,0,0,0,NULL,10),(54,6,'updated_at','timestamp','Updated At',1,0,0,0,0,0,NULL,11),(55,6,'image','image','Page Image',0,1,1,1,1,1,NULL,12),(56,7,'id','text','Id',1,0,0,0,0,0,'{}',1),(57,7,'name','text','Name',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required|max:191|unique:projects,name\"},\"display\":{\"width\":\"6\"}}',2),(58,7,'client_id','hidden','Client',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',3),(59,7,'payment_mode','select_dropdown','Payment Mode',1,0,1,1,1,1,'{\"default\":\"Direct\",\"options\":{\"Direct\":\"Direct\",\"Fiver\":\"Fiver\",\"Upwork\":\"Upwork\",\"Payonner\":\"Payonner\"},\"display\":{\"width\":\"6\"}}',4),(60,7,'start_date','timestamp','Start Date',1,1,1,1,1,1,'{\"format\":\"%Y-%m-%d\",\"display\":{\"width\":\"6\"}}',5),(61,7,'expected_delivery_date','timestamp','Expected Delivery Date',0,1,1,1,1,1,'{\"format\":\"%Y-%m-%d\",\"display\":{\"width\":\"6\"}}',6),(62,7,'created_at','timestamp','Created At',0,0,1,0,0,1,'{}',9),(63,7,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',10),(64,7,'deleted_at','timestamp','Deleted At',0,0,0,0,0,0,'{}',11),(65,7,'project_belongsto_user_relationship','relationship','Client',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"scope\":\"onlyClient\",\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"client_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',7),(66,8,'id','text','Id',1,0,0,0,0,0,'{}',1),(67,8,'project_id','hidden','Project Id',1,0,1,1,1,1,'{}',2),(68,8,'title','text','Title',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required|max:119\"},\"display\":{\"width\":\"12\"}}',7),(69,8,'status','select_dropdown','Status',1,1,1,1,1,1,'{\"default\":\"In Progress\",\"options\":{\"In Progress\":\"In Progress\",\"QA\":\"QA\",\"Completed\":\"Completed\"},\"display\":{\"width\":\"6\"}}',6),(75,8,'created_at','timestamp','Created At',0,0,1,1,0,1,'{\"format\":\"%Y-%m-%d\"}',8),(76,8,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',9),(77,8,'deleted_at','timestamp','Deleted At',0,0,0,0,0,0,'{}',10),(78,8,'project_target_belongsto_project_relationship','relationship','Project',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\Models\\\\Project\",\"table\":\"projects\",\"type\":\"belongsTo\",\"column\":\"project_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',4),(79,9,'id','text','Id',1,0,0,0,0,0,'{}',1),(80,9,'project_target_id','hidden','Project Target Id',1,1,1,1,1,1,'{}',3),(81,9,'developer_id','hidden','Developer Id',1,0,1,1,1,1,'{}',2),(87,9,'description','rich_text_box','Description',1,0,1,1,1,1,'{}',11),(89,9,'created_at','timestamp','Created At',0,0,1,0,0,1,'{}',13),(90,9,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',14),(91,9,'deleted_at','timestamp','Deleted At',0,0,0,0,0,0,'{}',15),(92,9,'project_target_task_belongsto_project_target_task_relationship','relationship','Project Target',1,1,1,1,1,1,'{\"display\":{\"width\":\"12\",\"scope\":\"currentDeveloper\"},\"model\":\"App\\\\Models\\\\ProjectTarget\",\"table\":\"project_targets\",\"type\":\"belongsTo\",\"column\":\"project_target_id\",\"key\":\"id\",\"label\":\"title\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',4),(93,9,'project_target_task_belongsto_user_relationship','relationship','Developer',0,1,1,0,0,1,'{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"developer_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',6),(94,8,'developer_id','hidden','Developer Id',1,0,1,1,1,1,'{}',3),(95,8,'project_target_belongsto_user_relationship','relationship','Developer',0,0,1,0,0,1,'{\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"developer_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',5),(96,10,'id','text','Id',1,0,0,0,0,0,'{}',1),(99,10,'project_id','hidden','Project Id',1,0,1,1,1,1,'{}',2),(100,10,'developer_id','hidden','Developer Id',1,0,1,1,1,1,'{}',3),(101,10,'created_at','timestamp','Date',0,1,1,0,0,1,'{}',8),(102,10,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',9),(103,10,'eod_belongsto_project_relationship','relationship','Project',1,1,1,1,1,1,'{\"model\":\"App\\\\Models\\\\Project\",\"table\":\"projects\",\"type\":\"belongsTo\",\"column\":\"project_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',4),(104,10,'email','rich_text_box','Email',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"display\":{\"width\":\"8\",\"id\":\"dynamic_email_content\"},\"tinymceOptions\":{\"height\":350,\"min_height\":350}}',5),(105,12,'id','text','Id',1,0,0,0,0,0,'{}',1),(106,12,'project_id','hidden','Project Id',1,1,1,1,1,1,'{}',3),(109,12,'cc','text','CC',0,0,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"description\":\"CC emails should be comma separated like a@webpenter.com, b@webpenter.com\"}',9),(110,12,'bcc','text','BCC',0,0,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"description\":\"BCC emails should be comma separated like a@webpenter.com, b@webpenter.com\"}',10),(111,12,'subject','text','Email Subject',1,0,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',8),(112,12,'greetings','rich_text_box','Email Greetings',0,0,0,0,0,0,'{}',17),(113,12,'signature','rich_text_box','Signature',1,0,1,1,1,1,'{}',18),(114,12,'developer_id','hidden','Developer Id',1,1,1,1,1,1,'{}',5),(115,12,'created_at','timestamp','Created At',0,0,1,0,0,1,'{}',19),(116,12,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',20),(117,12,'eod_configuration_belongsto_project_relationship','relationship','Project',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\Models\\\\Project\",\"table\":\"projects\",\"type\":\"belongsTo\",\"column\":\"project_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',2),(118,12,'eod_configuration_belongsto_user_relationship','relationship','From',1,1,1,1,1,0,'{\"display\":{\"width\":\"6\",\"scope\":\"onlyDeveloper\"},\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"developer_id\",\"key\":\"id\",\"label\":\"email\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',4),(119,12,'eod_configuration_belongsto_user_relationship_1','relationship','To',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"scope\":\"onlyClient\",\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"client_id\",\"key\":\"id\",\"label\":\"email\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',6),(120,12,'client_id','hidden','Client Id',1,0,1,1,1,1,'{}',7),(123,13,'id','text','Id',1,0,0,0,0,0,'{}',1),(124,13,'mailable','text','Mailable',1,1,1,1,1,1,'{}',2),(125,13,'subject','text','Subject',0,1,1,1,1,1,'{}',3),(126,13,'html_template','rich_text_box','Html Template',1,1,1,1,1,1,'{}',4),(127,13,'text_template','text_area','Text Template',0,1,1,1,1,1,'{}',5),(128,13,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',6),(129,13,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',7),(130,1,'email_verified_at','timestamp','Email Verified At',0,0,1,1,1,1,'{}',6),(131,9,'date','date','Date',1,0,1,1,1,0,'{\"validation\":{\"rule\":\"required\"},\"display\":{\"width\":\"4\"}}',6),(133,9,'hours','text','Hours',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"display\":{\"width\":\"4\"}}',7),(134,9,'minutes','text','Minutes',1,1,1,1,1,1,'{\"display\":{\"width\":\"4\"}}',8),(137,12,'project_target_status','radio_btn','Project Target Status',1,0,0,0,0,0,'{\"default\":\"1\",\"options\":{\"0\":\"Not Send in EOD\",\"1\":\"Send in EOD\"},\"display\":{\"width\":\"6\"}}',15),(138,12,'project_task_hours','radio_btn','Project Task Hours',1,0,0,0,0,0,'{\"default\":\"1\",\"options\":{\"0\":\"Not Send in EOD\",\"1\":\"Send in EOD\"},\"display\":{\"width\":\"6\"}}',16),(139,10,'eod_belongsto_user_relationship','relationship','Developer',0,1,1,0,0,0,'{\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"developer_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',7),(140,14,'id','text','Id',1,0,0,0,0,0,'{}',1),(141,14,'user_id','text','User Id',1,1,1,1,1,1,'{}',3),(142,14,'phone','text','Phone',0,0,1,1,1,1,'{\"validation\":{\"phone\":\"numeric|max:119|unique:client_information,phone\"},\"display\":{\"width\":\"6\"}}',4),(143,14,'website_url','text','Website Url',1,1,1,1,1,1,'{\"validation\":{\"website_email_or_username\":\"max:119|unique:client_information,webiste_email_or_username\"},\"display\":{\"width\":\"6\"}}',5),(144,14,'website_email_or_username','text','Website Email Or Username',1,1,1,1,1,1,'{\"validation\":{\"website_url\":\"url|max:119|unique:client_information,website_url\"},\"display\":{\"width\":\"6\"}}',6),(145,14,'website_login_password','text','Website Login Password',1,1,1,1,1,1,'{\"validation\":{\"website_login_password\":\"min:6|max:191\"},\"display\":{\"width\":\"6\"}}',7),(146,14,'server_login_information','rich_text_box','Server Login Information',0,0,1,1,1,1,'{}',8),(147,14,'server_login_files','file','Server Login Files',0,0,1,1,1,1,'{}',9),(148,14,'created_at','timestamp','Created At',0,0,1,1,0,1,'{\"format\":\"%Y-%m-%d\"}',10),(149,14,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',11),(150,14,'client_information_belongsto_user_relationship','relationship','Client',0,1,1,1,1,1,'{\"scope\":\"onlyClient\",\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"user_id\",\"key\":\"id\",\"label\":\"email\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',2),(151,15,'id','text','Id',1,0,0,0,0,0,'{}',1),(152,15,'user_id','text','User Id',1,1,1,1,1,1,'{}',3),(153,15,'phone','text','Phone',1,0,1,1,1,1,'{\"validation\":{\"phone\":\"numeric|max:119|unique:developer_informations,phone\"},\"display\":{\"width\":\"6\"}}',4),(154,15,'git_username','text','Git Username',1,1,1,1,1,1,'{\"validation\":{\"phone\":\"numeric|max:119|unique:developer_informations,phone\"},\"display\":{\"width\":\"6\"}}',5),(155,15,'notes','rich_text_box','Notes',1,0,1,1,1,1,'{}',6),(156,15,'resume','file','Resume',0,1,1,1,1,1,'{}',7),(157,15,'created_at','timestamp','Created At',0,0,1,1,0,1,'{\"format\":\"%Y-%m-%d\"}',8),(158,15,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',9),(159,15,'developer_information_belongsto_user_relationship','relationship','users',1,1,1,1,1,1,'{\"scope\":\"onlyDeveloper\",\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"user_id\",\"key\":\"id\",\"label\":\"email\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',2),(160,16,'id','text','Id',1,0,0,0,0,0,'{}',1),(161,16,'project_id','hidden','Project Id',1,1,1,1,1,1,'{}',2),(162,16,'developer_id','hidden','Developer Id',1,1,1,1,1,1,'{}',3),(163,16,'title','text','Title',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"display\":{\"width\":\"6\"}}',6),(164,16,'description','text_area','Description',0,1,1,1,1,1,'{}',8),(165,16,'total_amount','number','Total Amount',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"display\":{\"width\":\"6\"}}',7),(166,16,'created_at','timestamp','Created At',0,0,1,1,0,1,'{}',9),(167,16,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',10),(168,16,'project_milestone_belongsto_project_relationship','relationship','projects',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\Models\\\\Project\",\"table\":\"projects\",\"type\":\"belongsTo\",\"column\":\"project_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',4),(169,16,'project_milestone_belongsto_user_relationship','relationship','users',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\",\"scope\":\"currentDeveloper\"},\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"developer_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',5),(170,1,'client_notes','text','Client Notes',0,0,1,1,1,1,'{}',10),(171,17,'id','text','Id',1,0,0,0,0,0,'{}',1),(172,17,'developer_id','hidden','Developer Id',0,0,1,1,1,1,'{}',2),(173,17,'project_id','hidden','Project Id',1,0,1,1,1,1,'{}',4),(174,17,'project_target_id','hidden','Project Target Id',1,0,1,1,1,1,'{}',5),(175,17,'total_earning','text','Total Earning',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"description\":\"Total Earning of this add payment request (will be in the USD like 100$).\"}',10),(177,17,'currency_current_rate','number','Currency Current Rate',0,0,1,1,0,1,'{\"display\":{\"width\":\"6\"},\"description\":\"Enter currency current rate (will be in Rs.).\"}',11),(178,17,'fee','text','Fee',0,0,0,0,0,0,'{\"display\":{\"width\":\"6\"},\"description\":\"Enter 20 for fiver and upwork, for custom client enter transaction fee\"}',15),(179,17,'status','select_dropdown','Status',1,1,1,1,0,1,'{\"default\":\"Requested\",\"options\":{\"Requested\":\"Requested\",\"Pending\":\"Pending\",\"Approved\":\"Approved\"},\"display\":{\"width\":\"6\"}}',16),(180,17,'notes','markdown_editor','Notes',0,0,1,1,1,1,'{\"description\":\"You can add your custom note like order link or any other reference for reminder\"}',18),(181,17,'created_at','timestamp','Created At',0,1,1,0,0,1,'{\"format\":\"%Y-%m-%d\"}',20),(182,17,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',21),(183,17,'deleted_at','timestamp','Deleted At',0,0,0,0,0,0,'{}',23),(184,17,'user_payment_belongsto_project_relationship','relationship','projects',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\Models\\\\Project\",\"table\":\"projects\",\"type\":\"belongsTo\",\"column\":\"project_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',6),(185,17,'user_payment_belongsto_project_target_relationship','relationship','project_targets',1,0,1,1,1,1,'{\"display\":{\"width\":\"6\",\"scope\":\"currentDeveloper\"},\"model\":\"\\\\App\\\\Models\\\\ProjectTarget\",\"table\":\"project_targets\",\"type\":\"belongsTo\",\"column\":\"project_target_id\",\"key\":\"id\",\"label\":\"title\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',7),(186,7,'status','select_dropdown','Status',1,1,1,1,1,1,'{\"default\":\"In Progresss\",\"options\":{\"In Progress\":\"In Progress\",\"Pending\":\"Pending\",\"Cancel\":\"Cancel\",\"Completed\":\"Completed\"},\"display\":{\"width\":\"6\"}}',8),(187,17,'dev_earning','text','Dev Earning',0,1,1,1,0,1,'{\"display\":{\"width\":\"6\"},\"description\":\"Total Developer Earning (lik 35% of 100$, it will be in the USD like 35$).\"}',12),(188,17,'payable','text','Payable',0,1,1,1,0,1,'{\"display\":{\"width\":\"6\"},\"description\":\"Enter currency current rate (will be in Rs.).\"}',13),(189,17,'paid','text','Paid',0,1,1,1,0,1,'{\"display\":{\"width\":\"6\"},\"description\":\"Enter currency current rate (will be in Rs.).\"}',14),(190,12,'enable_slack','radio_btn','Enable Slack',1,1,1,1,1,1,'{\"default\":\"0\",\"options\":{\"0\":\"Disable Slack\",\"1\":\"Enable Slack\"},\"display\":{\"width\":\"6\"}}',12),(191,12,'slack_webhook_url','select_dropdown','Slack Webhook Url',0,0,1,1,1,1,'{\"display\":{\"width\":\"12\"},\"description\":\"Add slack app webhook URL.\",\"default\":\"https:\\/\\/hooks.slack.com\\/services\\/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\",\"options\":{\"https:\\/\\/hooks.slack.com\\/services\\/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\":\"Slack Daily EOD Channel\",\"https:\\/\\/hooks.slack.com\\/services\\/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\":\"Slack Junior Dev and Student EOD Channel\",\"https:\\/\\/hooks.slack.com\\/services\\/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\":\"Slack BrokenBow Daily EOD Channel\",\"https:\\/\\/hooks.slack.com\\/services\\/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\":\"Junior Girls EOD Channell\"}}',11),(192,17,'user_payment_belongsto_user_relationship','relationship','users',0,1,1,0,0,1,'{\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"developer_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',8),(193,10,'plan_for_tomorrow','rich_text_box','Plan For Tomorrow',1,0,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"display\":{\"width\":\"4\",\"id\":\"plan_for_tomorrow_input\"},\"tinymceOptions\":{\"height\":300,\"min_height\":300}}',6),(194,18,'id','text','Id',1,0,0,0,0,0,'{}',1),(195,18,'amount','text','Amount',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"display\":{\"width\":\"6\"}}',2),(196,18,'amount_in','select_dropdown','Amount In',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"default\":\"usd\",\"options\":{\"usd\":\"USD\",\"pkr\":\"PKR\"},\"display\":{\"width\":\"6\"}}',8),(197,18,'source','select_dropdown','Source',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"default\":\"payoneer\",\"options\":{\"payoneer\":\"Payoneer\",\"fiverr\":\"Fiverr\",\"upwork\":\"Upwork\",\"student_fee\":\"Student Fee\",\"other\":\"Other\"},\"display\":{\"width\":\"6\"}}',9),(198,18,'note','text_area','Note',0,1,1,1,1,1,'{\"description\":\"you can add your any note\"}',10),(199,18,'created_at','timestamp','Created At',0,0,1,0,0,1,'{}',11),(200,18,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',12),(201,18,'deleted_at','timestamp','Deleted At',0,0,1,0,0,1,'{}',13),(202,18,'income_belongsto_user_relationship','relationship','users',1,1,1,1,1,1,'{\"scope\":\"onlyAdministrator\",\"display\":{\"width\":\"6\"},\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"user_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',6),(203,18,'user_id','text','User Id',1,1,1,1,1,1,'{}',7),(204,19,'id','text','Id',1,0,0,0,0,0,'{}',1),(205,19,'amount','text','Amount',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"display\":{\"width\":\"6\"}}',2),(206,19,'amount_in','select_dropdown','Amount In',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"default\":\"usd\",\"options\":{\"usd\":\"USD\",\"pkr\":\"PKR\"},\"display\":{\"width\":\"6\"}}',5),(207,19,'purpose','select_dropdown','Purpose',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"default\":\"dev_percentage\",\"options\":{\"dev_percentage\":\"Dev Percentage\",\"salaries\":\"Salaries\",\"internet_bill\":\"Internet Bill\",\"water_bill\":\"Water Bill\",\"electricity_bill\":\"Electricity Bill\",\"company_bonus\":\"Company Bonus\",\"credit_to_dev\":\"Credit To Dev\",\"buy_company_asset\":\"Buy Company Asset\",\"entertainment\":\"Entertainment\",\"company_expense\":\"Company Expense\",\"other\":\"Other\"},\"display\":{\"width\":\"6\"}}',6),(208,19,'note','text_area','Note',0,1,1,1,1,1,'{\"description\":\"you can add your any note\"}',7),(209,19,'created_at','timestamp','Created At',0,0,1,0,0,1,'{}',8),(210,19,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',9),(211,19,'deleted_at','timestamp','Deleted At',0,0,0,0,0,0,'{}',10),(212,19,'expense_belongsto_user_relationship','relationship','users',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"scope\":\"onlyAdministrator\",\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"user_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',3),(213,19,'user_id','text','User Id',1,1,1,1,1,1,'{}',4),(216,18,'attachments','multiple_images','Attachments',0,0,1,1,1,1,'{}',14),(217,19,'attachments','multiple_images','Attachments',0,0,1,1,1,1,'{}',11),(218,20,'id','text','Id',1,0,0,0,0,0,'{}',1),(219,20,'name','text','Name',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"}}',2),(221,20,'description','markdown_editor','Description',1,0,1,1,1,1,'{\"validation\":{\"rule\":\"required\"}}',4),(222,20,'created_at','timestamp','Created At',0,0,1,0,0,1,'{}',5),(223,20,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',6),(225,22,'id','text','Id',1,0,0,0,0,0,'{}',1),(226,22,'category_id','hidden','Category Id',1,1,1,1,1,1,'{}',3),(227,22,'description','markdown_editor','Description',1,1,1,1,1,1,'{}',4),(228,22,'created_at','timestamp','Created At',0,0,1,1,0,1,'{}',5),(229,22,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',6),(230,22,'portfolio_belongsto_category_relationship','relationship','categories',1,1,1,1,1,1,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Category\",\"table\":\"categories\",\"type\":\"belongsTo\",\"column\":\"category_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',2),(231,19,'expense_belongsto_user_relationship_1','relationship','Developer',0,1,1,1,1,1,'{\"display\":{\"width\":\"6\",\"scope\":\"onlyDeveloper\"},\"model\":\"App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"developer_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',12),(232,19,'developer_id','text','Developer Id',0,1,1,1,1,1,'{}',3),(233,24,'id','text','Id',1,1,0,0,0,0,'{}',1),(234,24,'student_id','text','Student Id',1,1,1,1,1,1,'{}',2),(235,24,'amount','text','Amount',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"display\":{\"width\":\"6\"}}',3),(236,24,'status','select_dropdown','Status',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"default\":\"paid\",\"options\":{\"paid\":\"Paid\",\"pending\":\"Pending\",\"over_due\":\"Over_due\"},\"display\":{\"width\":\"6\"}}',4),(237,24,'receiver_id','text','Receiver Id',0,0,0,0,0,0,'{}',6),(238,24,'notes','text_area','Notes',0,1,1,1,1,1,'{\"description\":\"you can add your any note\"}',8),(239,24,'created_at','timestamp','Created At',0,0,1,0,0,1,'{}',9),(240,24,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',10),(241,24,'deleted_at','timestamp','Deleted At',0,0,0,0,0,0,'{}',11),(242,24,'student_fee_belongsto_user_relationship','relationship','users',1,1,1,1,1,1,'{\"scope\":\"onlyStudent\",\"display\":{\"width\":\"6\"},\"model\":\"app\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"student_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',5),(243,24,'student_fee_belongsto_user_relationship_1','relationship','Given To',0,0,0,0,0,0,'{\"scope\":\"onlyAdministrator\",\"display\":{\"width\":\"6\"},\"model\":\"app\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"receiver_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',7),(244,25,'id','text','Id',1,0,0,0,0,0,'{}',1),(245,25,'title','text','Title',1,1,1,1,1,1,'{}',2),(246,25,'description','code_editor','Description',1,1,1,1,1,1,'{}',3),(247,25,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',4),(248,25,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',5),(249,17,'attachments','multiple_images','Attachments',0,0,1,1,1,1,'{}',19),(250,24,'batch','select_dropdown','Batch',1,1,1,1,1,1,'{\"default\":\"batch1html\",\"options\":{\"batch1thml\":\"Batch 1 HTML\\/CSS\\/BT\",\"batch1php\":\"Batch 1 PHP\",\"batch2html\":\"Batch 2 HTML\\/CSS\\/BT\",\"batch2php\":\"Batch 2 PHP\",\"HTML CSS JS PHP\":\"HTML CSS JS PHP\"},\"display\":{\"width\":\"6\"}}',2),(251,24,'date','date','Month ',0,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"display\":{\"width\":\"6\"},\"format\":\"%Y-%m\"}',3),(252,17,'income_id','text','Income Id',1,0,1,1,1,1,'{}',3),(254,18,'show_to_dev','radio_btn','Show To Dev',1,1,1,1,1,1,'{\"default\":\"yes\",\"options\":{\"yes\":\"Yes\",\"no\":\"No\"},\"display\":{\"width\":\"6\"}}',4),(255,17,'user_payment_belongsto_income_relationship','relationship','incomes',1,0,1,1,1,1,'{\"display\":{\"width\":\"6\",\"scope\":\"onlyShowToDev\"},\"model\":\"App\\\\Models\\\\Income\",\"table\":\"incomes\",\"type\":\"belongsTo\",\"column\":\"income_id\",\"key\":\"id\",\"label\":\"note\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',17),(256,26,'id','text','Id',1,0,0,0,0,0,'{}',2),(257,26,'user_id','text','User Id',1,1,1,1,1,1,'{}',3),(258,26,'contract_detail','rich_text_box','Contract Detail',1,1,1,1,1,1,'{}',5),(259,26,'created_at','timestamp','Created At',0,0,1,1,0,1,'{}',6),(260,26,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',8),(262,17,'update_by_command','radio_btn','Update By Command',1,0,1,0,0,1,'{\"options\":{\"0\":\"No\",\"1\":\"Yes\"}}',22),(263,17,'client_source','select_dropdown','Client Source',0,0,1,1,1,1,'{\"default\":\"fiverr\",\"options\":{\"fiverr\":\"Fiverr\",\"upwork\":\"Upwork\",\"payonner\":\"Payonner\",\"other\":\"Other\"},\"display\":{\"width\":\"6\"}}',9),(264,24,'paid_date','date','Paid Date',0,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',7),(265,18,'transaction_id','text','Transaction Id',0,1,1,1,1,1,'{\"validation\":{\"rule\":\"unique:incomes,transaction_id\"},\"display\":{\"width\":\"6\"}}',3),(266,18,'transaction_date','date','Transaction Date',0,0,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',5),(267,19,'expense_hasone_income_relationship','relationship','incomes',0,1,1,1,1,1,'{\"model\":\"App\\\\Models\\\\Income\",\"table\":\"incomes\",\"type\":\"belongsTo\",\"column\":\"income_id\",\"key\":\"id\",\"label\":\"note\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',13),(268,19,'income_id','text','Income Id',1,1,1,1,1,1,'{}',12),(269,27,'id','text','Id',1,0,0,0,0,0,'{}',1),(270,27,'user_id','text','User Id',1,1,1,1,1,1,'{}',2),(271,27,'reason','select_dropdown','Reason',0,1,1,1,1,1,'{\"default\":\"dailystandup\",\"options\":{\"dailystandup\":\"Daily Stand Up\",\"eod\":\"EOD\",\"other\":\"Other\"},\"display\":{\"width\":\"6\"}}',5),(272,27,'date','date','Date',0,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',6),(273,27,'note','text_area','Note',1,1,1,1,1,1,'{}',7),(274,27,'created_at','timestamp','Created At',0,0,1,0,0,1,'{}',8),(275,27,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',9),(276,27,'deleted_at','timestamp','Deleted At',0,0,0,0,0,0,'{}',10),(277,27,'fine_belongsto_user_relationship','relationship','users',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"model\":\"app\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"user_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',3),(278,27,'amount','text','Amount',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',4),(279,1,'no_fee','text','No Fee',0,0,0,0,0,0,'{}',5),(280,1,'deleted_at','timestamp','Deleted At',0,0,0,0,0,0,'{}',14),(281,1,'percentage','text','Percentage',0,0,1,0,0,0,'{}',3),(282,29,'id','text','Id',1,0,0,0,0,0,'{}',1),(283,29,'user_id','hidden','User Id',1,1,1,1,1,1,'{}',2),(285,29,'reason','text_area','Reason',1,1,1,1,1,1,'{}',5),(286,29,'created_at','timestamp','Created At',0,0,0,0,0,0,'{}',7),(287,29,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',8),(288,29,'deleted_at','timestamp','Deleted At',0,0,0,0,0,0,'{}',9),(289,1,'active','text','Active',1,0,0,0,0,0,'{}',4),(290,29,'leaf_belongsto_user_relationship','relationship','users',0,1,1,1,0,1,'{\"model\":\"app\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"user_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',6),(291,20,'course_belongsto_category_relationship','relationship','categories',1,1,1,1,1,1,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Category\",\"table\":\"categories\",\"type\":\"belongsTo\",\"column\":\"category_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',7),(292,20,'category_id','text','Category Id',1,1,1,1,1,1,'{}',6),(293,31,'id','text','Id',1,0,0,0,0,0,'{}',1),(294,31,'category_id','text','Category Id',0,1,1,1,1,1,'{}',2),(295,31,'name','text','Name',1,1,1,1,1,1,'{}',3),(296,31,'description','text_area','Description',1,0,1,1,1,1,'{}',4),(297,31,'attachment','file','Attachment',1,1,1,1,1,1,'{}',5),(298,31,'created_at','timestamp','Created At',0,0,1,1,0,1,'{}',6),(299,31,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',7),(300,31,'deleted_at','timestamp','Deleted At',0,0,0,0,0,1,'{}',8),(301,31,'short_list_candidate_belongsto_category_relationship','relationship','categories',1,1,1,1,1,1,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Category\",\"table\":\"categories\",\"type\":\"belongsTo\",\"column\":\"category_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',9),(302,12,'is_send_email','radio_btn','Is Send Email',0,1,1,1,1,1,'{\"default\":\"0\",\"options\":{\"0\":\"Disable Email\",\"1\":\"Enable Email\"},\"display\":{\"width\":\"6\"}}',13),(303,12,'is_default_setting_for_eod','radio_btn','Is Default Setting For Eod',1,1,1,1,1,1,'{\"default\":\"0\",\"options\":{\"0\":\"Disable setting for all EODs\",\"1\":\"Enable setting for all EODs\"},\"display\":{\"width\":\"12\"}}',14),(304,34,'id','text','Id',1,0,0,0,0,0,'{}',1),(305,34,'name','text','Name',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',2),(306,34,'url','text','Url',0,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',3),(307,34,'username_or_email','text','Username Or Email',0,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',4),(308,34,'password','text','Password',0,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',5),(309,34,'description','text_area','Description',0,0,1,1,1,1,'{}',6),(310,34,'created_at','timestamp','Created At',0,0,1,0,0,1,'{}',7),(311,34,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',8),(312,34,'deleted_at','timestamp','Deleted At',0,0,0,0,0,1,'{}',9),(313,35,'id','text','Id',1,0,0,0,0,0,'{}',1),(314,35,'project_id','text','Project Id',1,1,1,1,1,1,'{}',2),(315,35,'milestone_name','text','Milestone Name',0,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',4),(316,35,'amount','number','Amount',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',5),(317,35,'due_date','date','Due Date',0,0,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',6),(318,35,'attachments','file','Attachments',0,0,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',7),(319,35,'created_at','timestamp','Created At',0,1,1,0,0,1,'{}',8),(320,35,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',9),(321,35,'deleted_at','timestamp','Deleted At',0,0,0,0,0,1,'{}',10),(323,35,'project_payment_belongsto_project_relationship','relationship','projects',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\Models\\\\Project\",\"table\":\"projects\",\"type\":\"belongsTo\",\"column\":\"project_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',3),(324,7,'total_contract_amount','text','Total Contract Amount',0,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',6),(325,36,'id','text','Id',1,0,0,0,0,0,'{}',1),(326,36,'student_id','text','Student Id',1,1,1,1,1,1,'{}',2),(327,36,'amount','text','Amount',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"display\":{\"width\":\"6\"}}',4),(328,36,'batch','select_dropdown','Batch',1,1,1,1,1,1,'{\"default\":\"batch1html\",\"options\":{\"batch1thml\":\"Batch 1 HTML\\/CSS\\/BT\\/ JS\",\"batch1php\":\"Batch 1 PHP\",\"batch2html\":\"Batch 2 HTML\\/CSS\\/BT \\/ JS\",\"batch2php\":\"Batch 2 PHP\"},\"display\":{\"width\":\"6\"}}',3),(329,36,'date','date','Date',0,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"display\":{\"width\":\"6\"},\"format\":\"%Y-%m\"}',5),(330,36,'status','select_dropdown','Status',1,1,1,1,1,1,'{\"validation\":{\"rule\":\"required\"},\"default\":\"paid\",\"options\":{\"paid\":\"Paid\",\"pending\":\"Pending\",\"over_due\":\"Over_due\"},\"display\":{\"width\":\"6\"}}',6),(331,36,'paid_date','date','Paid Date',0,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',7),(332,36,'receiver_id','text','Receiver Id',0,0,0,0,0,0,'{}',9),(333,36,'notes','text','Notes',0,1,1,1,1,1,'{\"display\":{\"width\":\"6\"},\"description\":\"you can add your any note\"}',8),(334,36,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',10),(335,36,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',11),(336,36,'deleted_at','timestamp','Deleted At',0,0,1,0,0,1,'{}',12),(337,36,'online_student_fee_belongsto_user_relationship','relationship','users',1,1,1,1,1,1,'{\"scope\":\"onlineOnlyStudent\",\"display\":{\"width\":\"6\"},\"model\":\"app\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"student_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',13),(338,36,'online_student_fee_belongsto_user_relationship_1','relationship','users',1,1,1,1,1,1,'{\"scope\":\"onlyAdministrator\",\"display\":{\"width\":\"6\"},\"model\":\"app\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"receiver_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',14),(339,26,'title','text','Title',1,1,1,1,1,0,'{}',4),(340,26,'start_date','date','Start Date',0,0,1,1,1,1,'{}',7),(341,26,'end_date','date','End Date',0,0,1,1,1,1,'{}',9),(342,26,'currency','text','Currency',0,0,1,0,0,1,'{}',10),(343,26,'status','text','Status',0,1,1,1,1,1,'{\"active\":\"active\",\"options\":{\"active\":\"Active\",\"close\":\"Close\",\"pending\":\"Pending\",\"other\":\"Other\"},\"display\":{\"width\":\"6\"}}',11),(344,26,'attachments','text','Attachments',0,0,1,1,1,1,'{}',12),(345,26,'deleted_at','timestamp','Deleted At',0,0,1,0,0,1,'{}',13),(346,26,'contract_belongsto_user_relationship','relationship','users',1,1,1,1,1,1,'{\"model\":\"app\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"user_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',1),(347,29,'start_date','date','Start Date',1,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',3),(348,29,'end_date','date','End Date',0,1,1,1,1,1,'{\"display\":{\"width\":\"6\"}}',4),(349,10,'client_id','text','Client Id',0,1,1,1,1,1,'{}',6);
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
  `description` text COLLATE utf8mb4_unicode_ci,
  `generate_permissions` tinyint(1) NOT NULL DEFAULT '0',
  `server_side` tinyint NOT NULL DEFAULT '0',
  `details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_types_name_unique` (`name`),
  UNIQUE KEY `data_types_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_types`
--

LOCK TABLES `data_types` WRITE;
/*!40000 ALTER TABLE `data_types` DISABLE KEYS */;
INSERT INTO `data_types` VALUES (1,'users','users','User','Users','voyager-person','TCG\\Voyager\\Models\\User','TCG\\Voyager\\Policies\\UserPolicy','TCG\\Voyager\\Http\\Controllers\\VoyagerUserController',NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"desc\",\"default_search_key\":null,\"scope\":null}','2022-01-24 00:22:04','2024-05-01 23:11:58'),(2,'menus','menus','Menu','Menus','voyager-list','TCG\\Voyager\\Models\\Menu',NULL,'','',1,0,NULL,'2022-01-24 00:22:04','2022-01-24 00:22:04'),(3,'roles','roles','Role','Roles','voyager-lock','TCG\\Voyager\\Models\\Role',NULL,'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController','',1,0,NULL,'2022-01-24 00:22:04','2022-01-24 00:22:04'),(4,'categories','categories','Category','Categories','voyager-categories','TCG\\Voyager\\Models\\Category',NULL,'','',1,0,NULL,'2022-01-24 00:22:05','2022-01-24 00:22:05'),(5,'posts','posts','Post','Posts','voyager-news','TCG\\Voyager\\Models\\Post','TCG\\Voyager\\Policies\\PostPolicy',NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"desc\",\"default_search_key\":null,\"scope\":null}','2022-01-24 00:22:05','2022-03-13 09:20:05'),(6,'pages','pages','Page','Pages','voyager-file-text','TCG\\Voyager\\Models\\Page',NULL,'','',1,0,NULL,'2022-01-24 00:22:06','2022-01-24 00:22:06'),(7,'projects','projects','Project','Projects','voyager-file-text','App\\Models\\Project',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-01-24 08:39:21','2024-03-17 18:02:55'),(8,'project_targets','project-targets','Project Target','Project Targets','voyager-folder','App\\Models\\ProjectTarget',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":\"currentDeveloper\"}','2022-01-24 09:52:26','2023-05-10 09:51:57'),(9,'project_target_tasks','project-target-tasks','Project Target Task','Project Target Tasks','voyager-news','App\\Models\\ProjectTargetTask',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":\"currentDeveloper\"}','2022-01-27 00:56:21','2023-05-03 12:59:24'),(10,'eods','eods','Eod','Eods','voyager-rocket','App\\Models\\Eod',NULL,'App\\Http\\Controllers\\Voyager\\EodController',NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":\"email\",\"scope\":\"currentDeveloperORClient\"}','2022-02-20 09:50:07','2024-06-09 07:51:05'),(12,'eod_configurations','eod-configurations','Eod Configuration','Eod Configurations','voyager-settings','App\\Models\\EodConfiguration',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-02-20 10:21:17','2024-03-10 22:07:28'),(13,'mail_templates','mail-templates','Mail Template','Mail Templates','voyager-mail','App\\Models\\VoyagerMailTemplate',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-03-19 08:46:13','2022-03-19 10:25:29'),(14,'client_information','client-information','Client Information','Client Informations','voyager-info-circled','App\\Models\\ClientInformation',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-09-14 10:34:43','2023-05-27 11:24:36'),(15,'developer_information','developer-information','Developer Information','Developer Informations','voyager-info-circled','App\\Models\\DeveloperInformation',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-09-15 01:01:42','2022-09-24 11:14:22'),(16,'project_milestones','project-milestones','Project Milestone','Project Milestones','voyager-paypal','App\\Models\\ProjectMilestone',NULL,NULL,'We need to keep track of developer income so design and develop this feature',1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-10-17 10:58:31','2022-10-17 11:14:53'),(17,'user_payments','user-payments','User Payment','User Payments','voyager-dollar','App\\Models\\UserPayment',NULL,'App\\Http\\Controllers\\Voyager\\DeveloperPaymentController',NULL,1,0,'{\"order_column\":\"created_at\",\"order_display_column\":\"created_at\",\"order_direction\":\"desc\",\"default_search_key\":null,\"scope\":\"currentUserAndManagement\"}','2023-03-01 22:05:24','2023-10-28 08:52:34'),(18,'incomes','incomes','Income','Incomes','voyager-dollar','App\\Models\\Income',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2023-05-25 09:44:50','2023-11-18 13:46:04'),(19,'expenses','expenses','Expense','Expenses','voyager-skull','App\\Models\\Expense',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2023-05-26 08:44:37','2023-11-18 13:46:32'),(20,'courses','courses','Course','Courses','voyager-documentation','App\\Models\\Courses',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2023-06-04 20:29:47','2024-01-07 10:56:13'),(22,'portfolios','portfolios','Portfolio','Portfolios','voyager-rocket','App\\Models\\Portfolio',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2023-06-23 07:58:41','2023-07-08 16:25:05'),(24,'student_fees','student-fees','Student Fee','Student Fees','voyager-receipt','App\\Models\\StudentFee',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":\"currentMonthOrPendingStatus\"}','2023-07-01 16:39:35','2023-11-13 22:15:42'),(25,'testings','testings','Testing','Testings','voyager-smile','App\\Models\\Testing',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2023-07-09 11:17:14','2023-12-31 10:40:45'),(26,'contracts','contracts','Contract','Contracts','voyager-logbook','App\\Models\\Contract',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2023-08-30 19:37:41','2024-04-28 20:21:11'),(27,'fines','fines','Fine','Fines','voyager-pirate-swords','App\\Models\\Fine',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2023-11-22 11:10:55','2023-11-22 11:20:38'),(29,'leaves','leaves','Leave','Leaves','voyager-frown','App\\Models\\Leave',NULL,'App\\Http\\Controllers\\Voyager\\LeaveController',NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":\"currentUser\"}','2023-12-10 19:15:02','2024-05-27 10:02:23'),(31,'short_list_candidates','short-list-candidates','Short List Candidate','Short List Candidates','voyager-study','App\\Models\\ShortListCandidates',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2024-02-04 20:43:50','2024-02-04 20:47:45'),(34,'credentials','credentials','Credential','Credentials','voyager-key','App\\Models\\Credential',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2024-03-09 09:39:22','2024-03-09 15:01:39'),(35,'project_payments','project-payments','Project Payment','Project Payments','voyager-milestone','App\\Models\\ProjectPayment',NULL,NULL,'when direct payment project will start, first we will add client then project with total payment, most of the time client give payment into different milestone like if total 200k first client give us 25k then we will add a record 25k against this project to track how much payment given how much remaining',1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2024-03-17 16:39:58','2024-03-17 17:47:46'),(36,'online_student_fees','online-student-fees','Online Student Fee','Online Student Fees','voyager-receipt','App\\Models\\OnlineStudentFee',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":\"currentMonthOrPendingStatus\"}','2024-03-25 05:55:36','2024-05-21 19:02:13');
/*!40000 ALTER TABLE `data_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `developer_information`
--

DROP TABLE IF EXISTS `developer_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `developer_information` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `git_username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `resume` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `developer_information`
--

LOCK TABLES `developer_information` WRITE;
/*!40000 ALTER TABLE `developer_information` DISABLE KEYS */;
/*!40000 ALTER TABLE `developer_information` ENABLE KEYS */;
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
  `enable_slack` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if 1 then send eod to slack channel',
  `is_send_email` tinyint(1) DEFAULT '1',
  `slack_webhook_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'slack app webhook url to send eod to slack channel',
  `project_target_status` tinyint(1) NOT NULL DEFAULT '1',
  `project_task_hours` tinyint(1) NOT NULL DEFAULT '1',
  `greetings` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'email heading',
  `signature` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'email signature',
  `developer_id` bigint unsigned NOT NULL COMMENT 'email send to client from this account',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_default_setting_for_eod` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eod_configurations`
--

LOCK TABLES `eod_configurations` WRITE;
/*!40000 ALTER TABLE `eod_configurations` DISABLE KEYS */;
INSERT INTO `eod_configurations` VALUES (1,1,5,'zaars59208@gmail.com','rashid.bukhari78600@gmail.com, rashid.bukhari143@gmail.com','Daily Project Report',0,1,NULL,1,1,'<p>&nbsp;</p>\r\n<p>Hi&nbsp;<strong>Tal Sanga</strong>,<br />Hope your fine,<br />Today Work Report</p>','<p>&nbsp;</p>\r\n<p>Rashid Bukhari<br />Full Stack Engineer<br />+92 300 8968490<br />webpenter.com</p>',5,'2022-05-30 00:55:29','2022-06-30 10:02:51',0),(2,4,9,NULL,'rashid.bukhari78600@gmail.com','Daily WP Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p style=\"padding-left: 40px;\">RASHID BUKHARI | Senior Software Engineer</p>',1,'2022-05-30 01:10:32','2024-02-22 09:58:12',1),(5,4,9,'zaars59208@gmail.com','rashid.bukhari78600@gmail.com,ayubkhokhar786@gmail.com,wearewebpenter@gmail.com','Daily updates',1,1,'https://hooks.slack.com/services/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',1,1,'<p><span style=\"text-decoration: underline;\">Good evening</span></p>','<p>&nbsp;</p>\r\n<p><span style=\"color: #000000;\"><strong>Ayub Khokhar&nbsp;<br /></strong>Front-End Developer | Team Lead | webpenter.LLC</span></p>\r\n<p>&nbsp;</p>',3,'2022-06-06 18:23:51','2024-02-20 20:25:08',1),(6,4,9,'zaars59208','rashid.bukhari78600@gmail.com','Daily Update Report',0,1,NULL,1,1,'<p>&nbsp;</p>\r\n<p>Hi <strong>WebPenter</strong>,</p>\r\n<p>&nbsp;</p>','<p>&nbsp;</p>\r\n<p>Muhammad Irfan<br />Software Enginner&nbsp;<br /><a href=\"webpenter.com\">webpenter.com</a></p>\r\n<p>&nbsp;</p>',12,'2022-06-07 17:27:43','2022-06-07 17:27:43',0),(7,5,11,'rashid.bukhari78600@gmail.com','zaars59208@gmail.com','daily tasks update',0,1,NULL,1,1,'<p>Hi Ben,</p>\r\n<p>I hope you are doing well, here is today\'s report about the done tasks, please send your review against this.</p>','<p>&nbsp;</p>\r\n<p>Webpenter.com<br />We are your Penter</p>\r\n<p>&nbsp;</p>',4,'2022-06-13 21:28:42','2022-06-17 10:28:28',0),(8,7,14,'rashid.bukhari78600@gmail.com','zaars59208@gmail.com','Koh Lanta Properties Customization',0,1,NULL,1,1,'<p>&nbsp;</p>\r\n<p>Webpenter</p>\r\n<p>&nbsp;</p>','<p>&nbsp;</p>\r\n<p><strong>WEBPENTER</strong></p>\r\n<p><strong>SOFTWARE DEVELOPMENT COMPANY</strong></p>\r\n<p><strong>Email: </strong><a title=\"WebPenter Contact\" href=\"mailto:wearewebpenter@gmail.com\" target=\"_blank\" rel=\"noopener\">wearewebpenter@gmail.com</a></p>\r\n<p><strong>Skype:</strong> live:c84c61e35b64c4ad</p>\r\n<p><a title=\"WebPenter, Software Development Company\" href=\"https://webpenter.com\" target=\"_blank\" rel=\"noopener\">www.webpenter.com</a></p>',4,'2022-06-18 13:02:31','2022-07-27 00:11:27',0),(9,4,9,'rashid.bukhari78600@gmail.com','muhammadsadiq037@gmail.com,zaars59208@gmail.com,ahmadraza4119@gmail.com','Daily Updates',0,1,NULL,1,1,'<p>&nbsp;</p>\r\n<p>Hi&nbsp;<strong>WEBPENTER,</strong></p>\r\n<p>Here is my today updates</p>\r\n<p>&nbsp;</p>','<p>&nbsp;</p>\r\n<p>Muhammad Sadiq</p>\r\n<p>&nbsp;</p>\r\n<p>skype: live:muhammadsadiq037</p>\r\n<p>email: muhammadsadiq037@gmail.com</p>\r\n<p>website: www.webpenter.com</p>',13,'2022-06-19 20:48:58','2022-06-19 21:00:55',0),(11,4,9,'zaars59208@gmail.com','rashid.bukhari786002gmail.com,ayubkhokhar786@gmail.com,ahmadraza4119@gmail.com','Daily update on html learning',0,1,NULL,1,1,'<p>Good Evening Devs</p>','<p>Huzaifah Khan</p>\r\n<p>Front-end developing student</p>',15,'2022-07-21 18:01:07','2022-07-23 16:34:03',0),(12,4,9,'Zaars59208@gmail.com','rashid.bukhari78600@gmail.com,ayubkhokhar786@gmail.com,ahmadraza4119@gmail.com','Daily Learnings Updates',1,1,'https://hooks.slack.com/services/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',1,1,'<p>Good Evening</p>','<p>Muzammil Hussain<br />Front-end Development Learny | Student</p>\r\n<p>&nbsp;</p>\r\n<p>website: www.webpenter.com<br />skype:&nbsp;&nbsp;muzammilhussainn14@gmail.com<br />Email:&nbsp;&nbsp;muzammilhussainn14@gmail.com</p>',17,'2022-07-22 15:53:22','2024-04-30 16:07:31',1),(13,4,9,'zaars59208@gmail.com','abkhaliqrana3434@outlook.com','Daily Updates',1,1,'https://hooks.slack.com/services/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',1,1,'<p>Good Evening</p>','<p>Abdul Khaliq<br />Web Development Learner | student of HTML, CSS, PHP | www.webpenter.com</p>',18,'2022-07-22 15:57:55','2024-03-14 20:07:11',1),(15,4,9,'zaars59208@gmail.com','rashid.bukhari78600@gmail.com,ayubkhokhar786@gmai.com,ahmadraza4119@gmail.com','daily learning updates',0,1,NULL,1,1,'<p>good evening&nbsp;</p>','<p>muzammil hussain</p>\r\n<p>front-end developer\'s student</p>',17,'2022-07-23 16:32:30','2022-07-23 16:32:30',0),(16,4,9,'zaars59208@gmail.com','rashid.bukhari78600@gmail.com','Learning Task',0,1,NULL,1,1,'<p>Hello Good Evening,<br /><br /></p>','<p><strong>Arif Rahim<br />Fullstack Developer | Webpenter.llc</strong></p>\r\n<p>Email:arifrahim@webpenter.com<br />Skype:live:.cid.1c45cf81ecf88b47</p>',19,'2022-08-20 11:18:29','2022-08-20 11:18:29',0),(17,4,9,'rashid.bukhari78600@gmail.com','ayubkhokhar786@gmail.com','zaars59208@gmail.com',0,1,NULL,1,1,'<p>good evening</p>','<p>Student at webpenter academy&nbsp;</p>',23,'2022-10-10 18:21:02','2022-10-10 18:21:02',0),(18,4,9,'rashid.bukhari78600@gmail.com','ayubkhokhar786@gmail.com','zaars59208@gmail.com',0,1,NULL,1,1,'<p>Good evening Everyone</p>','<p>student at webpenter&nbsp;</p>',22,'2022-10-10 18:22:25','2022-10-10 18:22:25',0),(19,12,9,'zaars59208@gmail.com','abkhaliqrana3434@gmail.com','Daily updates',0,1,NULL,1,1,'<p>Hello,</p>\r\n<p>good evening</p>','<p>Abdul khaliq&nbsp;</p>\r\n<p>Web developer</p>\r\n<p>webpenter.com</p>',18,'2023-04-29 17:07:48','2023-04-29 17:07:48',0),(20,4,9,'zaars59208@gmail.com,rashid.bukhari78600@gmail.com','fatimakhurram84@gmail.com','Daily Progress Report',0,1,NULL,1,1,NULL,'<p>&nbsp;</p>\r\n<p>Fatima Khurrum</p>\r\n<p>WordPress Developer&nbsp;</p>',35,'2023-05-11 13:35:28','2023-05-11 13:35:28',0),(21,19,9,NULL,NULL,'arifrahim710@gmail.com',0,1,NULL,1,1,'<p>Homey App</p>','<p>Arif Rahim</p>',19,'2023-05-11 17:19:37','2023-05-11 17:19:37',0),(22,4,9,'zaars59208@gmail.com,rashid.bukhari78600@gmail.com',NULL,'Daily Progress Report',0,1,NULL,1,1,NULL,'<p>Salman Awan<br />Intern Quality Assurance Engineer<br />+92 301 4350227<br /><a title=\"Webpenter \" href=\"webpenter.com\" target=\"_blank\" rel=\"noopener\">webpenter.com</a></p>',36,'2023-05-12 12:43:18','2023-05-12 12:43:18',0),(23,12,9,'zaars59208@gmail.com','abkhaliqrana3434@gmail.com','Daily updates',0,1,NULL,1,1,NULL,'<ul>\r\n<li>First page&nbsp; Mobile responsive</li>\r\n</ul>',18,'2023-05-12 16:55:52','2023-05-12 16:55:52',0),(24,20,9,NULL,NULL,'arifrahim710@gmail.com',0,1,NULL,1,1,NULL,'<p>Homey Coupon</p>',19,'2023-05-12 17:36:17','2023-05-12 17:43:43',0),(25,10,9,'zaars59208@gmail.com','muhammadsadiq037@gmail.com','daily update',0,1,NULL,1,1,NULL,'<p>Sadiq Ali</p>\r\n<p>web developer</p>',13,'2023-05-12 18:00:46','2023-05-12 18:00:46',0),(26,12,9,'abkhaliqrana3434@gmail.com, salmanawan993@gmail.com',NULL,'arifrahim710@gmail.com',0,1,NULL,1,1,NULL,'<p>Ipnrealty</p>',19,'2023-05-13 17:36:38','2023-05-16 17:15:52',0),(27,21,9,NULL,NULL,'arifrahim710@gmail.com',0,1,NULL,1,1,NULL,'<p>Hey!</p>',19,'2023-05-18 17:45:57','2023-05-18 17:45:57',0),(28,12,9,'rashidbukhari78600@gmail.com','salmanawan993@gmail.com','Daily progress report',0,1,NULL,1,1,NULL,'<p>salman awan</p>\r\n<p>&nbsp;</p>',36,'2023-05-19 19:29:17','2023-05-19 19:29:17',0),(29,22,9,'zaars59208@gmail.com','abkhaliqrana3434@gmail.com','Daily updates',0,1,NULL,1,1,NULL,'<p>Abdul khaliq</p>',18,'2023-05-22 18:12:34','2023-05-22 18:12:34',0),(30,23,9,'zaars59208@gmail.com','abkhaliqrana3434@gmail.com','Daily updates',0,1,NULL,1,1,NULL,'<p>abdul khaliq</p>',18,'2023-05-23 17:35:22','2023-05-23 17:35:22',0),(31,24,9,'zaars59208@gmail.com','abkhaliqrana3434@gmail.com','Daily updates',0,1,NULL,1,1,NULL,'<p>abdul khaliq</p>',18,'2023-05-25 18:00:33','2023-05-25 18:00:33',0),(32,25,9,'zaars59208@gmail.com','abkhaliqrana3434@gmail.com','Daily updates',0,1,NULL,1,1,NULL,'<p>abdul khaliq</p>',18,'2023-05-27 18:38:13','2023-05-27 18:38:13',0),(33,26,9,'ayubkhokhar786@gmail.com',NULL,'arifrahim710@gmail.com',0,1,NULL,1,1,NULL,'<p>Auhs EDU</p>',19,'2023-05-29 17:48:15','2023-05-31 17:34:43',0),(34,4,9,'ayubkhokhar786@gmail.com',NULL,'academy',0,1,NULL,1,1,NULL,'<p>hh</p>',37,'2023-05-31 17:30:17','2023-05-31 17:30:17',0),(35,27,9,'zaars59208@gmail.com','abkhaliqrana3434@gmail.com','Daily updates',0,1,NULL,1,1,NULL,'<p>abdul khaliq</p>',18,'2023-05-31 17:36:11','2023-05-31 17:36:11',0),(36,28,9,'zaars59208@gmail.com','abkhaliqrana3434@gmail.com','Daily updates',0,1,NULL,1,1,NULL,'<p>Abdul khaliq</p>',18,'2023-06-02 18:23:21','2023-06-02 18:23:21',0),(37,30,9,'ayubkhokhar786@gmail.com',NULL,'arifrahim710@gmail.com',0,1,NULL,1,1,NULL,'<p>Maia web cloning</p>',19,'2023-06-13 17:52:50','2023-06-13 17:52:50',0),(38,31,9,'ayubkhokhar786@gmail.com',NULL,'arifrahim710@gmail.com',0,1,NULL,1,1,NULL,'<p>Tenant Page</p>',19,'2023-06-20 17:53:48','2023-06-20 17:53:48',0),(39,31,9,'zaars59208@gmail.com','abkhaliqrana3434@gmail.com','Daily updates',0,1,NULL,1,1,NULL,'<p>Abdul khaliq</p>',18,'2023-06-21 17:49:55','2023-06-21 17:49:55',0),(40,4,9,'wearewebpenter@gmail.com','huzaifahfakhar@gmail.com','PHPLEARNING',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>I am PHP student Huzaifah Khan</p>',15,'2023-07-05 18:20:25','2024-04-30 16:06:16',1),(41,34,9,NULL,'muhammadsadiq037@gmail.com','haan QR code',0,1,NULL,1,1,NULL,'<p>Sadiq Ali</p>',13,'2023-07-11 20:14:55','2023-07-11 20:14:55',0),(42,14,9,'zaars59208@gmail.com','muhammadsadiq037@gmail.com','daily update',0,1,NULL,1,1,NULL,'<p>Sadiq Ali</p>',13,'2023-07-12 18:42:55','2023-07-12 18:50:26',0),(45,39,9,NULL,NULL,'edit pgae',1,1,NULL,1,1,NULL,'<p>abdulkhaliq</p>',18,'2023-12-02 17:47:16','2023-12-02 17:47:16',0),(47,4,9,'waqar@webpenter.com','ahmedbilal@webpenter.com','php learning',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>&nbsp;</p>\r\n<p>Name:Muhammad Khan</p>\r\n<p>Role:Student</p>\r\n<p>Email:muhammadkhan10220@gmail.com</p>\r\n<p>Phone:03068238003</p>',28,'2023-12-11 16:48:47','2024-04-30 16:05:30',1),(48,67,64,'waqaar.hussaiin@gmail.com','ahmedbilal@webpenter.com','daily update',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>&nbsp;</p>\r\n<p>Muhammad Khan | Student | muhammadkhan10220@gmail.com | 03068238003</p>',28,'2023-12-11 16:48:50','2024-05-22 17:46:12',1),(51,67,64,'waqar@webpenter.com','ahmedbilal@webpenter.com','Daily_updates',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Mehtab Altaf | Junior Dev | sainmehtab15@gmail.com | 03146634665</p>\r\n<p>&nbsp;</p>',27,'2023-12-11 17:30:57','2024-02-24 09:57:40',1),(52,67,64,'waqaar.hussaiin@gmail.com','ahmedbilal@webpenter.com','Daily-Update',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<ul>\r\n<li>Name : Huzaifah Khan</li>\r\n<li>Role : PHP Student</li>\r\n<li>Email: huzaifahfakhar@gmail.com</li>\r\n<li>Phone : 0314-7898394</li>\r\n</ul>',15,'2023-12-11 17:45:05','2024-04-30 16:04:51',1),(53,67,64,'ayub@webpenter.com','ahmedbilal@webpenter.com','Daily-update',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>&nbsp;Bilal Riaz | Student | bilal@gmail.com | 03156893391</p>',56,'2023-12-11 18:09:01','2024-04-30 16:04:04',1),(54,67,64,'waqaar.hussaiin@gmail.com','ahmedbilal@webpenter.com','daily-updates',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Shabaz | Student | shahbaz@gmail.com | 03053256749</p>',61,'2023-12-12 15:05:59','2024-04-30 16:03:45',1),(55,67,64,'waqaar.hussaiin@gmail.com','ahmedbilal@webpenter.com','daily updates',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Sair Bhatti | sairbhatti50@gmail.com | Student</p>\r\n<p>&nbsp;</p>',49,'2023-12-12 15:26:18','2024-04-30 16:03:00',1),(56,67,64,'ayubkhokhar786@gmail.com','ahmedbilal@webpenter.com','Daily Updates',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Hammad | Student | hammad@gmail.com | 03115097294</p>',55,'2023-12-12 15:27:46','2024-04-30 15:51:42',1),(57,67,9,'ayoubkhokhar786@gmail.com','ahmedbilal@webpenter.com','daily-update',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Sharjeel Shahid | Student | princesharjeel07@gmail.com | 03029224797</p>\r\n<p>&nbsp;</p>',41,'2023-12-12 15:47:26','2024-04-30 15:52:28',1),(58,4,9,'ayub@webpenter.com','ahmedbilal@webpenter.com','Daily Report',1,1,'https://hooks.slack.com/services/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',0,0,NULL,'<p>&nbsp;</p>\r\n<p>Ahmed Bilal | Junior Business Developer | www.webpenter.com</p>',66,'2023-12-20 19:35:20','2024-03-30 11:57:35',1),(59,67,64,'waqaar.hussaiin@gmail.com','ahmedbilal@webpenter.com','Daily Updates',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Sajid Abbasi | Student | nawabsajidbbasi236@gmail.com | 03337452719</p>\r\n<p>&nbsp;</p>',26,'2024-01-17 15:13:49','2024-05-23 10:16:12',1),(61,48,9,NULL,NULL,'Brokenbow',0,1,NULL,1,1,NULL,'<p>Abdul khaliq</p>',18,'2024-01-19 17:03:51','2024-01-19 17:03:51',0),(63,67,9,'ahmedbilal@webpenter.com','khanhuzaifah85@gmail.com','Daily update',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>huzaifa | Student | huzaifah@webpenter.com |</p>',15,'2024-01-20 18:49:18','2024-04-30 15:59:56',1),(65,5,9,NULL,NULL,'Gida',0,1,NULL,1,1,NULL,'<p>Rana Abdul Khaliq</p>',18,'2024-01-23 16:55:10','2024-01-23 16:55:10',0),(68,67,64,'ahmedbilal@webpenter.com','ahmedbilal@webpenter.com','Daily Updates',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Khurshid Bilal | Junior Dev | khurshid@webpenter.com | 0301-2648886</p>',38,'2024-01-27 20:17:08','2024-02-24 09:57:21',1),(69,4,9,NULL,NULL,'memberpress plugin',0,1,NULL,1,1,NULL,'<p>Abdul khaliq</p>',9,'2024-01-29 16:53:50','2024-01-29 16:53:50',0),(70,4,9,NULL,NULL,'Fyp project',0,1,NULL,1,1,NULL,'<p>Rana Abdul Khaliq</p>',9,'2024-01-31 16:27:02','2024-01-31 16:27:02',0),(71,4,9,NULL,NULL,'Fyp project',0,1,NULL,1,1,NULL,'<p>Rana Abdul khaliq</p>',9,'2024-02-02 17:15:37','2024-02-02 17:15:37',0),(72,61,63,NULL,'sadiq@webpenter.com','Daily Update',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Muhammad Sadiq</p>\r\n<p>Senior Sofware Engineer</p>\r\n<p>www.webpenter.com</p>',13,'2024-02-09 21:07:48','2024-02-10 14:23:19',0),(73,48,9,NULL,NULL,'daily update',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<h6><span style=\"color: #333333;\"><span style=\"color: #d1d2d3; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;\"><span style=\"font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #222529;\"><span style=\"background-color: #ffffff;\">Muhammad Sadiq</span><br /></span><span style=\"font-size: 15px; font-variant-ligatures: common-ligatures;\">Software engineere</span></span></span></h6>',13,'2024-02-13 20:09:57','2024-02-13 20:09:57',0),(78,82,9,NULL,NULL,'Daily Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Good Evening,<br /><br />Waqar Hussain | Web Developer | 0333 8000128</p>',16,'2024-02-20 18:51:20','2024-05-09 18:05:23',1),(79,67,64,'waqaar.hussaiin@gmail.com','ahmedbilal@webpenter.com','Daily Updates',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Haris Qadeer | ophariskha3132@gmail.com | Student</p>',45,'2024-02-21 15:53:49','2024-04-30 15:55:36',1),(80,67,64,'waqaar.hussaiin@gmail.com','ahmedbilal@webpenter.com','Daily Updates',1,0,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Amir Hussain | amirmazari467@gmail.com | Student</p>',65,'2024-02-21 15:59:31','2024-05-23 10:28:35',1),(81,67,64,'waqaar.hussaiin@gmail.com','ahmedbilal@webpenter.com','Daily Updates',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Rana Adeel | ranaadeel1776146@gmail.com | Student</p>',50,'2024-02-21 16:02:07','2024-04-30 15:53:56',1),(87,4,9,'wearewebpenter@gmail.com','ahmedbilal@webpenter.com','Daily Updates',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Ahmad Raza | Full Stack Developer | ahmedraza4119@gmail.com | 0309-3461901</p>',2,'2024-02-29 18:54:00','2024-02-29 18:54:00',1),(92,67,64,'ahmedbilal@webpenter.com','ahmadzulfiqar3553@gmail.com','Daily Updates',1,0,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Sheikh Ahmed | Student | sheikhahmed@webpenter.com</p>',71,'2024-03-08 16:42:32','2024-03-08 16:42:32',1),(93,67,64,'ahmedbilal@webpenter.com','saifullahwebpenter@gmail.com','Daily Updates',1,0,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Saif Ullah | Student | saifullah@webpenter.com&nbsp;</p>',72,'2024-03-08 17:33:07','2024-03-08 17:47:39',1),(94,67,64,'ahmedbilal@webpenter.com','hassanehsan@webpenter.com','Daily Updates',1,0,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Hassan Ehsan | Student | hassanehsan@webpenter.com</p>',73,'2024-03-08 17:34:49','2024-03-08 17:34:49',1),(95,67,64,'ahmedbilal@webpenter.com','ahsanshawal@webpenter.com','Daily Updates',1,0,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Ahsan shawal | Student | ahsanshawal@webpenter.com</p>',74,'2024-03-08 17:38:11','2024-05-06 18:03:50',1),(96,4,9,'ahmedbilal@webpenter.com','mujahidwebpenter@gmail.com','Daily Updates',1,0,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Mujahid BD | Business Dev | mujahidbd@webpenter.com&nbsp;</p>',75,'2024-03-08 17:42:38','2024-03-08 17:49:13',1),(97,67,64,'ahmedbilal@webpenter.com','naraishwebpenter329@gmail.com','Daily Updates',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Naresh Kumar | Student | nareshkumar@webpenter.com&nbsp;</p>',76,'2024-03-08 17:47:02','2024-05-06 17:57:52',1),(98,67,64,'ahmedbilal@webpenter.com','husnainwebpenter@gmail.com','Daily Updates',1,0,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Hassnain Zafar|Student|hasnainzafar@webpenter.com</p>',77,'2024-03-08 17:51:21','2024-03-08 17:51:21',1),(100,91,9,NULL,'wasifalvi25@gmail.com','Daily Progress Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Muskan Mubashir | Junior Software Engineer | webpenter.com</p>',82,'2024-03-10 22:11:07','2024-03-12 13:24:59',1),(101,91,9,NULL,'moizmissen786@gmail.com','Daily Progress Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Kashmala Saeed | Junior Developer | webpenter.com</p>',79,'2024-03-10 22:36:43','2024-03-10 22:43:22',1),(102,80,9,'ahmedbilal@webpenter.com','umarfarooq@webpenter.com','Daily Updates',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Umar Farooq | Laravel Developer | Webpenter.com</p>',54,'2024-03-11 17:42:00','2024-03-11 17:42:00',1),(104,91,9,NULL,'tooba4844@gmail.com','Daily Progress Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Tooba Khursheed l Junior Developer&nbsp;</p>',81,'2024-03-12 00:39:33','2024-03-12 00:39:33',1),(105,91,9,NULL,'samrinaakbar260@gmail.com','Eod daily report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Samrina Akbar</p>',85,'2024-03-12 13:33:09','2024-03-12 13:33:09',1),(106,91,9,NULL,'samrinaakbar260@gmail.com','Eod daily report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Samrina Akbar</p>',85,'2024-03-12 13:33:11','2024-03-12 13:33:11',1),(107,91,9,NULL,'aeshmalikmalik4@gmail.com','EOD daily report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Ayesha Malik</p>',80,'2024-03-12 13:37:07','2024-03-12 13:37:07',1),(109,91,9,NULL,'ainee.khan008@gmail.com','Daily Progress Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Qurat ul Aain | Junior Developer | New Misali ZPR Campus | www.webpenter.com</p>',86,'2024-03-12 22:17:10','2024-03-12 22:17:10',1),(112,84,9,NULL,NULL,'Group/UI/UX houzez',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Abdul khaliq</p>',18,'2024-03-15 17:35:41','2024-03-15 17:35:41',1),(113,4,9,NULL,NULL,'paypal/Group',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>ABDUL KHALIQ</p>',18,'2024-03-18 17:03:03','2024-03-18 17:03:03',1),(114,67,64,'ahmedbilal@webpenter.com',NULL,'Daily Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Taimoor Ahmad | Ryk Student | 03283005244</p>',90,'2024-05-06 17:38:48','2024-05-06 17:57:08',1),(115,67,64,'ahmedbilal@webpenter.com',NULL,'Daily Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Asif Maher | Ryk student | 03052769814</p>',91,'2024-05-06 17:46:51','2024-05-06 17:56:52',1),(116,67,64,'ahmedbilal@webpenter.com',NULL,'Daily Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Naraish Kumar | Ryk student | 0306 0267456</p>',92,'2024-05-06 17:59:53','2024-05-06 17:59:53',1),(117,67,64,'ahmedbilal@webpenter.com',NULL,'Daily Report',1,0,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Farrukh Javed | Ryk student | 0335 6030381</p>',93,'2024-05-06 18:01:36','2024-05-06 18:01:36',1),(118,67,64,'ahmedbilal@webpenter.com','ahmadzulfiqar3553@gmail.com','Daily Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Ahmad Zulfiqar | Ryk student | 0306 8600311</p>',94,'2024-05-06 18:38:01','2024-05-06 18:38:01',1),(119,67,64,'ahmedbilal@webpenter.com','ibtihajshahid492@gmail.com','Daily Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Ibtihaj shahid | RYK Student | 0332 7346111</p>',95,'2024-05-09 15:58:20','2024-05-09 15:58:20',1),(120,67,64,'ahmedbilal@webpenter.com',NULL,'Daily Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Sheikh Ahmad | RYK Student | 0306 8600311</p>',96,'2024-05-09 16:01:44','2024-05-09 16:01:44',1),(121,67,64,'ahmedbilal@webpenter.com',NULL,'Daily Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Hammad Umer | RYK Student | 0304 4403800</p>',97,'2024-05-09 16:05:30','2024-05-09 16:05:30',1),(122,67,64,'ahmedbilal@webpenter.com',NULL,'Daily Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>M hussnain | RYK Student | 0324 2748959</p>',98,'2024-05-09 16:08:03','2024-05-09 16:08:03',1),(123,4,9,NULL,NULL,'Eod',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p><span style=\"color: #000000;\"><em><strong><span style=\"font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;\">Good Evening,</span></strong></em></span></p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #000000;\"><em><strong><span style=\"font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;\">Waqar Hussain | Junior Web Developer | Instructor&nbsp;</span></strong></em></span></p>\r\n<p><span style=\"color: #000000;\"><em><span style=\"font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;\">Email:&nbsp;</span><span style=\"color: #0000ff;\"><strong><a class=\"c-link\" style=\"box-sizing: inherit; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8; color: #0000ff; text-decoration: underline;\" href=\"mailto:waqaar.hussaiin@gmail.com\" target=\"_blank\" rel=\"noopener noreferrer\" data-stringify-link=\"mailto:waqaar.hussaiin@gmail.com\" data-sk=\"tooltip_parent\" aria-haspopup=\"menu\">waqaar.hussaiin@gmail.com</a></strong></span></em></span></p>\r\n<p><span style=\"color: #000000;\"><em><span style=\"font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;\">Phone: <strong>0333-8000128</strong></span></em></span></p>',16,'2024-05-09 17:56:45','2024-05-09 18:07:40',1),(124,67,64,'ayub@webpenter.com',NULL,'Daily Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Khalid Hussain | Online Student | 0332-4220877</p>',100,'2024-05-20 17:01:00','2024-05-20 17:01:00',1),(125,67,64,'ayub@webpenter.com',NULL,'Daily Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Muhammad Irfan | Online Student | 03366082687</p>',101,'2024-05-20 17:04:08','2024-05-20 17:04:08',1),(126,67,64,'ayub@webpenter.com',NULL,'Daily Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Adnan Rahi | Online Student | 0303 7338095</p>',102,'2024-05-21 14:00:56','2024-05-21 14:00:56',0),(127,67,64,'ayub@webpenter.com',NULL,'Daily Report',1,0,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Muhammad Ahmad | Online Student | 0331 6281415</p>',103,'2024-05-21 20:52:41','2024-05-21 20:52:41',1),(128,67,64,'ahmedbilal@webpenter.com',NULL,'Daily Report',1,1,'https://hooks.slack.com/services/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',1,1,NULL,'<p>Shahzad Baloch | Student | 0301 9180303</p>',104,'2024-05-23 10:27:21','2024-05-23 10:27:21',1);
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
  `plan_for_tomorrow` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` bigint unsigned NOT NULL,
  `developer_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=680 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eods`
--

LOCK TABLES `eods` WRITE;
/*!40000 ALTER TABLE `eods` DISABLE KEYS */;
INSERT INTO `eods` VALUES (679,'test','test',1,1,105,'2024-06-09 07:49:33','2024-06-09 07:49:35');
/*!40000 ALTER TABLE `eods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL COMMENT 'user that pay expense',
  `developer_id` bigint unsigned DEFAULT NULL,
  `amount` double unsigned NOT NULL,
  `amount_in` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'usd',
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'salaries' COMMENT 'purpose for expense',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `attachments` text COLLATE utf8mb4_unicode_ci,
  `income_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
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
-- Table structure for table `fines`
--

DROP TABLE IF EXISTS `fines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `amount` double NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=232 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fines`
--

LOCK TABLES `fines` WRITE;
/*!40000 ALTER TABLE `fines` DISABLE KEYS */;
INSERT INTO `fines` VALUES (1,50,18,'dailystandup','2023-11-22','Due to miss DSU','2023-11-22 11:21:26','2023-11-22 11:27:53',NULL),(2,50,36,'dailystandup','2023-11-22','Due to miss DSU','2023-11-22 11:21:58','2023-11-22 11:21:58',NULL),(3,50,16,'dailystandup','2023-11-22','Due to miss DSU','2023-11-22 11:22:23','2023-11-22 11:22:23',NULL),(4,50,18,'dailystandup','2023-11-27','Due to miss DSU','2023-11-27 12:36:03','2023-11-27 12:36:03',NULL),(6,50,16,'eod','2023-11-27','Due to the EOD','2023-11-27 21:18:40','2023-11-27 21:18:40',NULL),(7,50,36,'eod','2023-11-27','Due to miss EOD','2023-11-27 21:19:21','2023-11-27 21:19:21',NULL),(185,50,2,'eod','2024-01-17','No EOD entry for 2024-01-17','2024-01-17 22:20:55','2024-01-17 22:20:55',NULL),(186,50,3,'eod','2024-01-17','No EOD entry for 2024-01-17','2024-01-17 22:20:55','2024-01-17 22:20:55',NULL),(188,50,13,'eod','2024-01-17','No EOD entry for 2024-01-17','2024-01-17 22:20:57','2024-01-17 22:20:57',NULL),(189,50,16,'eod','2024-01-17','No EOD entry for 2024-01-17','2024-01-17 22:20:57','2024-01-17 22:20:57',NULL),(195,500,3,'other','2024-02-19','Due to Eod Task shared with video but ignored','2024-02-19 23:05:11','2024-02-19 23:05:11',NULL),(196,500,16,'other','2024-02-19','Due to Eod Task shared with video but ignored','2024-02-19 23:09:36','2024-02-19 23:09:36',NULL),(197,500,66,'other','2024-02-19','Due to Eod Task shared with video but ignored','2024-02-19 23:10:28','2024-02-19 23:10:28',NULL),(198,50,2,'eod','2024-03-20','No EOD entry for 2024-03-20','2024-03-20 22:52:58','2024-03-20 22:52:58',NULL),(199,50,3,'eod','2024-03-20','No EOD entry for 2024-03-20','2024-03-20 22:52:59','2024-03-20 22:52:59',NULL),(200,50,12,'eod','2024-03-20','No EOD entry for 2024-03-20','2024-03-20 22:53:00','2024-03-20 22:53:00',NULL),(201,50,13,'eod','2024-03-20','No EOD entry for 2024-03-20','2024-03-20 22:53:00','2024-03-20 22:53:00',NULL),(202,50,18,'eod','2024-03-20','No EOD entry for 2024-03-20','2024-03-20 22:53:01','2024-03-20 22:53:01',NULL),(203,50,19,'eod','2024-03-20','No EOD entry for 2024-03-20','2024-03-20 22:53:02','2024-03-20 22:53:02',NULL),(204,50,32,'eod','2024-03-20','No EOD entry for 2024-03-20','2024-03-20 22:53:02','2024-03-20 22:53:02',NULL),(205,50,2,'eod','2024-03-21','No EOD entry for 2024-03-21','2024-03-21 05:00:06','2024-03-21 05:00:06',NULL),(206,50,3,'eod','2024-03-21','No EOD entry for 2024-03-21','2024-03-21 05:00:07','2024-03-21 05:00:07',NULL),(207,50,12,'eod','2024-03-21','No EOD entry for 2024-03-21','2024-03-21 05:00:07','2024-03-21 05:00:07',NULL),(208,50,13,'eod','2024-03-21','No EOD entry for 2024-03-21','2024-03-21 05:00:08','2024-03-21 05:00:08',NULL),(209,50,16,'eod','2024-03-21','No EOD entry for 2024-03-21','2024-03-21 05:00:09','2024-03-21 05:00:09',NULL),(210,50,18,'eod','2024-03-21','No EOD entry for 2024-03-21','2024-03-21 05:00:10','2024-03-21 05:00:10',NULL),(211,50,19,'eod','2024-03-21','No EOD entry for 2024-03-21','2024-03-21 05:00:10','2024-03-21 05:00:10',NULL),(212,50,32,'eod','2024-03-21','No EOD entry for 2024-03-21','2024-03-21 05:00:11','2024-03-21 05:00:11',NULL),(213,50,54,'eod','2024-03-21','No EOD entry for 2024-03-21','2024-03-21 05:00:12','2024-03-21 05:00:12',NULL),(214,50,2,'eod','2024-03-22','No EOD entry for 2024-03-22','2024-03-22 05:00:06','2024-03-22 05:00:06',NULL),(215,50,3,'eod','2024-03-22','No EOD entry for 2024-03-22','2024-03-22 05:00:07','2024-03-22 05:00:07',NULL),(216,50,12,'eod','2024-03-22','No EOD entry for 2024-03-22','2024-03-22 05:00:07','2024-03-22 05:00:07',NULL),(217,50,13,'eod','2024-03-22','No EOD entry for 2024-03-22','2024-03-22 05:00:08','2024-03-22 05:00:08',NULL),(218,50,16,'eod','2024-03-22','No EOD entry for 2024-03-22','2024-03-22 05:00:09','2024-03-22 05:00:09',NULL),(219,50,18,'eod','2024-03-22','No EOD entry for 2024-03-22','2024-03-22 05:00:09','2024-03-22 05:00:09',NULL),(220,50,19,'eod','2024-03-22','No EOD entry for 2024-03-22','2024-03-22 05:00:10','2024-03-22 05:00:10',NULL),(221,50,32,'eod','2024-03-22','No EOD entry for 2024-03-22','2024-03-22 05:00:11','2024-03-22 05:00:11',NULL),(222,50,54,'eod','2024-03-22','No EOD entry for 2024-03-22','2024-03-22 05:00:12','2024-03-22 05:00:12',NULL),(223,50,2,'eod','2024-03-23','No EOD entry for 2024-03-23','2024-03-23 05:00:06','2024-03-23 05:00:06',NULL),(224,50,3,'eod','2024-03-23','No EOD entry for 2024-03-23','2024-03-23 05:00:07','2024-03-23 05:00:07',NULL),(225,50,12,'eod','2024-03-23','No EOD entry for 2024-03-23','2024-03-23 05:00:08','2024-03-23 05:00:08',NULL),(226,50,13,'eod','2024-03-23','No EOD entry for 2024-03-23','2024-03-23 05:00:08','2024-03-23 05:00:08',NULL),(227,50,16,'eod','2024-03-23','No EOD entry for 2024-03-23','2024-03-23 05:00:09','2024-03-23 05:00:09',NULL),(228,50,18,'eod','2024-03-23','No EOD entry for 2024-03-23','2024-03-23 05:00:10','2024-03-23 05:00:10',NULL),(229,50,19,'eod','2024-03-23','No EOD entry for 2024-03-23','2024-03-23 05:00:10','2024-03-23 05:00:10',NULL),(230,50,32,'eod','2024-03-23','No EOD entry for 2024-03-23','2024-03-23 05:00:11','2024-03-23 05:00:11',NULL),(231,50,54,'eod','2024-03-23','No EOD entry for 2024-03-23','2024-03-23 05:00:12','2024-03-23 05:00:12',NULL);
/*!40000 ALTER TABLE `fines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incomes`
--

DROP TABLE IF EXISTS `incomes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `incomes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `show_to_dev` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `user_id` bigint unsigned NOT NULL COMMENT 'income save into user id',
  `amount` double unsigned NOT NULL,
  `amount_in` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'usd',
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'payoneer' COMMENT 'source of income',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `attachments` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incomes`
--

LOCK TABLES `incomes` WRITE;
/*!40000 ALTER TABLE `incomes` DISABLE KEYS */;
/*!40000 ALTER TABLE `incomes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
-- Table structure for table `leaves`
--

DROP TABLE IF EXISTS `leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leaves` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leaves`
--

LOCK TABLES `leaves` WRITE;
/*!40000 ALTER TABLE `leaves` DISABLE KEYS */;
INSERT INTO `leaves` VALUES (2,65,'2023-12-15','WebPenter Software House\r\nZahir Peer\r\n\r\nAssalam-o-Alaikum!,\r\n\r\nMujhe umeed hai ke aap khairiyat se honge. Main Amir Hussain  hoon aur mujhe yeh likh kar khushi hui ke main WebPenter Software House mein kaam Seekh Raha hoon.\r\n\r\nMain is liye yeh application likh raha hoon ke mere ghar par kuch zaroori kaam urgent taur par anjaam dene ke liye mujhe chutti ki darkhwast karni hai. Mujhe yakeen hai ke main apni zimmedariyon ka dhyaan rakh kar kaam karta hoon, lekin is dafa mujhe ghar par mohlat chahiye hai taake main apne zaroori kaamon ko pura kar saku.\r\n\r\nMai koshish karunga ke meri chutti ka asar kaam mein kam ho aur main apne kaam ko jald se jald pura karke wapas aaoon ga.\r\n\r\nMain aapke samajh aur madad ke liye mashkoor honga.\r\n\r\nShukriya,\r\nAmir Hussain\r\nStudent\r\n0336-7814467','2023-12-14 14:55:37','2024-06-08 11:21:33','2024-06-08 11:21:33',NULL),(3,1,'2024-01-04','Leave of Ahmad Raza without inform to team members and management','2024-01-04 21:33:50','2024-06-08 11:21:33','2024-06-08 11:21:33',NULL),(4,1,'2024-01-23','Leave without inform','2024-01-23 22:04:10','2024-06-08 11:21:33','2024-06-08 11:21:33',NULL),(5,15,'2024-02-03','Due to fever','2024-02-03 16:42:35','2024-06-08 11:21:33','2024-06-08 11:21:33',NULL),(6,15,'2024-02-03','Due to fever','2024-02-03 16:42:36','2024-06-08 11:21:33','2024-06-08 11:21:33',NULL),(8,26,'2024-05-27','Aslam-o-Alaikum Sir,\r\n Sir please give me leave today. I have important work to do at home, so I will not be able to come to the academy today.\r\n Sajid Abbasi','2024-05-27 08:57:17','2024-06-08 11:21:34','2024-06-08 11:21:34','2024-05-27'),(9,1,'2024-05-27','test leave feature with slack','2024-05-27 09:18:08','2024-05-27 09:58:39','2024-05-27 09:58:39','2024-05-27'),(10,1,'2024-05-27','test slack integration','2024-05-27 09:53:01','2024-05-27 09:58:32','2024-05-27 09:58:32','2024-05-27'),(11,1,'2024-05-01','test feature','2024-05-27 10:00:58','2024-05-27 10:01:15','2024-05-27 10:01:15','2024-05-28'),(12,1,'2024-05-27','leave fature user selection automatic','2024-05-27 10:03:22','2024-06-01 20:45:47','2024-06-01 20:45:47','2024-05-27'),(13,26,'2024-05-30','Sir, a few days ago, my nephew was born. Tomorrow we will celebrate his birthday. Have to make arrangements for tomorrow. So please give me a day off today and tomorrow.','2024-05-30 08:26:23','2024-06-08 11:21:34','2024-06-08 11:21:34','2024-05-31');
/*!40000 ALTER TABLE `leaves` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,1,'Dashboard','','_self','voyager-boat',NULL,NULL,1,'2022-01-24 00:22:04','2023-06-25 07:28:43','voyager.dashboard',NULL),(2,1,'Media','','_self','voyager-images',NULL,NULL,17,'2022-01-24 00:22:04','2024-01-07 10:26:50','voyager.media.index',NULL),(3,1,'Users','','_self','voyager-person',NULL,NULL,7,'2022-01-24 00:22:04','2024-01-07 10:26:59','voyager.users.index',NULL),(4,1,'Roles','','_self','voyager-lock',NULL,NULL,9,'2022-01-24 00:22:04','2024-01-07 10:26:54','voyager.roles.index',NULL),(5,1,'Tools','','_self','voyager-tools',NULL,NULL,3,'2022-01-24 00:22:04','2024-01-07 10:27:15',NULL,NULL),(6,1,'Menu Builder','','_self','voyager-list',NULL,5,1,'2022-01-24 00:22:04','2023-06-25 07:28:14','voyager.menus.index',NULL),(7,1,'Database','','_self','voyager-data',NULL,5,4,'2022-01-24 00:22:04','2024-01-07 10:26:45','voyager.database.index',NULL),(8,1,'Compass','','_self','voyager-compass',NULL,5,2,'2022-01-24 00:22:05','2024-01-07 10:26:32','voyager.compass.index',NULL),(9,1,'BREAD','','_self','voyager-bread',NULL,5,3,'2022-01-24 00:22:05','2024-01-07 10:26:45','voyager.bread.index',NULL),(10,1,'Settings','','_self','voyager-settings',NULL,NULL,20,'2022-01-24 00:22:05','2024-01-07 10:26:45','voyager.settings.index',NULL),(11,1,'Categories','','_self','voyager-categories',NULL,NULL,19,'2022-01-24 00:22:05','2024-01-07 10:26:50','voyager.categories.index',NULL),(12,1,'Posts','','_self','voyager-news',NULL,NULL,23,'2022-01-24 00:22:06','2024-01-07 10:26:45','voyager.posts.index',NULL),(13,1,'Pages','','_self','voyager-file-text',NULL,NULL,18,'2022-01-24 00:22:06','2024-01-07 10:26:50','voyager.pages.index',NULL),(14,1,'Projects','','_self','voyager-file-text',NULL,NULL,12,'2022-01-24 08:39:21','2024-01-07 10:26:54','voyager.projects.index',NULL),(15,1,'Project Targets','','_self','voyager-folder',NULL,NULL,13,'2022-01-24 09:52:26','2024-01-07 10:26:54','voyager.project-targets.index',NULL),(16,1,'Project Target Tasks','','_self','voyager-news','#000000',NULL,14,'2022-01-27 00:56:21','2024-01-07 10:26:54','voyager.project-target-tasks.index','null'),(17,1,'EOD','','_self','voyager-rocket','#000000',NULL,15,'2022-02-20 09:50:07','2024-02-18 22:40:55','voyager.eods.index','null'),(18,1,'Eod Configurations','','_self','voyager-settings',NULL,NULL,16,'2022-02-20 10:21:17','2024-02-18 22:40:55','voyager.eod-configurations.index',NULL),(19,1,'Mail Templates','','_self','voyager-mail','#000000',NULL,22,'2022-03-19 08:46:13','2024-01-07 10:26:45','voyager.mail-templates.index','null'),(20,1,'Client Informations','','_self','voyager-info-circled','#000000',NULL,10,'2022-09-14 10:34:43','2024-01-07 10:26:54','voyager.client-information.index','null'),(21,1,'Developer Informations','','_self','voyager-info-circled',NULL,NULL,11,'2022-09-15 01:01:42','2024-01-07 10:26:54','voyager.developer-information.index',NULL),(22,1,'Project Milestones','','_self','voyager-paypal',NULL,NULL,21,'2022-10-17 10:58:31','2024-01-07 10:26:45','voyager.project-milestones.index',NULL),(23,1,'User Payments','','_self','voyager-dollar',NULL,NULL,2,'2023-03-01 22:05:24','2023-11-24 10:21:30','voyager.user-payments.index',NULL),(24,1,'Incomes','','_self','voyager-dollar',NULL,NULL,4,'2023-05-25 09:44:50','2024-01-07 10:27:15','voyager.incomes.index',NULL),(25,1,'Expenses','','_self','voyager-skull',NULL,NULL,5,'2023-05-26 08:44:37','2024-01-07 10:26:59','voyager.expenses.index',NULL),(26,1,'Courses','','_self','voyager-documentation',NULL,NULL,24,'2023-06-04 20:29:47','2024-01-07 10:26:45','voyager.courses.index',NULL),(27,1,'Portfolios','','_self','voyager-rocket',NULL,NULL,25,'2023-06-23 07:58:41','2024-01-07 10:26:45','voyager.portfolios.index',NULL),(28,1,'Student Fees','','_self','voyager-receipt',NULL,NULL,6,'2023-07-01 16:39:35','2024-01-07 10:26:59','voyager.student-fees.index',NULL),(29,1,'Testings','','_self','voyager-smile',NULL,NULL,26,'2023-07-09 11:17:14','2024-01-07 10:26:45','voyager.testings.index',NULL),(30,1,'Contracts','','_self','voyager-logbook',NULL,NULL,27,'2023-08-30 19:37:41','2024-01-07 10:26:45','voyager.contracts.index',NULL),(31,1,'Fines','','_self','voyager-pirate-swords',NULL,NULL,8,'2023-11-22 11:10:55','2024-01-07 10:26:59','voyager.fines.index',NULL),(32,1,'Leaves','','_self','voyager-frown',NULL,NULL,28,'2023-12-10 19:15:02','2024-01-07 10:26:45','voyager.leaves.index',NULL),(33,1,'Short List Candidates','','_self','voyager-study',NULL,NULL,29,'2024-02-04 20:43:50','2024-02-04 20:43:50','voyager.short-list-candidates.index',NULL),(34,1,'Credentials','','_self','voyager-key',NULL,NULL,30,'2024-03-09 09:39:22','2024-03-09 09:39:22','voyager.credentials.index',NULL),(35,1,'Project Payments','','_self','voyager-milestone',NULL,NULL,31,'2024-03-17 16:39:58','2024-03-17 16:39:58','voyager.project-payments.index',NULL),(36,1,'Online Student Fees','','_self','voyager-receipt',NULL,NULL,32,'2024-03-25 05:55:36','2024-03-25 05:55:36','voyager.online-student-fees.index',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2016_01_01_000000_add_voyager_user_fields',1),(4,'2016_01_01_000000_create_data_types_table',1),(5,'2016_05_19_173453_create_menu_table',1),(6,'2016_10_21_190000_create_roles_table',1),(7,'2016_10_21_190000_create_settings_table',1),(8,'2016_11_30_135954_create_permission_table',1),(9,'2016_11_30_141208_create_permission_role_table',1),(10,'2016_12_26_201236_data_types__add__server_side',1),(11,'2017_01_13_000000_add_route_to_menu_items_table',1),(12,'2017_01_14_005015_create_translations_table',1),(13,'2017_01_15_000000_make_table_name_nullable_in_permissions_table',1),(14,'2017_03_06_000000_add_controller_to_data_types_table',1),(15,'2017_04_21_000000_add_order_to_data_rows_table',1),(16,'2017_07_05_210000_add_policyname_to_data_types_table',1),(17,'2017_08_05_000000_add_group_to_settings_table',1),(18,'2017_11_26_013050_add_user_role_relationship',1),(19,'2017_11_26_015000_create_user_roles_table',1),(20,'2018_03_11_000000_add_user_settings',1),(21,'2018_03_14_000000_add_details_to_data_types_table',1),(22,'2018_03_16_000000_make_settings_value_nullable',1),(23,'2019_08_19_000000_create_failed_jobs_table',1),(24,'2019_12_14_000001_create_personal_access_tokens_table',1),(25,'2016_01_01_000000_create_pages_table',2),(26,'2016_01_01_000000_create_posts_table',2),(27,'2016_02_15_204651_create_categories_table',2),(28,'2017_04_11_000000_alter_post_nullable_fields_table',2),(32,'2022_01_24_120331_create_projects_table',3),(64,'2018_10_10_000000_create_mail_templates_table',4),(65,'2022_01_24_121647_create_project_targets_table',4),(66,'2022_01_24_121734_create_project_target_tasks_table',4),(67,'2022_01_27_061023_create_eods_table',4),(68,'2022_02_20_151353_create_eod_configurations_table',4),(69,'2022_03_13_150823_create_eod_project_target',4),(70,'2022_04_16_101636_create_jobs_table',4),(71,'2022_06_12_132343_alter_project_target_task_table',5),(72,'2022_06_12_135405_add_notes_into_users',5),(73,'2022_09_10_104016_create_client_information_table',6),(74,'2022_09_15_003853_create_developer_information_table',7),(75,'2022_10_17_102656_create_project_milestones_table',8),(81,'2023_03_01_195621_create_user_payments_table',9),(82,'2023_03_01_224456_add_status_into_projects',9),(83,'2023_04_27_072727_add_slack_fields_into_e_o_d_configuration',10),(84,'2023_05_02_102634_make_greetings_nullable',11),(85,'2023_05_02_105535_add_plan_for_tomorrow',11),(86,'2023_05_25_091912_create_incomes_table',12),(90,'2023_05_25_093239_create_expenses_table',13),(91,'2023_05_26_082404_add_user_id_into_income',14),(96,'2023_05_26_091643_add_attachments_into_expenses',15),(97,'2023_05_26_092213_add_attachments_into_income',15),(99,'2023_06_04_202521_create_courses_table',16),(100,'2023_06_23_075215_create_portfolios_table',17),(101,'2023_06_25_065405_add_developer_id_into_expenses_table',18),(102,'2023_07_01_163257_create_student_fees_table',19),(103,'2023_07_09_111117_create_testings_table',20),(104,'2023_07_29_140530_add_attachment_into_user_payments',21),(105,'2023_08_03_100410_add_field_into_student_fees',22),(106,'2023_08_08_071029_add_income_id_into_user_payments',23),(109,'2023_08_08_083928_add_show_to_dev',24),(110,'2023_08_30_193419_create_contracts_table',25),(111,'2023_09_29_122452_update_by_command',26),(112,'2023_10_15_125213_add_client_source',27),(113,'2023_10_19_221406_null_able_user_payments',28),(114,'2023_11_04_193116_add_paid_date',28),(115,'2023_11_13_220839_add_no_fee_to_users_table',29),(116,'2023_11_18_131604_add_column_into_income_tabele',30),(117,'2023_11_18_133323_add_income_id_into_expenses',31),(118,'2023_11_22_110419_create_fines_table',32),(119,'2023_11_22_111758_add_amount_into_fine',33),(120,'2023_11_24_102636_add_soft_delete',34),(121,'2023_11_29_103016_add_percentage_into_users_table',34),(122,'2023_12_10_180219_create_leaves_table',35),(123,'2023_12_10_181941_add_active_into_user_table',35),(124,'2024_01_07_104422_add_category_id_into_courses',36),(125,'2024_01_07_184303_add_fields_into_contract',37),(126,'2024_01_07_190423_make_client_id_nullable_in_client_information_table',38),(127,'2024_02_03_045423_create_short_list_candidates_table',38),(128,'2024_02_04_225155_add_send_email',39),(129,'2024_02_17_212802_add_is_default_setting_for_eod',40),(130,'2024_03_09_092938_create_credentials_table',41),(131,'2024_03_17_161922_create_project_payments_table',42),(132,'2024_03_17_163639_increase_description_length_in_data_types_table',43),(133,'2024_03_17_175023_add_total_contract_amount_to_projects_table',44),(134,'2024_03_17_181615_increase_attachments_length_in_project_payments_table',45),(135,'2024_03_23_082311_create_online_student_fees_table',46),(136,'2024_03_23_082828_add_end_date_to_leaves_table',46),(137,'2024_05_01_234411_ad_developer_payment_request_id',47),(138,'2024_05_02_000045_add_generated_by_system_to_user_payments_table',47),(139,'2024_06_09_124156_add_client_id',48);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `online_student_fees`
--

DROP TABLE IF EXISTS `online_student_fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `online_student_fees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `amount` double NOT NULL,
  `batch` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_date` datetime DEFAULT NULL,
  `receiver_id` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `online_student_fees`
--

LOCK TABLES `online_student_fees` WRITE;
/*!40000 ALTER TABLE `online_student_fees` DISABLE KEYS */;
INSERT INTO `online_student_fees` VALUES (1,87,5000,'Online HTML CSS BOOTSTRAP JS','2024-03-25 10:25:21','pending',NULL,NULL,NULL,'2024-03-25 10:25:21','2024-05-21 18:55:54','2024-05-21 18:55:54'),(2,87,5000,'Online HTML CSS BOOTSTRAP JS','2024-05-21 18:50:46','pending',NULL,NULL,NULL,'2024-05-21 18:50:46','2024-05-21 18:55:49','2024-05-21 18:55:49'),(3,100,5000,'Online HTML CSS BOOTSTRAP JS','2024-05-21 18:50:46','pending',NULL,NULL,NULL,'2024-05-21 18:50:46','2024-06-09 09:05:24','2024-06-09 09:05:24'),(4,101,5000,'Online HTML CSS BOOTSTRAP JS','2024-05-21 18:50:46','pending',NULL,NULL,NULL,'2024-05-21 18:50:46','2024-06-09 09:05:24','2024-06-09 09:05:24'),(5,102,5000,'Online HTML CSS BOOTSTRAP JS','2024-05-21 18:50:46','pending',NULL,NULL,NULL,'2024-05-21 18:50:46','2024-05-21 19:03:22','2024-05-21 19:03:22'),(6,101,5000,'batch1thml','2024-05-21 00:00:00','pending',NULL,NULL,NULL,'2024-05-21 19:02:59','2024-06-09 09:05:24','2024-06-09 09:05:24');
/*!40000 ALTER TABLE `online_student_fees` ENABLE KEYS */;
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
INSERT INTO `permission_role` VALUES (1,1),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,10),(1,11),(1,12),(1,19),(1,32),(1,33),(1,37),(1,38),(1,41),(1,42),(1,43),(2,1),(2,6),(3,1),(3,6),(4,1),(4,6),(5,1),(5,6),(6,1),(6,6),(7,1),(7,6),(8,1),(8,6),(9,1),(9,6),(10,1),(10,6),(11,1),(11,6),(12,1),(12,6),(13,1),(13,6),(14,1),(14,6),(15,1),(15,6),(16,1),(16,6),(16,7),(16,27),(16,29),(16,32),(16,43),(17,1),(17,6),(17,7),(17,27),(17,29),(17,32),(17,43),(18,1),(18,6),(18,29),(18,32),(18,43),(19,1),(19,6),(19,7),(19,24),(19,27),(19,32),(19,43),(20,1),(20,6),(20,32),(21,1),(21,6),(22,1),(22,6),(23,1),(23,6),(24,1),(24,6),(25,1),(25,6),(26,1),(26,6),(26,22),(26,32),(26,33),(26,35),(27,1),(27,6),(27,22),(27,32),(27,33),(27,35),(28,1),(28,6),(28,22),(28,32),(28,33),(28,35),(29,1),(29,6),(29,22),(29,32),(29,33),(29,35),(30,1),(30,6),(30,22),(30,35),(31,1),(31,6),(31,18),(32,1),(32,6),(32,18),(33,1),(33,6),(33,18),(34,1),(34,6),(34,18),(35,1),(35,6),(36,1),(36,6),(36,13),(36,17),(37,1),(37,6),(37,13),(37,17),(38,1),(38,6),(38,13),(38,17),(39,1),(39,6),(39,13),(39,17),(40,1),(40,6),(40,17),(41,1),(41,3),(41,5),(41,6),(42,1),(42,3),(42,5),(42,6),(43,1),(43,3),(43,5),(43,6),(44,1),(44,3),(44,5),(44,6),(45,1),(45,3),(45,6),(46,1),(46,5),(46,6),(47,1),(47,5),(47,6),(48,1),(48,5),(48,6),(49,1),(49,5),(49,6),(50,1),(50,5),(50,6),(51,1),(51,5),(51,6),(52,1),(52,5),(52,6),(53,1),(53,5),(53,6),(54,1),(54,5),(54,6),(55,1),(55,5),(55,6),(56,1),(56,3),(56,4),(56,5),(56,6),(56,8),(56,12),(56,32),(56,33),(56,37),(56,38),(56,41),(57,1),(57,3),(57,5),(57,6),(57,8),(57,12),(57,32),(57,33),(57,37),(57,38),(57,41),(58,1),(58,5),(58,6),(58,32),(58,33),(59,1),(59,3),(59,5),(59,6),(59,8),(59,12),(59,32),(59,33),(59,37),(59,38),(59,41),(60,1),(60,5),(60,6),(60,33),(61,1),(61,3),(61,5),(61,6),(61,9),(61,32),(61,33),(61,37),(61,43),(62,1),(62,3),(62,5),(62,6),(62,9),(62,32),(62,33),(62,37),(62,43),(63,1),(63,3),(63,5),(63,6),(63,9),(63,32),(63,33),(63,37),(63,43),(64,1),(64,3),(64,5),(64,6),(64,9),(64,32),(64,33),(64,37),(64,43),(65,1),(65,3),(65,5),(65,6),(65,9),(65,43),(66,1),(66,6),(67,1),(67,6),(68,1),(68,6),(69,1),(69,6),(70,1),(70,6),(71,1),(71,7),(71,33),(71,43),(72,1),(72,7),(72,33),(72,43),(73,1),(73,7),(73,33),(73,43),(74,1),(74,7),(74,33),(74,43),(75,1),(75,7),(75,43),(76,1),(76,10),(76,33),(76,43),(77,1),(77,10),(77,33),(77,43),(78,1),(78,10),(78,33),(78,43),(79,1),(79,10),(79,33),(79,43),(80,1),(80,10),(80,43),(81,1),(81,11),(82,1),(82,11),(83,1),(83,11),(84,1),(84,11),(85,1),(85,11),(86,1),(86,14),(86,19),(87,1),(87,14),(87,19),(88,1),(88,15),(88,19),(89,1),(89,14),(90,1),(90,16),(90,19),(91,1),(91,19),(92,1),(92,19),(93,1),(93,19),(94,1),(94,19),(94,20),(95,1),(95,19),(96,1),(96,19),(97,1),(97,19),(98,1),(98,19),(99,1),(99,19),(99,21),(100,1),(100,19),(101,1),(101,3),(101,23),(101,32),(101,33),(101,37),(101,38),(101,41),(102,1),(102,3),(102,23),(102,32),(102,33),(102,37),(102,38),(102,41),(103,1),(103,3),(103,23),(103,32),(103,33),(103,37),(103,38),(104,1),(104,3),(104,23),(104,32),(104,33),(104,37),(104,38),(105,1),(105,3),(105,23),(106,1),(106,25),(106,33),(107,1),(107,25),(107,33),(108,1),(108,25),(108,33),(109,1),(109,25),(109,33),(110,1),(110,25),(110,33),(111,1),(111,26),(111,32),(111,42),(112,1),(112,26),(112,32),(112,42),(113,1),(113,32),(113,34),(113,42),(114,1),(114,26),(114,32),(114,42),(115,1),(115,32),(115,42),(116,1),(116,3),(116,28),(116,37),(116,38),(116,41),(117,1),(117,3),(117,28),(117,37),(117,38),(117,41),(118,1),(118,3),(118,28),(118,37),(118,38),(119,1),(119,3),(119,28),(119,37),(119,38),(119,41),(120,1),(121,1),(121,30),(121,43),(122,1),(122,30),(122,43),(123,1),(123,30),(123,43),(124,1),(124,30),(124,43),(125,1),(125,43),(126,1),(126,3),(126,31),(127,1),(127,3),(128,1),(129,1),(129,31),(130,1),(131,1),(131,3),(131,12),(131,33),(131,37),(131,38),(131,41),(132,1),(132,3),(132,12),(132,33),(132,37),(132,38),(132,41),(133,1),(133,3),(133,33),(133,37),(133,38),(133,41),(134,1),(134,3),(134,12),(134,33),(134,37),(134,38),(134,41),(135,1),(136,1),(136,33),(136,35),(136,43),(137,1),(137,33),(137,35),(137,43),(138,1),(138,33),(138,35),(138,43),(139,1),(139,33),(139,35),(139,43),(140,1),(140,33),(140,35),(140,43),(141,1),(141,33),(141,36),(142,1),(142,33),(142,36),(143,1),(143,33),(143,36),(144,1),(144,33),(144,36),(145,1),(145,33),(145,36),(146,1),(146,33),(146,39),(147,1),(147,33),(147,39),(148,1),(148,33),(148,39),(149,1),(149,33),(149,39),(150,1),(150,33),(151,1),(151,40),(151,42),(152,1),(152,40),(152,42),(153,1),(153,40),(153,42),(154,1),(154,40),(154,42),(155,1),(155,40),(155,42);
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
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permissions_key_index` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'browse_admin',NULL,'2022-01-24 00:22:05','2022-01-24 00:22:05'),(2,'browse_bread',NULL,'2022-01-24 00:22:05','2022-01-24 00:22:05'),(3,'browse_database',NULL,'2022-01-24 00:22:05','2022-01-24 00:22:05'),(4,'browse_media',NULL,'2022-01-24 00:22:05','2022-01-24 00:22:05'),(5,'browse_compass',NULL,'2022-01-24 00:22:05','2022-01-24 00:22:05'),(6,'browse_menus','menus','2022-01-24 00:22:05','2022-01-24 00:22:05'),(7,'read_menus','menus','2022-01-24 00:22:05','2022-01-24 00:22:05'),(8,'edit_menus','menus','2022-01-24 00:22:05','2022-01-24 00:22:05'),(9,'add_menus','menus','2022-01-24 00:22:05','2022-01-24 00:22:05'),(10,'delete_menus','menus','2022-01-24 00:22:05','2022-01-24 00:22:05'),(11,'browse_roles','roles','2022-01-24 00:22:05','2022-01-24 00:22:05'),(12,'read_roles','roles','2022-01-24 00:22:05','2022-01-24 00:22:05'),(13,'edit_roles','roles','2022-01-24 00:22:05','2022-01-24 00:22:05'),(14,'add_roles','roles','2022-01-24 00:22:05','2022-01-24 00:22:05'),(15,'delete_roles','roles','2022-01-24 00:22:05','2022-01-24 00:22:05'),(16,'browse_users','users','2022-01-24 00:22:05','2022-01-24 00:22:05'),(17,'read_users','users','2022-01-24 00:22:05','2022-01-24 00:22:05'),(18,'edit_users','users','2022-01-24 00:22:05','2022-01-24 00:22:05'),(19,'add_users','users','2022-01-24 00:22:05','2022-01-24 00:22:05'),(20,'delete_users','users','2022-01-24 00:22:05','2022-01-24 00:22:05'),(21,'browse_settings','settings','2022-01-24 00:22:05','2022-01-24 00:22:05'),(22,'read_settings','settings','2022-01-24 00:22:05','2022-01-24 00:22:05'),(23,'edit_settings','settings','2022-01-24 00:22:05','2022-01-24 00:22:05'),(24,'add_settings','settings','2022-01-24 00:22:05','2022-01-24 00:22:05'),(25,'delete_settings','settings','2022-01-24 00:22:05','2022-01-24 00:22:05'),(26,'browse_categories','categories','2022-01-24 00:22:05','2022-01-24 00:22:05'),(27,'read_categories','categories','2022-01-24 00:22:05','2022-01-24 00:22:05'),(28,'edit_categories','categories','2022-01-24 00:22:05','2022-01-24 00:22:05'),(29,'add_categories','categories','2022-01-24 00:22:05','2022-01-24 00:22:05'),(30,'delete_categories','categories','2022-01-24 00:22:05','2022-01-24 00:22:05'),(31,'browse_posts','posts','2022-01-24 00:22:06','2022-01-24 00:22:06'),(32,'read_posts','posts','2022-01-24 00:22:06','2022-01-24 00:22:06'),(33,'edit_posts','posts','2022-01-24 00:22:06','2022-01-24 00:22:06'),(34,'add_posts','posts','2022-01-24 00:22:06','2022-01-24 00:22:06'),(35,'delete_posts','posts','2022-01-24 00:22:06','2022-01-24 00:22:06'),(36,'browse_pages','pages','2022-01-24 00:22:06','2022-01-24 00:22:06'),(37,'read_pages','pages','2022-01-24 00:22:06','2022-01-24 00:22:06'),(38,'edit_pages','pages','2022-01-24 00:22:06','2022-01-24 00:22:06'),(39,'add_pages','pages','2022-01-24 00:22:06','2022-01-24 00:22:06'),(40,'delete_pages','pages','2022-01-24 00:22:06','2022-01-24 00:22:06'),(41,'browse_projects','projects','2022-01-24 08:39:21','2022-01-24 08:39:21'),(42,'read_projects','projects','2022-01-24 08:39:21','2022-01-24 08:39:21'),(43,'edit_projects','projects','2022-01-24 08:39:21','2022-01-24 08:39:21'),(44,'add_projects','projects','2022-01-24 08:39:21','2022-01-24 08:39:21'),(45,'delete_projects','projects','2022-01-24 08:39:21','2022-01-24 08:39:21'),(46,'browse_project_targets','project_targets','2022-01-24 09:52:26','2022-01-24 09:52:26'),(47,'read_project_targets','project_targets','2022-01-24 09:52:26','2022-01-24 09:52:26'),(48,'edit_project_targets','project_targets','2022-01-24 09:52:26','2022-01-24 09:52:26'),(49,'add_project_targets','project_targets','2022-01-24 09:52:26','2022-01-24 09:52:26'),(50,'delete_project_targets','project_targets','2022-01-24 09:52:26','2022-01-24 09:52:26'),(51,'browse_project_target_tasks','project_target_tasks','2022-01-27 00:56:21','2022-01-27 00:56:21'),(52,'read_project_target_tasks','project_target_tasks','2022-01-27 00:56:21','2022-01-27 00:56:21'),(53,'edit_project_target_tasks','project_target_tasks','2022-01-27 00:56:21','2022-01-27 00:56:21'),(54,'add_project_target_tasks','project_target_tasks','2022-01-27 00:56:21','2022-01-27 00:56:21'),(55,'delete_project_target_tasks','project_target_tasks','2022-01-27 00:56:21','2022-01-27 00:56:21'),(56,'browse_eods','eods','2022-02-20 09:50:07','2022-02-20 09:50:07'),(57,'read_eods','eods','2022-02-20 09:50:07','2022-02-20 09:50:07'),(58,'edit_eods','eods','2022-02-20 09:50:07','2022-02-20 09:50:07'),(59,'add_eods','eods','2022-02-20 09:50:07','2022-02-20 09:50:07'),(60,'delete_eods','eods','2022-02-20 09:50:07','2022-02-20 09:50:07'),(61,'browse_eod_configurations','eod_configurations','2022-02-20 10:21:17','2022-02-20 10:21:17'),(62,'read_eod_configurations','eod_configurations','2022-02-20 10:21:17','2022-02-20 10:21:17'),(63,'edit_eod_configurations','eod_configurations','2022-02-20 10:21:17','2022-02-20 10:21:17'),(64,'add_eod_configurations','eod_configurations','2022-02-20 10:21:17','2022-02-20 10:21:17'),(65,'delete_eod_configurations','eod_configurations','2022-02-20 10:21:17','2022-02-20 10:21:17'),(66,'browse_mail_templates','mail_templates','2022-03-19 08:46:13','2022-03-19 08:46:13'),(67,'read_mail_templates','mail_templates','2022-03-19 08:46:13','2022-03-19 08:46:13'),(68,'edit_mail_templates','mail_templates','2022-03-19 08:46:13','2022-03-19 08:46:13'),(69,'add_mail_templates','mail_templates','2022-03-19 08:46:13','2022-03-19 08:46:13'),(70,'delete_mail_templates','mail_templates','2022-03-19 08:46:13','2022-03-19 08:46:13'),(71,'browse_client_information','client_information','2022-09-14 10:34:43','2022-09-14 10:34:43'),(72,'read_client_information','client_information','2022-09-14 10:34:43','2022-09-14 10:34:43'),(73,'edit_client_information','client_information','2022-09-14 10:34:43','2022-09-14 10:34:43'),(74,'add_client_information','client_information','2022-09-14 10:34:43','2022-09-14 10:34:43'),(75,'delete_client_information','client_information','2022-09-14 10:34:43','2022-09-14 10:34:43'),(76,'browse_developer_information','developer_information','2022-09-15 01:01:42','2022-09-15 01:01:42'),(77,'read_developer_information','developer_information','2022-09-15 01:01:42','2022-09-15 01:01:42'),(78,'edit_developer_information','developer_information','2022-09-15 01:01:42','2022-09-15 01:01:42'),(79,'add_developer_information','developer_information','2022-09-15 01:01:42','2022-09-15 01:01:42'),(80,'delete_developer_information','developer_information','2022-09-15 01:01:42','2022-09-15 01:01:42'),(81,'browse_project_milestones','project_milestones','2022-10-17 10:58:31','2022-10-17 10:58:31'),(82,'read_project_milestones','project_milestones','2022-10-17 10:58:31','2022-10-17 10:58:31'),(83,'edit_project_milestones','project_milestones','2022-10-17 10:58:31','2022-10-17 10:58:31'),(84,'add_project_milestones','project_milestones','2022-10-17 10:58:31','2022-10-17 10:58:31'),(85,'delete_project_milestones','project_milestones','2022-10-17 10:58:31','2022-10-17 10:58:31'),(86,'browse_user_payments','user_payments','2023-03-01 22:05:24','2023-03-01 22:05:24'),(87,'read_user_payments','user_payments','2023-03-01 22:05:24','2023-03-01 22:05:24'),(88,'edit_user_payments','user_payments','2023-03-01 22:05:24','2023-03-01 22:05:24'),(89,'add_user_payments','user_payments','2023-03-01 22:05:24','2023-03-01 22:05:24'),(90,'delete_user_payments','user_payments','2023-03-01 22:05:24','2023-03-01 22:05:24'),(91,'browse_incomes','incomes','2023-05-25 09:44:50','2023-05-25 09:44:50'),(92,'read_incomes','incomes','2023-05-25 09:44:50','2023-05-25 09:44:50'),(93,'edit_incomes','incomes','2023-05-25 09:44:50','2023-05-25 09:44:50'),(94,'add_incomes','incomes','2023-05-25 09:44:50','2023-05-25 09:44:50'),(95,'delete_incomes','incomes','2023-05-25 09:44:50','2023-05-25 09:44:50'),(96,'browse_expenses','expenses','2023-05-26 08:44:37','2023-05-26 08:44:37'),(97,'read_expenses','expenses','2023-05-26 08:44:37','2023-05-26 08:44:37'),(98,'edit_expenses','expenses','2023-05-26 08:44:37','2023-05-26 08:44:37'),(99,'add_expenses','expenses','2023-05-26 08:44:37','2023-05-26 08:44:37'),(100,'delete_expenses','expenses','2023-05-26 08:44:37','2023-05-26 08:44:37'),(101,'browse_courses','courses','2023-06-04 20:29:47','2023-06-04 20:29:47'),(102,'read_courses','courses','2023-06-04 20:29:47','2023-06-04 20:29:47'),(103,'edit_courses','courses','2023-06-04 20:29:47','2023-06-04 20:29:47'),(104,'add_courses','courses','2023-06-04 20:29:47','2023-06-04 20:29:47'),(105,'delete_courses','courses','2023-06-04 20:29:47','2023-06-04 20:29:47'),(106,'browse_portfolios','portfolios','2023-06-23 07:58:41','2023-06-23 07:58:41'),(107,'read_portfolios','portfolios','2023-06-23 07:58:41','2023-06-23 07:58:41'),(108,'edit_portfolios','portfolios','2023-06-23 07:58:41','2023-06-23 07:58:41'),(109,'add_portfolios','portfolios','2023-06-23 07:58:41','2023-06-23 07:58:41'),(110,'delete_portfolios','portfolios','2023-06-23 07:58:41','2023-06-23 07:58:41'),(111,'browse_student_fees','student_fees','2023-07-01 16:39:35','2023-07-01 16:39:35'),(112,'read_student_fees','student_fees','2023-07-01 16:39:35','2023-07-01 16:39:35'),(113,'edit_student_fees','student_fees','2023-07-01 16:39:35','2023-07-01 16:39:35'),(114,'add_student_fees','student_fees','2023-07-01 16:39:35','2023-07-01 16:39:35'),(115,'delete_student_fees','student_fees','2023-07-01 16:39:35','2023-07-01 16:39:35'),(116,'browse_testings','testings','2023-07-09 11:17:14','2023-07-09 11:17:14'),(117,'read_testings','testings','2023-07-09 11:17:14','2023-07-09 11:17:14'),(118,'edit_testings','testings','2023-07-09 11:17:14','2023-07-09 11:17:14'),(119,'add_testings','testings','2023-07-09 11:17:14','2023-07-09 11:17:14'),(120,'delete_testings','testings','2023-07-09 11:17:14','2023-07-09 11:17:14'),(121,'browse_contracts','contracts','2023-08-30 19:37:41','2023-08-30 19:37:41'),(122,'read_contracts','contracts','2023-08-30 19:37:41','2023-08-30 19:37:41'),(123,'edit_contracts','contracts','2023-08-30 19:37:41','2023-08-30 19:37:41'),(124,'add_contracts','contracts','2023-08-30 19:37:41','2023-08-30 19:37:41'),(125,'delete_contracts','contracts','2023-08-30 19:37:41','2023-08-30 19:37:41'),(126,'browse_fines','fines','2023-11-22 11:10:55','2023-11-22 11:10:55'),(127,'read_fines','fines','2023-11-22 11:10:55','2023-11-22 11:10:55'),(128,'edit_fines','fines','2023-11-22 11:10:55','2023-11-22 11:10:55'),(129,'add_fines','fines','2023-11-22 11:10:55','2023-11-22 11:10:55'),(130,'delete_fines','fines','2023-11-22 11:10:55','2023-11-22 11:10:55'),(131,'browse_leaves','leaves','2023-12-10 19:15:02','2023-12-10 19:15:02'),(132,'read_leaves','leaves','2023-12-10 19:15:02','2023-12-10 19:15:02'),(133,'edit_leaves','leaves','2023-12-10 19:15:02','2023-12-10 19:15:02'),(134,'add_leaves','leaves','2023-12-10 19:15:02','2023-12-10 19:15:02'),(135,'delete_leaves','leaves','2023-12-10 19:15:02','2023-12-10 19:15:02'),(136,'browse_short_list_candidates','short_list_candidates','2024-02-04 20:43:50','2024-02-04 20:43:50'),(137,'read_short_list_candidates','short_list_candidates','2024-02-04 20:43:50','2024-02-04 20:43:50'),(138,'edit_short_list_candidates','short_list_candidates','2024-02-04 20:43:50','2024-02-04 20:43:50'),(139,'add_short_list_candidates','short_list_candidates','2024-02-04 20:43:50','2024-02-04 20:43:50'),(140,'delete_short_list_candidates','short_list_candidates','2024-02-04 20:43:50','2024-02-04 20:43:50'),(141,'browse_credentials','credentials','2024-03-09 09:39:22','2024-03-09 09:39:22'),(142,'read_credentials','credentials','2024-03-09 09:39:22','2024-03-09 09:39:22'),(143,'edit_credentials','credentials','2024-03-09 09:39:22','2024-03-09 09:39:22'),(144,'add_credentials','credentials','2024-03-09 09:39:22','2024-03-09 09:39:22'),(145,'delete_credentials','credentials','2024-03-09 09:39:22','2024-03-09 09:39:22'),(146,'browse_project_payments','project_payments','2024-03-17 16:39:58','2024-03-17 16:39:58'),(147,'read_project_payments','project_payments','2024-03-17 16:39:58','2024-03-17 16:39:58'),(148,'edit_project_payments','project_payments','2024-03-17 16:39:58','2024-03-17 16:39:58'),(149,'add_project_payments','project_payments','2024-03-17 16:39:58','2024-03-17 16:39:58'),(150,'delete_project_payments','project_payments','2024-03-17 16:39:58','2024-03-17 16:39:58'),(151,'browse_online_student_fees','online_student_fees','2024-03-25 05:55:36','2024-03-25 05:55:36'),(152,'read_online_student_fees','online_student_fees','2024-03-25 05:55:36','2024-03-25 05:55:36'),(153,'edit_online_student_fees','online_student_fees','2024-03-25 05:55:36','2024-03-25 05:55:36'),(154,'add_online_student_fees','online_student_fees','2024-03-25 05:55:36','2024-03-25 05:55:36'),(155,'delete_online_student_fees','online_student_fees','2024-03-25 05:55:36','2024-03-25 05:55:36');
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
-- Table structure for table `portfolios`
--

DROP TABLE IF EXISTS `portfolios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolios`
--

LOCK TABLES `portfolios` WRITE;
/*!40000 ALTER TABLE `portfolios` DISABLE KEYS */;
/*!40000 ALTER TABLE `portfolios` ENABLE KEYS */;
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
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `status` enum('PUBLISHED','DRAFT','PENDING') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_milestones`
--

DROP TABLE IF EXISTS `project_milestones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_milestones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `developer_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` double(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_milestones`
--

LOCK TABLES `project_milestones` WRITE;
/*!40000 ALTER TABLE `project_milestones` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_milestones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_payments`
--

DROP TABLE IF EXISTS `project_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `milestone_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `due_date` date DEFAULT NULL,
  `attachments` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_payments_project_id_foreign` (`project_id`),
  CONSTRAINT `project_payments_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_payments`
--

LOCK TABLES `project_payments` WRITE;
/*!40000 ALTER TABLE `project_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_payments` ENABLE KEYS */;
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
  `date` date NOT NULL,
  `hours` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `minutes` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_target_tasks_developer_id_foreign` (`developer_id`),
  CONSTRAINT `project_target_tasks_developer_id_foreign` FOREIGN KEY (`developer_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_target_tasks`
--

LOCK TABLES `project_target_tasks` WRITE;
/*!40000 ALTER TABLE `project_target_tasks` DISABLE KEYS */;
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_targets`
--

LOCK TABLES `project_targets` WRITE;
/*!40000 ALTER TABLE `project_targets` DISABLE KEYS */;
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
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'In Progress',
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Direct',
  `total_contract_amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expected_delivery_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_client_id_foreign` (`client_id`),
  CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrator','Administrator','2022-01-24 00:22:05','2023-03-06 00:49:45'),(2,'User','User','2022-01-24 00:22:05','2023-02-18 13:04:31'),(3,'Developer','Developer','2022-01-24 00:26:42','2022-01-24 00:26:42'),(4,'Client','Client','2022-01-24 07:03:09','2022-01-24 07:03:09'),(5,'Quality Assurance','Quality Assurance','2022-04-09 14:47:34','2022-04-09 14:47:34'),(6,'Super Admin','Super Admin','2022-06-25 18:11:41','2022-06-25 18:11:41'),(7,'Client Information Management','Client Information Management','2022-09-14 11:00:36','2022-09-14 11:03:54'),(8,'EOD Management','EOD Management','2022-09-14 11:04:41','2022-09-14 11:04:41'),(9,'EOD Configuration Management','EOD Configuration Management','2022-09-14 11:09:47','2022-09-14 11:09:47'),(10,'Developer - Information Management','Developer - Information Management','2022-09-15 01:09:16','2022-09-15 01:09:16'),(11,'Project Milestone Management','Project Milestone Management','2022-10-17 11:04:24','2022-10-17 11:04:24'),(12,'Student','Student','2022-10-17 11:05:57','2022-10-17 11:05:57'),(13,'Information Pages','Information Pages','2023-02-13 12:01:37','2023-02-13 12:01:37'),(14,'Add and View Payment Requests','Add and View Payment Requests','2023-03-01 22:22:56','2023-03-01 22:22:56'),(15,'Approve Payment Requests','Approve Payment Requests','2023-03-01 22:23:34','2023-03-01 22:23:34'),(16,'Delete Payment Requests','Delete Payment Requests','2023-03-01 22:23:49','2023-03-01 22:23:49'),(17,'Manage Pages','Manage Pages','2023-03-05 23:40:40','2023-03-05 23:40:40'),(18,'Add, Edit, View Posts','Add, Edit, View Posts','2023-03-05 23:42:55','2023-03-05 23:42:55'),(19,'Accountant','Accountant','2023-03-06 00:16:03','2023-03-06 00:16:03'),(20,'Income Management','Income Management','2023-05-25 09:48:06','2023-05-25 09:48:06'),(21,'Expense Management','Expensen Management','2023-05-25 09:48:44','2023-05-25 09:48:44'),(22,'Categories Management','Categories Management','2023-06-04 20:35:04','2023-06-04 20:35:04'),(23,'Courses Management','Courses Management','2023-06-04 20:35:37','2023-06-04 20:35:37'),(24,'Add User Role','Add User Role','2023-06-22 20:00:40','2023-06-22 20:00:40'),(25,'Porfolio Management','Porfolio Management','2023-06-23 08:01:53','2023-06-23 08:01:53'),(26,'Student Fee Management','Student Fee Management','2023-07-01 16:41:42','2023-07-01 16:41:42'),(27,'Add and View User Role','Add and View User Role','2023-07-03 11:07:47','2023-07-03 11:07:47'),(28,'Testing Data','Testing Data','2023-07-09 11:18:06','2023-07-09 11:18:06'),(29,'Edit User Role','Edit User Role','2023-07-19 17:22:37','2023-07-19 17:22:37'),(30,'Manage contracts','Manage contracts','2023-08-30 19:44:37','2023-08-30 19:44:37'),(31,'Fine Management','Fine Management','2023-11-22 11:12:33','2023-11-22 11:12:33'),(32,'CR','CR','2023-12-10 17:12:37','2023-12-10 17:12:37'),(33,'Bussiness Developer','Bussiness Developer','2023-12-20 19:29:53','2023-12-20 19:29:53'),(34,'Edit update student fee','Edit update student fee','2024-01-25 12:36:12','2024-01-25 12:36:12'),(35,'Short List Candidates Permissions','Short List Candidates Permissions','2024-02-04 20:46:26','2024-02-04 20:46:26'),(36,'Credentails Management','Credentails Management','2024-03-09 15:05:10','2024-03-09 15:05:10'),(37,'GR Captain','GR Captain','2024-03-09 20:57:21','2024-03-09 20:57:21'),(38,'Female Student','Female Student','2024-03-09 20:58:45','2024-03-09 20:58:45'),(39,'Client Project Payments','Client Project Payments','2024-03-17 16:41:56','2024-03-17 16:41:56'),(40,'Online Student Fee Management','Online Student Fee Management','2024-03-23 09:37:10','2024-03-25 06:52:18'),(41,'Online Student','Online Student','2024-03-25 06:57:33','2024-03-25 06:57:33'),(42,'Student and Online Student Management','Student and Online Student Management','2024-06-09 08:09:53','2024-06-09 08:09:53'),(43,'HR (Human resources) Manager','HR (Human resources) Manager','2024-06-09 08:35:47','2024-06-09 08:35:47');
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
  `value` text COLLATE utf8mb4_unicode_ci,
  `details` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '1',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site.title','Site Title','Site Title','','text',1,'Site'),(2,'site.description','Site Description','Site Description','','text',2,'Site'),(3,'site.logo','Site Logo','','','image',3,'Site'),(4,'site.google_analytics_tracking_id','Google Analytics Tracking ID',NULL,'','text',4,'Site'),(5,'admin.bg_image','Admin Background Image','settings/December2023/P3bQu2PORMRwdMirW1HR.jpg','','image',5,'Admin'),(6,'admin.title','Admin Title','Webpenter Daily Portal','','text',1,'Admin'),(7,'admin.description','Admin Description','Send Daily Updates To Clients','','text',2,'Admin'),(8,'admin.loader','Admin Loader','','','image',3,'Admin'),(9,'admin.icon_image','Admin Icon Image','settings/October2023/9N6D9VcedAIy1c3JHXIi.jpg','','image',4,'Admin'),(10,'admin.google_analytics_client_id','Google Analytics Client ID (used for admin dashboard)','G-9LHPF62J7N','','text',1,'Admin'),(16,'email-configuration.driver','Driver (like: smtp)','smtp',NULL,'text',6,'Email Configuration'),(17,'email-configuration.host','Host (like: smtp.mailtrap.io)','smtp.googlemail.com',NULL,'text',7,'Email Configuration'),(18,'email-configuration.port','Port (like:2525)','465',NULL,'text',8,'Email Configuration'),(21,'email-configuration.from','From (your sender email address like: a@b.com)','wearewebpenter@gmail.com',NULL,'text',9,'Email Configuration'),(22,'email-configuration.encryption','Encryption (like: tls)','ssl',NULL,'text',11,'Email Configuration'),(23,'email-configuration.username','Username (username of your account)','wearewebpenter@gmail.com',NULL,'text',12,'Email Configuration'),(24,'email-configuration.password','Password (password of your account)','qjsohznohrmkebgs',NULL,'text',13,'Email Configuration'),(25,'email-configuration.from.name','From Name','wearewebpenter@gmail.com',NULL,'text',10,'Email Configuration'),(26,'admin.management_payment_request_email','Management Payment Request Email','wearewebpenter@gmail.com',NULL,'text',14,'Admin'),(27,'academy.student_fee_amount','Student Fee Amount','2000',NULL,'text',15,'Academy'),(28,'academy.student_role_id','Student Rol Id','12',NULL,'text',16,'Academy'),(29,'academy.student_batch_name','Student Batch Name','JS Batch',NULL,'text',17,'Academy'),(30,'academy.online_student_fee_amount','Online Student Fee Amount','5000',NULL,'text',18,'Academy'),(31,'academy.online_student_role_id','Online Student Role Id','41',NULL,'text',19,'Academy'),(32,'academy.online_student_batch_name','Online Student Batch Name','Online HTML CSS BOOTSTRAP JS',NULL,'text',20,'Academy'),(33,'admin.developer_role_id','Developer Role Id','3',NULL,'text',21,'Admin'),(34,'admin.administrator_role_id','Administrator Role Id','1',NULL,'text',22,'Admin');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `short_list_candidates`
--

DROP TABLE IF EXISTS `short_list_candidates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `short_list_candidates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `short_list_candidates`
--

LOCK TABLES `short_list_candidates` WRITE;
/*!40000 ALTER TABLE `short_list_candidates` DISABLE KEYS */;
INSERT INTO `short_list_candidates` VALUES (1,5,'test created at','test','[{\"download_link\":\"short-list-candidates\\/February2024\\/czShyJ0RnqxphNu3E52g.csv\",\"original_name\":\"2BwnRgFsC9oFLyVWhe1N.csv\"}]','2024-02-04 20:48:07','2024-02-04 20:50:30','2024-02-04 20:50:30');
/*!40000 ALTER TABLE `short_list_candidates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_fees`
--

DROP TABLE IF EXISTS `student_fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_fees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `batch` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime DEFAULT NULL,
  `student_id` bigint unsigned NOT NULL,
  `amount` double NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_date` datetime DEFAULT NULL,
  `receiver_id` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_fees`
--

LOCK TABLES `student_fees` WRITE;
/*!40000 ALTER TABLE `student_fees` DISABLE KEYS */;
INSERT INTO `student_fees` VALUES (1,'',NULL,25,3000,'paid',NULL,4,'Shahzad baloch payment','2023-07-01 16:55:38','2023-07-01 16:56:05','2023-07-01 16:56:05'),(2,'batch1php','2023-08-03 00:00:00',26,2000,'paid',NULL,1,'fees collected','2023-07-06 18:04:00','2023-08-05 11:30:43',NULL),(3,'batch1php','2023-07-31 00:00:00',27,2000,'paid',NULL,1,'7/6/23\r\nfees collected php','2023-07-06 18:05:00','2023-11-27 16:25:32',NULL),(4,'batch1php','2023-07-05 00:00:00',25,2000,'paid',NULL,4,'due to laptop issue php','2023-07-06 18:06:00','2023-11-27 16:25:08',NULL),(5,'batch1php','2023-08-11 00:00:00',29,2000,'paid',NULL,4,'fees collected php','2023-07-06 18:07:00','2023-11-27 16:24:48',NULL),(6,'batch1php','2023-08-02 00:00:00',30,2000,'pending',NULL,1,'fees collected till 02/8/23','2023-07-06 18:08:00','2023-11-24 10:16:06','2023-11-24 10:16:06'),(7,'batch1php','2023-07-05 00:00:00',37,2000,'pending',NULL,4,'php','2023-07-06 18:09:00','2023-11-24 10:22:46','2023-11-24 10:22:46'),(8,'batch1php','2023-07-05 00:00:00',38,2000,'paid',NULL,1,'php','2023-07-06 18:10:00','2023-11-27 16:24:28',NULL),(9,'batch1thml','2023-08-02 00:00:00',44,2000,'paid',NULL,1,'fess collected 3000 2 month \r\ntill 02/08/23 html','2023-07-19 18:09:00','2023-11-27 16:24:07',NULL),(10,'batch1thml','2023-08-19 00:00:00',45,2000,'paid',NULL,4,'Fees collected html','2023-07-19 18:09:00','2023-11-27 16:20:37',NULL),(11,'batch1php','2023-08-04 00:00:00',46,2000,'paid',NULL,4,'Fess collected php','2023-07-19 18:10:00','2023-11-27 16:23:45',NULL),(12,'batch1thml','2023-08-15 00:00:00',40,2000,'paid',NULL,1,'fees collected html','2023-07-19 19:33:00','2023-11-27 16:23:29',NULL),(13,'batch1thml','2023-08-05 00:00:00',41,2000,'paid',NULL,4,'fees collected \r\n03029224797 html','2023-07-19 19:34:00','2023-11-27 16:21:59',NULL),(14,'batch1thml','2023-08-04 00:00:00',42,2000,'paid',NULL,4,'fees collected html\r\n03133197666','2023-07-19 19:34:00','2023-11-27 16:23:12',NULL),(15,'batch1thml','2023-08-12 00:00:00',43,2000,'paid',NULL,1,'fees collected php','2023-07-19 19:35:00','2023-11-27 16:25:53',NULL),(16,'batch1thml','2023-07-03 00:00:00',48,2000,'paid',NULL,1,'fees collected html','2023-08-01 15:18:00','2023-11-27 16:26:14',NULL),(17,'batch1thml','2023-07-31 00:00:00',49,2000,'paid',NULL,4,'html','2023-08-01 16:41:00','2023-11-27 16:26:32',NULL),(18,'batch1thml','2023-08-01 00:00:00',50,2000,'paid','2023-11-06 00:00:00',4,'contact no, 03085782560','2023-08-01 17:34:00','2023-11-13 17:56:57',NULL),(19,'batch1thml','2023-07-21 00:00:00',51,2000,'paid',NULL,1,'new student','2023-08-24 17:10:21','2023-11-27 16:21:33',NULL),(20,'batch1thml','2023-07-21 00:00:00',52,2000,'paid',NULL,1,'new student','2023-08-24 17:13:57','2023-11-27 16:21:06',NULL),(21,'HTML CSS JS PHP','2023-11-03 00:00:00',25,2000,'paid','2023-11-10 00:00:00',1,NULL,'2023-11-03 20:56:26','2023-11-13 17:55:28',NULL),(22,'HTML CSS JS PHP','2023-11-03 00:00:00',26,2000,'paid','2023-11-02 00:00:00',1,NULL,'2023-11-03 20:56:00','2023-11-13 17:56:08',NULL),(23,'HTML CSS JS PHP','2023-11-03 00:00:00',27,2000,'paid','2023-12-12 00:00:00',1,'Paid to Waqar, Waqar given to me','2023-11-03 20:56:26','2023-12-12 15:55:30',NULL),(24,'HTML CSS JS PHP','2023-11-03 00:00:00',28,2000,'paid','2023-11-13 00:00:00',1,'previous month pending 2000','2023-11-03 20:56:26','2023-11-13 16:32:29',NULL),(25,'HTML CSS JS PHP','2023-11-03 20:56:26',29,2000,'pending',NULL,1,NULL,'2023-11-03 20:56:26','2023-12-13 20:52:35','2023-12-13 20:52:35'),(26,'HTML CSS JS PHP','2023-11-03 20:56:26',30,2000,'pending',NULL,1,NULL,'2023-11-03 20:56:26','2023-11-24 10:16:06','2023-11-24 10:16:06'),(27,'HTML CSS JS PHP','2023-11-03 20:56:26',31,2000,'pending',NULL,1,NULL,'2023-11-03 20:56:26','2023-11-24 10:16:24','2023-11-24 10:16:24'),(28,'HTML CSS JS PHP','2023-11-03 20:56:26',37,2000,'pending',NULL,1,NULL,'2023-11-03 20:56:26','2023-11-24 10:22:46','2023-11-24 10:22:46'),(29,'HTML CSS JS PHP','2023-11-03 20:56:26',38,2000,'pending',NULL,1,NULL,'2023-11-03 20:56:26','2023-12-13 20:52:05','2023-12-13 20:52:05'),(30,'HTML CSS JS PHP','2023-11-03 20:56:26',39,2000,'pending',NULL,1,NULL,'2023-11-03 20:56:26','2023-11-24 10:24:21','2023-11-24 10:24:21'),(31,'HTML CSS JS PHP','2023-11-03 00:00:00',40,2000,'paid','2023-12-20 00:00:00',1,'Paid of 11 but student saying I\'m was on the leaves of month 11','2023-11-03 20:56:26','2023-12-20 18:58:54',NULL),(32,'HTML CSS JS PHP','2023-11-03 00:00:00',41,2000,'paid','2023-11-13 00:00:00',1,NULL,'2023-11-03 20:56:26','2023-11-13 16:30:52',NULL),(33,'HTML CSS JS PHP','2023-11-03 20:56:26',42,2000,'pending',NULL,1,NULL,'2023-11-03 20:56:26','2024-01-03 20:25:57','2024-01-03 20:25:57'),(34,'HTML CSS JS PHP','2023-11-03 00:00:00',43,2000,'paid','2023-11-15 00:00:00',1,NULL,'2023-11-03 20:56:26','2023-11-15 19:27:25',NULL),(35,'HTML CSS JS PHP','2023-11-03 20:56:26',44,2000,'pending',NULL,1,NULL,'2023-11-03 20:56:26','2024-01-03 20:25:57','2024-01-03 20:25:57'),(36,'HTML CSS JS PHP','2023-11-03 00:00:00',45,2000,'paid','2023-11-20 00:00:00',1,'Fee collected by Waqar','2023-11-03 20:56:26','2023-11-21 14:26:20',NULL),(37,'HTML CSS JS PHP','2023-11-03 00:00:00',46,2000,'paid','2023-12-06 00:00:00',1,'Collected by Waqar','2023-11-03 20:56:26','2023-12-06 11:54:16',NULL),(38,'HTML CSS JS PHP','2023-11-03 20:56:26',48,2000,'pending',NULL,1,NULL,'2023-11-03 20:56:26','2024-01-03 20:25:57','2024-01-03 20:25:57'),(39,'HTML CSS JS PHP','2023-11-03 00:00:00',49,2000,'paid','2023-11-27 00:00:00',1,NULL,'2023-11-03 20:56:26','2023-11-27 16:12:51',NULL),(40,'HTML CSS JS PHP','2023-11-03 00:00:00',50,2000,'paid','2023-11-23 00:00:00',1,NULL,'2023-11-03 20:56:26','2023-11-23 15:48:29',NULL),(41,'HTML CSS JS PHP','2023-11-03 20:56:26',51,2000,'pending',NULL,1,NULL,'2023-11-03 20:56:26','2024-02-24 17:03:47','2024-02-24 17:03:47'),(42,'HTML CSS JS PHP','2023-11-03 20:56:26',52,2000,'pending',NULL,1,NULL,'2023-11-03 20:56:26','2024-02-24 17:03:56','2024-02-24 17:03:56'),(43,'batch1thml','2023-11-13 00:00:00',55,2000,'paid','2024-01-18 00:00:00',NULL,'Fee collected by Ahamd Bilal','2023-11-13 16:33:49','2024-01-18 22:52:17',NULL),(44,'batch1thml','2023-11-13 00:00:00',56,2000,'paid','2023-12-14 00:00:00',NULL,'Paid by Bilal to me','2023-11-13 16:34:19','2023-12-14 14:42:05',NULL),(45,'batch1thml','2023-11-13 00:00:00',57,2000,'pending',NULL,NULL,NULL,'2023-11-13 16:34:46','2024-06-09 09:05:12','2024-06-09 09:05:12'),(46,'batch1thml','2023-11-13 00:00:00',58,2000,'pending',NULL,NULL,'previous 2000 in pending','2023-11-13 16:35:20','2024-01-03 20:22:23','2024-01-03 20:22:23'),(47,'batch1thml','2023-11-13 00:00:00',59,2000,'pending',NULL,NULL,NULL,'2023-11-13 16:56:11','2024-01-03 20:22:40','2024-01-03 20:22:40'),(48,'batch1thml','2023-11-13 00:00:00',60,2000,'paid','2023-11-13 00:00:00',NULL,NULL,'2023-11-13 16:57:20','2023-11-13 16:58:58',NULL),(49,'batch1thml','2023-11-13 00:00:00',61,2000,'paid','2023-11-23 00:00:00',NULL,NULL,'2023-11-13 16:57:55','2023-11-23 15:42:12',NULL),(50,'batch1thml','2023-11-13 00:00:00',62,2000,'pending',NULL,NULL,NULL,'2023-11-13 16:58:27','2024-02-24 17:03:06','2024-02-24 17:03:06'),(51,'HTML CSS JS PHP','2023-12-04 12:13:31',25,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2024-01-03 20:25:57','2024-01-03 20:25:57'),(52,'HTML CSS JS PHP','2023-12-04 00:00:00',26,2000,'paid','2023-12-06 00:00:00',1,'Collected by Waqar','2023-12-04 12:13:31','2023-12-06 11:54:53',NULL),(53,'HTML CSS JS PHP','2023-12-04 00:00:00',27,2000,'paid','2023-12-30 00:00:00',1,NULL,'2023-12-04 12:13:31','2024-02-06 16:45:47',NULL),(54,'HTML CSS JS PHP','2023-12-04 00:00:00',28,2000,'paid',NULL,1,'Paid to waqar sir','2023-12-04 12:13:31','2024-02-02 15:09:07',NULL),(55,'HTML CSS JS PHP','2023-12-04 12:13:31',29,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2023-12-13 20:52:28','2023-12-13 20:52:28'),(56,'HTML CSS JS PHP','2023-12-04 12:13:31',30,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2023-12-16 18:09:50','2023-12-16 18:09:50'),(57,'HTML CSS JS PHP','2023-12-04 12:13:31',40,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2024-06-09 09:05:12','2024-06-09 09:05:12'),(58,'HTML CSS JS PHP','2023-12-04 00:00:00',41,2000,'paid','2024-01-18 00:00:00',1,'Collected by ayub','2023-12-04 12:13:31','2024-01-18 22:53:59',NULL),(59,'HTML CSS JS PHP','2023-12-04 12:13:31',42,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2023-12-16 18:11:09','2023-12-16 18:11:09'),(60,'HTML CSS JS PHP','2023-12-04 12:13:31',43,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2023-12-16 18:10:58','2023-12-16 18:10:58'),(61,'HTML CSS JS PHP','2023-12-04 12:13:31',44,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2024-01-03 20:25:57','2024-01-03 20:25:57'),(62,'HTML CSS JS PHP','2023-12-04 00:00:00',45,2000,'paid','2023-12-28 00:00:00',1,'2k though Salman','2023-12-04 12:13:31','2023-12-28 16:54:34',NULL),(63,'HTML CSS JS PHP','2023-12-04 00:00:00',46,2000,'paid','2023-12-06 00:00:00',1,'Collected by Waqar','2023-12-04 12:13:31','2023-12-06 11:53:42',NULL),(64,'HTML CSS JS PHP','2023-12-04 12:13:31',48,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2024-01-03 20:25:57','2024-01-03 20:25:57'),(65,'HTML CSS JS PHP','2023-12-04 00:00:00',49,2000,'paid','2024-01-18 00:00:00',1,'Ayub sir get fees from sair','2023-12-04 12:13:31','2024-01-25 12:46:17',NULL),(66,'HTML CSS JS PHP','2023-12-04 12:13:31',50,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2024-06-09 09:05:12','2024-06-09 09:05:12'),(67,'HTML CSS JS PHP','2023-12-04 12:13:31',51,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2024-02-24 17:02:00','2024-02-24 17:02:00'),(68,'HTML CSS JS PHP','2023-12-04 12:13:31',52,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2024-02-24 17:02:25','2024-02-24 17:02:25'),(69,'HTML CSS JS PHP','2023-12-04 00:00:00',55,2000,'paid','2024-03-04 00:00:00',1,'Paid  to Khurshid Bilal','2023-12-04 12:13:31','2024-03-04 15:29:01',NULL),(70,'HTML CSS JS PHP','2023-12-04 00:00:00',56,2000,'pending','2024-03-07 00:00:00',1,'Paid to Rasid Bukhari Sir','2023-12-04 12:13:31','2024-06-09 09:05:12','2024-06-09 09:05:12'),(71,'HTML CSS JS PHP','2023-12-04 12:13:31',57,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2024-06-09 09:05:12','2024-06-09 09:05:12'),(72,'HTML CSS JS PHP','2023-12-04 12:13:31',58,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2024-01-03 20:22:23','2024-01-03 20:22:23'),(73,'HTML CSS JS PHP','2023-12-04 12:13:31',59,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2024-01-03 20:22:40','2024-01-03 20:22:40'),(74,'HTML CSS JS PHP','2023-12-04 00:00:00',60,2000,'paid','2024-01-23 00:00:00',1,'Khurshid has taken the fee from Hamza.','2023-12-04 12:13:31','2024-01-25 16:46:25',NULL),(75,'HTML CSS JS PHP','2023-12-04 00:00:00',61,2000,'paid','2024-01-18 00:00:00',1,'Fee collected by Ahamd Bilal','2023-12-04 12:13:31','2024-01-18 22:53:10',NULL),(76,'HTML CSS JS PHP','2023-12-04 12:13:31',62,2000,'pending',NULL,1,NULL,'2023-12-04 12:13:31','2024-02-24 17:02:50','2024-02-24 17:02:50'),(77,'batch1thml','2023-12-01 00:00:00',65,2000,'paid','2023-12-20 00:00:00',NULL,'Total 4k \r\n2k amir \r\n2k other','2023-12-13 20:47:00','2023-12-20 18:54:50',NULL),(78,'HTML CSS JS PHP','2024-01-03 00:00:00',28,2000,'paid','2024-01-20 00:00:00',1,'sir waqar get the fees from muhammad khan','2024-01-03 20:18:22','2024-01-25 12:43:49',NULL),(79,'HTML CSS JS PHP','2024-01-03 20:18:22',30,2000,'pending',NULL,1,NULL,'2024-01-03 20:18:22','2024-01-03 20:25:57','2024-01-03 20:25:57'),(80,'HTML CSS JS PHP','2024-01-03 00:00:00',40,2000,'paid','2024-01-08 00:00:00',1,'Submitted by Sajid Bhai','2024-01-03 20:18:22','2024-01-09 22:27:57',NULL),(81,'HTML CSS JS PHP','2024-01-03 00:00:00',41,2000,'paid',NULL,1,'Paid to Khursheed','2024-01-03 20:18:22','2024-02-02 15:05:17',NULL),(82,'HTML CSS JS PHP','2024-01-03 20:18:22',43,2000,'pending',NULL,1,NULL,'2024-01-03 20:18:22','2024-01-03 20:25:57','2024-01-03 20:25:57'),(83,'HTML CSS JS PHP','2024-01-03 20:18:22',45,2000,'pending',NULL,1,NULL,'2024-01-03 20:18:22','2024-06-09 09:05:07','2024-06-09 09:05:07'),(84,'HTML CSS JS PHP','2024-01-03 20:18:22',48,2000,'pending',NULL,1,NULL,'2024-01-03 20:18:22','2024-01-03 20:25:57','2024-01-03 20:25:57'),(85,'HTML CSS JS PHP','2024-01-03 00:00:00',49,2000,'paid','2024-03-04 00:00:00',1,'Paid to Khurshid Bilal','2024-01-03 20:18:22','2024-03-04 15:30:24',NULL),(86,'HTML CSS JS PHP','2024-01-03 20:18:22',50,2000,'pending',NULL,1,NULL,'2024-01-03 20:18:22','2024-06-09 09:05:12','2024-06-09 09:05:12'),(87,'HTML CSS JS PHP','2024-01-03 20:18:22',51,2000,'pending',NULL,1,NULL,'2024-01-03 20:18:22','2024-02-24 17:00:46','2024-02-24 17:00:46'),(88,'HTML CSS JS PHP','2024-01-03 20:18:22',52,2000,'pending',NULL,1,NULL,'2024-01-03 20:18:22','2024-02-24 17:01:03','2024-02-24 17:01:03'),(89,'HTML CSS JS PHP','2024-01-03 00:00:00',55,2000,'paid','2024-01-18 00:00:00',1,'Ahmed Bilal get fees from Hammad','2024-01-03 20:18:22','2024-01-25 12:48:33',NULL),(90,'HTML CSS JS PHP','2024-01-03 00:00:00',56,2000,'paid','2024-02-06 00:00:00',1,'Paid to khursheed','2024-01-03 20:18:22','2024-02-06 16:42:52',NULL),(91,'HTML CSS JS PHP','2024-01-03 00:00:00',57,2000,'paid','2024-02-06 00:00:00',1,'Paid to Khursheed','2024-01-03 20:18:22','2024-02-06 12:58:29',NULL),(92,'HTML CSS JS PHP','2024-01-03 20:18:22',58,2000,'pending',NULL,1,NULL,'2024-01-03 20:18:22','2024-01-03 20:22:23','2024-01-03 20:22:23'),(93,'HTML CSS JS PHP','2024-01-03 20:18:22',59,2000,'pending',NULL,1,NULL,'2024-01-03 20:18:22','2024-01-03 20:22:40','2024-01-03 20:22:40'),(94,'HTML CSS JS PHP','2024-01-03 00:00:00',60,2000,'paid',NULL,1,'Paid to khursheed','2024-01-03 20:18:22','2024-02-02 15:07:59',NULL),(95,'HTML CSS JS PHP','2024-01-03 00:00:00',61,2000,'paid','2024-01-18 00:00:00',1,'Ahmed Bilal get fees from Shahbaz','2024-01-03 20:18:22','2024-01-25 12:49:44',NULL),(96,'HTML CSS JS PHP','2024-01-03 20:18:22',62,2000,'pending',NULL,1,NULL,'2024-01-03 20:18:22','2024-02-24 17:01:23','2024-02-24 17:01:23'),(97,'HTML CSS JS PHP','2024-01-03 00:00:00',65,2000,'paid','2024-01-15 00:00:00',1,'Cash payment','2024-01-03 20:18:22','2024-01-15 19:12:13',NULL),(98,'HTML CSS JS PHP','2024-02-15 10:00:01',28,2000,'pending',NULL,1,NULL,'2024-02-15 10:00:01','2024-06-09 09:05:07','2024-06-09 09:05:07'),(99,'HTML CSS JS PHP','2024-02-15 00:00:00',40,2000,'paid','2024-02-14 00:00:00',1,'Paid to khursheed','2024-02-15 10:00:01','2024-02-15 12:11:01',NULL),(100,'HTML CSS JS PHP','2024-02-15 00:00:00',41,2000,'paid','2024-03-04 00:00:00',1,'Paid to khurshid Bilal','2024-02-15 10:00:01','2024-03-06 19:46:07',NULL),(101,'HTML CSS JS PHP','2024-02-15 10:00:01',45,2000,'pending',NULL,1,NULL,'2024-02-15 10:00:01','2024-06-09 09:05:07','2024-06-09 09:05:07'),(102,'HTML CSS JS PHP','2024-02-15 00:00:00',49,2000,'paid','2024-05-08 00:00:00',1,'paid to waqar sir','2024-02-15 10:00:01','2024-05-08 08:39:32',NULL),(103,'HTML CSS JS PHP','2024-02-15 10:00:01',50,2000,'pending',NULL,1,NULL,'2024-02-15 10:00:01','2024-06-09 09:05:07','2024-06-09 09:05:07'),(104,'HTML CSS JS PHP','2024-02-15 10:00:01',51,2000,'pending',NULL,1,NULL,'2024-02-15 10:00:01','2024-02-24 16:56:58','2024-02-24 16:56:58'),(105,'HTML CSS JS PHP','2024-02-15 10:00:01',52,2000,'pending',NULL,1,NULL,'2024-02-15 10:00:01','2024-02-24 16:57:11','2024-02-24 16:57:11'),(106,'HTML CSS JS PHP','2024-02-15 00:00:00',55,2000,'paid','2024-05-03 00:00:00',1,'Paid to Waqar Husaain','2024-02-15 10:00:01','2024-05-03 15:41:03',NULL),(107,'HTML CSS JS PHP','2024-02-15 10:00:01',56,2000,'pending',NULL,1,NULL,'2024-02-15 10:00:01','2024-06-09 09:05:07','2024-06-09 09:05:07'),(108,'HTML CSS JS PHP','2024-02-15 10:00:01',57,2000,'pending',NULL,1,NULL,'2024-02-15 10:00:01','2024-06-09 09:05:07','2024-06-09 09:05:07'),(109,'HTML CSS JS PHP','2024-02-15 00:00:00',60,2000,'paid','2024-03-05 00:00:00',1,'Paid to khurshid','2024-02-15 10:00:01','2024-03-05 16:52:20',NULL),(110,'HTML CSS JS PHP','2024-02-15 00:00:00',61,2000,'paid','2024-03-08 00:00:00',1,'Paid to Khurshid','2024-02-15 10:00:01','2024-03-08 16:08:01',NULL),(111,'HTML CSS JS PHP','2024-02-15 10:00:01',62,2000,'pending',NULL,1,NULL,'2024-02-15 10:00:01','2024-02-24 16:57:29','2024-02-24 16:57:29'),(112,'HTML CSS JS PHP','2024-02-15 00:00:00',65,2000,'paid','2024-03-15 00:00:00',1,'Paid to khurshid','2024-02-15 10:00:01','2024-03-16 11:30:30',NULL),(113,'HTML CSS JS PHP','2024-03-13 11:22:37',28,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:04:59','2024-06-09 09:04:59'),(114,'HTML CSS JS PHP','2024-03-13 11:22:37',40,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:04:59','2024-06-09 09:04:59'),(115,'HTML CSS JS PHP','2024-03-13 00:00:00',41,2000,'paid','2024-03-05 00:00:00',1,NULL,'2024-03-13 11:22:37','2024-03-15 15:23:57',NULL),(116,'HTML CSS JS PHP','2024-03-13 11:22:37',45,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:04:59','2024-06-09 09:04:59'),(117,'HTML CSS JS PHP','2024-03-13 00:00:00',49,2000,'paid','2024-05-08 00:00:00',1,'Paid to waqar sir','2024-03-13 11:22:37','2024-05-08 08:39:01',NULL),(118,'HTML CSS JS PHP','2024-03-13 11:22:37',50,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:04:59','2024-06-09 09:04:59'),(119,'HTML CSS JS PHP','2024-03-13 11:22:37',55,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:04:59','2024-06-09 09:04:59'),(120,'HTML CSS JS PHP','2024-03-13 11:22:37',56,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:04:59','2024-06-09 09:04:59'),(121,'HTML CSS JS PHP','2024-03-13 11:22:37',57,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:04:59','2024-06-09 09:04:59'),(122,'HTML CSS JS PHP','2024-03-13 00:00:00',60,2000,'paid','2024-03-05 00:00:00',1,NULL,'2024-03-13 11:22:37','2024-03-15 15:24:30',NULL),(123,'HTML CSS JS PHP','2024-03-13 00:00:00',61,2000,'paid','2024-05-02 00:00:00',1,'Paid to waqar sir','2024-03-13 11:22:37','2024-05-03 10:50:30',NULL),(124,'HTML CSS JS PHP','2024-03-13 00:00:00',65,2000,'paid','2024-06-05 00:00:00',1,'Paid to ahmed bilal','2024-03-13 11:22:37','2024-06-05 17:12:49',NULL),(125,'HTML CSS JS PHP','2024-03-13 00:00:00',70,2000,'paid','2024-06-05 00:00:00',1,'paid to ayub khokhar','2024-03-13 11:22:37','2024-05-06 17:13:24',NULL),(126,'HTML CSS JS PHP','2024-03-13 11:22:37',71,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:04:59','2024-06-09 09:04:59'),(127,'HTML CSS JS PHP','2024-03-13 11:22:37',72,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:04:59','2024-06-09 09:04:59'),(128,'HTML CSS JS PHP','2024-03-13 11:22:37',73,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:04:59','2024-06-09 09:04:59'),(129,'HTML CSS JS PHP','2024-03-13 11:22:37',74,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:05:07','2024-06-09 09:05:07'),(130,'HTML CSS JS PHP','2024-03-13 11:22:37',75,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:05:07','2024-06-09 09:05:07'),(131,'HTML CSS JS PHP','2024-03-13 11:22:37',76,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:05:07','2024-06-09 09:05:07'),(132,'HTML CSS JS PHP','2024-03-13 11:22:37',77,2000,'pending',NULL,1,NULL,'2024-03-13 11:22:37','2024-06-09 09:05:07','2024-06-09 09:05:07');
/*!40000 ALTER TABLE `student_fees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testings`
--

DROP TABLE IF EXISTS `testings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testings`
--

LOCK TABLES `testings` WRITE;
/*!40000 ALTER TABLE `testings` DISABLE KEYS */;
INSERT INTO `testings` VALUES (1,'Stripe wearewebpenter@gmail.com','stripe login: wearewebpenter@gmail.com\r\npassword: iampak@786\r\n \r\nTest mode\r\nPublishable key: pk_test_51JEVNVLPoQk2ZdymTLYxPVsQ5NLFjmaUzAVmbG7DRIKKGOXjw3kIQZTzQoG8760JIs2Xli7LLCbOfsBjUU9Omw3d00AeooUJZb\r\nSecret key : sk_test_51JEVNVLPoQk2ZdymuvBXRJPvaCqDSjNJC8DneLwlrCCJ47X9IQYYG9SMgyv9QWNF8G5EVFVyhZhnlZp6GU5UtXRx000exDgQWf\r\nwebhook that was for extraspace: whsec_zd8thgnUpZNb7XqKtjnHcDy44T7uejiG\r\nconnect account: ca_LCSmCpZ4cSkTw7fHTt7it0ZRjpnlYIFh','2023-07-09 11:41:00','2023-08-26 12:23:31'),(2,'Mailtrap login','Use these settings to send messages directly from your email client or mail transfer agent.\r\n\r\n**MailTrap Login**\r\nwearewebpenter@gmail.com\r\niampak@786\r\n\r\n**Laravel Project Settings**\r\nMAIL_MAILER=smtp\r\nMAIL_HOST=sandbox.smtp.mailtrap.io\r\nMAIL_PORT=2525\r\nMAIL_USERNAME=b158ffb76c3ad4\r\nMAIL_PASSWORD=f73635166b078c\r\nMAIL_ENCRYPTION=tls\r\n\r\n\r\n**WordPress Settings**\r\nfunction mailtrap($phpmailer) {\r\n  $phpmailer->isSMTP();\r\n  $phpmailer->Host = \'sandbox.smtp.mailtrap.io\';\r\n  $phpmailer->SMTPAuth = true;\r\n  $phpmailer->Port = 2525;\r\n  $phpmailer->Username = \'b158ffb76c3ad4\';\r\n  $phpmailer->Password = \'f73635166b078c\';\r\n}\r\n\r\nadd_action(\'phpmailer_init\', \'mailtrap\');\r\n\r\n**CURL for testing**\r\ncurl \\\r\n--ssl-reqd \\\r\n--url \'smtp://sandbox.smtp.mailtrap.io:2525\' \\\r\n--user \'b158ffb76c3ad4:f73635166b078c\' \\\r\n--mail-from from@example.com \\\r\n--mail-rcpt to@example.com \\\r\n--upload-file - <<EOF\r\nFrom: Magic Elves <from@example.com>\r\nTo: Mailtrap Inbox <to@example.com>\r\nSubject: You are awesome!\r\nContent-Type: multipart/alternative; boundary=\"boundary-string\"\r\n\r\n--boundary-string\r\nContent-Type: text/plain; charset=\"utf-8\"\r\nContent-Transfer-Encoding: quoted-printable\r\nContent-Disposition: inline\r\n\r\nCongrats for sending test email with Mailtrap!\r\n\r\nIf you are viewing this email in your inbox =E2=80=93 the integration works.\r\nNow send your email using our SMTP server and integration of your choice!\r\n\r\nGood luck! Hope it works.\r\n\r\n--boundary-string\r\nContent-Type: text/html; charset=\"utf-8\"\r\nContent-Transfer-Encoding: quoted-printable\r\nContent-Disposition: inline\r\n\r\n<!doctype html>\r\n<html>\r\n  <head>\r\n    <meta http-equiv=3D\"Content-Type\" content=3D\"text/html; charset=3DUTF-8\">\r\n  </head>\r\n  <body style=3D\"font-family: sans-serif;\">\r\n    <div style=3D\"display: block; margin: auto; max-width: 600px;\" class=3D\"main\">\r\n      <h1 style=3D\"font-size: 18px; font-weight: bold; margin-top: 20px\">Congrats for sending test email with Mailtrap!</h1>\r\n      <p>If you are viewing this email in your inbox =E2=80=93 the integration works.</p>\r\n      <img alt=3D\"Inspect with Tabs\" src=3D\"https://assets-examples.mailtrap.io/integration-examples/welcome.png\" style=3D\"width: 100%;\">\r\n      <p>Now send your email using our SMTP server and integration of your choice!</p>\r\n      <p>Good luck! Hope it works.</p>\r\n    </div>\r\n    <!-- Example of invalid for email html/css, will be detected by Mailtrap: -->\r\n    <style>\r\n      .main { background-color: white; }\r\n      a:hover { border-left-width: 1em; min-height: 2em; }\r\n    </style>\r\n  </body>\r\n</html>\r\n\r\n--boundary-string--\r\nEOF','2023-12-31 10:38:00','2023-12-31 10:41:06'),(3,'Sites that provide disposable number for sms verification','*🌀Sites that provide disposable numbers for SMS Verification🌀*\r\n\r\n1) http://hs3x.com\r\n2) http://smsget.net\r\n3) https://sms-online.co\r\n4) https://catchsms.com\r\n5) http://sms-receive.net\r\n6) http://sms.sellaite.com\r\n7) http://receivefreesms.net\r\n8) https://receive-a-sms.com\r\n9) http://receivesmsonline.in\r\n10) http://receivefreesms.com\r\n10) https://thestarkarmyx.t.me\r\n11) http://receivesmsonline.me\r\n12) https://smsreceivefree.com\r\n13) https://smsreceiveonline.com\r\n14) https://receive-sms-online.com\r\n15) https://www.receivesmsonline.net\r\n16) https://www.temp-mails.com/number\r\n17) https://www.freeonlinephone.org\r\n18) https://getfreesmsnumber.com','2024-01-04 23:09:34','2024-01-04 23:09:34');
/*!40000 ALTER TABLE `testings` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=355 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translations`
--

LOCK TABLES `translations` WRITE;
/*!40000 ALTER TABLE `translations` DISABLE KEYS */;
INSERT INTO `translations` VALUES (1,'data_types','display_name_singular',5,'pt','Post','2022-01-24 00:22:06','2022-01-24 00:22:06'),(2,'data_types','display_name_singular',6,'pt','Página','2022-01-24 00:22:06','2022-01-24 00:22:06'),(3,'data_types','display_name_singular',1,'pt','Utilizador','2022-01-24 00:22:06','2022-01-24 00:22:06'),(4,'data_types','display_name_singular',4,'pt','Categoria','2022-01-24 00:22:06','2022-01-24 00:22:06'),(5,'data_types','display_name_singular',2,'pt','Menu','2022-01-24 00:22:06','2022-01-24 00:22:06'),(6,'data_types','display_name_singular',3,'pt','Função','2022-01-24 00:22:06','2022-01-24 00:22:06'),(7,'data_types','display_name_plural',5,'pt','Posts','2022-01-24 00:22:06','2022-01-24 00:22:06'),(8,'data_types','display_name_plural',6,'pt','Páginas','2022-01-24 00:22:06','2022-01-24 00:22:06'),(9,'data_types','display_name_plural',1,'pt','Utilizadores','2022-01-24 00:22:06','2022-01-24 00:22:06'),(10,'data_types','display_name_plural',4,'pt','Categorias','2022-01-24 00:22:06','2022-01-24 00:22:06'),(11,'data_types','display_name_plural',2,'pt','Menus','2022-01-24 00:22:06','2022-01-24 00:22:06'),(12,'data_types','display_name_plural',3,'pt','Funções','2022-01-24 00:22:06','2022-01-24 00:22:06'),(13,'categories','slug',1,'pt','categoria-1','2022-01-24 00:22:06','2022-01-24 00:22:06'),(14,'categories','name',1,'pt','Categoria 1','2022-01-24 00:22:06','2022-01-24 00:22:06'),(15,'categories','slug',2,'pt','categoria-2','2022-01-24 00:22:06','2022-01-24 00:22:06'),(16,'categories','name',2,'pt','Categoria 2','2022-01-24 00:22:06','2022-01-24 00:22:06'),(17,'pages','title',1,'pt','Olá Mundo','2022-01-24 00:22:06','2022-01-24 00:22:06'),(18,'pages','slug',1,'pt','ola-mundo','2022-01-24 00:22:06','2022-01-24 00:22:06'),(19,'pages','body',1,'pt','<p>Olá Mundo. Scallywag grog swab Cat o\'nine tails scuttle rigging hardtack cable nipper Yellow Jack. Handsomely spirits knave lad killick landlubber or just lubber deadlights chantey pinnace crack Jennys tea cup. Provost long clothes black spot Yellow Jack bilged on her anchor league lateen sail case shot lee tackle.</p>\r\n<p>Ballast spirits fluke topmast me quarterdeck schooner landlubber or just lubber gabion belaying pin. Pinnace stern galleon starboard warp carouser to go on account dance the hempen jig jolly boat measured fer yer chains. Man-of-war fire in the hole nipperkin handsomely doubloon barkadeer Brethren of the Coast gibbet driver squiffy.</p>','2022-01-24 00:22:06','2022-01-24 00:22:06'),(20,'menu_items','title',1,'pt','Painel de Controle','2022-01-24 00:22:06','2022-01-24 00:22:06'),(21,'menu_items','title',2,'pt','Media','2022-01-24 00:22:06','2022-01-24 00:22:06'),(22,'menu_items','title',12,'pt','Publicações','2022-01-24 00:22:06','2022-01-24 00:22:06'),(23,'menu_items','title',3,'pt','Utilizadores','2022-01-24 00:22:06','2022-01-24 00:22:06'),(24,'menu_items','title',11,'pt','Categorias','2022-01-24 00:22:06','2022-01-24 00:22:06'),(25,'menu_items','title',13,'pt','Páginas','2022-01-24 00:22:06','2022-01-24 00:22:06'),(26,'menu_items','title',4,'pt','Funções','2022-01-24 00:22:06','2022-01-24 00:22:06'),(27,'menu_items','title',5,'pt','Ferramentas','2022-01-24 00:22:06','2022-01-24 00:22:06'),(28,'menu_items','title',6,'pt','Menus','2022-01-24 00:22:06','2022-01-24 00:22:06'),(29,'menu_items','title',7,'pt','Base de dados','2022-01-24 00:22:06','2022-01-24 00:22:06'),(30,'menu_items','title',10,'pt','Configurações','2022-01-24 00:22:06','2022-01-24 00:22:06'),(31,'data_rows','display_name',66,'en','Id','2022-05-26 11:13:51','2022-05-26 11:13:51'),(32,'data_rows','display_name',67,'en','Project Id','2022-05-26 11:13:51','2022-05-26 11:13:51'),(33,'data_rows','display_name',94,'en','Developer Id','2022-05-26 11:13:51','2022-05-26 11:13:51'),(34,'data_rows','display_name',68,'en','Title','2022-05-26 11:13:51','2022-05-26 11:13:51'),(35,'data_rows','display_name',69,'en','Status','2022-05-26 11:13:51','2022-05-26 11:13:51'),(36,'data_rows','display_name',75,'en','Created At','2022-05-26 11:13:51','2022-05-26 11:13:51'),(37,'data_rows','display_name',76,'en','Updated At','2022-05-26 11:13:51','2022-05-26 11:13:51'),(38,'data_rows','display_name',77,'en','Deleted At','2022-05-26 11:13:51','2022-05-26 11:13:51'),(39,'data_rows','display_name',78,'en','Project','2022-05-26 11:13:51','2022-05-26 11:13:51'),(40,'data_rows','display_name',95,'en','Developer','2022-05-26 11:13:51','2022-05-26 11:13:51'),(41,'data_types','display_name_singular',8,'en','Project Target','2022-05-26 11:13:51','2022-05-26 11:13:51'),(42,'data_types','display_name_plural',8,'en','Project Targets','2022-05-26 11:13:51','2022-05-26 11:13:51'),(43,'data_rows','display_name',79,'en','Id','2022-05-26 11:21:52','2022-05-26 11:21:52'),(44,'data_rows','display_name',80,'en','Project Target Id','2022-05-26 11:21:52','2022-05-26 11:21:52'),(45,'data_rows','display_name',81,'en','Developer Id','2022-05-26 11:21:52','2022-05-26 11:21:52'),(46,'data_rows','display_name',87,'en','Description','2022-05-26 11:21:52','2022-05-26 11:21:52'),(47,'data_rows','display_name',131,'en','Date','2022-05-26 11:21:52','2022-05-26 11:21:52'),(48,'data_rows','display_name',133,'en','Hours','2022-05-26 11:21:52','2022-05-26 11:21:52'),(49,'data_rows','display_name',134,'en','Minutes','2022-05-26 11:21:52','2022-05-26 11:21:52'),(50,'data_rows','display_name',89,'en','Created At','2022-05-26 11:21:52','2022-05-26 11:21:52'),(51,'data_rows','display_name',90,'en','Updated At','2022-05-26 11:21:52','2022-05-26 11:21:52'),(52,'data_rows','display_name',91,'en','Deleted At','2022-05-26 11:21:52','2022-05-26 11:21:52'),(53,'data_rows','display_name',92,'en','Project Target','2022-05-26 11:21:52','2022-05-26 11:21:52'),(54,'data_rows','display_name',93,'en','Developer','2022-05-26 11:21:52','2022-05-26 11:21:52'),(55,'data_types','display_name_singular',9,'en','Project Target Task','2022-05-26 11:21:52','2022-05-26 11:21:52'),(56,'data_types','display_name_plural',9,'en','Project Target Tasks','2022-05-26 11:21:52','2022-05-26 11:21:52'),(57,'data_rows','display_name',105,'en','Id','2022-05-30 00:52:51','2022-05-30 00:52:51'),(58,'data_rows','display_name',106,'en','Project Id','2022-05-30 00:52:51','2022-05-30 00:52:51'),(59,'data_rows','display_name',120,'en','Client Id','2022-05-30 00:52:51','2022-05-30 00:52:51'),(60,'data_rows','display_name',109,'en','CC','2022-05-30 00:52:51','2022-05-30 00:52:51'),(61,'data_rows','display_name',110,'en','BCC','2022-05-30 00:52:51','2022-05-30 00:52:51'),(62,'data_rows','display_name',111,'en','Email Subject','2022-05-30 00:52:51','2022-05-30 00:52:51'),(63,'data_rows','display_name',137,'en','Project Target Status','2022-05-30 00:52:51','2022-05-30 00:52:51'),(64,'data_rows','display_name',138,'en','Project Task Hours','2022-05-30 00:52:51','2022-05-30 00:52:51'),(65,'data_rows','display_name',112,'en','Email Greetings','2022-05-30 00:52:51','2022-05-30 00:52:51'),(66,'data_rows','display_name',113,'en','Signature','2022-05-30 00:52:51','2022-05-30 00:52:51'),(67,'data_rows','display_name',114,'en','Developer Id','2022-05-30 00:52:51','2022-05-30 00:52:51'),(68,'data_rows','display_name',115,'en','Created At','2022-05-30 00:52:51','2022-05-30 00:52:51'),(69,'data_rows','display_name',116,'en','Updated At','2022-05-30 00:52:51','2022-05-30 00:52:51'),(70,'data_rows','display_name',117,'en','Project','2022-05-30 00:52:51','2022-05-30 00:52:51'),(71,'data_rows','display_name',118,'en','From','2022-05-30 00:52:51','2022-05-30 00:52:51'),(72,'data_rows','display_name',119,'en','To','2022-05-30 00:52:51','2022-05-30 00:52:51'),(73,'data_types','display_name_singular',12,'en','Eod Configuration','2022-05-30 00:52:51','2022-05-30 00:52:51'),(74,'data_types','display_name_plural',12,'en','Eod Configurations','2022-05-30 00:52:51','2022-05-30 00:52:51'),(75,'data_rows','display_name',96,'en','Id','2022-06-15 09:11:26','2022-06-15 09:11:26'),(76,'data_rows','display_name',104,'en','Email','2022-06-15 09:11:26','2022-06-15 09:11:26'),(77,'data_rows','display_name',99,'en','Project Id','2022-06-15 09:11:26','2022-06-15 09:11:26'),(78,'data_rows','display_name',100,'en','Developer Id','2022-06-15 09:11:26','2022-06-15 09:11:26'),(79,'data_rows','display_name',101,'en','Created At','2022-06-15 09:11:26','2022-06-15 09:11:26'),(80,'data_rows','display_name',102,'en','Updated At','2022-06-15 09:11:26','2022-06-15 09:11:26'),(81,'data_rows','display_name',103,'en','Project','2022-06-15 09:11:26','2022-06-15 09:11:26'),(82,'data_types','display_name_singular',10,'en','Eod','2022-06-15 09:11:26','2022-06-15 09:11:26'),(83,'data_types','display_name_plural',10,'en','Eods','2022-06-15 09:11:26','2022-06-15 09:11:26'),(84,'data_rows','display_name',139,'en','users','2022-06-15 09:16:17','2022-06-15 09:16:17'),(85,'data_rows','display_name',140,'en','Id','2022-09-14 10:40:41','2022-09-14 10:40:41'),(86,'data_rows','display_name',141,'en','User Id','2022-09-14 10:40:41','2022-09-14 10:40:41'),(87,'data_rows','display_name',142,'en','Phone','2022-09-14 10:40:41','2022-09-14 10:40:41'),(88,'data_rows','display_name',143,'en','Website Url','2022-09-14 10:40:41','2022-09-14 10:40:41'),(89,'data_rows','display_name',144,'en','Website Email Or Username','2022-09-14 10:40:41','2022-09-14 10:40:41'),(90,'data_rows','display_name',145,'en','Website Login Password','2022-09-14 10:40:41','2022-09-14 10:40:41'),(91,'data_rows','display_name',146,'en','Server Login Information','2022-09-14 10:40:41','2022-09-14 10:40:41'),(92,'data_rows','display_name',147,'en','Server Login Files','2022-09-14 10:40:41','2022-09-14 10:40:41'),(93,'data_rows','display_name',148,'en','Created At','2022-09-14 10:40:41','2022-09-14 10:40:41'),(94,'data_rows','display_name',149,'en','Updated At','2022-09-14 10:40:41','2022-09-14 10:40:41'),(95,'data_rows','display_name',150,'en','users','2022-09-14 10:40:41','2022-09-14 10:40:41'),(96,'data_types','display_name_singular',14,'en','Client Information','2022-09-14 10:40:41','2022-09-14 10:40:41'),(97,'data_types','display_name_plural',14,'en','Client Informations','2022-09-14 10:40:41','2022-09-14 10:40:41'),(98,'menu_items','title',20,'en','Client Informations','2022-09-14 10:47:58','2022-09-14 10:47:58'),(99,'data_rows','display_name',151,'en','Id','2022-09-15 01:06:18','2022-09-15 01:06:18'),(100,'data_rows','display_name',152,'en','User Id','2022-09-15 01:06:18','2022-09-15 01:06:18'),(101,'data_rows','display_name',153,'en','Phone','2022-09-15 01:06:18','2022-09-15 01:06:18'),(102,'data_rows','display_name',154,'en','Git Username','2022-09-15 01:06:18','2022-09-15 01:06:18'),(103,'data_rows','display_name',155,'en','Notes','2022-09-15 01:06:18','2022-09-15 01:06:18'),(104,'data_rows','display_name',156,'en','Resume','2022-09-15 01:06:18','2022-09-15 01:06:18'),(105,'data_rows','display_name',157,'en','Created At','2022-09-15 01:06:18','2022-09-15 01:06:18'),(106,'data_rows','display_name',158,'en','Updated At','2022-09-15 01:06:18','2022-09-15 01:06:18'),(107,'data_rows','display_name',159,'en','users','2022-09-15 01:06:18','2022-09-15 01:06:18'),(108,'data_types','display_name_singular',15,'en','Developer Information','2022-09-15 01:06:18','2022-09-15 01:06:18'),(109,'data_types','display_name_plural',15,'en','Developer Informations','2022-09-15 01:06:18','2022-09-15 01:06:18'),(110,'data_rows','display_name',160,'en','Id','2022-10-17 11:01:14','2022-10-17 11:01:14'),(111,'data_rows','display_name',161,'en','Project Id','2022-10-17 11:01:14','2022-10-17 11:01:14'),(112,'data_rows','display_name',162,'en','Developer Id','2022-10-17 11:01:14','2022-10-17 11:01:14'),(113,'data_rows','display_name',163,'en','Title','2022-10-17 11:01:14','2022-10-17 11:01:14'),(114,'data_rows','display_name',164,'en','Description','2022-10-17 11:01:14','2022-10-17 11:01:14'),(115,'data_rows','display_name',165,'en','Total Amount','2022-10-17 11:01:14','2022-10-17 11:01:14'),(116,'data_rows','display_name',166,'en','Created At','2022-10-17 11:01:14','2022-10-17 11:01:14'),(117,'data_rows','display_name',167,'en','Updated At','2022-10-17 11:01:14','2022-10-17 11:01:14'),(118,'data_rows','display_name',168,'en','projects','2022-10-17 11:01:14','2022-10-17 11:01:14'),(119,'data_rows','display_name',169,'en','users','2022-10-17 11:01:14','2022-10-17 11:01:14'),(120,'data_types','display_name_singular',16,'en','Project Milestone','2022-10-17 11:01:14','2022-10-17 11:01:14'),(121,'data_types','display_name_plural',16,'en','Project Milestones','2022-10-17 11:01:14','2022-10-17 11:01:14'),(122,'data_rows','display_name',1,'en','ID','2023-02-13 12:03:08','2023-02-13 12:03:08'),(123,'data_rows','display_name',21,'en','Role','2023-02-13 12:03:08','2023-02-13 12:03:08'),(124,'data_rows','display_name',2,'en','Name','2023-02-13 12:03:08','2023-02-13 12:03:08'),(125,'data_rows','display_name',3,'en','Email','2023-02-13 12:03:08','2023-02-13 12:03:08'),(126,'data_rows','display_name',8,'en','Avatar','2023-02-13 12:03:08','2023-02-13 12:03:08'),(127,'data_rows','display_name',130,'en','Email Verified At','2023-02-13 12:03:08','2023-02-13 12:03:08'),(128,'data_rows','display_name',4,'en','Password','2023-02-13 12:03:08','2023-02-13 12:03:08'),(129,'data_rows','display_name',5,'en','Remember Token','2023-02-13 12:03:08','2023-02-13 12:03:08'),(130,'data_rows','display_name',11,'en','Settings','2023-02-13 12:03:08','2023-02-13 12:03:08'),(131,'data_rows','display_name',6,'en','Created At','2023-02-13 12:03:08','2023-02-13 12:03:08'),(132,'data_rows','display_name',7,'en','Updated At','2023-02-13 12:03:08','2023-02-13 12:03:08'),(133,'data_rows','display_name',9,'en','Role','2023-02-13 12:03:08','2023-02-13 12:03:08'),(134,'data_rows','display_name',10,'en','voyager::seeders.data_rows.roles','2023-02-13 12:03:08','2023-02-13 12:03:08'),(135,'data_types','display_name_singular',1,'en','User','2023-02-13 12:03:08','2023-02-13 12:03:08'),(136,'data_types','display_name_plural',1,'en','Users','2023-02-13 12:03:08','2023-02-13 12:03:08'),(137,'data_rows','display_name',170,'en','Client Notes','2023-02-13 12:04:46','2023-02-13 12:04:46'),(138,'data_rows','display_name',171,'en','Id','2023-03-01 22:14:33','2023-03-01 22:14:33'),(139,'data_rows','display_name',172,'en','Developer Id','2023-03-01 22:14:33','2023-03-01 22:14:33'),(140,'data_rows','display_name',173,'en','Project Id','2023-03-01 22:14:33','2023-03-01 22:14:33'),(141,'data_rows','display_name',174,'en','Project Target Id','2023-03-01 22:14:33','2023-03-01 22:14:33'),(142,'data_rows','display_name',175,'en','Total Earning','2023-03-01 22:14:33','2023-03-01 22:14:33'),(143,'data_rows','display_name',176,'en','Payable To Developer','2023-03-01 22:14:33','2023-03-01 22:14:33'),(144,'data_rows','display_name',177,'en','Currency Current Rate','2023-03-01 22:14:33','2023-03-01 22:14:33'),(145,'data_rows','display_name',178,'en','Fee','2023-03-01 22:14:33','2023-03-01 22:14:33'),(146,'data_rows','display_name',179,'en','Status','2023-03-01 22:14:33','2023-03-01 22:14:33'),(147,'data_rows','display_name',180,'en','Notes','2023-03-01 22:14:33','2023-03-01 22:14:33'),(148,'data_rows','display_name',181,'en','Created At','2023-03-01 22:14:33','2023-03-01 22:14:33'),(149,'data_rows','display_name',182,'en','Updated At','2023-03-01 22:14:33','2023-03-01 22:14:33'),(150,'data_rows','display_name',183,'en','Deleted At','2023-03-01 22:14:33','2023-03-01 22:14:33'),(151,'data_types','display_name_singular',17,'en','User Payment','2023-03-01 22:14:33','2023-03-01 22:14:33'),(152,'data_types','display_name_plural',17,'en','User Payments','2023-03-01 22:14:33','2023-03-01 22:14:33'),(153,'data_rows','display_name',184,'en','projects','2023-03-01 22:16:24','2023-03-01 22:16:24'),(154,'data_rows','display_name',185,'en','project_targets','2023-03-01 22:16:24','2023-03-01 22:16:24'),(155,'data_rows','display_name',56,'en','Id','2023-03-01 22:49:40','2023-03-01 22:49:40'),(156,'data_rows','display_name',57,'en','Name','2023-03-01 22:49:40','2023-03-01 22:49:40'),(157,'data_rows','display_name',58,'en','Client','2023-03-01 22:49:40','2023-03-01 22:49:40'),(158,'data_rows','display_name',59,'en','Payment Mode','2023-03-01 22:49:40','2023-03-01 22:49:40'),(159,'data_rows','display_name',60,'en','Start Date','2023-03-01 22:49:40','2023-03-01 22:49:40'),(160,'data_rows','display_name',61,'en','Expected Delivery Date','2023-03-01 22:49:40','2023-03-01 22:49:40'),(161,'data_rows','display_name',62,'en','Created At','2023-03-01 22:49:40','2023-03-01 22:49:40'),(162,'data_rows','display_name',63,'en','Updated At','2023-03-01 22:49:40','2023-03-01 22:49:40'),(163,'data_rows','display_name',64,'en','Deleted At','2023-03-01 22:49:40','2023-03-01 22:49:40'),(164,'data_rows','display_name',65,'en','Client','2023-03-01 22:49:40','2023-03-01 22:49:40'),(165,'data_types','display_name_singular',7,'en','Project','2023-03-01 22:49:40','2023-03-01 22:49:40'),(166,'data_types','display_name_plural',7,'en','Projects','2023-03-01 22:49:40','2023-03-01 22:49:40'),(167,'data_rows','display_name',186,'en','Status','2023-03-01 22:50:31','2023-03-01 22:50:31'),(168,'data_rows','display_name',187,'en','Dev Earning','2023-03-05 23:58:48','2023-03-05 23:58:48'),(169,'data_rows','display_name',188,'en','Payable','2023-03-05 23:58:48','2023-03-05 23:58:48'),(170,'data_rows','display_name',189,'en','Paid','2023-03-05 23:58:48','2023-03-05 23:58:48'),(171,'categories','name',1,'en','Category 1','2023-04-09 23:25:00','2023-04-09 23:25:00'),(172,'categories','slug',1,'en','category-1','2023-04-09 23:25:00','2023-04-09 23:25:00'),(173,'categories','name',2,'en','Category 2','2023-04-09 23:25:26','2023-04-09 23:25:26'),(174,'categories','slug',2,'en','category-2','2023-04-09 23:25:26','2023-04-09 23:25:26'),(175,'data_rows','display_name',190,'en','Enable Slack','2023-04-27 08:31:20','2023-04-27 08:31:20'),(176,'data_rows','display_name',191,'en','Slack Webhook Url','2023-04-27 08:31:20','2023-04-27 08:31:20'),(177,'data_rows','display_name',192,'en','users','2023-04-29 16:25:03','2023-04-29 16:25:03'),(178,'data_rows','display_name',193,'en','Plan For Tomorrow','2023-05-03 13:01:35','2023-05-03 13:01:35'),(179,'data_rows','display_name',194,'en','Id','2023-05-25 09:50:43','2023-05-25 09:50:43'),(180,'data_rows','display_name',195,'en','Amount','2023-05-25 09:50:43','2023-05-25 09:50:43'),(181,'data_rows','display_name',196,'en','Amount In','2023-05-25 09:50:43','2023-05-25 09:50:43'),(182,'data_rows','display_name',197,'en','Source','2023-05-25 09:50:43','2023-05-25 09:50:43'),(183,'data_rows','display_name',198,'en','Note','2023-05-25 09:50:43','2023-05-25 09:50:43'),(184,'data_rows','display_name',199,'en','Created At','2023-05-25 09:50:43','2023-05-25 09:50:43'),(185,'data_rows','display_name',200,'en','Updated At','2023-05-25 09:50:43','2023-05-25 09:50:43'),(186,'data_rows','display_name',201,'en','Deleted At','2023-05-25 09:50:43','2023-05-25 09:50:43'),(187,'data_types','display_name_singular',18,'en','Income','2023-05-25 09:50:43','2023-05-25 09:50:43'),(188,'data_types','display_name_plural',18,'en','Incomes','2023-05-25 09:50:43','2023-05-25 09:50:43'),(189,'data_rows','display_name',202,'en','users','2023-05-26 08:29:21','2023-05-26 08:29:21'),(190,'data_rows','display_name',203,'en','User Id','2023-05-26 08:35:27','2023-05-26 08:35:27'),(191,'data_rows','display_name',204,'en','Id','2023-05-26 08:57:18','2023-05-26 08:57:18'),(192,'data_rows','display_name',205,'en','Amount','2023-05-26 08:57:18','2023-05-26 08:57:18'),(193,'data_rows','display_name',206,'en','Amount In','2023-05-26 08:57:18','2023-05-26 08:57:18'),(194,'data_rows','display_name',207,'en','Purpose','2023-05-26 08:57:18','2023-05-26 08:57:18'),(195,'data_rows','display_name',208,'en','Note','2023-05-26 08:57:18','2023-05-26 08:57:18'),(196,'data_rows','display_name',209,'en','Created At','2023-05-26 08:57:18','2023-05-26 08:57:18'),(197,'data_rows','display_name',210,'en','Updated At','2023-05-26 08:57:18','2023-05-26 08:57:18'),(198,'data_rows','display_name',211,'en','Deleted At','2023-05-26 08:57:18','2023-05-26 08:57:18'),(199,'data_rows','display_name',212,'en','users','2023-05-26 08:57:18','2023-05-26 08:57:18'),(200,'data_types','display_name_singular',19,'en','Expense','2023-05-26 08:57:18','2023-05-26 08:57:18'),(201,'data_types','display_name_plural',19,'en','Expenses','2023-05-26 08:57:18','2023-05-26 08:57:18'),(202,'data_rows','display_name',213,'en','User Id','2023-05-26 08:59:55','2023-05-26 08:59:55'),(203,'data_rows','display_name',214,'en','Attachment','2023-05-26 09:33:15','2023-05-26 09:33:15'),(204,'data_rows','display_name',217,'en','Attachments','2023-05-26 09:36:30','2023-05-26 09:36:30'),(205,'data_rows','display_name',216,'en','Attachments','2023-05-26 09:36:53','2023-05-26 09:36:53'),(206,'data_rows','display_name',218,'en','Id','2023-06-04 20:38:01','2023-06-04 20:38:01'),(207,'data_rows','display_name',219,'en','Name','2023-06-04 20:38:01','2023-06-04 20:38:01'),(208,'data_rows','display_name',221,'en','Description','2023-06-04 20:38:01','2023-06-04 20:38:01'),(209,'data_rows','display_name',222,'en','Created At','2023-06-04 20:38:01','2023-06-04 20:38:01'),(210,'data_rows','display_name',223,'en','Updated At','2023-06-04 20:38:01','2023-06-04 20:38:01'),(211,'data_rows','display_name',224,'en','categories','2023-06-04 20:38:01','2023-06-04 20:38:01'),(212,'data_types','display_name_singular',20,'en','Course','2023-06-04 20:38:01','2023-06-04 20:38:01'),(213,'data_types','display_name_plural',20,'en','Courses','2023-06-04 20:38:01','2023-06-04 20:38:01'),(214,'data_rows','display_name',225,'en','Id','2023-06-23 08:03:21','2023-06-23 08:03:21'),(215,'data_rows','display_name',226,'en','Category Id','2023-06-23 08:03:21','2023-06-23 08:03:21'),(216,'data_rows','display_name',227,'en','Description','2023-06-23 08:03:21','2023-06-23 08:03:21'),(217,'data_rows','display_name',228,'en','Created At','2023-06-23 08:03:21','2023-06-23 08:03:21'),(218,'data_rows','display_name',229,'en','Updated At','2023-06-23 08:03:21','2023-06-23 08:03:21'),(219,'data_rows','display_name',230,'en','categories','2023-06-23 08:03:21','2023-06-23 08:03:21'),(220,'data_types','display_name_singular',22,'en','Portfolio','2023-06-23 08:03:21','2023-06-23 08:03:21'),(221,'data_types','display_name_plural',22,'en','Portfolios','2023-06-23 08:03:21','2023-06-23 08:03:21'),(222,'data_rows','display_name',231,'en','users','2023-06-25 07:05:36','2023-06-25 07:05:36'),(223,'data_rows','display_name',232,'en','Developer Id','2023-06-25 07:14:16','2023-06-25 07:14:16'),(224,'data_rows','display_name',233,'en','Id','2023-07-01 16:45:55','2023-07-01 16:45:55'),(225,'data_rows','display_name',234,'en','Student Id','2023-07-01 16:45:55','2023-07-01 16:45:55'),(226,'data_rows','display_name',235,'en','Amount','2023-07-01 16:45:55','2023-07-01 16:45:55'),(227,'data_rows','display_name',236,'en','Status','2023-07-01 16:45:55','2023-07-01 16:45:55'),(228,'data_rows','display_name',237,'en','Receiver Id','2023-07-01 16:45:55','2023-07-01 16:45:55'),(229,'data_rows','display_name',238,'en','Notes','2023-07-01 16:45:55','2023-07-01 16:45:55'),(230,'data_rows','display_name',239,'en','Created At','2023-07-01 16:45:55','2023-07-01 16:45:55'),(231,'data_rows','display_name',240,'en','Updated At','2023-07-01 16:45:55','2023-07-01 16:45:55'),(232,'data_rows','display_name',241,'en','Deleted At','2023-07-01 16:45:55','2023-07-01 16:45:55'),(233,'data_types','display_name_singular',24,'en','Student Fee','2023-07-01 16:45:55','2023-07-01 16:45:55'),(234,'data_types','display_name_plural',24,'en','Student Fees','2023-07-01 16:45:55','2023-07-01 16:45:55'),(235,'data_rows','display_name',242,'en','users','2023-07-01 16:52:02','2023-07-01 16:52:02'),(236,'data_rows','display_name',243,'en','users','2023-07-01 16:52:02','2023-07-01 16:52:02'),(237,'data_rows','display_name',249,'en','Attachments','2023-08-08 10:26:12','2023-08-08 10:26:12'),(238,'data_rows','display_name',254,'en','Show To Dev','2023-08-15 10:09:31','2023-08-15 10:09:31'),(239,'data_rows','display_name',252,'en','Income Id','2023-08-15 10:20:35','2023-08-15 10:20:35'),(240,'data_rows','display_name',255,'en','incomes','2023-08-15 10:20:35','2023-08-15 10:20:35'),(241,'data_rows','display_name',256,'en','Id','2023-08-30 19:41:11','2023-08-30 19:41:11'),(242,'data_rows','display_name',257,'en','User Id','2023-08-30 19:41:11','2023-08-30 19:41:11'),(243,'data_rows','display_name',258,'en','Contract Detail','2023-08-30 19:41:11','2023-08-30 19:41:11'),(244,'data_rows','display_name',259,'en','Created At','2023-08-30 19:41:11','2023-08-30 19:41:11'),(245,'data_rows','display_name',260,'en','Updated At','2023-08-30 19:41:11','2023-08-30 19:41:11'),(246,'data_rows','display_name',261,'en','users','2023-08-30 19:41:11','2023-08-30 19:41:11'),(247,'data_types','display_name_singular',26,'en','Contract','2023-08-30 19:41:11','2023-08-30 19:41:11'),(248,'data_types','display_name_plural',26,'en','Contracts','2023-08-30 19:41:11','2023-08-30 19:41:11'),(249,'data_rows','display_name',250,'en','Batch','2023-08-30 19:56:00','2023-08-30 19:56:00'),(250,'data_rows','display_name',251,'en','Date','2023-08-30 19:56:00','2023-08-30 19:56:00'),(251,'data_rows','display_name',262,'en','Update By Command','2023-10-15 12:07:36','2023-10-15 12:07:36'),(252,'data_rows','display_name',263,'en','Client Source','2023-10-15 20:51:19','2023-10-15 20:51:19'),(253,'data_rows','display_name',264,'en','Paid Date','2023-11-04 19:36:14','2023-11-04 19:36:14'),(254,'data_rows','display_name',265,'en','Transaction Id','2023-11-18 13:30:30','2023-11-18 13:30:30'),(255,'data_rows','display_name',266,'en','Transaction Date','2023-11-18 13:30:30','2023-11-18 13:30:30'),(256,'data_rows','display_name',267,'en','incomes','2023-11-18 13:39:53','2023-11-18 13:39:53'),(257,'data_rows','display_name',268,'en','Income Id','2023-11-18 13:46:32','2023-11-18 13:46:32'),(258,'data_rows','display_name',269,'en','Id','2023-11-22 11:16:53','2023-11-22 11:16:53'),(259,'data_rows','display_name',270,'en','User Id','2023-11-22 11:16:53','2023-11-22 11:16:53'),(260,'data_rows','display_name',271,'en','Reason','2023-11-22 11:16:53','2023-11-22 11:16:53'),(261,'data_rows','display_name',272,'en','Date','2023-11-22 11:16:53','2023-11-22 11:16:53'),(262,'data_rows','display_name',273,'en','Note','2023-11-22 11:16:53','2023-11-22 11:16:53'),(263,'data_rows','display_name',274,'en','Created At','2023-11-22 11:16:53','2023-11-22 11:16:53'),(264,'data_rows','display_name',275,'en','Updated At','2023-11-22 11:16:53','2023-11-22 11:16:53'),(265,'data_rows','display_name',276,'en','Deleted At','2023-11-22 11:16:53','2023-11-22 11:16:53'),(266,'data_rows','display_name',277,'en','users','2023-11-22 11:16:53','2023-11-22 11:16:53'),(267,'data_types','display_name_singular',27,'en','Fine','2023-11-22 11:16:53','2023-11-22 11:16:53'),(268,'data_types','display_name_plural',27,'en','Fines','2023-11-22 11:16:53','2023-11-22 11:16:53'),(269,'data_rows','display_name',279,'en','No Fee','2023-12-10 17:24:22','2023-12-10 17:24:22'),(270,'data_rows','display_name',280,'en','Deleted At','2023-12-10 17:24:22','2023-12-10 17:24:22'),(271,'data_rows','display_name',281,'en','Percentage','2023-12-10 17:24:50','2023-12-10 17:24:50'),(272,'data_rows','display_name',289,'en','Active','2023-12-29 10:52:31','2023-12-29 10:52:31'),(273,'data_rows','display_name',244,'en','Id','2023-12-31 10:39:27','2023-12-31 10:39:27'),(274,'data_rows','display_name',245,'en','Title','2023-12-31 10:39:27','2023-12-31 10:39:27'),(275,'data_rows','display_name',246,'en','Description','2023-12-31 10:39:27','2023-12-31 10:39:27'),(276,'data_rows','display_name',247,'en','Created At','2023-12-31 10:39:27','2023-12-31 10:39:27'),(277,'data_rows','display_name',248,'en','Updated At','2023-12-31 10:39:27','2023-12-31 10:39:27'),(278,'data_types','display_name_singular',25,'en','Testing','2023-12-31 10:39:27','2023-12-31 10:39:27'),(279,'data_types','display_name_plural',25,'en','Testings','2023-12-31 10:39:27','2023-12-31 10:39:27'),(280,'data_rows','display_name',291,'en','categories','2024-01-07 10:51:00','2024-01-07 10:51:00'),(281,'data_rows','display_name',292,'en','Category Id','2024-01-07 10:56:13','2024-01-07 10:56:13'),(282,'data_rows','display_name',293,'en','Id','2024-02-04 20:45:17','2024-02-04 20:45:17'),(283,'data_rows','display_name',294,'en','Category Id','2024-02-04 20:45:17','2024-02-04 20:45:17'),(284,'data_rows','display_name',295,'en','Name','2024-02-04 20:45:17','2024-02-04 20:45:17'),(285,'data_rows','display_name',296,'en','Description','2024-02-04 20:45:17','2024-02-04 20:45:17'),(286,'data_rows','display_name',297,'en','Attachment','2024-02-04 20:45:17','2024-02-04 20:45:17'),(287,'data_rows','display_name',298,'en','Created At','2024-02-04 20:45:17','2024-02-04 20:45:17'),(288,'data_rows','display_name',299,'en','Updated At','2024-02-04 20:45:17','2024-02-04 20:45:17'),(289,'data_rows','display_name',300,'en','Deleted At','2024-02-04 20:45:17','2024-02-04 20:45:17'),(290,'data_rows','display_name',301,'en','categories','2024-02-04 20:45:17','2024-02-04 20:45:17'),(291,'data_types','display_name_singular',31,'en','Short List Candidate','2024-02-04 20:45:17','2024-02-04 20:45:17'),(292,'data_types','display_name_plural',31,'en','Short List Candidates','2024-02-04 20:45:17','2024-02-04 20:45:17'),(293,'data_rows','display_name',302,'en','Is Send Email','2024-02-04 22:57:40','2024-02-04 22:57:40'),(294,'data_rows','display_name',303,'en','Is Default Setting For Eod','2024-02-18 22:28:54','2024-02-18 22:28:54'),(295,'data_rows','display_name',304,'en','Id','2024-03-09 15:00:34','2024-03-09 15:00:34'),(296,'data_rows','display_name',305,'en','Name','2024-03-09 15:00:34','2024-03-09 15:00:34'),(297,'data_rows','display_name',306,'en','Url','2024-03-09 15:00:34','2024-03-09 15:00:34'),(298,'data_rows','display_name',307,'en','Username Or Email','2024-03-09 15:00:34','2024-03-09 15:00:34'),(299,'data_rows','display_name',308,'en','Password','2024-03-09 15:00:34','2024-03-09 15:00:34'),(300,'data_rows','display_name',309,'en','Description','2024-03-09 15:00:34','2024-03-09 15:00:34'),(301,'data_rows','display_name',310,'en','Created At','2024-03-09 15:00:34','2024-03-09 15:00:34'),(302,'data_rows','display_name',311,'en','Updated At','2024-03-09 15:00:34','2024-03-09 15:00:34'),(303,'data_rows','display_name',312,'en','Deleted At','2024-03-09 15:00:34','2024-03-09 15:00:34'),(304,'data_types','display_name_singular',34,'en','Credential','2024-03-09 15:00:34','2024-03-09 15:00:34'),(305,'data_types','display_name_plural',34,'en','Credentials','2024-03-09 15:00:34','2024-03-09 15:00:34'),(306,'data_rows','display_name',282,'en','Id','2024-03-10 22:24:40','2024-03-10 22:24:40'),(307,'data_rows','display_name',283,'en','User Id','2024-03-10 22:24:40','2024-03-10 22:24:40'),(308,'data_rows','display_name',284,'en','Date','2024-03-10 22:24:40','2024-03-10 22:24:40'),(309,'data_rows','display_name',285,'en','Reason','2024-03-10 22:24:40','2024-03-10 22:24:40'),(310,'data_rows','display_name',286,'en','Created At','2024-03-10 22:24:40','2024-03-10 22:24:40'),(311,'data_rows','display_name',287,'en','Updated At','2024-03-10 22:24:40','2024-03-10 22:24:40'),(312,'data_rows','display_name',288,'en','Deleted At','2024-03-10 22:24:40','2024-03-10 22:24:40'),(313,'data_rows','display_name',290,'en','users','2024-03-10 22:24:40','2024-03-10 22:24:40'),(314,'data_types','display_name_singular',29,'en','Leave','2024-03-10 22:24:40','2024-03-10 22:24:40'),(315,'data_types','display_name_plural',29,'en','Leaves','2024-03-10 22:24:40','2024-03-10 22:24:40'),(316,'data_rows','display_name',313,'en','Id','2024-03-17 16:47:59','2024-03-17 16:47:59'),(317,'data_rows','display_name',314,'en','Project Id','2024-03-17 16:47:59','2024-03-17 16:47:59'),(318,'data_rows','display_name',315,'en','Milestone Name','2024-03-17 16:47:59','2024-03-17 16:47:59'),(319,'data_rows','display_name',316,'en','Amount','2024-03-17 16:47:59','2024-03-17 16:47:59'),(320,'data_rows','display_name',317,'en','Due Date','2024-03-17 16:47:59','2024-03-17 16:47:59'),(321,'data_rows','display_name',318,'en','Attachments','2024-03-17 16:47:59','2024-03-17 16:47:59'),(322,'data_rows','display_name',319,'en','Created At','2024-03-17 16:47:59','2024-03-17 16:47:59'),(323,'data_rows','display_name',320,'en','Updated At','2024-03-17 16:47:59','2024-03-17 16:47:59'),(324,'data_rows','display_name',321,'en','Deleted At','2024-03-17 16:47:59','2024-03-17 16:47:59'),(325,'data_rows','display_name',322,'en','categories','2024-03-17 16:47:59','2024-03-17 16:47:59'),(326,'data_types','display_name_singular',35,'en','Project Payment','2024-03-17 16:47:59','2024-03-17 16:47:59'),(327,'data_types','display_name_plural',35,'en','Project Payments','2024-03-17 16:47:59','2024-03-17 16:47:59'),(328,'data_rows','display_name',323,'en','projects','2024-03-17 16:49:39','2024-03-17 16:49:39'),(329,'data_rows','display_name',325,'en','Id','2024-03-25 06:15:38','2024-03-25 06:15:38'),(330,'data_rows','display_name',326,'en','Student Id','2024-03-25 06:15:38','2024-03-25 06:15:38'),(331,'data_rows','display_name',327,'en','Amount','2024-03-25 06:15:38','2024-03-25 06:15:38'),(332,'data_rows','display_name',328,'en','Batch','2024-03-25 06:15:38','2024-03-25 06:15:38'),(333,'data_rows','display_name',329,'en','Date','2024-03-25 06:15:38','2024-03-25 06:15:38'),(334,'data_rows','display_name',330,'en','Status','2024-03-25 06:15:38','2024-03-25 06:15:38'),(335,'data_rows','display_name',331,'en','Paid Date','2024-03-25 06:15:38','2024-03-25 06:15:38'),(336,'data_rows','display_name',332,'en','Receiver Id','2024-03-25 06:15:38','2024-03-25 06:15:38'),(337,'data_rows','display_name',333,'en','Notes','2024-03-25 06:15:38','2024-03-25 06:15:38'),(338,'data_rows','display_name',334,'en','Created At','2024-03-25 06:15:38','2024-03-25 06:15:38'),(339,'data_rows','display_name',335,'en','Updated At','2024-03-25 06:15:38','2024-03-25 06:15:38'),(340,'data_rows','display_name',336,'en','Deleted At','2024-03-25 06:15:38','2024-03-25 06:15:38'),(341,'data_types','display_name_singular',36,'en','Online Student Fee','2024-03-25 06:15:38','2024-03-25 06:15:38'),(342,'data_types','display_name_plural',36,'en','Online Student Fees','2024-03-25 06:15:38','2024-03-25 06:15:38'),(343,'data_rows','display_name',337,'en','users','2024-03-25 06:30:13','2024-03-25 06:30:13'),(344,'data_rows','display_name',338,'en','users','2024-03-25 06:31:25','2024-03-25 06:31:25'),(345,'data_rows','display_name',339,'en','Title','2024-04-28 20:01:47','2024-04-28 20:01:47'),(346,'data_rows','display_name',340,'en','Start Date','2024-04-28 20:01:47','2024-04-28 20:01:47'),(347,'data_rows','display_name',341,'en','End Date','2024-04-28 20:01:47','2024-04-28 20:01:47'),(348,'data_rows','display_name',342,'en','Currency','2024-04-28 20:01:47','2024-04-28 20:01:47'),(349,'data_rows','display_name',343,'en','Status','2024-04-28 20:01:47','2024-04-28 20:01:47'),(350,'data_rows','display_name',344,'en','Attachments','2024-04-28 20:01:47','2024-04-28 20:01:47'),(351,'data_rows','display_name',345,'en','Deleted At','2024-04-28 20:01:47','2024-04-28 20:01:47'),(352,'data_rows','display_name',346,'en','users','2024-04-28 20:04:14','2024-04-28 20:04:14'),(353,'data_rows','display_name',347,'en','Start Date','2024-05-27 08:41:59','2024-05-27 08:41:59'),(354,'data_rows','display_name',348,'en','End Date','2024-05-27 08:41:59','2024-05-27 08:41:59');
/*!40000 ALTER TABLE `translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_payments`
--

DROP TABLE IF EXISTS `user_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `second_entry_id` bigint unsigned DEFAULT NULL,
  `income_id` bigint unsigned NOT NULL,
  `developer_id` bigint unsigned DEFAULT NULL,
  `project_id` bigint unsigned NOT NULL,
  `project_target_id` bigint unsigned NOT NULL,
  `total_earning` double NOT NULL,
  `client_source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'fiverr, upwork, payoneer, direct',
  `dev_earning` double DEFAULT NULL COMMENT 'value will be in dollars and other currencies etc ',
  `payable` double DEFAULT NULL COMMENT 'company need to pay to the dev',
  `paid` double DEFAULT NULL COMMENT 'company paid to the dev',
  `fee` int DEFAULT NULL COMMENT 'fiverr 20% upwork 20% 0 for direct clients',
  `currency_current_rate` double DEFAULT NULL COMMENT 'currency current rates',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Requested',
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `generated_by_system` tinyint(1) NOT NULL DEFAULT '0',
  `attachments` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `update_by_command` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=313 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_payments`
--

LOCK TABLES `user_payments` WRITE;
/*!40000 ALTER TABLE `user_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_payments` ENABLE KEYS */;
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
INSERT INTO `user_roles` VALUES (2,14),(2,18),(3,7),(3,8),(3,9),(3,10),(3,14),(3,15),(3,18),(3,20),(3,21),(3,23),(3,25),(3,30),(3,31),(3,36),(4,11),(8,4),(12,3),(13,14),(13,18),(14,4),(15,3),(16,14),(16,18),(17,3),(18,14),(18,18),(19,14),(19,18),(22,3),(28,2),(32,14),(32,18),(36,7),(36,8),(36,9),(36,24),(36,25),(36,26),(36,27),(36,29),(38,8),(38,9),(38,26),(38,32),(38,34),(47,4),(49,12),(50,12),(55,12),(56,12),(57,12),(60,12),(61,12),(66,8),(66,9),(66,26),(66,27),(66,29),(66,35),(66,36),(66,40);
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
  `percentage` double(8,2) unsigned DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `role_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_fee` tinyint DEFAULT '0',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'users/default.png',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci,
  `client_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'to save client important information',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,1,1,'Rashid','rashid.bukhari78600@gmail.com',0,'users/October2023/JTmmMCfvXp8xbp7sCm8C.jpg',NULL,'$2y$10$QPAJBwk3WEfAosRYJAt8uuZaDwqhzI5oE.hr/Qd1ncrGnlD4Jsz3u','VHPPSbV64emx3BTd6D2P2LFCxBcxuK7npAQOA5c4bTNaESf2iKd1KMKK3xsH','{\"locale\":\"en\"}',NULL,'2022-01-24 00:22:05','2023-10-15 11:44:24',NULL),(2,NULL,1,3,'Ahmad Raza','ahmadraza4119@gmail.com',0,'users/April2022/yJOIrcPvO9i6zqT4dXTT.png',NULL,'$2y$10$6E0k7gyzFWNHPhQrdror4ubMz.jokgYkj6QLkT2Za3qFO32zUF0pi',NULL,'{\"locale\":\"en\"}',NULL,'2022-01-24 00:30:21','2022-05-30 00:58:27',NULL),(3,0.03,1,3,'Ayub','ayubkhokhar786@gmail.com',0,'users/September2023/JdWdcHpEifVo91VB5yGU.jpg',NULL,'$2y$10$H52jKUtVfVhHtO/VOG1TN.8W2V2IunEr5Elc3xKMVm.8FjfROGYJG','V0k2cNUctK19gF5yEEnAUfodTeLF35HHbb3ziwjIW5pZjpa7ZRnXmURGQqYl','{\"locale\":\"en\"}',NULL,'2022-01-24 00:31:35','2023-09-30 11:21:57',NULL),(4,NULL,1,1,'Zahid','zaars59208@gmail.com',0,'users/July2022/Mu4rIeCWIBwavmEcncOo.jpg',NULL,'$2y$10$xpyYOgG8oolP0fsorQZc1uoX8xnNJ.qwtY9t4O3am9OLIPTqNW7m2','OgamAD23Ygyz6vUkV3q6SkGPS2SHYiZnNckbjw7mc1W2IP4COwAa0dVtJmEf','{\"locale\":\"en\"}',NULL,'2022-01-24 00:32:38','2023-06-11 11:07:06',NULL),(5,NULL,1,4,'Tal Sanga','tal.sanga@onyxsa.co.uk',0,'users/June2022/MfbEzuwOXy682eoUWBmR.jpeg',NULL,'$2y$10$X8ORJXBWimp7MGsDfLi5.OLsZOD9nExgVfGHD9WJdwMVV96zYrSIa',NULL,'{\"locale\":\"en\"}',NULL,'2022-01-24 09:38:22','2022-06-19 20:38:10',NULL),(6,NULL,1,4,'Steve','steve@webpenter.com',0,'users/default.png',NULL,'$2y$10$PA.Dtnba1VtJpwLgFAip7OxlxbPayUG0J26PAaEdeKbK..XCEEDIK',NULL,'{\"locale\":\"en\"}',NULL,'2022-03-13 09:32:42','2022-03-13 09:32:42',NULL),(8,NULL,1,4,'Francesco Abisso','francesco.abisso@gmail.com',0,'users/June2022/Cy5vfYI90RptBuKqjnLQ.jpg',NULL,'$2y$10$ta9FBDo2YMvrcvCcIXi3XuXWdt.k4moETXB0Ii6s1IrLYplI7Rw7O',NULL,'{\"locale\":\"en\"}',NULL,'2022-04-22 04:59:55','2022-06-19 13:48:03',NULL),(9,NULL,1,4,'We Are Webpenter','wearewebpenter@gmail.com',0,'users/June2022/16RAoumuDKbHPpjVRXL3.png',NULL,'$2y$10$jQxhSB52s7HdpLCD/gaJ9.irkfUts1ptzWi3bkoGBO6YMO/58BmSe',NULL,'{\"locale\":\"en\"}',NULL,'2022-05-30 01:01:40','2022-06-13 16:47:10',NULL),(11,NULL,1,4,'Ben','bensonom@yahoo.com',0,'users/June2022/0RCkA8vOf5S17o0wCdj0.png',NULL,'$2y$10$87YV/gC4GZKD4MsFuX2vsunet3Rw5qjSm.EL/DgrsJqIQ74twiJaa',NULL,'{\"locale\":\"en\"}',NULL,'2022-06-04 16:18:36','2022-06-13 16:40:32',NULL),(12,NULL,1,3,'Irfan','irfanullah9959@gmail.com',0,'users/default.png',NULL,'$2y$10$h9.SADc9uSurDRscU8sVg.8tSULJIoxoesPfHzjB5K7/pvMf5MEJG',NULL,'{\"locale\":\"en\"}',NULL,'2022-06-07 17:25:19','2022-06-07 17:25:19',NULL),(13,NULL,1,3,'Muhammad Sadiq','sadiq@webpenter.com',0,'users/default.png',NULL,'$2y$10$1iKJrepFU3rrmj/Qj40tPenFit09NAwjbLjjbsLnepSBVa22aYOLy','YdMEN2xteNzCRkRlLk6iudonhrogWK2QdtnaEVTMVaXZJmfTNOAq4bp5ypYh','{\"locale\":\"en\"}',NULL,'2022-06-16 08:15:54','2024-02-05 17:47:46',NULL),(14,NULL,1,4,'Phil L','phil@wondersofwordpress.com',0,'users/June2022/IeJbugCcTSRkhd3BqrLO.png',NULL,'$2y$10$WDkyqMwBAz7FuvJkdbj8NONeTM1MGxpyoFe/uqQil1i2K1SI2431W',NULL,'{\"locale\":\"en\"}',NULL,'2022-06-18 12:52:30','2022-06-18 12:52:30',NULL),(15,NULL,1,2,'Huzaifah Khan','huzaifahfakhar@gmail.com',0,'users/January2024/6et0guYGDYztO4oa5dGL.jpg',NULL,'$2y$10$qxGWEYJew6Kft82CN55CheePlEaYsXISjxrqtlgDdpt05k18kG83K','kIaGLI3guolAofKXvKth7DwVRmZqKn93TVd3gcSIKjdrdnFulMaGdy8mMRvC','{\"locale\":\"en\"}',NULL,'2022-07-21 17:00:19','2024-01-19 16:56:51',NULL),(16,NULL,1,3,'Waqar Hussain','waqar@webpenter.com',0,'users/January2024/lp09Y3WkHLSoymRsTeRU.png',NULL,'$2y$10$pf2xjzAuTW1eSKuZ4p9NN.hLVgA0GaY3DLnoZFFw2ajHDYFRpteKa','kKGX9hg5p84oIahKOi9X1a08zaA6s933fs34Cottcx4ArsDuqOZieV9jkUcT','{\"locale\":\"en\"}',NULL,'2022-07-21 17:02:47','2024-01-22 17:50:24',NULL),(17,NULL,1,2,'Muzammil Hussain','muzammilhussainn14@gmail.com',0,'users/default.png',NULL,'$2y$10$2NFHr.khP6XUAi1YmDg9WehuhJBooSo1G/3U.WqZTQMXObUiOzCT6','W3JxaSI2OX9RaoAYZzS4BDRqLzeeQOz7HOT8A7br9gzAikQwEmP5Uqssd9yI','{\"locale\":\"en\"}',NULL,'2022-07-21 17:04:15','2022-07-21 17:04:15',NULL),(18,NULL,1,3,'Abdul Khaliq','abdulkhaliq@webpenter.com',NULL,'users/July2022/7gGRyJURUt58aCiYvOae.jpg',NULL,'$2y$10$KUd9GbSZkN2TEgo5yc.E4.7Iss2/DLqU4Q3YKNCbPT.XByT67ufX6','I2A1Mzpgj0CwMagV6NzSsc2IUPMdbOMKDtk8r3CG3EMCeZVSGjUffapRbtPS','{\"locale\":\"en\"}',NULL,'2022-07-21 17:06:01','2024-01-19 16:58:34',NULL),(19,NULL,1,3,'Arif Rahim','arif@webpenter.com',0,'users/August2022/QW171ZyUFjlHvQzMWB74.png',NULL,'$2y$10$0UntvxnqXTC8uz9SrlgZUOHIs6pHLrjLn5G0uAWt851hZcWlXjtVm','GZ229WrzB8CPNM0px9fGxveji1WLBra14DYBKWUS2NgxlzYjnsRAnklxdKKd','{\"locale\":\"en\"}',NULL,'2022-08-19 22:31:23','2024-04-01 22:06:00',NULL),(20,NULL,1,4,'Mike','mikecrane@me.com',0,'users/September2022/0769D5hOza7cxtqzQQCn.png',NULL,'$2y$10$B/aWXboRj/wxOArCJKv09ur42lT1hxyF75b5Vx7QuiiGR/Wt4ZPBW',NULL,'{\"locale\":\"en\"}',NULL,'2022-09-15 11:26:08','2022-09-15 11:30:33',NULL),(21,NULL,1,4,'Fiverr - gerardxalabarde','cocopool@demo.com',0,'users/September2022/now0yyg1lmZbZMjHBiTz.png',NULL,'$2y$10$daGZW82ZlHXaN/TPu9QhOuKP94M/ohN1mfkl5ZVKW0zr2JLKsh0lm',NULL,'{\"locale\":\"en\"}',NULL,'2022-09-30 13:15:42','2022-09-30 13:15:42',NULL),(22,NULL,1,2,'Ghulam Muhammad','jamghulammuhammad020@gmail.com',0,'users/default.png',NULL,'$2y$10$TL.i6EZk1WyQcWImILjrqePI1TCp5DatS/VUmddb20F90MmPdgpxy',NULL,'{\"locale\":\"en\"}',NULL,'2022-10-10 18:16:49','2022-10-10 18:16:49',NULL),(24,NULL,1,4,'Matt Busi - WorkBench - shadowrock','matt@shadowrock.io',0,'users/October2022/cVnuONIekgwBvq5N5Cwt.png',NULL,'$2y$10$axoJOhkAfFRLFe.mRdwqz.JZ9jsRT8HETv59xJ.e8vG3Pt5vR9rA2',NULL,'{\"locale\":\"en\"}',NULL,'2022-10-30 21:18:03','2022-10-30 21:19:42',NULL),(25,NULL,1,12,'Shahzad baloch','shahzadrajabalikhan@gmail.com',0,'users/default.png',NULL,'$2y$10$7KWhJIowT5TODKH9RnpRROIHppRaQc9eIyhxkR8gOOnpOaQ48y9Na','zTD7yRI6b1ntNhADgZEnTrgPzsB5LSseHSo5ss0Su9tUDGmvf9h095f45d0y','{\"locale\":\"en\"}',NULL,'2023-02-16 17:52:47','2023-12-10 18:11:48','2024-01-03 20:16:23'),(26,NULL,1,12,'Sajid Abbasi','nawabsajidabbasi236@gmail.com',1,'users/default.png',NULL,'$2y$10$e3YRuCA1fRV7hPcIaAPxheWsTfyLDn6FmnhHlCihqkMkfx4ieMdhK','8cZlmtOz7L0OgowYwALTVrgEx7sP0iHroyyvZO0luvcrf3YCjtWopdp8vhl6','{\"locale\":\"en\"}',NULL,'2023-02-16 17:54:14','2024-01-16 16:22:41',NULL),(27,NULL,1,12,'Mehtab sain','sainmehtab15@gmail.com',1,'users/January2024/Gu1yntnvkeNRH8VG99jJ.jpg',NULL,'$2y$10$UlOM1gaiJVYpO8ctpN.F8.Z.Bx5oeRu4yNFW9fN75WBqJOS/AqdAS','g2221MIfpmoxcuQ4hwiUlWkx79Zr3PYC20EfdfF6eRKcI7XB7GAbausVJjXT','{\"locale\":\"en\"}',NULL,'2023-02-16 17:54:58','2024-01-08 12:17:58',NULL),(28,NULL,1,12,'Muhammad Khan','muhammadkhan10220@gmail.com',0,'users/default.png',NULL,'$2y$10$P6m3w1V5c2e4teJCcVEYPu/4auSR57koi.WQu3eR0vIkz/OKkAF/2','VGjF8HuKuMeFv0yqxSEsdQ2iB5BmNa2L6up6xJoFnswOGxvV2RRwrU1AWLzZ','{\"locale\":\"en\"}',NULL,'2023-02-16 17:55:48','2023-12-10 17:55:03',NULL),(29,NULL,1,12,'Ihtasham Khan','ihtsham9000@gmail.com',0,'users/default.png',NULL,'$2y$10$967FGHhbhPh6cy5LUorYseqWcLuJ08EWacdW1h4NLXrc9MdzmY8o.','04Rf5OldVTRHw2cnAylYFnrraPyjjq6ZLCgRTHP9WOYMOUElcG58vRtuSYKU','{\"locale\":\"en\"}',NULL,'2023-02-16 17:57:28','2023-07-03 16:33:48','2024-01-03 20:14:52'),(32,NULL,1,3,'Test Dev','dev@gmail.com',0,'users/default.png',NULL,'$2y$10$xc/zjrU9Oi3dX92QxkR/TOEJWke1IRr0a9a7inKC0acUXnDfpyY3m',NULL,'{\"locale\":\"en\"}',NULL,'2023-03-01 22:31:47','2023-03-01 22:31:47',NULL),(33,NULL,1,4,'Sarah  - Fiverr - Homey','krahnstar@gmail.com',0,'users/default.png',NULL,'$2y$10$C6THYOaHEdPLaVS/vp1D1OifyMP0WhLGBNdbOtPbbQDJXLuzoRxH.',NULL,'{\"locale\":\"en\"}',NULL,'2023-04-29 16:16:32','2023-04-29 16:16:32',NULL),(34,NULL,1,19,'Accountant','Accountant@webpenter.com',0,'users/September2023/6ungatbwXOSX4P6gUzNz.jpeg',NULL,'$2y$10$YhTDLuAIQJcEGy4M2/0O1.N0DQThTjtdzcsMHwzrJIN8L7tJq2ct.','JSrIGUvwWy6QRmphmMg4plyxoPIJ5fJc3KOASWtakTp3db3xGRRtLC86M6vN','{\"locale\":\"en\"}',NULL,'2023-04-29 16:56:14','2023-09-28 16:17:22',NULL),(36,NULL,1,5,'Salman Awan','salmanawan993@gmail.com',0,'users/August2023/eeW8xmDqNnG1YI2L1fIz.png',NULL,'$2y$10$hRYFYqU7Co79SDuDpJTYn.eH/7s6wn7oV2vWCjWaLA5h3L6yrywWq',NULL,'{\"locale\":\"en\"}',NULL,'2023-05-12 12:40:06','2023-08-26 10:56:22',NULL),(38,NULL,1,12,'Khurshid Bilal','khurshid@webpenter.com',1,'users/February2024/QcyXju8Ej7pn8sH9zKoX.jpg',NULL,'$2y$10$md0Rg3Y2DQaiBLi9RP8k1uEl7587quZ4fY0nQd4.IPblUTXerTVS2','S3q6Gyt1qiAwDgSSddf9ymcOV2eew53otIvoXvYpcZsmWrgvagZK7pUhWs2B','{\"locale\":\"en\"}',NULL,'2023-06-23 17:18:39','2024-02-13 12:52:02',NULL),(40,NULL,1,12,'Sajid Qasim','sajidqasim01@gamil.com',0,'users/default.png',NULL,'$2y$10$HNytln5/ZAnmcD2kYIhOVurVctobzlWFp7zeVCJDDHjzgKfrWRCYm',NULL,'{\"locale\":\"en\"}',NULL,'2023-07-18 18:26:38','2023-12-11 15:04:42',NULL),(41,NULL,1,12,'sharjeel','princesharjeel07@gmail.com',0,'users/December2023/DCzEWdsmBSMhJ54a8RH2.jfif',NULL,'$2y$10$jddFhAlx3eojwETeP.Neu.GSH5oUkNWfs5XleGRwWLHC6YWCfC.x2','R6vC7aqXWUHeh3FGHrccyTZC9JpVgUB3KFp6sRxQBY9njnEgWQjYrnWyH65O','{\"locale\":\"en\"}',NULL,'2023-07-18 18:29:20','2023-12-11 16:35:49',NULL),(45,NULL,1,12,'Haris','ophariskha3132@gmail.com',0,'users/default.png',NULL,'$2y$10$laSmU5Tybh/qgfjKeUlnMuQE6XVcoJ7ohEGUiPturbUkJR1F8OTsO','tbD9HdoATT4mCuoimisEnO9MiRea5cAQNEMpk0hacC0ZrZjtman1iT30zv2T','{\"locale\":\"en\"}',NULL,'2023-07-18 18:37:59','2023-12-10 17:59:49',NULL),(46,NULL,1,12,'Huzaifah','khanhuzaifah85@gmail.com',1,'users/default.png',NULL,'$2y$10$Ta3rszjrS8V39.3x9rqE0.Jh.H0rVL6TwI5oc6PAy1ohZub8HzgEG',NULL,'{\"locale\":\"en\"}',NULL,'2023-07-18 18:39:20','2023-12-10 18:01:45',NULL),(47,NULL,1,4,'Shrey','d.pham@h23.eu',0,'users/default.png',NULL,'$2y$10$3k8uKaBMpSuGPpiQ4fCQYut1SdSCszFU8ptyRyTrPVeMJt682uxqO',NULL,'{\"locale\":\"en\"}',NULL,'2023-07-30 18:13:55','2023-07-30 18:13:55',NULL),(49,NULL,1,12,'Muhammad Sair','sairbhatti50@gmail.com',0,'users/default.png',NULL,'$2y$10$3ga/0geKfLtuyNV97wIQ0eE1N8VQQojFx5al/uX2EPAA8dms0hSla',NULL,'{\"locale\":\"en\"}',NULL,'2023-08-01 16:15:47','2023-12-11 16:08:43',NULL),(50,NULL,1,12,'Rana Adeel','ranaadeel1776146@gmail.com',0,'users/default.png',NULL,'$2y$10$KcmFUWsYenftxXNUhljKY.WN67iz.84bgzzwngfZJ0kFM1MjTYwje','rOkQZt2PgSmuembgy4UJvP391IDMEWwHmH7R4mShc24DhHsi1Y55Vb7TJawq','{\"locale\":\"en\"}',NULL,'2023-08-01 17:32:58','2024-02-21 15:07:47',NULL),(54,NULL,1,3,'Umar Farooq','umarfarooq@webpenter.com',0,'users/October2023/o55HhunGkeHkFoEKLBoY.png',NULL,'$2y$10$65O8iTOxVGrmtuuhEBnmcOVUp1qgi9NKJYviTYFlvwsxk0bqItnjC','nN1D2efP1g0npO9JiyLEcaznyvkdJiQGsYrJJP6sNCDREperPftjCqd184ue','{\"locale\":\"en\"}',NULL,'2023-10-01 21:29:39','2024-05-06 21:52:48',NULL),(55,NULL,1,12,'hammad','hammad@gmail.com',0,'users/default.png',NULL,'$2y$10$Q56Xj8Kf/UmXBNQmhKqSQOxTRlrrh.KjGwuphLAdeeWdbl/J5T8.y','WuLgghexjQEO8RdP7lzfZWB3UFR051m2h7n3grDdaldYWzbQBEAGYQVS3e9H','{\"locale\":\"en\"}',NULL,'2023-11-10 16:52:05','2023-12-10 17:40:19',NULL),(56,NULL,1,12,'bilal','bilal@gmail.com',0,'users/default.png',NULL,'$2y$10$pbqnKQOU6TxOwdudxiXPGOOUi6btU8bVeiHUY0KJIwdO6Td5j.oJC',NULL,'{\"locale\":\"en\"}',NULL,'2023-11-10 16:53:30','2023-12-10 18:08:11',NULL),(57,NULL,1,12,'hamza riaz','hamza@gmail.com',0,'users/default.png',NULL,'$2y$10$u7j6z.MFX0sqWeLoQ6H3yedDzL00QL4tpdo0PT2ZmeR1NzTEZh.2e','ZcDmqe2Zuxg92JAL9DKLwcNRmzvkWl4WGemNNbnYK6ACBVqcQJhryU9s3jUM','{\"locale\":\"en\"}',NULL,'2023-11-10 16:55:00','2024-04-30 15:30:18',NULL),(60,NULL,1,12,'hamza rasheed','hamzarasheed@gmail.com',0,'users/default.png',NULL,'$2y$10$gZOX3ngXaOoJSVoFUM9WTOIRknknHGzI3mohjfZxGBx8LaQJeoCDm',NULL,'{\"locale\":\"en\"}',NULL,'2023-11-10 17:02:13','2023-12-10 18:05:37',NULL),(61,NULL,1,12,'shahbaz','shahbaz@gmail.com',0,'users/April2024/Cqx26xHSpNObj2zeLFUc.jpg',NULL,'$2y$10$B43afry6v9tYgpH2gY0PRuWlFk8qdtVi0cYt0JqFo/90WSPidn5Ga','1kLqxfR7SnSjmnr5msmY2Mj24qTVfr1q6hSHR15WUG6M31LbAmp2ZfevOMAw','{\"locale\":\"en\"}',NULL,'2023-11-10 17:04:11','2024-04-30 15:26:59',NULL),(63,NULL,1,4,'Chad','chad@brokenbow.com',NULL,'users/November2023/m2ObIC8rAN5TsTz3sinQ.png',NULL,'$2y$10$ozob7xy62krKcaeJaK/XcuI89xtv2PfT/jjWVSucWujD9LrqartY6',NULL,'{\"locale\":\"en\"}',NULL,'2023-11-27 17:52:46','2023-11-27 17:52:46',NULL),(64,NULL,1,4,'Webpenter boys academy','academy@webpenter.com',0,'users/default.png',NULL,'$2y$10$Edkgksafk41IjhCR3.GI/uUZQb/9fHp.Jh0.ia9.lFsbxQ2cfnGw6',NULL,'{\"locale\":\"en\"}',NULL,'2023-12-11 17:00:45','2023-12-11 17:00:45',NULL),(65,NULL,1,12,'Amir Hussain','amirmazari467@gmail.com',0,'users/default.png',NULL,'$2y$10$kTvhcB3RjkOWOTA3Vnb/L.djCXp.90lMX5V399V4V0IV42IS8KDvK','n3AkurK2goWmyKprtogKPY1JCG9W0XRwmwF3egsuhXVcdyQuVDlwffT3kz1m','{\"locale\":\"en\"}',NULL,'2023-12-13 20:44:38','2023-12-13 20:44:38',NULL),(66,NULL,1,33,'Ahmed Bilal','ahmedbilal@webpenter.com',0,'users/February2024/r7MJwpY4ILiNZwg3D3TY.jpg',NULL,'$2y$10$lOPyRppsrQBa9XXxOUtou.QPN0vfOJmW2.05vUkSvfOMV1ncs9stu','rTkQvrbmecyhAmmtYfUw2pQtHixe3qit9Ce2wsxjArYH08Df2kplBCWedxp8','{\"locale\":\"en\"}',NULL,'2023-12-20 19:31:02','2024-05-09 10:58:33',NULL),(67,NULL,1,4,'UMER TAHIR IOS Developer','umerbs135@gmail.com',0,'users/December2023/jDZAB4qcu8cFURV8QNcu.png',NULL,'$2y$10$Hmwjplt5BtMCzApCcHrSw.4W8FQBHUXKKNYqcHZfsXWfhDG2mGpIq',NULL,'{\"locale\":\"en\"}',NULL,'2023-12-29 20:50:01','2023-12-29 20:50:01',NULL),(70,NULL,1,12,'Zubair','Zubairalvi@gmail.com',0,'users/default.png',NULL,'$2y$10$7etMhViPy.Z7.ZtUJN2Z8uZKtWuCRcjOfgYh0ykMO6mQXKatzPDuW','gMmtCBrv6KpEu31g9bLyBQn619B1eABjRlxuZGeoNES7D6l4i7ofxjVJnkNH','{\"locale\":\"en\"}',NULL,'2024-02-21 13:47:56','2024-02-21 13:47:56',NULL),(71,NULL,1,12,'Sheikh Ahmed','sheikhahmed@webpenter.com',0,'users/default.png',NULL,'$2y$10$qj2yVpd0AR0HqRG3FuSZK.l6a/KJdTbvYK0SieW4Wv.pGaO1Wd9Fm',NULL,'{\"locale\":\"en\"}',NULL,'2024-03-08 16:21:27','2024-03-08 16:21:27',NULL),(72,NULL,1,12,'Saif Ullah','saifullah@webpenter.com',0,'users/May2024/MCNn1yXInreeVFvHTixw.jpg',NULL,'$2y$10$bjXJUCQV77mjw34TWmOBGu5D75U8AdJEKqFcwdODzkBsY97i3g1Ja','K03T1vFsS99Cwp9t4DNePxccfxyWfl60dDa0dFmNJ1kJuszyC2XjaE6m48Js','{\"locale\":\"en\"}',NULL,'2024-03-08 16:22:33','2024-05-22 16:49:44',NULL),(73,NULL,1,12,'Hassan Ehsan','hassanehsanwebpenter@gmail.com',0,'users/default.png',NULL,'$2y$10$CtSie8.Dk0/IwTR2z.cIK.2vkZxHMJd1SoRINjsAmcqBpfcJG91nG','uwEvAeiUkThqWHqU1T1IRUBFy52DJyJSBwA2mfL00H9rawFfXFyIfhSna64w','{\"locale\":\"en\"}',NULL,'2024-03-08 16:29:01','2024-05-03 16:54:59',NULL),(74,NULL,1,12,'Ahsan shawal','ahsanshawal@webpenter.com',0,'users/May2024/xAuxmp1V0ZR6spdCSMCi.jpg',NULL,'$2y$10$R7k40angwwTQERXDKJI3cOUigjB6jBLxVhMgzgBT7lshnRZxWsXPy','KyrbghNLgTEJL5EJNbRJIu5WaEuiMpkPbEbP0N9OaFEEY8NOWdV68FMITB0k','{\"locale\":\"en\"}',NULL,'2024-03-08 16:30:50','2024-05-07 00:50:47',NULL),(75,NULL,1,12,'Mujahid BD','mujahidbd@webpenter.com',0,'users/default.png',NULL,'$2y$10$GLx/1vU8boFt6cTMduao/.ixcXjGajSROYqpI7FB0qKTpwkyfQgKW',NULL,'{\"locale\":\"en\"}',NULL,'2024-03-08 16:32:46','2024-03-08 16:32:46',NULL),(76,NULL,1,12,'Naresh Kumar','nareshkumar@webpenter.com',0,'users/default.png',NULL,'$2y$10$9msq77COtdrlhKWCYF0aKuLKkMT2MmZoAQAVhFSGYyOeOKiPIMe96',NULL,'{\"locale\":\"en\"}',NULL,'2024-03-08 16:33:53','2024-03-08 16:33:53',NULL),(77,NULL,1,12,'Hassnain Zafar','hasnainzafar@webpenter.com',0,'users/default.png',NULL,'$2y$10$Mi6FK9p35aVAFawHCUhsaeUIV.FNIInLmGLFwswN4awNI7Bi3ar3O','wFlRFJC4kPp76rIXc3V9TtgXHnLB2aY7eabCQ0KEyT8UnGTH3AXmYAT7cpy3','{\"locale\":\"en\"}',NULL,'2024-03-08 16:34:53','2024-03-08 16:34:53',NULL),(78,NULL,1,37,'Rabia Basit','wasifalvi25@gmail.com',0,'users/March2024/3xDwyZs6iHeRftpdRIdD.png',NULL,'$2y$10$CJkA4P9SEgZxyjevAOiSAeldfsin29ax5UyqI.4moS9NzVKMABXZm',NULL,'{\"locale\":\"en\"}',NULL,'2024-03-10 14:55:43','2024-03-10 14:55:43',NULL),(79,NULL,1,38,'Kashmala Saeed','moizmissen786@gmail.com',0,'users/March2024/BoenKhtZYUuAdY8RgWHv.png',NULL,'$2y$10$zBZELBkT/HvLZz7Tw5y2Pu.GKteSQmVsPXqHzCuxaWyZiz0ntOM8y',NULL,'{\"locale\":\"en\"}',NULL,'2024-03-10 14:59:11','2024-03-10 14:59:11',NULL),(80,NULL,1,38,'Ayesha','aeshmalikmalik4@gmail.com',0,'users/March2024/ZLtFOY2xraCju6HRiv1V.png',NULL,'$2y$10$LL/b2zQJgb8xuNat1.oeiepgbuobDHD1LEI4Aoh/6frxZIF9iqj2i',NULL,'{\"locale\":\"en\"}',NULL,'2024-03-10 15:00:54','2024-03-12 14:44:48',NULL),(81,NULL,1,38,'Tooba khursheed','tooba4844@gmail.com',0,'users/March2024/VVfme9zORc4hB6Tq6LSd.png',NULL,'$2y$10$ryq47NgL5dI0aAdhnX1fROGSwDDP/jRKrNCyQt/jIUSqEq0F1qAea',NULL,'{\"locale\":\"en\"}',NULL,'2024-03-10 15:01:47','2024-03-10 15:01:47',NULL),(82,NULL,1,38,'Muskan','mubasherkhananjum@gmail.com',0,'users/March2024/r2rln5TKrxYXcsM6qwXN.png',NULL,'$2y$10$skHvMlX.6StAKwRsRwlhouPFY3q.OfDP4cVxqvwkjtPED8Kz5/1Ha',NULL,'{\"locale\":\"en\"}',NULL,'2024-03-10 15:02:38','2024-03-10 15:02:38',NULL),(85,NULL,1,38,'Samrina','samrinaakbar260@gmail.com',0,'users/March2024/AUQUepd72zjXIhIEvIJN.png',NULL,'$2y$10$cnu/pON6UqUL95qHZyldgea8CYOUJ5YZ7QtfflIdHhiY9JFYbxdAu',NULL,'{\"locale\":\"en\"}',NULL,'2024-03-10 19:32:50','2024-03-10 19:45:07',NULL),(86,NULL,1,38,'Qurat ul Aain','ainee.khan008@gmail.com',0,'users/March2024/vWn0LkBfBmiCxE6F0AzX.png',NULL,'$2y$10$LQhMpcLNdlEq0AI9rqPQE.wWC.n/wbTrmgu6S13UiVrq1bK9H1pSy',NULL,'{\"locale\":\"en\"}',NULL,'2024-03-12 22:14:00','2024-03-12 22:14:00',NULL),(88,NULL,1,4,'casadastalux','casadasta@webpenter.com',0,'users/default.png',NULL,'$2y$10$S6hO.axckpFPsd1vdeWpYugaqlvodwieK6iFecNX9O4Uk0OA3pn5q',NULL,'{\"locale\":\"en\"}',NULL,'2024-04-01 16:58:05','2024-04-01 16:58:43',NULL),(89,NULL,1,3,'Sadam nextbridge','dsbsadam@gmail.com',0,'users/May2024/pqWLUIJ4JRwfjSaNE0Yb.jpg',NULL,'$2y$10$vlrnBDGfqNmi55IoKMUci.ya906iaEKPhc9kx0TMIK6cQsQQ2HalC',NULL,'{\"locale\":\"en\"}',NULL,'2024-04-24 22:03:59','2024-05-01 22:43:24',NULL),(90,NULL,1,12,'Taimoor Ahmad','taimoorahmadwebpenter@gmail.com',0,'users/May2024/TFh2XlaF3jZumg4sut8e.jpeg',NULL,'$2y$10$dboHTMfRUNnGNBkM.ynVGeqf9eejzAQPA5KgC4B6mP8dA..ID24zS','STk086jKlwtHCSKOOldr4UpFze9EV8V9J4aE1refvk2pF4GidQLaU89tFB3p','{\"locale\":\"en\"}',NULL,'2024-05-06 17:34:40','2024-05-09 18:01:06',NULL),(91,NULL,1,12,'Asif Maher','muhammadasifwebpenter@gmail.com',0,'users/default.png',NULL,'$2y$10$VDoYuAhYLS01Ht4DhLARDOX8.qhq7ix2OP7zfLtnoQqkcl24zutb6','aSzOqtcz8TwG1tE8D8jk7XEPyGEytbYNJbTZ18prytBaI5h0L61yy2A9pw4r','{\"locale\":\"en\"}',NULL,'2024-05-06 17:42:23','2024-05-09 16:02:23',NULL),(92,NULL,1,12,'Naraish kumar','naraishwebpenter329@gmail.com',0,'users/default.png',NULL,'$2y$10$9AEubzAq5GZcVg1nqZgts.NhmkzGTavJ0yjSpUd8QeXY7eGyS3O6y','hH6p0ZT4BdyPHtY4bz66UYrepqmPBV5vxcyNVUZykgxnI4XIYi0ZBbSkvbSa','{\"locale\":\"en\"}',NULL,'2024-05-06 17:53:30','2024-05-06 17:53:30',NULL),(93,NULL,1,12,'Farrukh Javed','farrukhwebpenter@gmail.com',0,'users/default.png',NULL,'$2y$10$w/S7764COPfU84fQ5mMJzes6pT0kUKySkqxlUX1rITpI7kdl185pq','gVc96UrT8TMXULjg1qN0jpmfdRgv3R83yMnxd2yXD5HtohZ9itJ4qUmKEN6Q','{\"locale\":\"en\"}',NULL,'2024-05-06 17:59:23','2024-05-06 17:59:23',NULL),(94,NULL,1,12,'Ahmad Zulfiqar','ahmadzulfiqar3553@gmail.com',0,'users/default.png',NULL,'$2y$10$WR4BWFlTBBHtkqH3zXup0eB2a5zcIyVelF0gXIV4b3jPYOe72FpHC',NULL,'{\"locale\":\"en\"}',NULL,'2024-05-06 18:36:11','2024-05-07 11:42:54',NULL),(95,NULL,1,12,'Ibtihaj shahid','ibtihajwebpenter@gmail.com',0,'users/default.png',NULL,'$2y$10$27R6LdOslVpPRm8DfsaKy.kwl2IN6OsggxabVNv0Wim7OaloVpbcO',NULL,'{\"locale\":\"en\"}',NULL,'2024-05-09 15:47:31','2024-05-09 15:47:31',NULL),(96,NULL,1,12,'Sheikh Ahmad','ahmadwebpenter@gmail.com',0,'users/default.png',NULL,'$2y$10$nXfoaBa78e3rZ6ePb/059egs2gALS20iiVAxnn9yWSUF3xxTkKDta',NULL,'{\"locale\":\"en\"}',NULL,'2024-05-09 15:59:27','2024-05-09 15:59:27',NULL),(97,NULL,1,12,'Hammad Umer','hammadwebpenter@gmail.com',0,'users/default.png',NULL,'$2y$10$1oc4iYMPvuTgIIHIoiDgb.iRSCS1.A0FPFjcJF1DUVz46UYCbHo/i',NULL,'{\"locale\":\"en\"}',NULL,'2024-05-09 16:04:05','2024-05-09 16:04:05',NULL),(98,NULL,1,12,'M Hussnain','hussnainwebpenter@gmail.com',0,'users/default.png',NULL,'$2y$10$ohopmUST9lRXjFdr8aQnGOdaYw4Yp.57bzEqxFX5WBxe3cJUJgBcq',NULL,'{\"locale\":\"en\"}',NULL,'2024-05-09 16:06:50','2024-05-09 16:06:50',NULL),(99,NULL,1,3,'Ali Azmat','aliazmat185@gmail.com',0,'users/May2024/cwSpqYK0A1kobMY053b5.png',NULL,'$2y$10$xZwWKGwOjkmvxO24sm5Je.5Xn5AEAOjf7QSyVeaEcNkW/Vw93u2mC',NULL,'{\"locale\":\"en\"}',NULL,'2024-05-16 23:38:09','2024-05-16 23:38:09',NULL),(100,NULL,1,41,'Khalid Saad','khalidhussainsaad877@gmail.com',0,'users/default.png',NULL,'$2y$10$IZt1hJnbfHfVLAwu4W2mCOJ690tpfs3yHsXFGbJ9.QljlkMsDpRiu',NULL,'{\"locale\":\"en\"}',NULL,'2024-05-20 16:58:07','2024-05-20 16:58:07',NULL),(101,NULL,1,41,'Muhammad Irfan','muhammadirfanali687@gmail.com',0,'users/default.png',NULL,'$2y$10$YeC145/o3LEdOd9rocodQuHNf9DrmS7TcLg1.NHXwrw2w1hgk/w2y',NULL,'{\"locale\":\"en\"}',NULL,'2024-05-20 17:02:36','2024-05-20 17:02:36',NULL),(102,NULL,1,41,'Adnan Rahi','rahiadnan93@gmail.com',0,'users/default.png',NULL,'$2y$10$oBCImNqjgCcKWZoaiJvWPuaRfACYFgC1BWHmluqBo.56qPW1i6QEm','pElUUx2NeANO7qBaDOuPSDsnP3kiBekWf4chfz4NztzVcUSdpuTiU1tt7YuZ','{\"locale\":\"en\"}',NULL,'2024-05-21 13:59:06','2024-05-21 13:59:06',NULL),(103,NULL,1,41,'Muhammad Ahmad','khansahb0331628@gmail.com',0,'users/default.png',NULL,'$2y$10$OjKXTY3T/8MZH6lYDf0HB.153NTRxHykLpGPFyyyhlAWm5OXQ6v/q',NULL,'{\"locale\":\"en\"}',NULL,'2024-05-21 20:50:25','2024-05-21 20:50:25',NULL),(104,NULL,1,12,'Shahzad Baloch','shahzadbalouch2005@gmail.com',0,'users/default.png',NULL,'$2y$10$Zw9VVpxAXkdbVve22lKUz.inHsTndjk6pg5pSnQaeN9KNkXByHp2e',NULL,'{\"locale\":\"en\"}',NULL,'2024-05-23 10:25:35','2024-05-23 10:25:35',NULL),(105,NULL,1,4,'Client','client@portal.com',0,'users/default.png',NULL,'$2y$10$G/yC34aNrS1wGrAE90Mm0.NEJY7RAsM/RArYkF6h.a8o87BrzjDBC',NULL,'{\"locale\":\"en\"}',NULL,'2024-06-09 07:24:48','2024-06-09 07:24:48',NULL),(106,NULL,1,42,'Student and Online Student Manager','studentmanager@portal.com',0,'users/default.png',NULL,'$2y$10$5hyUMkzMmTnqQ54TerZg6eg8G.XILSveXQbFgolQrSJhF83aPx9mW',NULL,'{\"locale\":\"en\"}',NULL,'2024-06-09 08:23:30','2024-06-09 08:23:30',NULL),(107,NULL,1,1,'Administrator','administrator@portal.com',0,'users/default.png',NULL,'$2y$10$JK45i3IqziYXBMXx66opfumSOsBAs3t8pYsh.Udf3eA9hyoF5i8Ne',NULL,'{\"locale\":\"en\"}',NULL,'2024-06-09 08:27:59','2024-06-09 08:27:59',NULL),(108,NULL,1,43,'HR Manager','hrmanager@portal.com',0,'users/default.png',NULL,'$2y$10$BlRhL3hUbUgj6sZ87Cj7XOD1zBpb33fDMVQnNuIZEvoxhrY0rNLTC',NULL,'{\"locale\":\"en\"}',NULL,'2024-06-09 08:36:24','2024-06-09 08:36:24',NULL),(109,NULL,1,33,'Bussiness Developer','bussinessdeveloper@portal.com',0,'users/default.png',NULL,'$2y$10$hStC4l4J3I2idqAEmd8LAeNjF12ErDNYWGBUFkJKKx6aeRF1y50Ki',NULL,'{\"locale\":\"en\"}',NULL,'2024-06-09 09:41:44','2024-06-09 09:41:44',NULL),(110,NULL,1,19,'Accountant','accountant@portal.com',0,'users/default.png',NULL,'$2y$10$tkmLY5640WHUuvBTwxjWTuM0sLLHyAADi3hUKLWywr5Nloiw0k4Hi',NULL,'{\"locale\":\"en\"}',NULL,'2024-06-09 09:50:27','2024-06-09 09:50:27',NULL);
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

-- Dump completed on 2024-06-12 18:56:27
